<?php

/**
 *
 * User: satish4820
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class GetNearByUsers extends REST_Controller {

    function index() {

        if (($this->flag) == "1") {

            $user_id = $this->user_id;

            $data = $this->request_paramiters;

            $users = $this->model_name->getNearByUsers($user_id, $data);

            if (!empty($users)) {
                $succes = array('success' => true, 'message' => $this->config->item('data_retried'), 'data' => $users);
                echo json_encode($succes);
                exit;
            } else {
                $error = array('success' => false, 'message' => $this->config->item('record_not_found'));
                echo json_encode($error);
                exit;
            }
        }
    }

}
