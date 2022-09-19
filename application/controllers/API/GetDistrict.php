<?php
/**
 *
 * User: satish4820
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class GetDistrict extends REST_Controller
{
    function index()
    {
        $data = $this->request_paramiters;
        $date = '';
        if (isset($data['date'])) {
            $date = $data['date'];
        }
        $district = $this->model_name->getDistrict($date);
        $deletedids = $this->model_name->getDeleted('distincts');
        $lastUpdated = $this->model_name->getLastUpdated('distincts');
        if ($deletedids) {
            $deletedids = explode(',', $deletedids['id']);
        }
        if ($date!='' && isset($district)) {
            $succes = array('success' => true, 'message' => $this->config->item('district_retried'), 'data' => $district,'deleted' => $deletedids,'last_updated' => $lastUpdated);
            echo json_encode($succes);
            exit;
        }elseif(!empty($district))
        {
            $succes = array('success' => true, 'message' => $this->config->item('district_retried'), 'data' => $district,'deleted' => $deletedids,'last_updated' => $lastUpdated);
            echo json_encode($succes);
            exit;
        }else{
            $error = array('success' => false, 'message' => $this->config->item('district_not_found'));
            echo json_encode($error);
            exit;
        }
    }
}