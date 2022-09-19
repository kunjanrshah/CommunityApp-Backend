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
            if (isset($data['start']) && $data['length'] != '-1') {
                $start = $data['start'];
                $length = $data['length'];
            }else {
                $start = 0;
                $length = 25;
            }
            $users = $this->model_name->getNearByUsers($user_id, $data,$start,$length);
            $usersTotalCount = $this->model_name->getNearByUsers($user_id, $data);

            if (!empty($users)) {
                $succes = array('success' => true, 'message' => $this->config->item('data_retried'),'total_records' => count($usersTotalCount), 'members' => $users);
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
