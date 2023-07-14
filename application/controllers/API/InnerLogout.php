<?php
/**
 *
 * User: satish4820
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class InnerLogout extends REST_Controller
{
    function index()
    {
        $data = $this->request_paramiters;
        // print_r($data);die;
        $id = $data['id'];
        $password = md5($data['password']);
        $UserExists = $this->db->query("SELECT * FROM users WHERE id=".$id);
        if($UserExists->num_rows() > 0)
        {
            $this->db->query("UPDATE users SET `last_login`='".date('Y-m-d H:i:s')."',login_status='0' WHERE id=".$id);
            $succes = array('success' => true, 'message' => $this->config->item('logout_success'));
            echo json_encode($succes);
            exit;
        }else{
            $error = array('success' => false, 'message' => $this->config->item('logout_fail'));
            echo json_encode($error);
            exit;
        }
    }
}