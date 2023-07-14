
<?php

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class UploadFiles extends REST_Controller {

   

    function index() {
        // admin_files
        $data = $this->request_paramiters;
        $file_id = 0;
        if (isset($data['file_id'])) {
        	$file_id = $data['file_id'];
        }

        if ($file_id) {
        	$this->db->select('*');
	        $this->db->from("admin_files");
	        $this->db->where("id", $file_id);
	        $files = $this->db->get()->row_array();
	        if ($files) {
	        	$this->db->delete('admin_files', array('id' => $file_id));
	        	$unlinkFile = realpath('./uploads/files/'.$files['filename']);
	        	unlink($unlinkFile);
	        	$result['success'] = 'success';
                $result['message'] = 'File deleted successfully!!!';
	        }else{
	        	$result['success'] = 'error';
				$result['message'] = 'File Not Found! ' ;
	        }
        }else{

	        if (isset($data['filename']) && $_FILES['uploaded_file']) {
	            $config = array(
	                'upload_path' => realpath('./uploads/files/'),
	                'field' => 'uploaded_file',
	                'allowed_types' => 'gif|jpg|jpeg|png|zip|pdf',
	                // 'max_size' => '3000',
	            );

	            $response = $this->uploadFile($config);
	            // print_r($response);die;
	            if ($response['status'] == "success") {
	                $this->db->query("INSERT INTO `admin_files`(`user_id`,`name`, `filename`, `created_at`) VALUES ('".$data['id']."','".$data['filename']."','".$response['name']."','".date('Y-m-d H:i:s')."')");
	                $result['success'] = 'success';
	                $result['message'] = 'File uploaded successfully!!!';
	            }else{
	                $result['success'] = 'error';
	                $result['message'] = 'File Not Uploaded! ' ;
	            }
	            
	        }else{
	            $result['success'] = 'error';
	            $result['message'] = 'File Not Found! ' ;
	        }   
        }
        echo json_encode($result);
        die;

    }

}

?>