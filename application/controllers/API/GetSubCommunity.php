<?php
/**
 *
 * User: satish4820
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');
class GetSubCommunity extends REST_Controller
{
    function index()
    {
        $data = $this->request_paramiters;
        $date = '';
        if (isset($data['date'])) {
            $date = $data['date'];
        }
        $SubCommunity = $this->model_name->getSubCommunity($date);
        $deletedids = $this->model_name->getDeleted('sub_community');
        $lastUpdated = $this->model_name->getLastUpdated('sub_community');
        // print_r($lastUpdated);die;
        if ($deletedids) {
            $deletedids = explode(',', $deletedids['id']);
        }

        if ($date!='' && isset($SubCommunity)) {
            $succes = array('success' => true, 'message' => $this->config->item('surname_retried'), 'data' => $SubCommunity,'deleted' => $deletedids,'last_updated' => $lastUpdated);
            echo json_encode($succes);
            exit;
        }elseif(!empty($SubCommunity)) {
            $succes = array('success' => true, 'message' => $this->config->item('surname_retried'), 'data' => $SubCommunity,'deleted' => $deletedids,'last_updated' => $lastUpdated);
            echo json_encode($succes);
            exit;
        }else{
            $error = array('success' => false, 'message' => $this->config->item('surname_not_found'));
            echo json_encode($error);
            exit;
        }
    }
}