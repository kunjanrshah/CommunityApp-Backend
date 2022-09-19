<?php

/**
 * User: satish4820
 * Date: 2/25/2018
 * Time: 11:17 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class Sync extends REST_Controller {

    function index() {

        if (($this->flag) == "1") {

            $request = $this->request_paramiters;
            $user_id = $this->user_id;
            $city = $request['city'];
            $is_reset = (isset($request['is_reset'])) ? $request['is_reset'] : "0";
            $profile_id = (isset($request['profile_id'])) ? $request['profile_id'] : "";

            if ($user_id != "") {
                $user = $this->model_name->getUsersById($user_id);

                if (!empty($user)) {
                    if ($profile_id == "") {
                        $sync_time = $user['sync_time'];
                        if ($is_reset == "1") {
                            $sync_time = "0";
                        }
                        $updatedUsers = $this->model_name->getSyncUsers($sync_time, $city);

                        $users = array();
                        if (!empty($updatedUsers)) {
                            foreach ($updatedUsers as $user) {
                                $users[] = $this->model_name->userResponse($user, $user_id);
                            }
                        }


                        $params = array(
                            'sync_time' => time()
                        );

                        $this->model_name->updateUser($user_id, $params);
                    } else {
                        $user = $this->model_name->getUsersById($profile_id);
                        $users = array();
                        $users[] = $this->model_name->userResponse($user, $user_id);
                    }

                    if (!empty($users)) {
                        $succes = array('success' => true, 'message' => $this->config->item('sync_success'), 'data' => $users);
                        echo json_encode($succes);
                        exit;
                    } else {
                        $error = array('success' => false, 'message' => $this->config->item('sync_data_not_found'));
                        echo json_encode($error);
                        exit;
                    }
                }
            } else {
                $error = array('success' => false, 'message' => $this->config->item('user_id_missing'));
                echo json_encode($error);
                exit;
            }
        }
    }

}
