
<?php

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class GetMasterUpdateCounts extends REST_Controller {

   

    function index() {
        // admin_files
        $data = $this->request_paramiters;
        
        $subCommId = $data['sub_community_id'];
        $countList = $this->model_name->getMasterUpdateCounts();
        $userCounts = $this->model_name->getStatusMatrimonyCounts($subCommId);
        
        if(!empty($countList)){
            $lists = [];
            foreach ($countList as $key => $value) {
                $lists[$value['table_name']] = $value['counts'];
            }
            $succes = array('success' => true, 'message' => 'Data Retrived Successfully', 'countList' => $lists, 'userCounts' => $userCounts);
            echo json_encode($succes);
            exit;
        }else{
            $error = array('success' => false, 'message' => 'No Data Found');
            echo json_encode($error);
            exit;
        }
        echo json_encode($result);
        die;
    }

}

?>