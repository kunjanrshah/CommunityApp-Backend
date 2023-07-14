<?php
/**
 *
 * User: satish4820
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class AddMember extends REST_Controller
{
    function index() 
    {
        if (($this->flag) == "1") {
            
            $data = $this->request_paramiters;
            if (isset($this->user_id)) {
                $user_id = $this->user_id;
            }else{
                $user_id = $data['user_id'];
            }
            // print_r($user_id);die;
            $data['head_id'] = $user_id;
            if (isset($data['id'])) {
                $data['head_id'] = $data['id'];
                unset($data['id']);
            }
            $data['created_dt'] = date('Y-m-d H:i:s');

            // GEt Head Details
            $this->db->select('*');
            $this->db->from('users');
            $this->db->where('id', $data['head_id']);
            $h_query = $this->db->get();
            $head_arr = $h_query->row();
            
            // print_r($data);die;
            $validation = $this->checkExist($data);
            if($validation['flag']) {
                $mandatory = $this->checkMandatory($data);
                if($mandatory['flag']) {
                    $sql = "INSERT INTO users SET ";
                    foreach($data as $k=>$v) {
                    	// if ($k=='profile_password' && $v!='') {
                    	// 	$v=md5($v);
                    	// }
                        $sql .= "$k='".$v."',";
                    }
                    //$sql = substr($sql,0,-1);

                    $sql .= "plain_password='".$data['profile_password']."',";
                    $sql .= "password='".sha1($data['profile_password'])."',";
                    
                    $sql .= "state_id='".$head_arr->state_id."',";
                    $sql .= "city_id='".$head_arr->city_id."',";
                    $sql .= "area='".$head_arr->area."',";
                    $sql .= "pincode='".$head_arr->pincode."',";
                    $sql .= "address='".$head_arr->address."',";
                    $sql .= "home_lat='".$head_arr->home_lat."',";
                    $sql .= "home_lng='".$head_arr->home_lng."',";
                    $sql .= "is_rented='".$head_arr->is_rented."',";
                    $sql .= "gotra_id='".$head_arr->gotra_id."',";
                    $sql .= "native_place_id='".$head_arr->native_place_id."',";
                    $sql .= "distinct_id='".$head_arr->distinct_id."',";
                    $sql .= "local_address='".$head_arr->local_address."'";
                    
                     //echo $sql;die;
                    if($this->db->query($sql)) {
                        $MemberId = $this->db->insert_id();
                        $this->db->query("UPDATE users SET member_code='".$MemberId."' WHERE id = '".$MemberId."'");
                        $this->db->select('*');
                        $this->db->from("users");
                        $this->db->where("id", $MemberId);
                        $data = $this->db->get()->row_array();
                        $response['success'] = 'success';
                        $response['data'] = $data;
                        $response['message'] = 'Member added';

                      //  $this->sendNotification($user_id,$MemberId);                                
                        echo json_encode($response);exit;
                    }else{
                        $response['success'] = 'error';
                        $response['message'] = 'error processing your request. Try again';
                        echo json_encode($response);exit;
                    }
                }else{
                    $response['success'] = 'error';
                    $response['data'] = $mandatory['message'];
                    $response['message'] = 'Mandatory Data!';
                    echo json_encode($response);exit;
                }
            }else{
                $response['success'] = 'error';
                $response['data'] = $validation['message'];
                $response['message'] = 'Mobile/Email validation';
                echo json_encode($response);exit;
            }
            // $user_mobile = $user_email = [];
            // if ($data['mobile']) {
            //     $user_mobile = $this->model_name->userMobileExists($data['mobile']);
            // }
            // if ($data['email_address']) {
            //     $user_email = $this->model_name->findUserByEmail($data['email_address']);
            // }
            
            // if (!empty($user_mobile)) {
            //     $response['success'] = 'error';
            //     $response['message'] = 'Mobile No Exist';
            //     echo json_encode($response);exit;
            // }
            // if (!empty($user_email)) {
            //     $response['success'] = 'error';
            //     $response['message'] = 'Email Exist';
            //     echo json_encode($response);exit;
            // }
            
            // $error = 0;
            // $mandatory = array('first_name','sub_cast_id','birth_date','gender','relation_id');
            // foreach($mandatory as $k=>$v) {
            //     if($data[$v] == '') {
            //         $response['success'] = 'error';
            //         $response['message'] = $v.' cannot be empty';
            //         echo json_encode($response);exit;
            //     }
            // }
            
            
            
        }
    }

    function sendNotification($id,$addedId){
        // echo "string_".$addedId;die;
        $userData = $this->db->query("SELECT * FROM users WHERE id =".$id);
        $data = $userData->row();

        $userData2 = $this->db->query("SELECT * FROM users WHERE id =".$addedId);
        $data2 = $userData2->row();
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

            $text_msg = "Please approve " . $data2->first_name . ' ' . $data2->last_name . "'s Request ";
            /* send notification to android */
            //$androidToken = $this->model_name->getAdminAccessToken("Android");
            if (!empty($allToken)) {
                $notify = [
                    'user_id'=>$addedId,
                    'message'=>$text_msg,
                ];
                $this->model_name->send_android_notification_registration($notify, $allToken);
            }
        }
    }

    function checkExist($data){
        // print_r($data);die;
        $message = [];
        $flag = 1;
        foreach($data as $k=>$v) {
            if (in_array($k, ['mobile','email_address','member_code']) && $v!='') {
                // echo "SELECT id FROM users WHERE ".$k."='".$v."'";die;
                $thisData = $this->db->query("SELECT id FROM users WHERE ".$k."='".$v."'");
                // print_r($thisData->num_rows());die;
                if($thisData->num_rows() > 0) {
                    $flag = 0;
                    $message[$k] = $k.' is already in used.';
                }
            }
        }
        return ['flag'=>$flag,'message'=>$message];
    }

    function checkMandatory($data){
        // print_r($data);die;
        $message = [];
        $flag = 1;
        $mandatory = array('first_name','sub_cast_id','birth_date','gender','relation_id');
        foreach($mandatory as $k=>$v) {
            if($data[$v] == '') {
                // $message['message'] = $v.' cannot be empty';
                $message[$k] = $v.' as '.$k.' cannot be empty.';
                // echo json_encode($response);exit;
            }
        }
        return ['flag'=>$flag,'message'=>$message];
    }
}