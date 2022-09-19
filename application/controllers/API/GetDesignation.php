<?php
/**
 *
 * User: satish4820
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class GetDesignation extends REST_Controller
{
    function index()
    {
        $data = $this->request_paramiters;
        $date = '';
        if (isset($data['date'])) {
            $date = $data['date'];
        }
        $designation = $this->model_name->getDesignation($date);
        $deletedids = $this->model_name->getDeleted('designations');
        $lastUpdated = $this->model_name->getLastUpdated('designations');
        if ($deletedids) {
            $deletedids = explode(',', $deletedids['id']);
        }
        
        if ($date!='' && isset($designation)) {
            $succes = array('success' => true, 'message' => $this->config->item('designation_retried'), 'data' => $designation,'deleted' => $deletedids,'last_updated' => $lastUpdated);
            echo json_encode($succes);
            exit;
        }elseif(!empty($designation)) {
            $succes = array('success' => true, 'message' => $this->config->item('designation_retried'), 'data' => $designation,'deleted' => $deletedids,'last_updated' => $lastUpdated);
            echo json_encode($succes);
            exit;
        }else{
            $error = array('success' => false, 'message' => $this->config->item('designation_not_found'));
            echo json_encode($error);
            exit;
        }
    }
}