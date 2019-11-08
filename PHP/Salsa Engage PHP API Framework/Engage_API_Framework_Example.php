<?php

// Use the Engage API Framework PHP
require 'Engage_API_Framework.php';

$IntAuthToken = "kJaajG-TYmF2-xJf2mmb7VH4e5r3mZ_qVyeB_nGonm5I1UGXEibQAsY-7X6PrueNnBHm5Mzbb5ici_5_XdVvFHKY5C5IXE2JI02VbiUsgx8";
$DevAuthToken = "kJaajG-TYmF2-xJf2mmb7VH4e5r3mZ_qVyeB_nGonm6Gf6kFZtndVBq9y5n1znX9XbsaLBptZY12bY8CK9POolOEwgoLXRhEYS-npx7s7vczOCMITBe5zlIhDWvx-X5U";
$UATUri = 'https://hq.uat.igniteaction.net';
$IntUri = 'https://api.salsalabs.org';
$DevUri = 'https://dev-api.salsalabs.org';

//Create API Framework instance then Initialize
$IntAPI = new Engage_API;
$IntAPI->Init_API($IntAuthToken, $UATUri);


// https://help.salsalabs.com/hc/en-us/articles/224531208-General-Use#curl-example
//Run Integration Metrics and display
echo "\n<==== Begin Integration Metrics ====>\n";
$data = $IntAPI->Int_API("Metrics");
echo json_encode($data, JSON_PRETTY_PRINT);
echo "\n<==== END Integration Metrics ====>\n";


/*
//Create Payload for search request
$payload = [
    'payload' => [
        'modifiedFrom' => '2015-01-01T11:49:24.905Z',
        'count' => 10,
        'offset' => 0,
    ],
];
*/

/*
//Search for activity test
// https://help.salsalabs.com/hc/en-us/articles/224470267-Engage-API-Activity-Data#acquiring-activities
echo "\n<==== BEGIN Search Activities ====>\n";
$data = $IntAPI->Int_API("Search_Activities", $payload);
echo json_encode($data, JSON_PRETTY_PRINT);
echo "\n<==== END Search Activities ====>\n";
*/

/*
//Search for supporter test
// https://help.salsalabs.com/hc/en-us/articles/224470107-Engage-API-Supporter-Data#searching-for-supporters
echo "\n<==== BEGIN Search Supporters ====>\n";
$data = $IntAPI->Int_API("Search_Supporters", $payload);
echo json_encode($data, JSON_PRETTY_PRINT);
echo "\n<==== END Search Supporters ====>\n";
*/

/*
//Create Payload for delete request
$payload = [
    'payload' => [
        'supporters' => [
            ['supporterId' => '695185d9-a1c9-4cf2-8b11-23457aa7baef'],
            ['supporterId' => '240f8744-da5f-4bc5-b176-3cb8067831ac'],
            ['supporterId' => '5a208717-4559-4b94-b718-1b51d7cf318c'],
            ['supporterId' => '6bbe047a-c9df-468b-b9c6-9b89377223bb'],
        ]
    ]
];

// https://help.salsalabs.com/hc/en-us/articles/224470107-Engage-API-Supporter-Data#deleting-supporters
echo "\n<==== BEGIN Delete Supporters ====>\n";
$data = $IntAPI->Int_API("Delete_Supporters", $payload);
echo json_encode($data, JSON_PRETTY_PRINT);
echo "\n<==== END Delete Supporters ====>\n";
*/

/*
//Create Payload for adding a Supporter
$payload = [
    'payload' => [
        'supporters' => [
            ['title' => 'Mr.', 'firstName' => 'Salsa', 'lastName' => 'Staff', 'dateOfBirth' => '1985-05-06T04:00:00.000Z', 'gender' => 'MALE', 'contacts' => [
                    ['type' => 'EMAIL', 'value' => 'address@email.com', 'status' => 'OPT_IN']
                ]
            ],
            ['supporterId' => '240f8744-da5f-4bc5-b176-3cb8067831ac', 'title' => 'Mr.', 'firstName' => 'Salsa','lastName' => 'Staff', 'dateOfBirth' => '1985-05-06T04:00:00.000Z', 'middleName' => 'Middle'],
            ['supporterId' => '00000000-af08-42bb-8ca2-50fc232e7b40','title' => 'Mr.', 'firstName' => 'Salsa','lastName' => 'Staff', 'dateOfBirth' => '1985-05-06T04:00:00.000Z', 'middleName' => 'Middle'],
            ['supporterId' => 'debd7904-f17b-46be-a0f9-d3684735b66f','title' => 'Mr.', 'firstName' => 'Salsa','lastName' => 'Staff', 'dateOfBirth' => '1985-05-06T04:00:00.000Z', 'middleName' => 'Middle', 'contacts' => [
                    ['type' => 'EMAIL', 'value' => 'bad@email@bad.com', 'status' => 'OPT_IN'] //Create an invalid request due to email being incorrect
                ]
            ],
        ]
    ]
];

// https://help.salsalabs.com/hc/en-us/articles/224470107-Engage-API-Supporter-Data#adding-updating-and-deleting-supporters
echo "\n<==== BEGIN PUT Supporters ====>\n";
$data = $IntAPI->Int_API("PUT_Supporters", $payload);
echo json_encode($data, JSON_PRETTY_PRINT);
echo "\n<==== END PUT Supporters ====>\n";
*/

/*
//Create Payload for Segment Search Request
$payload = [
    'payload' => [
        'identifierType' => 'SEGMENT_ID', 'identifiers' => [
            '59a11107-9e0a-46ab-8d4b-a75b74f9e935'
        ],
    ]
];

//Search_Segment
// https://help.salsalabs.com/hc/en-us/articles/224531528-Engage-API-Segment-Data#searching-for-segments
echo "\n<==== Begin Search_Segment ====>\n";
$data = $IntAPI->Int_API("Search_Segment", $payload);
echo json_encode($data, JSON_PRETTY_PRINT);
echo "\n<==== END Search_Segment ====>\n";
*/

/*
//Create Payload Add/Update Segment
$payload = [
    'payload' => [
        'segments' => [
            ['segmentId' => 'ba47bcd3-ce2e-4ba3-aa4e-00377d7cf16d', 'name' => 'Update_Test_Group!', 'description' => 'Update 3', 'externalSystemIdId' => '98735'], //Update Segment
            ['name' => 'New_Test_Group2!', 'description' => 'Created Test Group', 'externalSystemIdId' => '98736'],//Create new Segment
        ]
    ]
];

//PUT_Segment
// https://help.salsalabs.com/hc/en-us/articles/224531528-Engage-API-Segment-Data#addingupdating-segments
echo "\n<==== Begin PUT_Segment ====>\n";
$data = $IntAPI->Int_API("PUT_Segment", $payload);
echo json_encode($data, JSON_PRETTY_PRINT);
echo "\n<==== END PUT_Segment ====>\n";
*/

/*
//Create Payload to delete Segment
$payload = [
    'payload' => [
        'segments' => [
            ['segmentId' => '376010ec-4a1b-423d-a73e-4e21fbc8c230'],
        ]
    ]
];

//TODO: Delete Segments may not work currently with the Engage API. Under development
//ERROR: `405 Http method DELETE is not supported by this URL` 
// https://help.salsalabs.com/hc/en-us/articles/224531528-Engage-API-Segment-Data#deleting-segments
echo "\n<==== Begin Delete_Segment ====>\n";
$data = $IntAPI->Int_API("Delete_Segment", $payload);
echo json_encode($data, JSON_PRETTY_PRINT);
echo "\n<==== END Delete_Segment ====>\n";
*/


/*
$payload = [
    'payload' => [
        'segmentId' => '59a11107-9e0a-46ab-8d4b-a75b74f9e935', 'offset' => '0', 'count' => '20'
    ]
];

//Search_Members_Segment
// https://help.salsalabs.com/hc/en-us/articles/224531528-Engage-API-Segment-Data#searching-for-supporters-assigned-to-a-segment
echo "\n<==== Begin Search_Members_Segment ====>\n";
$data = $IntAPI->Int_API("Search_Members_Segment", $payload);
echo json_encode($data, JSON_PRETTY_PRINT);
echo "\n<==== END Search_Members_Segment ====>\n";
*/

/*
$payload = [
    'payload' => [
        'segmentId' => '59a11107-9e0a-46ab-8d4b-a75b74f9e935', 'supporterIds' => [
            '06c9bab8-1c1f-4995-8948-a224df032260','0ecf03b6-716b-440d-bf20-ac09d1780706','2197e3f8-a0d3-46a2-9429-fcbdf0df9cf5'
        ]
    ]
];
*/

/*
//PUT_Member_Segment
// https://help.salsalabs.com/hc/en-us/articles/224531528-Engage-API-Segment-Data#assigning-supporters-to-a-segment
echo "\n<==== Begin PUT_Members_Segment ====>\n";
$data = $IntAPI->Int_API("PUT_Members_Segment", $payload);
echo json_encode($data, JSON_PRETTY_PRINT);
echo "\n<==== END PUT_Members_Segment ====>\n";
*/
    
/*
//Remove_Member_Segment
// https://help.salsalabs.com/hc/en-us/articles/224531528-Engage-API-Segment-Data#deleting-supporters-from-a-segment
echo "\n<==== Begin Remove_Members_Segment ====>\n";
$data = $IntAPI->Int_API("Remove_Members_Segment", $payload);
echo json_encode($data, JSON_PRETTY_PRINT);
echo "\n<==== END Remove_Members_Segment ====>\n";
*/
    
/*
$payload = [
    'payload' => [
        'destination' => [
            'readOnly' => 'true', 'supporterId' => '4234ce67-aa4e-489c-8ffc-7b238ef829f4'
        ],
        'source' => [
            'supporterId' => '65f58ec0-32eb-4c52-b2bc-9bb2368fb92a'
        ]
    ]
];

//Merge_Supporters
// https://help.salsalabs.com/hc/en-us/articles/360007113814-Engage-API-Merge-Supporter-Records
echo "\n<==== Begin Merge_Supporters ====>\n";
$data = $IntAPI->Int_API("Merge_Supporters", $payload);
echo json_encode($data, JSON_PRETTY_PRINT);
echo "\n<==== END Merge_Supporters ====>\n";
*/

/*
$payload = [
    'payload' => [
        'donations' => [
            ['type' => 'CHARGE', 'date' => '2019-04-17T00:00:00.0Z', 'amount' => '500.0', 'gatewayTransactionId' => "1915149068231", 'supporter' => [
                'title' => 'Mr.', 'firstName' => 'Tres', 'lastName' => 'Robertoe', 'dateOfBirth' => '1985-05-06T04:00:00.000Z', 'gender' => 'MALE', 'contacts' => [
                        ['type' => 'EMAIL', 'value' => 'troberts+robertoe@salsalabs.com', 'status' => 'OPT_IN']
                    ] 
                ]
            ]
        ]
    ]
];

//Offline_Donations
// https://help.salsalabs.com/hc/en-us/articles/360002275693-Engage-API-Offline-Donations (3 Use Cases to Test)
echo "\n<==== Begin Offline_Donations ====>\n";
$data = $IntAPI->Int_API("Offline_Donations", $payload);
echo json_encode($data, JSON_PRETTY_PRINT);
echo "\n<==== END Offline_Donations ====>\n";
*/

/*
//Create Payload for Email Results
$payload = [
    'payload' => [
        'type' => 'Email', 'id' => '6fbcbe1d-8a84-4d6b-8dc2-19f7698f7866'
    ]
];

//Email_Individual_Results
// https://help.salsalabs.com/hc/en-us/articles/360019505914-Engage-API-Email-Results
echo "\n<==== Begin Email_Individual_Results ====>\n";
$data = $IntAPI->Int_API("Email_Individual_Results", $payload);
echo json_encode($data, JSON_PRETTY_PRINT);
echo "\n<==== END Email_Individual_Results ====>\n";
*/

/*
//Create Payload for Email Search
$payload = [
    'payload' => [
        'publishedFrom' => '2015-04-26T11:49:24.905Z','publishedTo' => '2019-12-31T11:49:24.905Z', 'type' => 'Email', 'offset' => '0', 'count' => '10'
    ]
];

//Email_Search_Results
// https://help.salsalabs.com/hc/en-us/articles/360019505914-Engage-API-Email-Results#email-activity-types
echo "\n<==== Begin Email_Search_Results ====>\n";
$data = $IntAPI->Int_API("Email_Search_Results", $payload);
echo json_encode($data, JSON_PRETTY_PRINT);
echo "\n<==== END Email_Search_Results ====>\n";
*/

//**** Developer API Section ****//
//Initialize API with Developer Token
$DevAPI = new Engage_API;
$DevAPI->Init_API($DevAuthToken, $UATUri);

/*
//Developer Metrics
// https://help.salsalabs.com/hc/en-us/articles/360001207013-API-Metrics
//Run Developer Metrics and display
echo "\n<==== Begin Developer Metrics ====>\n";
$data = $DevAPI->Dev_API("Metrics");
echo json_encode($data, JSON_PRETTY_PRINT);
echo "\n<==== END Developer Metrics ====>\n";
*/

/*
//Get Activity Types
// https://help.salsalabs.com/hc/en-us/articles/360001206893-Activity-Form-Types
echo "\n<==== Begin Get Activity Types ====>\n";
$data = $DevAPI->Dev_API("Get_Activity_Types");
echo json_encode($data, JSON_PRETTY_PRINT);
echo "\n<==== END Get Activity Types ====>\n";
*/

/*
//Get Activity List
// https://help.salsalabs.com/hc/en-us/articles/360001206693-Activity-Form-List
echo "\n<==== Begin Get Activity List ====>\n";
$params = "?activityTypes=FUNDRAISE,PETITION";
$data = $DevAPI->Dev_API("Get_Activity_List", $params);
echo json_encode($data, JSON_PRETTY_PRINT);
echo "\n<==== END Get Activity List ====>\n";
*/

/*
//Get Activity Metadata
// https://help.salsalabs.com/hc/en-us/articles/360001219914-Activity-Form-Metadata
echo "\n<==== Begin Get Activity Metadata ====>\n";
$params = "b53531b0-202e-499e-a0ea-1c506377a237";
$data = $DevAPI->Dev_API("Get_Activity_Metadata", $params);
echo json_encode($data, JSON_PRETTY_PRINT);
echo "\n<==== END Get Activity Metadata ====>\n";
*/

/*
//Get Activity Summary
// https://help.salsalabs.com/hc/en-us/articles/360001206733-Activity-Form-Summary
echo "\n<==== Begin Get Activity Summary ====>\n";
$params = "b53531b0-202e-499e-a0ea-1c506377a237";
$data = $DevAPI->Dev_API("Get_Activity_Summary", $params);
echo json_encode($data, JSON_PRETTY_PRINT);
echo "\n<==== END Get Activity Summary ====>\n";
*/

/*
//Get_Activity_Targets
// https://help.salsalabs.com/hc/en-us/articles/360001220094-Activity-Form-Summary-Targets
echo "\n<==== Begin Get_Activity_Targets ====>\n";
$params = "a4cee62d-ce9b-4e0f-b487-13ed1ca7603d";
$data = $DevAPI->Dev_API("Get_Activity_Targets", $params);
echo json_encode($data, JSON_PRETTY_PRINT);
echo "\n<==== END Get_Activity_Targets ====>\n";
*/

/*
//Get_Activity_Attendees
// https://help.salsalabs.com/hc/en-us/articles/360001219994-Activity-Form-Summary-Attendees
echo "\n<==== Begin Get_Activity_Attendees ====>\n";
$params = "c9bd5b2f-a99f-45d0-8e6c-72313f478b24";
$data = $DevAPI->Dev_API("Get_Activity_Attendees", $params);
echo json_encode($data, JSON_PRETTY_PRINT);
echo "\n<==== END Get_Activity_Attendees ====>\n";
*/

/*
//Get_Activity_Registrations
// https://help.salsalabs.com/hc/en-us/articles/360001206833-Activity-Form-Summary-Registrations
echo "\n<==== Begin Get_Activity_Registrations ====>\n";
$params = "c9bd5b2f-a99f-45d0-8e6c-72313f478b24";
$data = $DevAPI->Dev_API("Get_Activity_Registrations", $params);
echo json_encode($data, JSON_PRETTY_PRINT);
echo "\n<==== END Get_Activity_Registrations ====>\n";
*/
    
/*
//Get_Activity_Fundraisers
// https://help.salsalabs.com/hc/en-us/articles/360001206753-Activity-Form-Summary-Fundraisers
echo "\n<==== Begin Get_Activity_Fundraisers ====>\n";
$params = "8552a90e-e9ea-464a-a23b-d18fc996b37b";
$data = $DevAPI->Dev_API("Get_Activity_Fundraisers", $params);
echo json_encode($data, JSON_PRETTY_PRINT);
echo "\n<==== END Get_Activity_Fundraisers ====>\n";
*/

/*
//Get_Activity_Teams
// https://help.salsalabs.com/hc/en-us/articles/360001220114-Activity-Form-Summary-Teams
echo "\n<==== Begin Get_Activity_Teams ====>\n";
$params = ["8552a90e-e9ea-464a-a23b-d18fc996b37b","?count=5&offset=1"];
$data = $DevAPI->Dev_API("Get_Activity_Teams", $params);
echo json_encode($data, JSON_PRETTY_PRINT);
echo "\n<==== END Get_Activity_Teams ====>\n";
*/

/*
//Get_Activity_Purchases
// https://help.salsalabs.com/hc/en-us/articles/360001220034-Activity-Form-Summary-Purchases
echo "\n<==== Begin Get_Activity_Purchases ====>\n";
$params = "c9bd5b2f-a99f-45d0-8e6c-72313f478b24";
$data = $DevAPI->Dev_API("Get_Activity_Purchases", $params);
echo json_encode($data, JSON_PRETTY_PRINT);
echo "\n<==== END Get_Activity_Purchases ====>\n";
*/

/*
//Get_FundraiserMeta_Summary
// https://help.salsalabs.com/hc/en-us/articles/360001220214-Fundraiser-Metadata-and-Summary
echo "\n<==== Begin Get_FundraiserMeta_Summary ====>\n";
$params = "b53531b0-202e-499e-a0ea-1c506377a237";
$data = $DevAPI->Dev_API("Get_FundraiserMeta_Summary", $params);
echo json_encode($data, JSON_PRETTY_PRINT);
echo "\n<==== END Get_FundraiserMeta_Summary ====>\n";
*/

/*
//Get_TeamMeta_Summary
// https://help.salsalabs.com/hc/en-us/articles/360001220334-Team-Metadata-and-Summary
echo "\n<==== Begin Get_TeamMeta_Summary ====>\n";
$params = "8552a90e-e9ea-464a-a23b-d18fc996b37b";
$data = $DevAPI->Dev_API("Get_TeamMeta_Summary", $params);
echo json_encode($data, JSON_PRETTY_PRINT);
echo "\n<==== END Get_TeamMeta_Summary ====>\n";
*/

/*
//Get_EmailBlast_List
// https://help.salsalabs.com/hc/en-us/articles/360001220174-Email-Blasts-List
echo "\n<==== Begin Get_EmailBlast_List ====>\n";
$params = "?count=10&offset=1";
$data = $DevAPI->Dev_API("Get_EmailBlast_List", $params);
echo json_encode($data, JSON_PRETTY_PRINT);
echo "\n<==== END Get_EmailBlast_List ====>\n";
*/

/*
//Create Payload to get activities by Date
$payload = [
    'payload' => [
        'modifiedFrom' => '2016-05-26T11:49:24.905Z', 'offset' => '2', 'count' => '10'
    ]
];

//TODO: Test Submissions
// https://help.salsalabs.com/hc/en-us/articles/360001220294-Submissions
echo "\n<==== Begin Submissions ====>\n";
$params = "";
$data = $DevAPI->Dev_API("Submissions", $params, $payload);
echo json_encode($data, JSON_PRETTY_PRINT);
echo "\n<==== END Submissions ====>\n";
*/

?>