<?php
/**
 *
 * User: satish4820
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class DeleteMember extends REST_Controller
{
    function index() 
    {
        if (($this->flag) == "1") {
            
            $user_id = $this->user_id;
            $data = $this->request_paramiters;
            
            $data['member_id'] = $data['member_id'];
            //echo "SELECT id FROM users WHERE id=".$data['member_id'];
            $CheckMemberExists = $this->db->query("SELECT id FROM users WHERE id=".$data['member_id']);
            if($CheckMemberExists->num_rows() > 0) {
                $this->db->query("DELETE FROM users WHERE id=".$data['member_id']." || head_id = ".$data['member_id']);
                $response['success'] = 'true';
                $response['message'] = 'Member deleted';
                echo json_encode($response);exit;
            }else {
                $response['success'] = 'false';
                $response['message'] = 'Member does not exist.';
                echo json_encode($response);exit;
            }
        }
    }
}