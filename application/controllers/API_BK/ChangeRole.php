<?php
/**

 *

 * User: satish4820

 * Date: 2/28/2018

 * Time: 9:15 PM

 */
error_reporting(0);

require(APPPATH . '/libraries/REST_Controller.php');

class ChangeRole extends REST_Controller {

    function index() {

        if (($this->flag) == "1") {

            $data = $this->request_paramiters;

            $user_ids_str = $data['id'];
            $user_ids = explode(',', $data['id']);

            unset($data['id']);

            $data['updated_dt'] = time();

            $data['updated_time'] = time();

            $data = array_filter($data);

            if (!empty($user_ids)) {
                for ($i = 0; $i < sizeof($user_ids); $i++) {
                    if ($user_ids[$i] != "") {
                        $updateUser = $this->model_name->updateUser($user_ids[$i], $data);
                    }
                }
            }

            $response = array('success' => true, 'message' => $this->config->item('role_change'), 'data' => $user_ids_str);

            echo json_encode($response);

            exit;
        }
    }

}
