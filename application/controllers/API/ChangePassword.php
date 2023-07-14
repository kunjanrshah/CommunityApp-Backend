<?php

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class ChangePassword extends REST_Controller {

    function index() {
        if (($this->flag) == "1") {
            $data = $this->request_paramiters;
            
            $user_id = $data['id'];
            // $user_id = $this->user_id;
            $current_password = $data['current_password'];
            $new_password = $data['new_password'];
            
            // echo $user_id;die;
            // echo $current_password;die;
            // echo $new_password;die;
            if($user_id > 0 && $current_password != '' && $new_password != '') {
                $UserExists = $this->db->query("SELECT email_address FROM users WHERE profile_password='".$current_password."' AND id='".$user_id."'");
                if($UserExists->num_rows() > 0) {
                    if($this->db->query("UPDATE users SET password='".sha1($new_password)."',plain_password='".$new_password."' ,profile_password='".$new_password."' WHERE id = '".$user_id."'")) {
                        $response['success'] = 'success';
                        $response['message'] = 'PIN Updated';
                        echo json_encode($response);exit;
                    }else {
                        $response['success'] = 'error';
                        
                        $response['message'] = 'Error. Try Again';
                        echo json_encode($response);exit;
                    }
                }else {
                    $response['success'] = 'error';
                    
                    $response['message'] = 'Current Password is Invalid';
                    echo json_encode($response);exit;
                }
                
            }else {
                $response['success'] = 'error';
                
                $response['message'] = 'Error. Try Again';
                echo json_encode($response);exit;
            }
            
        }
    }

}

?>