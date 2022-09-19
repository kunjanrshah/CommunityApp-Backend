<?php

/**

 *

 * User: satish4820

 * Date: 2/28/2018

 * Time: 9:15 PM

 */
error_reporting(0);

require(APPPATH . '/libraries/REST_Controller.php');

class BlockUsers extends REST_Controller {

    function index() {

        if (($this->flag) == "1") {

            $data = $this->request_paramiters;
            $block_user_ids = "";
            if ($data['block_user_ids'] != "") {
                $block_user_ids = explode(',', $data['block_user_ids']);
            }

            $user_id = $this->user_id;

            $is_block = $data['is_block'];

            $params = array();
            $blockUsers = array();
            if (!empty($block_user_ids)) {
                for ($i = 0; $i < sizeof($block_user_ids); $i++) {
                    if ($is_block == "1") {
                        $d['user_id'] = $user_id;
                        $d['block_user_id'] = $block_user_ids[$i];
                        $d['created_at'] = date('Y-m-d H:i:s');
                        $params[] = $d;
                    } else {
                        $this->db->where('user_id', $user_id);
                        $this->db->where('block_user_id', $block_user_ids[$i]);
                        $this->db->delete('block_users');
                    }
                }
            } else {
                $blockUsers = $this->model_name->getBlockUsers($user_id);
            }

            if (!empty($params)) {
                $this->db->insert_batch('block_users', $params);
            }

            if ($is_block == "1") {
                $response = array('success' => true, 'message' => $this->config->item('users_block'));
            } else if ($is_block == "0" && empty($blockUsers)) {
                $response = array('success' => true, 'message' => $this->config->item('users_un_block'));
            } else {
                $response = array('success' => true, 'data' => $blockUsers);
            }

            echo json_encode($response);

            exit;
        }
    }

}
