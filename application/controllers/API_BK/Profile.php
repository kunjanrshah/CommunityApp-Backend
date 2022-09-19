<?php

/**
 *
 * User: satish4820
 * Date: 2/28/2018
 * Time: 9:15 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class Profile extends REST_Controller {

    function index() {
        if (($this->flag) == "1") {

            $data = $this->request_paramiters;
            $data['updated_dt'] = time();
            $data['updated_time'] = time();

            $user_id = $this->user_id;
            $is_update = $data['is_update'];
            $childs = $data['childs'];

            $update_user_id = (array_key_exists('update_user_id', $data)) ? $data['update_user_id'] : "";

            unset($data['childs']);
            unset($data['update_user_id']);
            unset($data['is_update']);

            if ($is_update == "1") {
                //$data = array_filter($data);

                $path = realpath('./uploads/users/');
                if (isset($data['profile_pic']) && !empty($data['profile_pic'])) {
                    $data['profile_pic'] = $this->uploadBase64Image($data['profile_pic'], $path);
                }
                if (isset($data['img_spouse']) && !empty($data['img_spouse'])) {
                    $data['img_spouse'] = $this->uploadBase64Image($data['img_spouse'], $path);
                }
                if (isset($data['img_father']) && !empty($data['img_father'])) {
                    $data['img_father'] = $this->uploadBase64Image($data['img_father'], $path);
                }
                if (isset($data['img_mother']) && !empty($data['img_mother'])) {
                    $data['img_mother'] = $this->uploadBase64Image($data['img_mother'], $path);
                }
                if (isset($data['img_sfather']) && !empty($data['img_sfather'])) {
                    $data['img_sfather'] = $this->uploadBase64Image($data['img_sfather'], $path);
                }
                if (isset($data['img_smother']) && !empty($data['img_smother'])) {
                    $data['img_smother'] = $this->uploadBase64Image($data['img_smother'], $path);
                }

                if ($update_user_id == "") {
                    $updateUser = $this->model_name->updateUser($user_id, $data);
                } else {
                    $updateUser = $this->model_name->updateUser($update_user_id, $data);
                }

                if ($updateUser) {
                    if (!empty($childs)) {
                        foreach ($childs as $child) {
                            $c_id = (!empty($child["id"])) ? $child["id"] : "";
                            if (!empty($child['child_image'])) {
                                $child['child_image'] = $this->uploadBase64Image($child['child_image'], $path);
                            }

                            if (!empty($child["delete"])) {
                                $this->model_name->deleteChild($c_id);
                            } elseif ($c_id != "") {
                                $this->model_name->updateChild($c_id, $child);
                            } else {
                                if ($update_user_id == "") {
                                    $child['user_id'] = $user_id;
                                } else {
                                    $child['user_id'] = $update_user_id;
                                }
                                //$child['user_id'] = $user_id;
                                $child = array_filter($child);
                                $this->model_name->addChild($child);
                            }
                        }
                    }
                }

                if ($update_user_id == "") {
                    $user = $this->model_name->getUsersById($user_id);
                } else {
                    $user = $this->model_name->getUsersById($update_user_id);
                }
                $user = $this->model_name->userResponse($user);

                $response = array('success' => true, 'message' => $this->config->item('profile_update'), 'data' => $user);
            } else {
                $user = $this->model_name->getUsersById($user_id);
                $user = $this->model_name->userResponse($user);

                $response = array('success' => true, 'message' => $this->config->item('user_data'), 'data' => $user);
            }

            echo json_encode($response);
            exit;
        }
    }

}
