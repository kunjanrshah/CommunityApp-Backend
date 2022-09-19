<?php

error_reporting(0);

require(APPPATH . '/libraries/REST_Controller.php');

class ChangePassword extends REST_Controller {

    function index() {

        if (($this->flag) == "1") {
            $data = $this->request_paramiters;

            $data['plain_password'] = $data['password'];
            $data['password'] = sha1($data['password']);


            if (!empty($data)) {

                    $changePassword = $this->model_name->updateUser($this->user_id, $data);

                    if ($changePassword == true) {

                        $succes = array('success' => true, 'message' => $this->config->item('password_changed'));

                        echo json_encode($succes);

                        exit;
                    } else {

                        $error = array('success' => false, 'message' => $this->config->item('failed_change_pass'));

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

?>