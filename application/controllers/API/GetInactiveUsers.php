<?php
/**
 *
 * User: satish4820
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class GetInactiveUsers extends REST_Controller
{
    function index() 
    {
        $data = $this->request_paramiters;
        
        $sub_id = $data['sub_community_id'];
        $local_id = $data['local_community_id'];
        
       /* echo $sub_id;
        echo $local_id;die;*/
        
        // print_r($data);die;
        if (isset($data['start']) && $data['length'] != '-1') {
            $start = $data['start'];
            $length = $data['length'];
        }else {
            $start = 0;
            $length = 25;
        }
        
        $users = $this->model_name->getInactiveUsers($data,$start,$length,$sub_id,$local_id);
        $usersCount = $this->model_name->getInactiveUsersCount($sub_id,$local_id);
        // print_r($users);die;
        
        if(!empty($users)){
            $succes = array('success' => true, 'message' => $this->config->item('data_retried'),'total_records' => count($usersCount), 'members' => $users);
            echo json_encode($succes);
            exit;
        }else{
            $error = array('success' => false, 'message' => $this->config->item('data_not_found'));
            echo json_encode($error);
            exit;
        }
    }
}