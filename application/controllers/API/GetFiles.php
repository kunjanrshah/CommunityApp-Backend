
<?php

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class GetFiles extends REST_Controller {

   

    function index() {
        // admin_files
        $data = $this->request_paramiters;
        $files = $this->model_name->getFiles($data['id']);
        // print_r($files);die;

        if(!empty($files)){
            $succes = array('success' => true, 'message' => 'Data Retrived Successfully', 'data' => $files);
            echo json_encode($succes);
            exit;
        }else{
            $error = array('success' => false, 'message' => 'No Files Found');
            echo json_encode($error);
            exit;
        }
        echo json_encode($result);
        die;
    }

}

?>