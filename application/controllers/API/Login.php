<?php

/* * **************************************
  Login API controller
  Created by: Satish Patel
  Created On: 25/02/18 9:00 PM
 */
/* * ************************************* */
//error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class Login extends REST_Controller {

    function index() {
        if (($this->flag) == "1") {

            $data = $this->request_paramiters;
            
            $headers = getallheaders();
            $device_id = $headers['Devicetoken'];
            
            $hashcode = $data['hashcode'];
            
            $data['password'] = sha1($data['password']);
            $dataDev = $this->deviceDetails;

            $login_type = $data['login_type'];
            $Key = $data['username'];

            // if($login_type == 0) {  //Login With Email & Password
            //     $Password = $data['password'];
            // }
              if($login_type == 3) {  //Login With Mobile & Password
                 $Password = $data['password'];
             }


            if($Key != '') {
                // if($login_type == 0) {
                //     if($Password != '') {
                //         $q = $this->db->query("SELECT id FROM users WHERE email_address ='".$Key."'");
                //         if($q->num_rows() > 0) {
                //             $user_exists = $this->model_name->loginWithEmail($Key,$Password);
                //             if(empty($user_exists)) {
                //                 $response['success'] = 'error';
                //                 $response['data']['login_type'] = $login_type;
                //                 $response['message'] = 'Invalid Email';
                //                 echo json_encode($response);exit;
                //             }
                //         }else {
                //             $response['success'] = 'error';
                //             $response['data']['login_type'] = $login_type;
                //             $response['message'] = 'Invalid Email';
                //             echo json_encode($response);exit;
                //         }

                //     }else {
                //         $response['success'] = 'error';
                //         $response['data']['login_type'] = $login_type;
                //         $response['message'] = $this->config->item('required_missing');
                //         echo json_encode($response);exit;
                //     }

                // }
                // if($login_type == 1) {
                    
                //     $q = $this->db->query("SELECT id FROM users WHERE mobile='".$Key."'");
                //     if($q->num_rows() > 0) {
                //         $user_exists = $this->model_name->loginWithOnlyMobile($Key);
                        
                //         if(empty($user_exists)) {
                //             $response['success'] = 'error';
                //             $response['data']['login_type'] = $login_type;
                //             $response['message'] = 'Invalid Password';
                //             echo json_encode($response);exit;
                //         }
                //     }else {
                //         $response['success'] = 'error';
                //         $response['data']['login_type'] = $login_type;
                //         $response['message'] = 'Invalid Mobile';
                //         echo json_encode($response);exit;
                //     }
                // }
                //else 
                if($login_type == 0) {
                    $q = $this->db->query("SELECT id FROM users WHERE email_address ='".$Key."'");
                    if($q->num_rows() > 0) {
                        $user_exists = $this->model_name->loginWithSocial($Key);
                        if(empty($user_exists)) {
                            $response['success'] = 'error';
                            $response['data']['login_type'] = $login_type;
                            $response['message'] = 'Invalid Email';
                            echo json_encode($response);exit;
                        }
                    }else {
                        $response['success'] = 'error';
                        $response['data']['login_type'] = $login_type;
                        $response['message'] = 'Invalid Email';
                        echo json_encode($response);exit;
                    }
                }else if($login_type == 1 || $login_type == 2) {

                    $q = $this->db->query("SELECT id FROM users WHERE mobile='".$Key."'");
                    if($q->num_rows() > 0) {
                        $user_exists = $this->model_name->loginWithOTP($Key);
                        if(empty($user_exists)) {
                            $response['success'] = 'error';
                            $response['data']['login_type'] = $login_type;
                            $response['message'] = 'Invalid Password';
                            echo json_encode($response);exit;
                        }
                    }else {
                        $response['success'] = 'error';
                        $response['data']['login_type'] = $login_type;
                        $response['message'] = 'Invalid Mobile';
                        echo json_encode($response);exit;
                    }
                }else if($login_type == 3){
                    
                    if($Password != '') {
                         $q = $this->db->query("SELECT id FROM users WHERE mobile ='".$Key."'");
                        
                        if($q->num_rows() > 0) {
                            $user_exists = $this->model_name->loginWithMobile($Key,$Password);
                             if(empty($user_exists)) {
                               $response['success'] = 'error';
                                $response['data']['login_type'] = $login_type;
                                 $response['message'] = 'Invalid Password';
                               echo json_encode($response);exit;
                            }
                         }else {
                             $response['success'] = 'error';
                            $response['data']['login_type'] = $login_type;
                             $response['message'] = 'Invalid Mobile';
                            echo json_encode($response);exit;
                        }

                     }else {
                         $response['success'] = 'error';
                         $response['data']['login_type'] = $login_type;
                         $response['message'] = $this->config->item('required_missing');
                         echo json_encode($response);exit;
                     }
                }

                if(!empty($user_exists) && $user_exists->id > 0) {
                    
                    $getstatus = $this->model_name->getUser($user_exists->id);

                    if($getstatus['status'] != 0) {
                        if($login_type ==  1) {
                            $OTP = rand(1001,9999);
                            $msg = urlencode("Your community App OTP Code is ".$OTP.". Please enter it to log in. ".$hashcode);
                            
                            $url = "http://bulksms.mysmsmantra.com:8080/WebSMS/SMSAPI.jsp?username=mamajigraph&password=mamaji@70&sendername=mamaji&mobileno=".$Key."&message=".$msg;
                            
                            $handle = curl_init();
                            curl_setopt($handle, CURLOPT_URL, ($url));
                            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
                            $output = curl_exec($handle);
                            if (curl_errno($handle)) {
                                $response['OTP'] = 'FAILED';
                            }
                            curl_close($handle);

                            if (strpos($output, 'successfully') !== false) {
                                $response['OTP'] = $OTP;
                            }else {
                                $response['OTP'] = 'FAILED';
                            }
                        }
                        $response['success'] = true;
                        $response['data'] = $this->model_name->getUser($user_exists->id);

                        $response['data']['login_type'] = $login_type;
                        $response['data']['access_token'] = $this->genRandomToken();
                        
                        $dataDev['device_token'] = $device_id;
                        $dataDev['access_token'] = $response['data']['access_token'];
                        $dataDev['user_id'] = $user_exists->id;
                        $this->model_name->addDeviceDetails($dataDev);
                        //$response['access_token'] = $dataDev['access_token'];

                        echo json_encode($response);exit;
                    }else{
                        $response['message'] = 'Admin has not approved';
                    	if ($getstatus['head_id'] != 0) {
                        	$response['message'] = 'Only Family Head can login';
                    	}
                        $response['success'] = false;
                        $response['data']['login_type'] = $login_type;
                        echo json_encode($response);exit;
                    }

                }else {
                    $response['success'] = false;
                    $response['data']['login_type'] = $login_type;
                    $response['message'] = $this->config->item('user_not_found');
                    echo json_encode($response);exit;
                }
            }else {
                $response['success'] = false;
                $response['data']['login_type'] = $login_type;
                $response['message'] = $this->config->item('user_not_found');
                echo json_encode($response);exit;
            }
        }
    }

}

?>
