<?php
/**
 *
 * User: satish4820
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class InnerLogin extends REST_Controller
{
    function index()
    {
        $data = $this->request_paramiters;
        // print_r($data);die;
        $id = $data['id'];
        // $password = md5($data['profile_password']);
        $password = $data['profile_password'];
        $UserExists = $this->db->query("SELECT * FROM users WHERE id=".$id." AND profile_password='".$password."'");
        if($UserExists->num_rows() > 0)
        {
            $this->db->select('*');
            $this->db->from("users");
            $this->db->where("id", $id);
            $loggedInUser = $this->db->get()->row_array();
            if ($loggedInUser['login_status']==0) {
                $headers = getallheaders();
                $device_id = $headers['Devicetoken'];
                $dataDev = $this->deviceDetails;
                $dataDev['device_token'] = $device_id;
                $dataDev['user_id'] = $id;
                // print_r($dataDev);die;
                $this->model_name->addDeviceDetails($dataDev);

                $this->db->query("UPDATE users SET `last_login`='".date('Y-m-d H:i:s')."', login_status='1' WHERE id=".$id);
                // echo "UPDATE users SET `last_login`='".date('Y-m-d H:i:s')."' , login_status='1' WHERE id=".$id;die;
                $succes = array('success' => true, 'message' => $this->config->item('login_success'), 'data' => $loggedInUser);
                echo json_encode($succes);
                exit;
            }else{
                $succes = array('success' => false, 'message' => $this->config->item('already_login'));
                echo json_encode($succes);
                exit;
            }
            
        }else{
            $error = array('success' => false, 'message' => $this->config->item('login_error_inner'));
            echo json_encode($error);
            exit;
        }
    }
}