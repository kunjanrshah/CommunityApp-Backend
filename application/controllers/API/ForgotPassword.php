<?php

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class ForgotPassword extends REST_Controller {

    function index() {
        if (($this->flag) == "1") {
            $data = $this->request_paramiters;
            
            //$email_address = $data['email_address'];
            $reset_type = $data['reset_type'];
            $key = $data['username'];

            if($reset_type == 'mobile') {
                $user = $this->model_name->userMobileExists($key);
            //    if($user['head_id'] == 0) {
                    if (!empty($user)) {
                        $password = $user['plain_password'];
                        $mobile = $key;

                        $url = "http://bulksms.mysmsmantra.com:8080/WebSMS/SMSAPI.jsp?username=mamajigraph&password=mamaji@70&sendername=mamaji&mobileno=".$mobile."&message=Password:".$password;
    
                        $handle = curl_init();
                        curl_setopt($handle, CURLOPT_URL, ($url));
                        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
                        $output = curl_exec($handle);
                        
                        // $url = "http://bulksms.mysmsmantra.com:8080/WebSMS/SMSAPI.jsp?username=mamajigraph&password=mamaji@70&sendername=mamaji&mobileno=".$mobile."&message=Your Community App Password is: ".$password;
        
                        // $handle = curl_init();
                        // curl_setopt($handle, CURLOPT_URL, ($url));
                        // curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
                        // $output = curl_exec($handle);
                        // print_r($output);die;
                        if (curl_errno($handle)) {
                            $response['success'] = 'error';
                            $response['message'] = 'Could Not Send SMS';
                            echo json_encode($response);exit;
                        }
                        curl_close($handle);
                        if (strpos($output, 'successfully') !== false) {
                            $response['success'] = 'success';
                            $response['message'] = 'Password Sent to '.$mobile;
                            echo json_encode($response);exit;
                        }else {
                            $response['success'] = 'error';
                            $response['message'] = 'Mobile No Does Not Exist';
                            echo json_encode($response);exit;
                        }
                    }else {
                        $response['success'] = 'error';
                        $response['message'] = 'Mobile No Does Not Exist';
                        echo json_encode($response);exit;
                    }
               /* }
                else{
                    $response['success'] = 'error';
                    $response['message'] = 'Family Head not found!';
                    echo json_encode($response);exit;
                }*/
            }
            
            if($reset_type == 'email') {
               
                $this->load->library('email');
                $config['protocol'] = 'sendmail';
                $config['mailtype'] = 'html';
                $config['charset'] = 'utf-8';
                $config['wordwrap'] = TRUE;
                $this->email->initialize($config);
                $data['content'] = 'forgotpassword';
                $this->session->unset_userdata('frontLogin');
                if (isset($data) && !empty($data)) {
                    $email = $key;
                   
                    if($email != '') {
                         
                        $user = $this->model_name->findUserByEmail($email);
                        if (!empty($user)) {
                            $subject = 'Your Password';
                            $message = 'Your Community App Password is : '.$user['plain_password'];
                            $this->email->from('demo@muslimghanchisamaj.in', 'muslimghanchisamaj');
                            $this->email->to($email);
                            $this->email->subject($subject);
                            $this->email->message($message);
                            
                            if($this->email->send()){
                                $response['success'] = 'success';
                                $response['message'] = 'Password Sent to '.$email;
                                echo json_encode($response);exit;
                            }
                            else{
                                $response['success'] = 'error';
                                $response['message'] = 'Email Does Not Exist';
                                echo json_encode($response);exit;
                            }
                        } 
                        else {
                            $response['success'] = 'error';
                            $response['message'] = 'Email Does Not Exist';
                            echo json_encode($response);exit;
                        }
                    }
                    
                }
            }
        }
    }

}

?>