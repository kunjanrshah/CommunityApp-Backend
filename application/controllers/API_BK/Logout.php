<?php

/* * **************************************
  Logout API controller
  Created by: Satish Patel
  Created On: 18/07/17 3:15 PM
 */
/* * ************************************* */
//error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class Logout extends REST_Controller {

    function index() {
        if (($this->flag) == "1") {
            /*             * ***** Value store device table Start ****** */
            $dataDev = $this->deviceDetails;
            /*             * ***** Value store device table End ****** */
            //print_r($data); exit;

            if (!empty($this->user_id) && !empty($dataDev['access_token'])) {

                $this->db->delete('tbl_devices', array('user_id' => $this->user_id, 'access_token' => $dataDev['access_token']));

                $succes = array('success' => true, 'message' => $this->config->item('logout_success'));
                echo json_encode($succes);
                exit;
            }else{
                $response = array("success" => false, "message" => $this->config->item('invalid_request'));
                echo json_encode($response);
                exit;
            }
        }
    }

}

?>