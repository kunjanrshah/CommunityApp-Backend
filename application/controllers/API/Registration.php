<?php

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class Registration extends REST_Controller {

    function format_key($k) {
        $k = str_replace("_"," ",$k);
        $k = ucwords(strtolower($k));
        return $k;
    }

    function index() {
        if (($this->flag) == "1") {

            $data = $this->request_paramiters;

            foreach($data as $k=>$v) {
                if(empty($v)) {

                    $response['success'] = 'error';
                    $response['message'] = $this->format_key($k).' Cannot Be Empty';
                    echo json_encode($response);exit;
                }
            }
            if (isset($data['is_admin']) && $data['is_admin']==1) {
                $data['status'] = 1;
                $is_admin = $data['is_admin'];
            }else{
                $data['status'] = 0;
                $is_admin = 0;
            }
            unset($data['is_admin']);
            $data['created_dt'] = date('Y-m-d H:i:s');
            $data['updated_dt'] = date('Y-m-d H:i:s');
            $data['password'] = sha1($data['plain_password']);
            $data['profile_password'] = $data['plain_password'];

            if (!empty($data)) {
                $checkUserExists = $this->model_name->checkUserExists($data['email_address']);

                if ($checkUserExists === FALSE) {

                    $checkUserMobileExists = $this->model_name->checkUserMobileExists($data['mobile']);

                    if ($checkUserMobileExists == FALSE) {
                         if (!empty($data['profile_pic'])) {
                            $original = realpath('./uploads/users/original/');
                            $thumb = realpath('./uploads/users/thumb/');
                            $image= $data['profile_pic'];
                            $data['profile_pic'] = $this->uploadBase64Image($image, $original);
                            
                            $src=$original."/".$data['profile_pic'];
                            $dest=$thumb."/".$data['profile_pic'];
                          
                            copy($src, $dest);
                        
                            $response['success'] = 'success';
                            $response['message'] = 'File uploaded successfully!';
                            
                          // echo json_encode($response);exit;
                        }
                        
                        $GetLocalAdmin = $this->db->query("SELECT user_id,device_token FROM tbl_devices WHERE user_id IN (SELECT id FROM users WHERE local_community_id = '".$data['local_community_id']."' AND role='LOCAL_ADMIN')");
                        $LocalAdminRes = $GetLocalAdmin->result();
                        
                        if(empty($LocalAdminRes)){
                         $GetLocalAdmin = $this->db->query("SELECT user_id,device_token FROM tbl_devices WHERE user_id IN (SELECT id FROM users WHERE sub_community_id = '".$data['sub_community_id']."' AND role='SUB_ADMIN')");    
                         $LocalAdminRes = $GetLocalAdmin->result();
                        
                        if(empty($LocalAdminRes)){
                            $GetLocalAdmin = $this->db->query("SELECT user_id,device_token FROM tbl_devices WHERE user_id IN (SELECT id FROM users WHERE  role='SUPERADMIN')");    
                                $LocalAdminRes = $GetLocalAdmin->result();
                            }
                        }
                        
                       
                        
                        $allToken = [];
                        foreach($LocalAdminRes as $row){
                            if (strlen($row->device_token)>10) {
                                $allToken[] = $row->device_token;
                            }
                        }
                       // $device_token = $LocalAdminRes->device_token;
                        // print_r($allToken);die;
                        $data['head_id'] = "0";
                        $result = $this->model_name->addUser($data);
                        // $result = 1;
                        if ($result) {

                            $this->db->where('id', $result);
                            $this->db->update('users', [
                                'member_code' => $result
                            ]);

                        $city = $this->db->query("SELECT city FROM cities WHERE id = '".$data['city_id']."'");    
                        $city1 = $city->result();
                        foreach ($city1 as $row){
                            $data['city_id'] = $row->city;
                        }
                        
                       
                        $query = $this->db->query("SELECT name FROM sub_casts WHERE id = '".$data['sub_cast_id']."'");    
                        $lastname1 = $query->result();
                        
                        foreach ($lastname1 as $row){
                            $data['sub_cast_id'] = $row->name;
                        }
                        
                        $text_msg = "Please approve " . $data['first_name'] . ' ' . $data['sub_cast_id'] . "'s Request ";
                        
                            
                            /* send notification to android */
                            //$androidToken = $this->model_name->getAdminAccessToken("Android");
                            if (!$is_admin) {
                                if (!empty($allToken)) {
                                    $notify = [
                                        'user_id'=>$result,
                                        'first_name'=>$data['first_name'],
                                        'last_name'=>$data['sub_cast_id'],
                                        'mobile'=>$data['mobile'],
                                        'email'=>$data['email_address'],
                                        'address'=>$data['address'],
                                        'city'=>$data['city_id'],
                                        'message'=>$text_msg
                                    ];
                                    $this->model_name->send_android_notification_registration($notify, $allToken);
                                }
                                $headers = getallheaders();
                                $device_id = $headers['Devicetoken'];
                                $dataDev['access_token'] = $this->genRandomToken();
                                $dataDev['device_token'] = $device_id;
                                $dataDev['user_id'] = $result;
                                $this->model_name->addDeviceDetails($dataDev);
                            }
                            
                            if (isset($is_admin) && $is_admin==1) {
                                $succes = array('success' => 'success', 'message' => $this->config->item('register_success'), 'user_id' => $result);
                            }else{
                                $succes = array('success' => 'success', 'message' => $this->config->item('register_request_sent'), 'user_id' => $result);
                            }
                        } else {
                            $succes = array('success' => 'success', 'message' => $this->config->item('register_success'), 'user_id' => $result);
                        }
                        
                        echo json_encode($succes);
                        exit;
                    } else {
                        $succes = array('success' => 'error', 'message' => $this->config->item('mobile_already_register'));
                        echo json_encode($succes);
                        exit;
                    }
                } else {
                    $succes = array('success' => 'error', 'message' => 'Email Id already exists');
                    echo json_encode($succes);
                    exit;
                }
            } else {
                $error = array('success' => 'error', 'message' => $this->config->item('required_missing'));
                echo json_encode($error);
                exit;
            }
        }
    }

}

?>
