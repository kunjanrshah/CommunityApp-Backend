<?php
/**
 *
 * User: satish4820
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class GetBusinessSubCategory extends REST_Controller
{
    function index()
    {
        if (($this->flag) == "1") {

            $data = $this->request_paramiters;
            $date = '';
            if (isset($data['date'])) {
                $date = $data['date'];
            }
            if (isset($data['business_category_id']) && $data['business_category_id'] > 0) {
                $deletedids = $this->model_name->getDeleted('business_sub_categories');
                $lastUpdated = $this->model_name->getLastUpdated('business_sub_categories');
                if ($deletedids) {
                    $deletedids = explode(',', $deletedids['id']);
                }
                $sub_cate = $this->model_name->getBusSubCate($data['business_category_id'],$date);
                // print_r($sub_cate);die;
                if ($date!='' && isset($sub_cate)) {
                    $succes = array('success' => true, 'message' => $this->config->item('sub_cate_retried'), 'data' => $sub_cate,'deleted' => $deletedids,'last_updated' => $lastUpdated);
                    echo json_encode($succes);
                    exit;
                }elseif(!empty($sub_cate))  {
                    $succes = array('success' => true, 'message' => $this->config->item('sub_cate_retried'), 'data' => $sub_cate,'deleted' => $deletedids,'last_updated' => $lastUpdated);
                    echo json_encode($succes);
                    exit;
                }else{
                    $error = array('success' => false, 'message' => $this->config->item('sub_cate_not_found'));
                    echo json_encode($error);
                    exit;
                }
            }
        } 
    }
}