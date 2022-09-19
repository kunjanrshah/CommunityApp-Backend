<?php

/**
 *
 * User: satish4820
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class GetGotra extends REST_Controller {

    function index() {

        if (($this->flag) == "1") {

            $gotra = $this->model_name->getGotra();

            if (!empty($gotra)) {
                $succes = array('success' => true, 'message' => $this->config->item('gotra_retried'), 'data' => $gotra);
                echo json_encode($succes);
                exit;
            } else {
                $error = array('success' => false, 'message' => $this->config->item('gotra_not_found'));
                echo json_encode($error);
                exit;
            }
        }
    }

}
