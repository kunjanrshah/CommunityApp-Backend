
<?php

error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class GetContactList extends REST_Controller {

   

    function index() {
        // admin_files
        $data = $this->request_paramiters;
        
        $contacts = $data['mobiles'];
        $contactlist = $this->model_name->getContactList($contacts);

        if(!empty($contactlist)){
            $succes = array('success' => true, 'message' => 'Data Retrived Successfully', 'members' => $contactlist);
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