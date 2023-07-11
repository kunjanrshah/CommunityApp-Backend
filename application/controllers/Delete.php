<?php
/**
 *
 * User: satish4820
 * Date: 2/26/2018
 * Time: 9:51 PM
 */

error_reporting(0);

require(APPPATH . '/libraries/REST_Controller.php');

class Delete extends REST_Controller
{
    function index()
    {
        if (($this->flag) == "1") {
            $data = $this->request_paramiters;


            $idsArr = !empty($data['idList']) ? explode(",", $data['idList']) : array();

            if(!empty($idsArr))
            {
                $this->db->where_in('id', $idsArr);
                $this->db->delete('users');

                    $response = array("success" => true, "message" => $this->config->item('record_deleted_success'));
                    echo json_encode($response);
                    exit;

            }
            else{
                $response = array("success" => false, "message" => $this->config->item('invalid_request'));
                echo json_encode($response);
                exit;
            }
        }
    }
}