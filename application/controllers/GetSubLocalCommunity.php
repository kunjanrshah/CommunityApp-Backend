<?php
/**
 *
 * User: satish4820
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class GetSubLocalCommunity extends REST_Controller
{
    function index()
    {
        $SubCommunity = $this->model_name->getSubCommunity();

        if(($SubCommunity) > 0)
        {
            foreach ($SubCommunity as $key => $item_SubCommunity) {
                
                $subdata[$key]['id'] = $item_SubCommunity['id'];
                $subdata[$key]['name'] = $item_SubCommunity['name'];
                $LocalCommunity = $this->model_name->getLocalCommunity($item_SubCommunity['id']);
                $subdata[$key]['localcommunity'] = $LocalCommunity;
            }

            $succes = array('success' => true, 'message' => $this->config->item('community_retried'), 'data' => $subdata);
            echo json_encode($succes);
            exit;
        }else{
            $error = array('success' => false, 'message' => $this->config->item('community_not_found'));
            echo json_encode($error);
            exit;
        }
    }
}