<?php
/**
 *
 * User: satish4820
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class GetLocalCommunity extends REST_Controller
{
    function index() 
    {
        // if (($this->flag) == "1") {

            $data = $this->request_paramiters;
            $date = '';
            if (isset($data['date'])) {
                $date = $data['date'];
            }
            if (isset($data['subcommunityid']) && $data['subcommunityid'] > 0) {

                $LocalCommunity = $this->model_name->getLocalCommunity($data['subcommunityid'],$date);
                $deletedids = $this->model_name->getDeleted('sub_community');
                $lastUpdated = $this->model_name->getLastUpdated('sub_community');
                if ($deletedids) {
                    $deletedids = explode(',', $deletedids['id']);
                }
                if ($date!='' && isset($LocalCommunity)) {
                    $succes = array('success' => true, 'message' => 'local community listed successfully', 'data' => $LocalCommunity,'deleted' => $deletedids,'last_updated' => $lastUpdated);
                    echo json_encode($succes);
                    exit;
                }elseif(!empty($LocalCommunity)){
                    $succes = array('success' => true, 'message' => 'local community listed successfully', 'data' => $LocalCommunity,'deleted' => $deletedids,'last_updated' => $lastUpdated);
                    echo json_encode($succes);
                    exit;
                }
                else{
                    $error = array('success' => false, 'message' => 'no local community found');
                    echo json_encode($error);
                    exit;
                }
            }
        // }
    }
}