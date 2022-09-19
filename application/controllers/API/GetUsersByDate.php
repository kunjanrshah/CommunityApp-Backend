<?php
/**
 *
 * /home/superbi1/public_html/muslimghanchi.org/application/controllers/API/GetUsersByDate.php
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class GetUsersByDate extends REST_Controller
{
    function index()
    {
        // echo "string";die;
        $data = $this->request_paramiters;
        // $date = '';
        // if (isset($data['date'])) {
        //     $date = $data['date'];
        // }
        if (isset($data['start']) && $data['length'] != '-1') {
            $start = $data['start'];
            $length = $data['length'];
        }else {
            $start = 0;
            $length = 25;
        }
        // echo $this->user_id;die;
        $users = $this->model_name->getUsersByDate($data,$data['id'],$start,$length);
        $usersCount = $this->model_name->getUsersByDate($data);
        // print_r($users);die;
        
        if(!empty($users)){
            $succes = array('success' => true, 'message' => $this->config->item('data_retried'),  'total_records' => count($usersCount),'members' => $users);
            echo json_encode($succes);
            exit;
        }else{
            $error = array('success' => false, 'message' => $this->config->item('data_not_found'));
            echo json_encode($error);
            exit;
        }
    }
}