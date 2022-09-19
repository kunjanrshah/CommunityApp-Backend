<?php

/**

 *

 * User: satish4820

 * Date: 2/28/2018

 * Time: 9:15 PM

 */
error_reporting(0);

require(APPPATH . '/libraries/REST_Controller.php');

class ShareUsers extends REST_Controller {

    function index() {

        if (($this->flag) == "1") {

            $data = $this->request_paramiters;
            $block_user_ids = "";
            if ($data['share_user_ids'] != "") {
                $block_user_ids = explode(',', $data['share_user_ids']);
            }

            $user_id = $this->user_id;

            $is_block = $data['is_share'];

            $params = array();
            $blockUsers = array();
            $user = $this->model_name->getUsersById($user_id);
            if (!empty($block_user_ids)) {
                for ($i = 0; $i < sizeof($block_user_ids); $i++) {
                    if ($is_block == "1") {
                        $d['user_id'] = $user_id;
                        $d['block_user_id'] = $block_user_ids[$i];
                        $d['created_at'] = date('Y-m-d H:i:s');
                        $params[] = $d;

                        $text_msg = $user['first_name'] . " " . $user['last_name'] . " has started sharing his location";
                        /* send notification to android */
                        $androidToken = $this->model_name->getUserAccessToken($block_user_ids[$i], "Android");
                        if (!empty($androidToken)) {
                            $android_devicetoken = array_column($androidToken, 'device_token');
                            $message = array("notification" => $text_msg, 'user_id' => $user_id);
                            $this->model_name->send_android_notification($message, $android_devicetoken);
                        }
                    } else {
                        $this->db->where('user_id', $user_id);
                        $this->db->where('block_user_id', $block_user_ids[$i]);
                        $this->db->delete('block_users');

                        $text_msg = $user['first_name'] . " " . $user['last_name'] . " stopped sharing location";
                        /* send notification to android */
                        $androidToken = $this->model_name->getUserAccessToken($block_user_ids[$i], "Android");
                        if (!empty($androidToken)) {
                            $android_devicetoken = array_column($androidToken, 'device_token');
                            $message = array("notification" => $text_msg, 'user_id' => $user_id);
                            $this->model_name->send_android_notification($message, $android_devicetoken);
                        }
                    }
                }
            } else {
                //$sharedBy = (isset($_REQUEST['shared_by'])) ? $_REQUEST['shared_by'] : "";
//                if ($sharedBy == "0") {
                $sharedUsers = $this->model_name->getBlockUsers($user_id);
//                } else {
                $sharedFromUsers = $this->model_name->getShareFromUsers($user_id);
//                }
            }

            if (!empty($params)) {
                $this->db->insert_batch('block_users', $params);
            }

            if ($is_block == "1") {
                $response = array('success' => true, 'message' => $this->config->item('share_location'));
            } else if ($is_block == "0" && empty($sharedUsers) && empty ($sharedFromUsers)) {
                $response = array('success' => true, 'message' => $this->config->item('remove_share_location'));
            } else {
                $blockUsers = array();
                $d['sharedUsers'] = $sharedUsers;
                $d['sharedFromUsers'] = $sharedFromUsers;
                $blockUsers[] = $d;
                $response = array('success' => true, 'data' => $blockUsers);
            }

            echo json_encode($response);

            exit;
        }
    }

}
