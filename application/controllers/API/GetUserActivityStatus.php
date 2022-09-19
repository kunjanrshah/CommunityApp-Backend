<?php
/**
 *
 * User: satish4820
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class GetUserActivityStatus extends REST_Controller
{
    function index()
    {
        $data = $this->request_paramiters;
        // print_r($data);die;
        $id = isset($data['id'])?$data['id']:'';
        if ($id) {
            $this->db->query("UPDATE users SET `last_login`='".date('Y-m-d H:i:s')."' WHERE id=".$data['id']);


            // $this->db->select('*');
            // $this->db->from("users");
            // $this->db->where("id", $id);
            // $user = $this->db->get()->row_array();

            // $result = [];
            // if ($user) {
            //     $head_id = ($user->head_id)?:$id;
            //     $this->db->select('id,last_login,login_status');
            //     $this->db->from("users");
            //     $this->db->where("id = ".$id.' OR head_id='.$id);
            //     $users = $this->db->get()->result_array();

            //     foreach ($users as $key => $value) {
            //         $online = 0;
            //         $lastTime = strtotime($value['last_login']);
            //         $currentTime = strtotime(date('Y-m-d H:i:s'))-(60*3.5);
            //         if ($currentTime<=$lastTime) {
            //             if ($value['login_status']) {
            //                 $online = 1;
            //             }
            //         }
            //         $lastTime = ($lastTime)?:'';
            //         $result[] = ['login_status'=>intval($value['login_status']),'last_login'=>$lastTime,'id'=>$value['id'],'online_status'=>$online];
            //     }
            // }

            // $succes = array('success' => true, 'message' => 'Users Activity Retrived', 'data' => $result);
            $succes = array('success' => true, 'message' => 'Users online updated');
            echo json_encode($succes);
        }else{
            $error = array('success' => false, 'message' => 'Not User found');
            echo json_encode($error);
            exit;
        }


        // if($UserExists->num_rows() > 0)
        // {
        //     $this->db->query("UPDATE users SET `last_login`='".date('Y-m-d H:i:s')."' WHERE id=".$id);
        //     $this->db->select('*');
        //     $this->db->from("users");
        //     $this->db->where("id", $id);
        //     $loggedInUser = $this->db->get()->row_array();

        //     $succes = array('success' => true, 'message' => $this->config->item('login_success'), 'data' => $loggedInUser);
        //     echo json_encode($succes);
        //     exit;
        // }else{
        //     $error = array('success' => false, 'message' => $this->config->item('login_error_inner'));
        //     echo json_encode($error);
        //     exit;
        // }
    }
}