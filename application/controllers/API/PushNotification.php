<?php
/**
 *
 * User: satish4820
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class PushNotification extends REST_Controller
{
    function index()
    {
        if (($this->flag) == "1") {
            
            $data = $this->request_paramiters;
            //API URL of FCM
            $url = 'https://fcm.googleapis.com/fcm/send';
            /*api_key available in:
            Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key*/    
            $api_key = 'AAAASdHkU-A:APA91bG-ojuRcHf-nHtVs_d1HoaxZAKJbnq1Gs_usKvxSvuwvbU44Z2pqQoIRKhcBdh-QM1je33FZFqerf2GaxmU8L_aqey3dUCsCSeZC7NthQLOeHJWF7IQ9Tkr4NJ7wYgkLjNKVVm7';
            
            $device_id = $data['device_id'];
            
            $fields = array (
                'to' => $device_id,
                'data' => array (
                    "message" => 'Hello World'
                )
            );
            
            //header includes Content type and api key
            $headers = array(
                'Content-Type:application/json',
                'Authorization:key='.$api_key
            );
                        
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            if ($result === FALSE) {
                die('FCM Send Error: ' . curl_error($ch));
            }
            curl_close($ch);
            echo  $result;exit;
        } 
    }
}
