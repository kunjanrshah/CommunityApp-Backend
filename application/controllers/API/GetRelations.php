<?php
/**
 *
 * User: satish4820
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class GetRelations extends REST_Controller
{
    function index()
    {
        $data = $this->request_paramiters;
        $date = '';
        if (isset($data['date'])) {
            $date = $data['date'];
        }
        $relations = $this->model_name->getListRelation($date);
        $deletedids = $this->model_name->getDeleted('relations');
        $lastUpdated = $this->model_name->getLastUpdated('relations');
        if ($deletedids) {
            $deletedids = explode(',', $deletedids['id']);
        }
        if ($date!='' && isset($relations)) {
            $succes = array('success' => true, 'message' => $this->config->item('relations_retried'), 'data' => $relations,'deleted' => $deletedids,'last_updated' => $lastUpdated);
            echo json_encode($succes);
            exit;
        }elseif(!empty($relations)) {
            $succes = array('success' => true, 'message' => $this->config->item('relations_retried'), 'data' => $relations,'deleted' => $deletedids,'last_updated' => $lastUpdated);
            echo json_encode($succes);
            exit;
        }else{
            $error = array('success' => false, 'message' => $this->config->item('relations_not_found'));
            echo json_encode($error);
            exit;
        }
    }
}