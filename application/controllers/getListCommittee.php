<?php
/**
 *
 * User: satish4820
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class getListCommittee extends REST_Controller
{
    function index()
    {
        if (($this->flag) == "1") {

                $sub_cate = $this->model_name->getListCommittee();
                if(!empty($sub_cate))  {
                    $succes = array('success' => true, 'message' => $this->config->item('sub_cate_retried'), 'data' => $sub_cate);
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