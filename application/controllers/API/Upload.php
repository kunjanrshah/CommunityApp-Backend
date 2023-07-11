
<?php

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class Upload extends REST_Controller {

   

    function index() {
       // if (($this->flag) == "1") {
            $data = $this->request_paramiters;
            // print_r($data);die;
            $userId = isset($_POST['id'])?$_POST['id']:'';
            $type = isset($_POST['type'])?$_POST['type']:'';
            // echo '<pre>';
            // print_r($_FILES);die();
            // print_r($userId);die;
            
            // $_FILES['oneimages'] = $_FILES['uploaded_file'];
            // $config = array(
            //     'upload_path' => realpath('./uploads/users/original/'),
            //     'thumb_path' => realpath('./uploads/users/thumb/'),
            //     'field' => 'uploaded_file',
            //     'allowed_types' => 'gif|jpg|jpeg|png',
            //     'max_size' => '3000',
            // );
            // $response = $this->uploadFile($config);
            // if ($response['status'] == "success") {
            //     $g['url'] = $response['name'];
            // }
            // print_r($response);die;
            if ($userId) {
                // echo "string";die;
                if ($type=='company') {
                    $this->db->select('id,business_logo');
                }else{
                    $this->db->select('id,profile_pic');
                }
                $this->db->from("users");
                $this->db->where("id", $userId);
                $data = $this->db->get()->row_array();
                if (!empty($data)) {
                    $ext = basename( $_FILES['uploaded_file']['name']);
                    $ext = explode('.', $ext);
                    $ext = end($ext);

                    if ($type=='company') {
                        $config = array(
                            'upload_path' => realpath('./uploads/logos/'),
                            'field' => 'uploaded_file',
                            'allowed_types' => 'gif|jpg|jpeg|png',
                            'max_size' => '3000',
                        );
                    }else{
                        $config = array(
                            'upload_path' => realpath('./uploads/users/original/'),
                            'thumb_path' => realpath('./uploads/users/thumb/'),
                            'field' => 'uploaded_file',
                            'allowed_types' => 'gif|jpg|jpeg|png',
                            'max_size' => '8000',
                        );
                    }
                    $response = $this->uploadFile($config);
                    if ($response['status'] == "success") {
                        // $g['url'] = $response['name'];

                        // if ($data['profile_pic']) {
                        //     unlink($file_path.$data['profile_pic']);
                        // }
                        if ($type=='company') {
                            $this->db->query("UPDATE users SET `business_logo`='".$response['name']."' WHERE id=".$userId);
                            $result['data'] = ['user_id'=>$userId,'business_logo'=>$response['name']];
                        }else{
                            $this->db->query("UPDATE users SET `profile_pic`='".$response['name']."' WHERE id=".$userId);
                            $result['data'] = ['user_id'=>$userId,'profile'=>$response['name']];
                        }
                        $result['success'] = 'success';
                        $result['message'] = 'File uploaded successfully!!!';
                    }else{
                        $result['success'] = 'error';
                        $result['message'] = 'File Not Found! ' ;
                    }

                    // $file_path = "uploads/profile/";
                    // $var = $_POST['result'];
                    // $file_name = time().$userId.'.'.$ext;
                    // $file_upload = $file_path .$file_name;
                    // if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $file_upload)) {
                        
                    // }
                }else{
                    $result['success'] = 'error';
                    $result['message'] = 'User not defined!' ;
                }
            }else{
                $result['success'] = 'error';
                $result['message'] = 'User not defined!' ;
            }
            
            
            echo json_encode($result);
            die;
            
            // $data = $this->request_paramiters;

            // if (!empty($data['uploaded_file'])) {
            //     $path = realpath('./uploads/users/');
                
            //     $temp = explode(".", $_FILES["uploaded_file"]["name"]);
            //     $newfilename = round(microtime(true)) . '.' . end($temp);
            //     if (!move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $path . $newfilename)) {
            //         $response['success'] = "error";
            //         $response['message'] = 'Could not upload the file!';
            //         echo json_encode($response);exit;
            //     }
            //     $data['uploaded_file'] = $path . $newfilename;
            //     $response['success'] = 'success';
            //     $response['message'] = 'File uploaded successfully!';
            //     echo json_encode($response);exit;
            // }else {
            //     $response['success'] = 'error';
            //     $response['message'] = 'File Not Found!';
            //     echo json_encode($response);exit; 
            // }
       // }
    }

}

?>