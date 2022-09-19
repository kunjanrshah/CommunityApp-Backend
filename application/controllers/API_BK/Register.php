<?php

/* * **************************************
  Register API controller
  Created by: Satish Patel
  Created On: 18/07/17 1:45 PM
 */
/* * ************************************* */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class Register extends REST_Controller {

    function index() {

        if (($this->flag) == "1") {

            $data = $this->request_paramiters;

            $data['plain_password'] = $data['password'];
            $data['password'] = sha1($data['password']);
            $data['created_dt'] = time();
            $data['role'] = 'USER';
            $user_id = $this->user_id;

            $dataDev = $this->deviceDetails;

            if (!empty($data)) {
                $checkUserExists = $this->model_name->checkUserExists($data['email_address']);

                if ($checkUserExists === FALSE) {

                    $checkUserMobileExists = $this->model_name->checkUserMobileExists($data['mobile']);

                    if ($checkUserMobileExists == FALSE) {
                        if (!empty($data['profile_pic'])) {
                            $path = realpath('./uploads/users/');
                            $data['profile_pic'] = $this->uploadBase64Image($data['profile_pic'], $path);
                        }
                        $result = $this->model_name->addUser($data);

                        if ($data['status'] == "0") {
                            $text_msg = "Please approve " . $data['first_name'] . ' ' . $data['last_name'] . "'s Request ";
                            /* send notification to android */
                            $androidToken = $this->model_name->getAdminAccessToken("Android");
                            if (!empty($androidToken)) {
                                $android_devicetoken = array_column($androidToken, 'device_token');
                                $message = array("notification" => $text_msg, 'user_id' => $result);
                                $this->model_name->send_android_notification($message, $android_devicetoken);
                            }

                            $dataDev['access_token'] = $this->genRandomToken();
                            $dataDev['user_id'] = $result;
                            $this->model_name->addDeviceDetails($dataDev);

                            $succes = array('success' => true, 'message' => $this->config->item('register_request_sent'), 'user_id' => $result);
                        } else {
                            $succes = array('success' => true, 'message' => $this->config->item('register_success'), 'user_id' => $result);
                        }
                        echo json_encode($succes);
                        exit;
                    } else {
                        $succes = array('success' => false, 'message' => $this->config->item('mobile_already_register'));
                        echo json_encode($succes);
                        exit;
                    }
                } else {
                    $succes = array('success' => false, 'message' => $this->config->item('already_register'));
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
