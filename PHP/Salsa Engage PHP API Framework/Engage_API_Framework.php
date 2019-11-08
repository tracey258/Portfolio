<?php

/****************************|
|                            |
| Salsa Engage API Framework |
|       Version 1.0.0        |
| Created by Tracey Roberts  |
|   Last Update April 2019   |
|                            |
|****************************/

// Uses Composer.
require 'vendor/autoload.php';
use GuzzleHttp\Client;

class Engage_API{
    
    public $client = null;
    
    function Init_API($AuthToken, $uri)
    {
        $headers = [
            'authToken' => $AuthToken,
            'Content-Type' => 'application/json'
        ];

        $this->$client = new GuzzleHttp\Client([
        'base_uri' => $uri,
        'headers'  => $headers
        ]);
    }

    function Int_API($command, $payload = false)
    {
        switch ($command)
        {
            case "Metrics":
                $IntCmd = '/api/integration/ext/v1/metrics';
                $method = 'GET';
                $response = $this->$client->request($method, $IntCmd);
                break;
                
            case "Search_Activities":
                $IntCmd = '/api/integration/ext/v1/activities/search';
                $method = 'POST';
                $response = $this->CheckPayload($IntCmd, $method, $payload);
                break;

            case "Search_Supporters":
                $IntCmd = '/api/integration/ext/v1/supporters/search';
                $method = 'POST';
                $response = $this->CheckPayload($IntCmd, $method, $payload);
                break;
                
            case "PUT_Supporters":
                $IntCmd = '/api/integration/ext/v1/supporters';
                $method = 'PUT';
                $response = $this->CheckPayload($IntCmd, $method, $payload);
                break;
                
            case "Delete_Supporters":
                $IntCmd = '/api/integration/ext/v1/supporters';
                $method = 'DELETE';
                $response = $this->CheckPayload($IntCmd, $method, $payload);
                break;
                
            case "Search_Segment":
                $IntCmd = '/api/integration/ext/v1/segments/search';
                $method = 'POST';
                $response = $this->CheckPayload($IntCmd, $method, $payload);
                break;
                
            case "PUT_Segment":
                $IntCmd = '/api/integration/ext/v1/segments';
                $method = 'PUT';
                $response = $this->CheckPayload($IntCmd, $method, $payload);
                break;
                
            case "Delete_Segment":
                $Int_Cmd = '/api/integration/ext/v1/segments';
                $method = 'DELETE';
                $response = $this->CheckPayload($IntCmd, $method, $payload);
                break;
                
            case "Search_Members_Segment":
                $IntCmd = '/api/integration/ext/v1/segments/members/search';
                $method = 'POST';
                $response = $this->CheckPayload($IntCmd, $method, $payload);
                break;
                
            case "PUT_Members_Segment":
                $IntCmd = '/api/integration/ext/v1/segments/members';
                $method = 'PUT';
                $response = $this->CheckPayload($IntCmd, $method, $payload);
                break;
                
            case "Remove_Members_Segment":
                $IntCmd = '/api/integration/ext/v1/segments/members';
                $method = 'DELETE';
                $response = $this->CheckPayload($IntCmd, $method, $payload);
                break;
                
            case "Merge_Supporters":
                $IntCmd = '/api/integration/ext/v1/supporters/merge';
                $method = 'POST';
                $response = $this->CheckPayload($IntCmd, $method, $payload);
                break;
                
            case "Offline_Donations":
                $IntCmd = '/api/integration/ext/v1/offlineDonations';
                $method = 'POST';
                $response = $this->CheckPayload($IntCmd, $method, $payload);
                break;
                
            case "Email_Individual_Results":
                $IntCmd = '/api/integration/ext/v1/emails/individualResults';
                $method = 'POST';
                $response = $this->CheckPayload($IntCmd, $method, $payload);
                break;
                
            case "Email_Search_Results":
                $IntCmd = '/api/integration/ext/v1/emails/search';
                $method = 'POST';
                $response = $this->CheckPayload($IntCmd, $method, $payload);
                break;
                
            default: 
                return "Invalid Integration Command >> " . $command . " -- Command Not Found";
        }
        
        if($response)
        {
            $data = json_decode($response -> getBody());
        }
        else 
        {
            $data = "Invalid Integration Command >> " . $command . " -- No response from API\n";
        }

        return $data;
    }//End Int_API
    
    function CheckPayload($IntCmd, $method, $payload)
    {
        if($payload)
        {
            $response = $this->$client->request($method, $IntCmd, [
                'json' => $payload
            ]);
        }
        else
        {
            echo "Invalid Integration Command >> " . $IntCmd . " -- No Payload\n";
            $response = null;
        }

        return $response;
        
    }//END CheckPayloadAndRun
    
    function Dev_API($command, $params = false, $payload = false)
    {
        switch ($command)
        {
            case "Metrics":
                $DevCmd = '/api/developer/ext/v1/callMetrics';
                $method = 'GET';
                $response = $this->$client->request($method, $DevCmd);
                break;
                
            case "Get_Activity_Types":
                $DevCmd = '/api/developer/ext/v1/activities/types';
                $method = 'GET';
                $response = $this->$client->request($method, $DevCmd);
                break;
                
            case "Get_Activity_List":
                $DevCmd = '/api/developer/ext/v1/activities';
                $method = 'GET';
                $response = $this->CheckParams($DevCmd, $method, $params);
                break;
                
            case "Get_Activity_Metadata":
                $DevCmd = '/api/developer/ext/v1/activities/UUID/metadata';
                $method = 'GET';
                $response = $this->CheckParams($DevCmd, $method, $params);
                break;
                
            case "Get_Activity_Summary":
                $DevCmd = '/api/developer/ext/v1/activities/UUID/summary';
                $method = 'GET';
                $response = $this->CheckParams($DevCmd, $method, $params);
                break;
                
            case "Get_Activity_Targets":
                $DevCmd = '/api/developer/ext/v1/activities/UUID/summary/targets';
                $method = 'GET';
                $response = $this->CheckParams($DevCmd, $method, $params);
                break;
                
            case "Get_Activity_Attendees":
                $DevCmd = '/api/developer/ext/v1/activities/UUID/summary/attendees';
                $method = 'GET';
                $response = $this->CheckParams($DevCmd, $method, $params);
                break;
                
            case "Get_Activity_Registrations":
                $DevCmd = '/api/developer/ext/v1/activities/UUID/summary/registrations';
                $method = 'GET';
                $response = $this->CheckParams($DevCmd, $method, $params);
                break;
                
            case "Get_Activity_Fundraisers":
                $DevCmd = '/api/developer/ext/v1/activities/UUID/summary/fundraisers';
                $method = 'GET';
                $response = $this->CheckParams($DevCmd, $method, $params);
                break;
                
            case "Get_Activity_Teams":
                $DevCmd = '/api/developer/ext/v1/activities/UUID/summary/teams';
                $method = 'GET';
                $response = $this->CheckParams($DevCmd, $method, $params);
                break;
                
            case "Get_Activity_Purchases":
                $DevCmd = '/api/developer/ext/v1/activities/UUID/summary/purchases';
                $method = 'GET';
                $response = $this->CheckParams($DevCmd, $method, $params);
                break;
                
            case "Get_FundraiserMeta_Summary":
                $DevCmd = '/api/developer/ext/v1/activities/fundraisers/UUID';
                $method = 'GET';
                $response = $this->CheckParams($DevCmd, $method, $params);
                break;
                
            case "Get_TeamMeta_Summary":
                $DevCmd = '/api/developer/ext/v1/activities/teams/UUID';
                $method = 'GET';
                $response = $this->CheckParams($DevCmd, $method, $params);
                break;
                
            case "Get_EmailBlast_List":
                $DevCmd = '/api/developer/ext/v1/blasts';
                $method = 'GET';
                $response = $this->CheckParams($DevCmd, $method, $params);
                break;
                
            case "Submissions":
                $DevCmd = '/api/developer/ext/v1/submissions';
                $method = 'POST';
                $response = $this->CheckPayload($DevCmd, $method, $payload);
                break;
                
            default: 
                return "Invalid Developer Command >> " . $command . " -- Command Not Found";
        }
        
        if($response)
        {
            $data = json_decode($response -> getBody());
        }
        else 
        {
            $data = "Invalid Developer Command -- No response from API";
        }

        return $data;
    }//End Dev_API
    
    function CheckParams($DevCmd, $method, $params)
    {
        switch($DevCmd)
        {
            case "/api/developer/ext/v1/activities":
                if($params)
                    $DevCmd .= $params;
                break;
                
            case "/api/developer/ext/v1/blasts":
                if($params)
                    $DevCmd .= $params;
                break;
                
            case "/api/developer/ext/v1/activities/UUID/metadata":
                if($params)
                    $DevCmd = str_replace("UUID", $params, $DevCmd);
                else
                {
                    echo "Invalid Developer Command >> Get_Activity_Metadata -- Mandatory UUID Parameter\n";
                    return null;
                }
                break;
                
            case "/api/developer/ext/v1/activities/UUID/summary":
                if(!is_array($params))
                {
                    $DevCmd = str_replace("UUID", $params, $DevCmd);
                }
                elseif(is_array($params))
                {
                    $DevCmd = str_replace("UUID", $params[0], $DevCmd);
                    if($params[1])
                        $DevCmd .= $params[1];
                }
                else
                {
                    echo "Invalid Developer Command >> Get_Activity_Summary -- Mandatory UUID Parameter\n";
                    return null;
                }
                break;
                
            case "/api/developer/ext/v1/activities/UUID/summary/targets":
                if(!is_array($params))
                {
                    $DevCmd = str_replace("UUID", $params, $DevCmd);
                }
                elseif(is_array($params))
                {
                    $DevCmd = str_replace("UUID", $params[0], $DevCmd);
                    if($params[1])
                        $DevCmd .= $params[1];
                }
                else
                {
                    echo "Invalid Developer Command >> Get_Activity_Targets -- Mandatory UUID Parameter\n";
                    return null;
                }
                break;
                
            case "/api/developer/ext/v1/activities/UUID/summary/attendees":
                if(!is_array($params))
                {
                    $DevCmd = str_replace("UUID", $params, $DevCmd);
                }
                elseif(is_array($params))
                {
                    $DevCmd = str_replace("UUID", $params[0], $DevCmd);
                    if($params[1])
                        $DevCmd .= $params[1];
                }
                else
                {
                    echo "Invalid Developer Command >> Get_Activity_Attendees -- Mandatory UUID Parameter\n";
                    return null;
                }
                break;
                
            case "/api/developer/ext/v1/activities/UUID/summary/fundraisers":
                if(!is_array($params))
                {
                    $DevCmd = str_replace("UUID", $params, $DevCmd);
                }
                elseif(is_array($params))
                {
                    $DevCmd = str_replace("UUID", $params[0], $DevCmd);
                    if($params[1])
                        $DevCmd .= $params[1];
                }
                else
                {
                    echo "Invalid Developer Command >> Get_Activity_Fundraisers -- Mandatory UUID Parameter\n";
                    return null;
                }
                break;
                
            case "/api/developer/ext/v1/activities/UUID/summary/teams":
                if(!is_array($params))
                {
                    $DevCmd = str_replace("UUID", $params, $DevCmd);
                }
                elseif(is_array($params))
                {
                    $DevCmd = str_replace("UUID", $params[0], $DevCmd);
                    if($params[1])
                        $DevCmd .= $params[1];
                }
                else
                {
                    echo "Invalid Developer Command >> Get_Activity_Teams -- Mandatory UUID Parameter\n";
                    return null;
                }
                break;
                
            case "/api/developer/ext/v1/activities/UUID/summary/purchases":
                if(!is_array($params))
                {
                    $DevCmd = str_replace("UUID", $params, $DevCmd);
                }
                elseif(is_array($params))
                {
                    $DevCmd = str_replace("UUID", $params[0], $DevCmd);
                    if($params[1])
                        $DevCmd .= $params[1];
                }
                else
                {
                    echo "Invalid Developer Command >> Get_Activity_Purchases -- Mandatory UUID Parameter\n";
                    return null;
                }
                break;
                
            case "/api/developer/ext/v1/activities/UUID/summary/registrations":
                if(!is_array($params))
                {
                    $DevCmd = str_replace("UUID", $params, $DevCmd);
                }
                elseif(is_array($params))
                {
                    $DevCmd = str_replace("UUID", $params[0], $DevCmd);
                    if($params[1])
                        $DevCmd .= $params[1];
                }
                else
                {
                    echo "Invalid Developer Command >> Get_Activity_Registrations -- Mandatory UUID Parameter\n";
                    return null;
                }
                break;
                
            case "/api/developer/ext/v1/activities/fundraisers/UUID":
                if($params)
                    $DevCmd = str_replace("UUID", $params, $DevCmd);
                else
                {
                    echo "Invalid Developer Command >> Get_FundraiserMeta_Summary -- Mandatory UUID Parameter\n";
                    return null;
                }
                break;
                
            case "/api/developer/ext/v1/activities/teams/UUID":
                if($params)
                    $DevCmd = str_replace("UUID", $params, $DevCmd);
                else
                {
                    echo "Invalid Developer Command >> Get_TeamMeta_Summary -- Mandatory UUID Parameter\n";
                    return null;
                }
                break;
        }
        
        return $response = $this->$client->request($method, $DevCmd);
    }//END CheckParams
}
?>