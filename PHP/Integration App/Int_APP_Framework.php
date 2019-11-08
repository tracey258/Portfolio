<?php

/****************************|
|                            |
|    Integration Framework   |
|       Version 1.0.0        |
| Created by Tracey Roberts  |
|   Last Update May 2019     |
|                            |
|****************************/

// Uses Composer.
require 'vendor/autoload.php';
use GuzzleHttp\{Client, RequestOptions};

class API{
    
    public $client = null;
    
    function Init_API($AuthToken, $uri)
    {
        $headers = [
            'Authorization' => 'Basic ' . $AuthToken,
        ];

        $this->$client = new GuzzleHttp\Client([
        'base_uri' => $uri,
        'headers'  => $headers
        ]);
    }
    
    function Get_SFAuth($uri)
    {
        $this->$client = new GuzzleHttp\Client(['base_uri' => $uri,'allow_redirects'=>true,]);
    }
    
    function Init_SFAPI($AuthToken, $uri)
    {
        $headers = [
            'Authorization' => 'Bearer ' . $AuthToken,
        ];

        $this->$client = new GuzzleHttp\Client([
        'base_uri' => $uri,
        'headers'  => $headers
        ]);
    }

    function call_API($method, $url)
    {
        $response = $this->$client->request($method, $url);
        $data = json_decode($response -> getBody());
        return $data;
    }
    
    function call_PostAPI($url, $options)
    {
        $response = $this->$client->post($url, $options);
        $data = json_decode($response->getBody());
        return $data;
    }
    
    function call_POST_API($url, $data)
    {
        $response = $this->$client->request("POST", $url, $data);
        $data = json_decode($response -> getBody());
        return $data;
    }
    
    function call_PUT_API($url, $data)
    {
        $response = $this->$client->request("PUT", $url, $data);
        $data = json_decode($response -> getBody());
        return $data;
    }
    
    function after ($sub, $inthat, $startPos)
    {
        $temp = "";
        $tempStart = strpos($inthat, $sub) + $startPos;
        
        $temp = strcspn($inthat, ' :|-!@#$%^&*()_=+`~,./\<>?[]{}ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz',$tempStart);
        //echo "String: " . $inthat . " Sub: " . $sub . " Temp Returned: " . $temp . " StartPos: " . $startPos . " TempStart: " . $tempStart . "\n";
        
        if (!is_bool(strpos($inthat, $sub)))
            return substr($inthat, $tempStart, $temp);
    }
}
?>