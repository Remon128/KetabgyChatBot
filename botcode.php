<?php

use GuzzleHttp\Client;

    $apiai_key = env('0f0896a16d3b45c1828a2a4fb7b651f5');
    $apiai_subscription_key = env('6b57c49652f44ea18694fa35a9c1b009');
    
    $query = $request->input('query');
    
    $client = new Client();
    $send = ['headers' => [
                'Content-Type' => 'application/json;charset=utf-8', 
                'Authorization' => 'Bearer '.$apiai_key
                ],
            'body' => json_encode([                
                'query' => $query, 
                'lang' => 'en',
                ])
            ];  
    $response = $client->post('https://api.api.ai/v1/query?v=20150910', $send);
  //  json_decode($response->getBody(),true);
    echo  $response->getBody() ;


curl 'https://api.api.ai/api/query?v=20150910&query=science&lang=en&sessionId=8f4f5fbc-b0fb-4653-9994-cf066c06638a&timezone=2017-03-24T10:28:24+0200' -H 'Authorization:Bearer 0f0896a16d3b45c1828a2a4fb7b651f5'


?>