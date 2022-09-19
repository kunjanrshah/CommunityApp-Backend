<?php

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class ForgotPassword extends REST_Controller {

    function index() {
        if (($this->flag) == "1") {
            $data = $this->request_paramiters;

            $email_address = $data['email_address'];

            if (!empty($email_address)) {

                $checkUserExists = $this->model_name->checkUserExists($email_address);

                if (!empty($checkUserExists)) {
                    $subject = 'Your Password';
                    $message = 'YOUR PASSWORD IS : '.$checkUserExists[0]['plain_password'];
                    $headers = 'From:kunjan@srbrothersinfotech.com' . "\r\n" .
                        'Reply-To:kunjan@srbrothersinfotech.com' . "\r\n" .
                        'X-Mailer: PHP/' . phpversion();
                    mail($email_address, $subject, $message, $headers);

                    $success = array("success" => true, "message" => $this->config->item('forgot_password_sent'), "password" => strval($checkUserExists[0]['plain_password']), "mobile" => strval($checkUserExists[0]['mobile']));
                    echo json_encode($success);
                    exit;

                } else {
                    $error = array('success' => false, 'message' => $this->config->item('user_not_found'));
                    echo json_encode($error);
                    exit;
                }
            } else {
                $error = array('success' => false, 'message' => $this->config->item('invalid_request'));
                echo json_encode($error);
                exit;
            }
        }
    }

}

?>