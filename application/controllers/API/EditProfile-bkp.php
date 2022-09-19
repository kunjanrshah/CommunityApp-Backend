<?php
/**
 *
 * User: satish4820
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class EditProfile extends REST_Controller
{
    function index() 
    {
        if (($this->flag) == "1") {

            $data = $this->request_paramiters;
            $data['updated_dt'] = date('Y-m-d H:i:s');
            $user_id = $this->user_id;
            if (!empty($data) && isset($data['id'])) {
            	$id = $data['id'];
            	// print_r($data);die;
                $validation = $this->checkExist($data);
                // print_r($validation);die;
                $UserExists = $this->db->query("SELECT role FROM users WHERE id=".$id);
                if($UserExists->num_rows() > 0 && $validation['flag']) {
                    foreach($data as $k=>$v) {
                        if($k != 'id'){
                            $flag = 1;
                            // if (in_array($k, ['mobile','email_address','member_code'])) {
                            //     $thisData = $this->db->query("SELECT id FROM users WHERE ".$k."='".$v."' AND id!=".$id);
                            //     if($thisData->num_rows() == 0) {
                            //         $flag = 1;
                            //     }
                            // }else{
                            //     $flag = 1;
                            // }
                            if ($flag) {
                                // if ($k=='profile_password' && $v!='') {
                                //     $v=md5($v);
                                // }
                                // if ($k=='plain_password') {
                                //     $this->db->query("UPDATE users SET `$k`='".$v."' WHERE id=".$id);
                                // }
                                

                                if( $k == "profile_password" && !empty( $v ) ){
                                    
                                    $profile_password        = $v;
                                    $plain_password          = $v;
                                    $password                = sha1( $v );

                                    $this->db->query("UPDATE users SET `$k`='".$v."', `plain_password`='".$v."', `password`='".$password."' WHERE id=".$id);

                                } else {
                                    $this->db->query("UPDATE users SET `$k`='".$v."' WHERE id=".$id);
                                }

                                // Family member
                                if (in_array($k, ['state_id','city_id','area', 'pincode', 'address', 'home_lat', 'home_lng', 'is_rented', 'gotra_id', 'native_place_id', 'local_address', 'distinct_id'])) {
                                    $this->db->query("UPDATE users SET `$k`='".$v."' WHERE head_id=".$id);
                                }
                            }

                        }
                    }
                    $this->db->select('*');
                    $this->db->from("users");
                    $this->db->where("id", $id);
                    $data = $this->db->get()->row_array();

                    $response['success'] = true;
                    $response['data'] = $data;
                    $response['message'] = 'Profile updated successfully!';

                    // $this->sendNotification($id);
                    echo json_encode($response);exit;
                
                }elseif (!$validation['flag']) {
                    $response['success'] = false;
                    $response['data'] = $validation['message'];
                    $response['message'] = 'Mobile/Email validation';
                    echo json_encode($response);exit;
                }else {
                    $response['success'] = false;
                    $response['message'] = 'User not found!';
                    echo json_encode($response);exit;
                }
                
            }else {
                $response['success'] = false;
                $response['message'] = 'No data found!';
                echo json_encode($response);exit;
            }
        }
    }

    function checkExist($data){
        // print_r($data);die;
        $currentUser = $this->db->query("SELECT id,head_id FROM users WHERE  id=".$data['id']);
        $currentUser = $currentUser->row();
        // print_r($currentUser);die;
        $errors = [];
        $flag = 1;
        if ($data['id']) {
        	$id = $data['id'];
        	foreach($data as $k=>$v) {
	            if($k != 'id'){
	                //if (in_array($k, ['mobile','email_address','member_code'])) {
                    if (in_array($k, ['mobile','email_address'])) {
                        
                        if (!$currentUser->head_id) {
                            $thisData = $this->db->query("SELECT id FROM users WHERE ".$k."='".$v."' AND id!=".$id);
    	                    if($thisData->num_rows() > 0) {
    	                        $flag = 0;
    	                        $message[$k] = $k.' is already in used.';
    	                    }
                        } else {

                            if( !empty( $v ) ){
                                $thisData = $this->db->query("SELECT id FROM users WHERE ".$k."='".$v."' AND id!=".$id);
                                if($thisData->num_rows() > 0) {
                                    $flag = 0;
                                    $message[$k] = $k.' is already in used.';
                                }    
                            }
                            
                        }
	                }
	            }
	        }
        }
        return ['flag'=>$flag,'message'=>$message];
    }

    function sendNotification($id){
        $userData = $this->db->query("SELECT * FROM users WHERE id =".$id);
        $data = $userData->row();

        if(!empty($data)){
            $GetLocalAdmin = $this->db->query("SELECT device_token FROM tbl_devices WHERE user_id IN (SELECT id FROM users WHERE local_community_id = '".$data->local_community_id."' AND role='LOCAL_ADMIN')");
            $LocalAdminRes = $GetLocalAdmin->result();
            
            if(empty($LocalAdminRes)){
             $GetLocalAdmin = $this->db->query("SELECT device_token FROM tbl_devices WHERE user_id IN (SELECT id FROM users WHERE sub_community_id = '".$data->sub_community_id."' AND role='SUB_ADMIN')");    
             $LocalAdminRes = $GetLocalAdmin->result();
            
            if(empty($LocalAdminRes)){
                $GetLocalAdmin = $this->db->query("SELECT device_token FROM tbl_devices WHERE user_id IN (SELECT id FROM users WHERE  role='SUPERADMIN')");    
                    $LocalAdminRes = $GetLocalAdmin->result();
                }
            }
            $allToken = [];
            foreach($LocalAdminRes as $row){
                if (strlen($row->device_token)>10) {
                    $allToken[] = $row->device_token;
                }
            }

            $text_msg = "Please approve " . $data->first_name . ' ' . $data->last_name . "'s Request ";
            /* send notification to android */
            //$androidToken = $this->model_name->getAdminAccessToken("Android");
            if (!empty($allToken)) {
                $notify = [
                    'user_id'=>$id,
                    'message'=>$text_msg,
                ];
                $this->model_name->send_android_notification_registration($notify, $allToken);
            }
        }
    }
}