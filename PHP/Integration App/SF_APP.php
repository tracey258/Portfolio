<?php

// Use the Engage API Framework PHP
require 'Int_APP_Framework.php';
use GuzzleHttp\{Client, RequestOptions, ClientErrorResponseException, TransferStats};

switch($argv[1])
{
    case "High":
        $dbgHighOn = true;
        $dbgMedOn = true;
        $dbgLowOn = true;
        echo "Debug Message High\n";
        break;
    case "Med":
        $dbgHighOn = true;
        $dbgMedOn = true;
        $dbgLowOn = false;
        echo "Debug Message Med\n";
        break;
    case "Low":
        $dbgHighOn = true;
        $dbgMedOn = false;
        $dbgLowOn = false;
        echo "Debug Message Low\n";
        break;
    default:
        $dbgHighOn = true;
        $dbgMedOn = true;
        $dbgLowOn = false;
        echo "Debug Message Med\n";
        break;
}

if($argv[2] == "False")
{
   $dbgStatsOn = false;
    echo "DebugStats Off\n";
}
else
{
    $dbgStatsOn = true;
    echo "DebugStats On\n";
}

if($argv[3] == "False")
{
    $dbgPoolList = false;
    echo "Debug Pool List Off\n";
}
else
{
    $dbgPoolList = true;
    echo "Debug Pool List On\n";
}

/***** Begin Credential File Pull *****/
$SF_file = "Special.json";

if(file_exists($SF_file))
{
    $cred = file_get_contents($SF_file);
}
else
{
    echo "Please put all credentials in a Special.json file and try again please.";
    exit();
}

$cred = json_decode($cred, true);
/***** End Credential File Pull   *****/

//Create hash for JIRA and ZD tokens
$ZenAuthToken = base64_encode($cred["ZD_Username"] . ":" . $cred["ZD_Password"]);
$JIRAAuthToken = base64_encode($cred["JIRA_Username"] . ":" . $cred["JIRA_Password"]);

//Create API Framework instance then Initialize for Zendesk
$ZenAPI = new API;
$ZenAPI->Init_API($ZenAuthToken, $cred["ZD_URI"]);

//Create API Framework instance then Initialize for JIRA
$JIRAAPI = new API;
$JIRAAPI->Init_API($JIRAAuthToken, $cred["JIRA_URI"]);

//Create API Framework instance then Initialize for SalesForce
$SFAPI = new API;
$SFAPI->Get_SFAuth($cred["SF_URI"]);

$opts = [
    RequestOptions::FORM_PARAMS => [
        'grant_type' => 'password',
        'client_id' => $cred["SF_client_id"],
        'client_secret' => $cred["SF_client_secret"],
        'username' => $cred["SF_username"],
        'password' => $cred["SF_password"] . $cred["SF_security_token"],
    ]
];

//Get Access Token (good for 8 hours)
try{
    $SFresp = $SFAPI->call_PostAPI("services/oauth2/token", $opts);
}
catch (\Exception $exception)
{
    echo "Cannot Connect to Salesforce\n";
    exit();
}

//Create a has to check the signature for validation
$hash = hash_hmac('sha256', $SFresp->id . $SFresp->issued_at, $cred["SF_client_secret"], true);

//If the hash and signature do not match then something went wrong
if(base64_encode($hash) !== $SFresp->signature)
{
    echo "Access token is invalid \n";
    exit();
}

//Save the access token and setup the SFAPI with this access token for future calls
$SFAccessToken = $SFresp->access_token;
$SFAPI->Init_SFAPI($SFAccessToken, $cred["SF_URI"]);

//Helper functions
function dbgEcho($message, $dbgOn)
{
    if($dbgOn)
        echo $message;
}

function GetZTime()
{
    //Get Current time to log and convert to Zulu Time
    $timestamp = time()+date("Z");
    
    return $lastRun = gmdate("Y/m/d H:i:s",$timestamp);
}

//Zendesk Globals
dbgEcho("<=== Begin Zendesk Pull Problem Tickets ====>\n", dbgHighOn);

$startTime = GetZTime();
$call = "/api/v2/problems.json";
$callJIRA = "/rest/api/latest/issue/";
$page = 0;
$ZDProbTickets = [];
$file = "ZD_JIRA_SF_Int_LastPull.txt";
$dbgHTMLFile = "Logs.html";
$AgentPool = [0 => "Jordan Premick"];

//**** Start Log variables ****

$noMatchZenIds = "";//No Zendesk Match Table
$noMatchCnt = 0;//Debug Count No Matches

$noJIRAMatch = "";//No JIRA Issue found
$reportJIRAStatus = "";//Report Status

$zdJIRA_StatusMismatch = "";//ZD -> JIRA Status mismatch
$zd_NoIncidents = "";//No incidents for Problem Ticket
$zd_ProblemIncidentMM = "";//ZD Problem->Incident mismatch
$zd_NoOrgID = "";//No Org ID for Incident

$sf_SOQLFail = "";//SF SOQL Query Failed
$sf_QueryNoResult = ""; //No SF Record found from SOQL Query

$jira_PushData = "";
//**** END Log Variables ****

//Get Last Pull time from file, set to 0 if non exists
if(file_exists($file))
{
    $lastRun = file_get_contents($file);
}
else
{
    echo "\n\n\t\t<*** Last Pull File Created! ***>\n\n";
    $lastRun = "1900/01/01 00:00:00";
}


//Pull all problem tickets
do {
    
    try
    {
        $data = $ZenAPI->call_API("GET", $call);
    }
    catch(Exception $e)
    {
        echo "Connection with Zendesk failed. Check credentials and internet connection. Then try again.\n\n";
        exit();
    }
    
    array_push($ZDProbTickets,$data);
    $page++;
    $call = $data->next_page;
}while($call != null);


dbgEcho("<=== END Zendesk Pull Problem Tickets ====>\n", dbgHighOn);

$zdPage = 0;

dbgEcho("<=== BEGIN Zendesk Problem Tickets Processing ====>\n", dbgHighOn);

//Cycle through all problem tickets
foreach($ZDProbTickets as $ZDpage)
{
    dbgEcho("<=== ZD Page " . $zdPage . " ====>\n", dbgHighOn);
    
    $dbgTicketCount = 0;
    
    foreach($ZDpage->tickets as $ZDticket)
    {
        $hasJIRA = 1;
        $JIRAdata = [];
        $jiraID = "";
        $dbgTicketCount++;
        $sfMRR = 0;
        $aId = 0;
        
        //Get Agent ID and add Agent to Agent Pool if not in it already
        dbgEcho("<!!! Get Assignee Ticket " . $j . " Page " . $i . " !!!>\n", $dbgMedOn);
        
        if($ZDticket->assignee_id != null)
        {
            $aId = $ZDticket->assignee_id;
            
            if($AgentPool[$aId] != null)
            {
                dbgEcho("Agent Found!: " . $AgentPool[$aId] . "\n", $dbgMedOn);
            }//end if agent in pool
            else
            {
                dbgEcho("Add Agent ID: " . $aId . "\n", $dbgLowOn);
                
                try
                {
                    $resp = $ZenAPI->call_API("GET", "/api/v2/users/" . $aId . ".json");
                }
                catch(Exception $e)
                {
                    echo "\t!!!Failed to retrieve Agent with ID: " . $aID . "!!!\n\n";
                }
                
                $AgentPool += [$aId => $resp->user->name];
                dbgEcho("Agent " . $aId . ": " . $AgentPool[$aId] . " Added!\n", $dbgLowOn);
            }//end else agent not in Pool
        }//end if assignee != null
        
        dbgEcho("<=== Processing Ticket " . $dbgTicketCount . " on Page " . $zdPage . " ====>\n", dbgHighOn);
        
        //Pull JIRA Project ID from ZD problem ticket name
        $tempSub = $ZDticket->subject;
        
        if(!is_bool(strpos($tempSub, "CRMT-")))
        {
            $jiraID = "CRMT-";
            $jiraID .= $ZenAPI->after("CRMT-",$tempSub,5);
        }
        elseif(!is_bool(strpos($tempSub, "SCT-")))
        {
            $jiraID = "SCT-";
            $jiraID .= $ZenAPI->after("SCT-",$tempSub,4);
        }
        elseif(!is_bool(strpos($tempSub, "CR-")))
        {
            $jiraID = "CR-";
            $jiraID .= $ZenAPI->after("CR-",$tempSub,3);
        }        
        elseif(!is_bool(strpos($tempSub, "SC-")))
        {
            $jiraID = "SC-";
            $jiraID .= $ZenAPI->after("SC-",$tempSub,3);
        }         
        elseif(!is_bool(strpos($tempSub, "GDPR-")))
        {
            $jiraID = "GDPR-";
            $jiraID .= $ZenAPI->after("GDPR-",$tempSub,5);
        }        
        elseif(!is_bool(strpos($tempSub, "CL-")))
        {
            $jiraID = "CL-";
            $jiraID .= $ZenAPI->after("CL-",$tempSub,3);
        }        
        elseif(!is_bool(strpos($tempSub, "IG-")))
        {
            $jiraID = "IG-";
            $jiraID .= $ZenAPI->after("IG-",$tempSub,3);
        }
        elseif(!is_bool(strpos($tempSub, "KW-")))
        {
            $jiraID = "http://www.knowwho.pro/Issue/Show/";
            $jiraID .= $ZenAPI->after("KW-",$tempSub,2,4);
            
            $hasJIRA = 0;
            dbgEcho("<*** Project ID: " . $jiraID . " does not exist in JIRA! Ignoring JIRA data for this record! ***>\n", $dbgMedOn);
            continue; //go to next ticket 
        }
        elseif(!is_bool(strpos($tempSub, "CP-")))
        {
            $jiraID = "https://copilot.cardpointe.com/copilot/#/ticket/";
            $jiraID .= $ZenAPI->after("CP-",$tempSub,2,7);
            
            $hasJIRA = 0;
            dbgEcho("<*** Project ID: " . $jiraID . " does not exist in JIRA! Ignoring JIRA data for this record! ***>\n", $dbgMedOn);
            continue; //go to next ticket 
        }
        else
        {
            $jiraID = "No JIRA Match -- Incorrect Subject Format!";
            
            //Log No JIRA Match/ Incorrect Subject Format
            $noMatchZenIds .= "<tr><th>" . $ZDticket->id . "</th><th>" . $AgentPool[$aId] . "</th></tr>\n";
            $noMatchCnt++;
            
            dbgEcho("<*** Project ID: " . $jiraID . " does not exist in JIRA! Ignoring JIRA data for this record! ***>\n", $dbgMedOn);
            $hasJIRA = 0;
            continue; //go to next ticket 
        }
        
        //Pull JIRA Data
        if($hasJIRA != 0)
        {
            try
            {
                $JIRAdata = $JIRAAPI->call_API("GET", $callJIRA . $jiraID);
                dbgEcho("<!!! JIRA " . $jiraID . " Found! !!!>\n",dbgMedOn);
            }
            catch (Exception $e)
            {
                dbgEcho("<!!! Project ID: " . $jiraID . " cannot be found due to no JIRA connection! ignoring JIRA data for this record! !!!>\n", $dbgHighOn);
                $hasJIRA = 0;
                
                //Log JIRA !Found
                $noJIRAMatch .= "<tr><th>" . $jiraID . "</th><th>" . $ZDticket->id . "</th><th>" . $AgentPool[$aId] . "</th></tr>";
                    
                continue; //go to next ticket 
            }
        }//end JIRA Pull
        else 
        {
            dbgEcho("<*** Project ID: " . $jiraID . " does not exist in JIRA! Ignoring JIRA data for this record! ***>\n", $dbgMedOn);
            continue; //go to next ticket 
        }
        
        $ZDStatus = "";
        $JIRAStatus = "";
        
        switch($ZDticket->status)
        {
            case "open":
                $ZDStatus = "open";
                break;
            case "solved":
                $ZDStatus = "done";
                break;
            case "closed":
                $ZDStatus = "done";
                break;
            default:
                $ZDStatus = "ignore";
                break;
        }//end ZDStatus switch
        
        switch($JIRAdata->fields->status->name)
        {
            case "New":
                $JIRAStatus = "open";
                break;
            case "Open":
                $JIRAStatus = "open";
                break;
            case "Done":
                $JIRAStatus = "done";
                break;
            case "Investigation Complete\/Solution Provided":
                $JIRAStatus = "done";
                break;
            case "Needs More Information":
                $JIRAStatus = "report";
                break;
            case "Pending Release":
                $JIRAStatus = "open";
                break;
            case "Closed":
                $JIRAStatus = "done";
                break;
            case "Invalid":
                $JIRAStatus = "done";
                break;
            case "Duplicate":
                $JIRAStatus = "done";
                break;
            default:
                $JIRAStatus = "open";
                break;
        }//end JIRAStatus switch
        
        //Skip to next record if we ignore this ZD ticket
        if($ZDStatus == "ignore")
        {
            dbgEcho("<=== ZD Ticket #" . $ZDticket->id . " ignored. Moving to next ticket! ====>\n", dbgMedOn);
            continue; // go to next record
        }
        
        if($JIRAStatus == "report")
        {
            //report ZD ID & JIRA Proj ID with statuses
            $reportJIRAStatus .= "<tr><th>" . $ZDticket->id . "</th><th>" . $ZDticket->status . "</th><th>" . $jiraID . "</th><th>" . $JIRAdata->fields->status->name . "</th><th>" . $AgentPool[$aId] . "</th></tr>\n";
            
            dbgEcho("<=== ZD Ticket #" . $ZDticket->id . " reported. Moving to next ticket! ====>\n", dbgMedOn);
            continue; //go to next record
        }
        
        //Compare ZD Status -> JIRA Status
        if($ZDStatus != $JIRAStatus)
        {
            //Log mismatch with ZD ID, JIRA ID, & statuses for both
            $zdJIRA_StatusMismatch .= "<tr><th>" . $ZDticket->id . "</th><th>" . $ZDticket->status . "</th><th>" . $jiraID . "</th><th>" . $JIRAdata->fields->status->name . "</th><th>" . $AgentPool[$aId] . "</th></tr>\n";
            
            dbgEcho("<=== ZD Ticket #" . $ZDticket->id . " is a MM w/ " . $jiraID . ". Logged and moving to next ticket! ====>\n", dbgMedOn);
            continue; //Go to next record
        }
        
        //Cycle through Open Problem Tickets
        if($ZDStatus == "open")
        {
            //Strip non time characters from returned Zulu Time
            $cmpDate = preg_replace("/[^0-9:-]/", " ", $ZDticket->updated_at);
            $cmpADate = new DateTime($lastRun);
            $cmpBDate = new DateTime($cmpDate);  
            
            //  If last pull date (Zulu) < tickets[i]->updated_at (Zulu)
            if ($cmpADate < $cmpBDate)
            {
                //Pull all incident tickets for this problem ticket if we have ZD ID
                if($ZDticket->id != null)
                {
                    $orgIDs = [];
                    $countIncidents = "";
                    
                    try
                    {
                        $resp = $ZenAPI->call_API("GET", "/api/v2/tickets/" . $ZDticket->id . "/incidents.json");
                    }
                    catch (Exception $e)
                    {
                        //Log no incidents for Problem ticket; log Problem Ticket ID, JIRA Proj ID, & both statuses
                        $zd_NoIncidents .= "<tr><th>" . $ZDticket->id . "</th><th>" . $ZDticket->status . "</th><th>" . $jiraID . "</th><th>" . $JIRAdata->fields->status->name . "</th><th>" . $AgentPool[$aId] . "</th></tr>\n";
                            
                        dbgEcho("<!!! ZD Ticket #" . $ZDticket->id . " incidents pull failed. Moving to next ticket! !!!>\n", dbgMedOn);
                        continue; //go to next record
                    }
                    
                    //Pull count of incident tickets for this problem ticket
                    $countIncidents = $resp->count;
                    
                    if($countIncidents == 0)
                    {
                        //Log no incidents for Problem ticket; log Problem Ticket ID, JIRA Proj ID, & both statuses
                        $zd_NoIncidents .= "<tr><th>" . $ZDticket->id . "</th><th>" . $ZDticket->status . "</th><th>" . $jiraID . "</th><th>" . $JIRAdata->fields->status->name . "</th><th>" . $AgentPool[$aId] . "</th></tr>\n";
                            
                        dbgEcho("<=== ZD Ticket #" . $ZDticket->id . " no incidents. Moving to next ticket! ====>\n", dbgMedOn);
                        continue; //go to next record
                    }
                    
                    foreach($resp->tickets as $incident)
                    {
                        //if incident->status != tickets[i]->status
                        if($incident->status == "solved" || $incident->status == "closed")
                        {
                            //log ZD Problem->Incident mismatch
                            $zd_ProblemIncidentMM .= "<tr><th>" . $ZDticket->id . "</th><th>" . $ZDticket->status . "</th><th>" . $incident->id . "</th><th>" . $incident->status . "</th><th>" . $AgentPool[$aId] . "</th></tr>\n";
                            
                            dbgEcho("<=== ZD Ticket #" . $ZDticket->id . " Problem and Incident " . $incident->id . " MM. ====>\n", dbgMedOn);
                        }
                        
                        //Pull unique Organization_ID from incident tickets
                        if(array_key_exists($incident->organization_id,$orgIDs))
                        {
                            $orgIDs[$incident->organization_id] = $incident->id;
                        }
                        else
                        {
                            $orgIDs[$incident->organization_id] = $incident->id; //Overwrite Org incident ID Should not happen!
                        }
                        
                    }//end for each incident
                    
                    //Pull each unique Organization_ID's name and SF ID from ZD API
                    foreach($orgIDs as $key => $value)
                    {   
                        try
                        {
                            $orgData = $ZenAPI->call_API("GET", "/api/v2/organizations/" . $key . ".json");
                        }
                        catch (Exception $e)
                        {
                            //Log no orgID for incident; log Incident ID
                            $zd_NoOrgID .= "<tr><th>" . $value . "</th><th>" . $AgentPool[$aId] . "</th></tr>\n";
                            
                            dbgEcho("<!!! ZD Incident Ticket #" . $value . " no Org ID. Moving to next incident! !!!>\n", dbgMedOn);
                            continue; //go to next org record
                        }
                        
                        //Check to see if Zendesk has a SF Acct ID for this organization
                        if($orgData->organization->organization_fields->salesforce_account_id == "")
                        {
                            //Get Org Name and prepare encode for SOQL Query to SF
                            $orgRet = $orgData->organization->name;
                            $orgRet =  str_replace("\\", "", $orgRet);
                            
                            //Encode for SOQL Query
                            $orgName = urlencode($orgRet);
                            $orgName = str_replace("+", "%20", $orgName);
                            $orgName = str_replace("/", "%2F", $orgName);
                            
                            //Use SF API Query using Organization name
                            $call = "/services/data/v45.0/query/?q=SELECT+Id+%2C+name+%2C+Most%5FRecent%5FMRR%5F%5Fc+%2C+Initial%5FMRR%5F%5Fc+FROM+Account+WHERE+name+=+%27" . $orgName . "%27";
                            
                            try
                            {
                                $orgQuery = $SFAPI->call_API("GET", $call);
                            }
                            catch (Exception $e)
                            {
                                //Log incident ID & Org Name as SF Query Failed + $orgName
                                $sf_SOQLFail .= "<tr><th>" . $value . "</th><th>" . $orgName . "</th></tr>\n";
                                
                                dbgEcho("<!!! ZD Incident Ticket #" . $value . " Query for " . $orgName . " failed! !!!>\n", dbgMedOn);
                            }
                            
                            //Add MRR from Query if not null result
                            if($orgQuery->records[0] != null)
                            {
                                //Pull Most Recent MRR from SF Query result if not zero
                                if($orgQuery->records[0]->Most_Recent_MRR__c != 0)
                                {
                                    $sfMRR += $orgQuery->records[0]->Most_Recent_MRR__c;
                                }
                                else
                                {
                                    //else pull Initial MRR
                                    $sfMRR += $orgQuery->records[0]->Initial_MRR__c;
                                }
                                
                                $orgID = $orgData->organization->id;
                                
                                $orgData = [
                                    GuzzleHttp\RequestOptions::JSON => [
                                        "organization" => [
                                            "organization_fields" => [
                                                "salesforce_account_id" => $orgQuery->records[0]->Id
                                            ]
                                        ]
                                    ]
                                ];
                                
                                //Push SF Acct ID to ZD Org Record 
                                try
                                {
                                    $ZenAPI->call_PUT_API("/api/v2/organizations/" . $orgID . ".json", $orgData);
                                    echo "Successful push of SF Acct ID:" . $orgQuery->records[0]->Id . " for ZD ORg ID: " . $orgID . "!\n\n";
                                }
                                catch (Exception $e)
                                {
                                    echo "Could not update Organization ZD ID: " . $orgID . " with SF Acct ID\n\n";
                                }
                            }//end if Query returns results
                            else
                            {
                                //Log incident ID & Org Name as SF Query not Found
                                $sf_QueryNoResult .= "<tr><th>" . $value . "</th><th>" . $orgRet . "</th></tr>\n";
                                
                                dbgEcho("<=== ZD Incident Ticket #" . $value . " Query for " . $orgRet . " found! ====>\n", dbgMedOn);
                            }
                              
                        }//end if SF ID is null
                        else
                        {
                            //Pull Most Recent MRR from SF using AcctID
                            try
                            {
                                $sfData = $SFAPI->call_API("GET", "/services/data/v45.0/sobjects/Account/" . $orgData->organization->organization_fields->salesforce_account_id);   
                            }
                            catch (Exception $e)
                            {
                                echo "<!!! Failed to connect to SF ID: " . $orgData->organization->organization_fields->salesforce_account_id . " No MRR recorded! !!!>";
                                continue;
                            }
                            
                            //Pull most recent MRR if not 0 else pull initial MRR
                            if($sfData->Most_Recent_MRR__c != 0)
                            {
                                $sfMRR += $sfData->Most_Recent_MRR__c;
                            }
                            else
                            {
                                $sfMRR += $sfData->Initial_MRR__c;
                            }
                            
                        }//end else (SF ID not null)
                        
                    }//end foreach key in orgIDs
                    
                    //If JIRA incidentCount, MRR mismatch, or Problem Ticket Mismatch
                    if($countIncidents != $JIRAdata->fields->customfield_12001 || $sfMRR != $JIRAdata->fields->customfield_12005 || $ZDticket->id != $JIRAdata->fields->customfield_12003)
                    {
                        //Push incidentCount & MRR to JIRA issue
                        echo "\n<--- PUSH to JIRA - Problem Ticket ID: " . $ZDticket->id . " --->\n\tIncident Count: " . $countIncidents . "\n";
                        echo "\tTotal MRR: $" . $sfMRR . "\n<--- END PUSH JIRA - Problem Ticket ID: " . $ZDticket->id . " --->\n";
                        
                        //Setup JIRA push data
                        $JIRAdata = [
                            GuzzleHttp\RequestOptions::JSON => [
                                "fields" => [
                                    "customfield_12001" => floatval($countIncidents),
                                    "customfield_12005" => floatval($sfMRR),
                                    "customfield_12003" => strval($ZDticket->id)
                                ]
                            ]
                        ];
                        
                        try
                        {
                            //Call JIRA PUT to update JIRA issue with ACC MRR, Incident Count, and ZD ID
                            $JIRAresp = $JIRAAPI->call_PUT_API("https://jira.salsalabs.net/rest/api/latest/issue/" . $jiraID . "?notifyUsers=false", $JIRAdata);   
                        }
                        catch (Exception $e)
                        {
                            echo "Failed to push data to JIRA: " . $JIRAresp . "\n\nPlease review log files for data that would have been pushed\n\n";
                        }
                        
                        //Push to logs
                        $jira_PushData .= "<tr><th>" . $ZDticket->id . "</th><th>" . $jiraID . "</th><th>" . $countIncidents . "</th><th>" . $sfMRR . "</th></tr>\n";
                    }
                    
                }//end if ZDticket is not null
                
            }//end if last Run < ZD updated at
            
        }//end cycle through Open Problem Tickets
        
    }//end foreach ZDticket
    
    $zdPage++;
}//end foreach ZDpage

dbgEcho("<=== END Zendesk Problem Tickets Processing ====>\n", dbgHighOn);

//Log Current Date and Time (Zulu) as last pull date in cache/file
$zDate = GetZTime();
file_put_contents($file, $zDate);
$endTime = $zDate;

//Gather All logs convert -> HTML
//Create HTML Header
$logHTML = "<html>
              <head>
                <style>
                  table {
                    font-family: arial, sans-serif;
                    border-collapse: collapse;
                    width: 100%;
                  }

                  td, th {
                    border: 1px solid #000000;
                    text-align: left;
                    padding: 8px;
                  }

                  tr:nth-child(even) {
                    background-color: #dddddd;
                  }
                  
                  h4 {
                    line-height: 12px;
                  }
                </style>
              </head>
              <body>
                <center><h2>ZenDesk JIRA SFDC Integration v1.0.b</h2></center>
                <br/>
                <h4><big>Last Run:</big> " . $lastRun . "</h4>
                <h4><big>Start Time:</big> " . $startTime . "</h4>
                <h4><big>End Time:</big>&nbsp;&nbsp;" . $endTime ."</h4>\n";

if($noMatchZenIds != "")
{
    //Build No Match Zendesk Ids table header then append logs and footer
    $logHTML .= "<h3>No ZenDesk ID Match/Incorrect Subject format</h3>
                <table>
                    <tr><th>ZenDesk ID</th><th>Agent</th></tr>\n";
    $logHTML .= $noMatchZenIds . "</table><br/>\n";
}

if($noJIRAMatch != "")
{
    //Build JIRA Project Not Found table header then append logs and footer
    $logHTML .= "<h3>JIRA Project Not Found</h3><table><tr><th>JIRA ID from ZD Subject</th><th>Zendesk Problem Ticket ID</th><th>Agent</th></tr>";
    $logHTML .= $noJIRAMatch . "</table><br/>";
}

if($zdJIRA_StatusMismatch != "")
{
    //Build ZD -> JIRA Status MM table header then append logs and footer
    $logHTML .= "<h3>ZD -> JIRA Status Mismatch</h3>
                <table>
                    <tr><th>ZD ID</th><th>ZD Status</th><th>JIRA ID</th><th>JIRA Status</th><th>Agent</th></tr>\n";
    $logHTML .= $zdJIRA_StatusMismatch . "</table><br/>\n";
}

if($zd_NoIncidents != "")
{
    //Build No Incidents for ZD Problem Ticket table header then append logs and footer
    $logHTML .= "<h3>No Incidents for ZD Problem Ticket</h3>
                <table>
                    <tr><th>ZD ID</th><th>ZD Status</th><th>JIRA ID</th><th>JIRA Status</th><th>Agent</th></tr>\n";
    $logHTML .= $zd_NoIncidents . "</table><br/>\n";
}

if($zd_NoOrgID != "")
{
    //Build No Org ID for ZD Incident table header then append logs and footer
    $logHTML .= "<h3>No Org ID for ZD Incident</h3>
                <table>
                    <tr><th>Incident ID</th><th>Agent</th></tr>\n";
    $logHTML .= $zd_NoOrgID . "</table><br/>\n";
}

if($reportJIRAStatus != "")
{
    //Build Report Status table header then append logs and footer
    $logHTML .= "<h3>JIRA Status Needs More Information</h3>
                <table>
                    <tr><th>ZD ID</th><th>ZD Status</th><th>JIRA ID</th><th>JIRA Status</th><th>Agent</th></tr>\n";
    $logHTML .= $reportJIRAStatus . "</table><br/>\n";
}

if($zd_ProblemIncidentMM != "")
{
    //Build ZD Problem -> Incident Status MM table header then append logs and footer
    $logHTML .= "<h3>ZD Problem -> Incident Status Mismatch</h3>
                <table>
                    <tr><th>ZD ID</th><th>ZD Status</th><th>Incident ID</th><th>Incident Status</th><th>Agent</th></tr>\n";
    $logHTML .= $zd_ProblemIncidentMM . "</table><br/>\n";
}

//Create seperate push for local file
$logfileHTML = $logHTML;

if($sf_SOQLFail != "")
{
    //Build SF SOQL Query Failed table header then append logs and footer
    $logfileHTML .= "<h3>SalesForce SOQL Query Failed</h3>
                <table>
                    <tr><th>Incident ID</th><th>Org Name</th></tr>\n";
    $logfileHTML .= $sf_SOQLFail . "</table><br/>\n";
}

if($sf_QueryNoResult != "")
{
    //Build SF Query No Results table header then append logs and footer
    $logfileHTML .= "<h3>SalesForce SOQL Query No Results</h3>
                <table>
                    <tr><th>Incident ID</th><th>Org Name</th></tr>\n";
   $logfileHTML .= $sf_QueryNoResult . "</table><br/>\n";
}

if($jira_PushData != "")
{
    //Build SF Query No Results table header then append logs and footer
    $logfileHTML .= "<h3>JIRA Push Data</h3>
                <table>
                    <tr><th>Problem Ticket ID</th><th>JIRA ID</th><th>Incident Count</th><th>Total MRR $</th></tr>\n";
    $logfileHTML .= $jira_PushData . "</table><br/>\n";
    $logfileHTML .= "</body>
</html>";
}

$logHTML .= "</body>
</html>";

//Create HTML file for debug
file_put_contents($dbgHTMLFile, $logfileHTML);

//*** UPDATE Ticket that sends update ***
$date = date("F j, Y");
$ticketData = [
    GuzzleHttp\RequestOptions::JSON => [
        "ticket" => [
            "subject" => $date . " Integration APP Logs",
            "requester_id" => $cred["ZD_ID"],
            "assignee_id" => $cred["ZD_ID"],
            "comment" => [
                "html_body" => $logHTML
            ]
        ]
    ]
];

try
{
    $resp = $ZenAPI->call_POST_API("/api/v2/tickets.json", $ticketData);
}
catch (Exception $e)
{
    echo "\t\t !!! Could not create log ticket in Zendesk. Please retrieve logs from file output.\n\n";
}
//*** END UPDATE ***

dbgEcho("<=== Files created End Program ====>\n", dbgHighOn);

//TODO: Use ZD trigger to send Jordan(using ZD Agent ID) the HTML Logs
?>