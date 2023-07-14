<?php

/**

 *

 * User: satish4820

 * Date: 9/04/2018

 * Time: 10:55 PM

 */
error_reporting(0);

require(APPPATH . '/libraries/REST_Controller.php');

class SetReminder extends REST_Controller {

    function index() {

        if (($this->flag) == "1") {
            // echo $this->access_token;die;
            $data = $this->request_paramiters;
            $data['user_id'] = $this->user_id;
            $data['created_at'] = date('Y-m-d H:i:s');
            $reminder_id = $data['reminder_id'];
            unset($data['reminder_id']);
            $data['profile_id'] = $data['id'];
            unset($data['id']);
            // print_r($data);die;
            if ($reminder_id == "0") {
                $checkReminder = $this->model_name->checkReminderSet($data['user_id'], $data['profile_id'], $data['reminder_type']);
                if ($checkReminder == FALSE) {
                    $reminderId = $this->model_name->addReminder($data);
                } else {
                    $reminderId = $checkReminder;
                }
            } else {
                $reminderId = $this->model_name->deleteReminder($reminder_id);
            }

            if ($reminderId) {
                if ($reminder_id == "0") {
                    $response = array('success' => true, 'message' => $this->config->item('reminder_set'), 'data' => array('reminder_id' => $reminderId));
                    echo json_encode($response);
                    exit;
                } else {
                    $response = array('success' => true, 'message' => $this->config->item('reminder_unset'), 'data' => array('reminder_id' => 0));
                    echo json_encode($response);
                    exit;
                }
            }
        }
    }

}
