<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Viber {
	private $url_api = "https://chatapi.viber.com/pa/";
    private $token = "4f88f4810427e097-60494fd632fd653e-d88788939bf421f4";

	public function __construct() {
		
	}

    public function message_post( $to, $text )
    {
        #$data['from']   = $from;
        #$data['sender'] = $sender;
        #$data['type']   = 'text';
        #$data['text']   = $text;
        /*
        {
            "receiver":"Qetdz/K76uiYUNjx81xoTw==",
            "min_api_version":1,
            "sender":{
                "name":"John McClane",
                "avatar":"http://avatar.example.com"
            },
            "tracking_data":"tracking data",
            "type":"text",
            "text":"Hello world!"
            }
        */
        $data["text"] = $text;
        $data["type"] = "text";
        $data["receiver"] = $to;
        $data["min_api_version"] = 1;
        $data["tracking_data"] = "tracking data";
        $data["sender"] = [
                            "name" => "NWFTHSR",
                            "avatar" => "http://avatar.example.com"
                        ];
        
        return $this->call_api('send_message', $data);
    }


    private function call_api($method, $data)
    {
        $url = $this->url_api.$method;
        
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\nX-Viber-Auth-Token: ".$this->token."\r\n",
                'method'  => 'POST',
                'content' => json_encode($data)
            )
        );

        
        
        $context  = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        return json_decode($response);
        
    }
    
	
	
}