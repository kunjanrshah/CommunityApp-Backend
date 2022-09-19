<?php

/* * **************************************
  Login API controller
  Created by: Satish Patel
  Created On: 25/02/18 9:00 PM
 */
/* * ************************************* */
//error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class login extends REST_Controller {

    function index() {
        if (($this->flag) == "1") {

            $data = $this->request_paramiters;

            $data['password'] = sha1($data['password']);

            /*             * ***** Value store device table Start ****** */
            $dataDev = $this->deviceDetails;
            /*             * ***** Value store device table End ****** */


            if (!empty($data['username']) && !empty($data['password'])) {

                $checkUserExists = $this->model_name->checkUserExistsEmailPassword($data['username'], $data['password']);

                if ($checkUserExists === FALSE) {
                    $error = array('success' => false, 'message' => $this->config->item('user_not_found'));
                    echo json_encode($error);
                    exit;
                } else {
                    if ($data['password'] !== $checkUserExists['password']) {
                        $error = array('success' => false, 'message' => $this->config->item('login_error'));
                        echo json_encode($error);
                        exit;
                    }

                    if ($checkUserExists['status'] == "0" OR $checkUserExists['deleted'] == "1") {
                        $error = array('success' => false, 'message' => $this->config->item('account_inactive'));
                        echo json_encode($error);
                        exit;
                    }

                    $dataDev['access_token'] = $this->genRandomToken();
                    $dataDev['user_id'] = $checkUserExists['id'];
                    $this->model_name->addDeviceDetails($dataDev);

                    $user = $this->model_name->userResponse($checkUserExists);
                    $user['access_token'] = $dataDev['access_token'];

                    $succes = array('success' => true, 'message' => $this->config->item('login_success'), 'data' => $user);
                    echo json_encode($succes);
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

?>
