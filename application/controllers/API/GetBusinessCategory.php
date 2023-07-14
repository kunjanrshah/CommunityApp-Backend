<?php
/**
 *
 * User: satish4820
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class GetBusinessCategory extends REST_Controller
{
    function index()
    {
        $data = $this->request_paramiters;
        $date = '';
        if (isset($data['date'])) {
            $date = $data['date'];
        }
        $category = $this->model_name->getBusinessCategory($date);
        $deletedids = $this->model_name->getDeleted('business_categories');
        $lastUpdated = $this->model_name->getLastUpdated('business_categories');
        if ($deletedids) {
            $deletedids = explode(',', $deletedids['id']);
        }
        if ($date!='' && isset($category)) {
            $succes = array('success' => true, 'message' => $this->config->item('retried_successfully'), 'data' => $category,'deleted' => $deletedids,'last_updated' => $lastUpdated);
            echo json_encode($succes);
            exit;
        }elseif(!empty($category)) {
            $succes = array('success' => true, 'message' => $this->config->item('retried_successfully'), 'data' => $category,'deleted' => $deletedids,'last_updated' => $lastUpdated);
            echo json_encode($succes);
            exit;
        }else{
            $error = array('success' => false, 'message' => $this->config->item('not_found'));
            echo json_encode($error);
            exit;
        }
    }
}