<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cronjob extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('API/API_model', 'model_name');
    }

    public function reminder() {
        // echo "reminder";die;
        $getReminders = $this->model_name->getReminders();
        // print_r($getReminders);die;
        if (!empty($getReminders)) {
            foreach ($getReminders as $reminder) {
                // $text_msg = $reminder['message'];
                // /* send notification to android */
                // $androidToken = $this->model_name->getUserAccessToken($reminder['profile_id'], "Android");
                // if (!empty($androidToken)) {
                //     $android_devicetoken = array_column($androidToken, 'device_token');
                //     $message = array("notification" => $text_msg, 'user_id' => $reminder['user_id']);
                //     $this->model_name->send_android_notification($message, $android_devicetoken);
                // }

                $reminderMsg = "";
                if ($reminder['reminder_type'] == "birth_date") {
                    $reminderMsg = "Birthday";
                } else if ($reminder['reminder_type'] == "marriage_date") {
                    $reminderMsg = "Marriage Aniversary";
                } else if ($reminder['reminder_type'] == "child_birth_date") {
                    $reminderMsg = "Child Birthday";
                } else {
                    $reminderMsg = "Wife Birthday";
                }

                //$text_msg = $reminder['profile_first_name'] . " " . $reminder['profile_last_name'] . "'s " . $reminderMsg . " Wish message send successfully.";
                $text_msg = "Today is " . $reminder['profile_first_name'] . " " . $reminder['profile_last_name'] . " " . $reminderMsg;
                /* send notification to android */
                $androidToken = $this->model_name->getUserAccessToken($reminder['user_id']);
                if (!empty($androidToken)) {
                    $android_devicetoken = array_column($androidToken, 'device_token');
                // print_r($android_devicetoken);die;
                    $message = array("notification" => $text_msg, 'profile_id' => $reminder['profile_id']);
                    // $this->model_name->send_android_notification($message, $android_devicetoken);
                    $notify = [
                        'user_id'=>$reminder['profile_id'],
                        'reminder_type'=>$reminder['reminder_type'],
                        'message'=>$text_msg,
                    ];
                    $this->model_name->send_android_notification_registration($notify, $android_devicetoken);
                }
            }
        }
    }

}
