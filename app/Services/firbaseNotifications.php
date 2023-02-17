<?php

namespace App\Services;

use Illuminate\Http\Request;

class firbaseNotifications
{
    private $SERVER_API_KEY;

    public function __construct()
    {
        //Configurations
        $this->SERVER_API_KEY = env("FIREBASE_SERVER_API_KEY");
    }

    public function send_notification($title, $body, $user_token, $notification){
        $data = [
            "to" => [
                $user_token
            ],
    
            "notification" => [
                "title"         => $title,
                "body"          => $body,
                "sound"         => "default"
            ],

            "data" => $notification,
        ];
    
        $dataString = json_encode($data);
    
        $headers = [
    
            'Authorization: key=' . $this->SERVER_API_KEY,
    
            'Content-Type: application/json',
    
        ];
    
        $ch = curl_init();
    
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    
        curl_setopt($ch, CURLOPT_POST, true);
    
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
    
        $response = curl_exec($ch);
    }
}