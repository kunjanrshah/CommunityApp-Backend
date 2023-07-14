<?php
/**
 *
 * User: satish4820
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class RoleChange extends REST_Controller
{
    function index() 
    {
        $data = $this->request_paramiters;
        // $json = ['one'=>'one11','youtube'=>['https://youtube.com','https://youtube.com']];
        // print_r(json_encode($json));die;
        // print_r($data);die;
        $this->db->where('id IN ('.$data['idList'].')');
        $update = ['role'=>$data['role']];
        if (isset($data['sub_community_id'])) {
            $update['sub_community_id'] = $data['sub_community_id'];
        }
        if (isset($data['local_community_id'])) {
            $update['local_community_id'] = $data['local_community_id'];
        }
        $update = $this->db->update('users', $update);
        // $users = $this->model_name->statusChange($data);
        // print_r($users);die;
        
        if($update){
            $succes = array('success' => true, 'message' => $this->config->item('role_changed'));
            echo json_encode($succes);
            exit;
        }else{
            $error = array('success' => false, 'message' => $this->config->item('data_not_found'));
            echo json_encode($error);
            exit;
        }
    }
}