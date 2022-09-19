<?php
/**
 *
 * User: satish4820
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class GetEducation extends REST_Controller
{
    function index()
    {
        $data = $this->request_paramiters;
        $date = '';
        if (isset($data['date'])) {
            $date = $data['date'];
        }
        $education = $this->model_name->getEducation($date);
        $deletedids = $this->model_name->getDeleted('educations');
        $lastUpdated = $this->model_name->getLastUpdated('educations');
        if ($deletedids) {
            $deletedids = explode(',', $deletedids['id']);
        }
        if ($date!='' && isset($education)) {
            $succes = array('success' => true, 'message' => $this->config->item('education_retried'), 'data' => $education,'deleted' => $deletedids,'last_updated' => $lastUpdated);
            echo json_encode($succes);
            exit;
        }elseif(!empty($education))
        {
            $succes = array('success' => true, 'message' => $this->config->item('education_retried'), 'data' => $education,'deleted' => $deletedids,'last_updated' => $lastUpdated);
            echo json_encode($succes);
            exit;
        }else{
            $error = array('success' => false, 'message' => $this->config->item('education_not_found'));
            echo json_encode($error);
            exit;
        }
    }
}