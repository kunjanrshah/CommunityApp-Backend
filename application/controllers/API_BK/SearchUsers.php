<?php

/**
 *
 * User: satish4820
 * Date: 3/7/2018
 * Time: 10:52 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class SearchUsers extends REST_Controller {

    function index() {

        if (($this->flag) == "1") {

            $user_id = $this->user_id;

            $request = $this->request_paramiters;
            
            if (!empty($request)) {
                $users = $this->model_name->getUsersBySearch($request, $user_id);
                $totalUsers = $this->model_name->getUsersBySearchCount($request);
                if (!empty($users)) {
                    $succes = array('success' => true, 'message' => $this->config->item('user_data'), 'totalRecords' => $totalUsers, 'data' => $users);
                    echo json_encode($succes);
                    exit;
                } else {
                    $error = array('success' => false, 'message' => $this->config->item('user_data_not_found'));
                    echo json_encode($error);
                    exit;
                }
            } else {
                $error = array('success' => false, 'message' => $this->config->item('required_missing'));
                echo json_encode($error);
                exit;
            }
        }
    }

}
