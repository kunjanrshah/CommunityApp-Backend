<?php

/**
 *
 * User: satish4820
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class GetRelations extends REST_Controller {

    function index() {

        if (($this->flag) == "1") {

            $data = $this->request_paramiters;

            $profile_id = (array_key_exists('profile_id', $data)) ? $data['profile_id'] : "";

            if ($profile_id == "") {
                $user_id = $this->user_id;
            } else {
                $user_id = $profile_id;
            }

            $relations = $this->model_name->getRelations($user_id);

            if (!empty($relations)) {
                $succes = array('success' => true, 'message' => $this->config->item('data_retried'), 'data' => $relations);
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
