<?php
/**
 *
 * User: satish4820
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class GetUserProfile extends REST_Controller
{
    function index() 
    {
        $data = $this->request_paramiters;
        $userData = $this->db->query("SELECT * FROM users WHERE id =".$data['id']);
        $userData = $userData->row();
        if($userData){
            $succes = array('success' => true, 'message' => 'Data Retrived Successfully','data'=>$userData);
            echo json_encode($succes);
            exit;
        }else{
            $error = array('success' => false, 'message' => 'No User Found');
            echo json_encode($error);
            exit;
        }
    }
}