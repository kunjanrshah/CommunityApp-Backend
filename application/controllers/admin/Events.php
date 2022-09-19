<?php

/* * **************************************
  Reports Controller
  Created by: Satish Patel
  Created On: 21-06-2017 11:23 AM
 */
/* * ************************************* */
require(APPPATH . '/libraries/Custom_Controller.php');

class Events extends Custom_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('Events_model');
        $this->load->model('API/API_model', 'model_name');
    }

    public function index() {
        $data['page_title'] = 'Events';
        //$data['events'] = $this->Events_model->getRows();

        $data['content'] = 'admin/events/index';
        $this->load->view('admin/layouts/main', $data);
    }

    public function events_list() {

        $sLimit = "";
        $start = 0;
        $length = 10;
        $draw = 1;

        if (isset($_POST['start']) && $_POST['length'] != '-1') {
            $start = $_POST['start'];
            $length = $_POST['length'];
        }

        if (isset($_POST['draw'])) {
            $draw = $_POST['draw'];
        }

        $sWhere = $_POST['search']['value'];

        $dataList = $this->Events_model->get_event_datatables("", $sWhere, $start, $length, "e.event_date", "DESC");
        $dataListCount = $this->Events_model->get_event_datatables("", $sWhere);

        $data = array();

        $no = $_POST['start'];
        foreach ($dataList as $r) {
            $no++;
            $row = array();

            $action = '<a title="Edit" href="' . base_url() . 'admin/events/edit/' . $r['id'] . '" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></a>&nbsp;';
            $action .= '<a title="Delete" href="javascript:void()" data-href="' . base_url() . 'admin/events/delete/' . $r['id'] . '" class="btn btn-danger btn-xs delete"><i class="fa fa-trash"></i></a>';

            $shareEvent = '<a  href="' . base_url() . 'admin/events/shareEvents/' . $r['id'] . '" class="btn btn-primary btn-xs">Share Event</a>';
            //$row[] = $r['id'];
            $row[] = $r['event_date'];
            $row[] = $r['title'];
            $row[] = $r['description'];
            $row[] = $r['location'];
            
            $row[] = $shareEvent;
            $row[] = '<a href="' . base_url('admin/events/viewShared/' . $r['id']) . '" class="btn btn-warning btn-xs">View Shared Event</a>';
            $row[] = $action;

            $data[] = $row;
        }
        $output = array(
            "draw" => $draw,
            "recordsTotal" => intval(count($dataListCount)),
            "recordsFiltered" => intval(count($dataListCount)),
            "data" => $data
        );

        echo json_encode($output);
        exit();
    }

    public function add() {
        $data['page_title'] = 'Add Event';

        if (isset($_POST) && !empty($_POST)) {
            $params['title'] = $this->input->post('title');
            $params['description'] = $this->input->post('description');
            $params['location'] = $this->input->post('address');
            $params['lat'] = $this->input->post('lat');
            $params['lng'] = $this->input->post('lng');
            $params['event_date'] = date('Y-m-d', strtotime($this->input->post('event_date')));
            
            $params['created_dt'] = time();
            $params['created_by'] = $this->login_user_id;

            $event_id = $this->Events_model->add_event($params);

            if ($event_id != "") {
                $youtube = $this->input->post('youtube');
                $youtubeArr = array();
                if (!empty($youtube)) {
                    for ($i = 0; $i < sizeof($youtube); $i++) {
                        if ($youtube[$i] != "") {
                            $y['event_id'] = $event_id;
                            $y['type'] = "YOUTUBE";
                            $y['url'] = $youtube[$i];
                            $y['created_dt'] = time();
                            $y['created_by'] = $this->login_user_id;
                            $youtubeArr[] = $y;
                        }
                    }
                }

                $galleryArr = array();
                if (!empty($_FILES['gallery']['name']) && $_FILES['gallery']['name'][0] != "") {
                    $imageCount = sizeof($_FILES['gallery']['name']);
                    for ($j = 0; $j < $imageCount; $j++) {
                        $g = array();
                        $g['event_id'] = $event_id;
                        $g['type'] = "IMAGE";
                        if ($_FILES['gallery']['name'][$j] != "") {

                            $_FILES['oneimages']['name'] = $_FILES['gallery']['name'][$j];
                            $_FILES['oneimages']['type'] = $_FILES['gallery']['type'][$j];
                            $_FILES['oneimages']['tmp_name'] = $_FILES['gallery']['tmp_name'][$j];
                            $_FILES['oneimages']['error'] = $_FILES['gallery']['error'][$j];
                            $_FILES['oneimages']['size'] = $_FILES['gallery']['size'][$j];

                            $config = array(
                                'upload_path' => realpath('./uploads/events/'),
                                'field' => 'oneimages',
                                'allowed_types' => 'gif|jpg|jpeg|png',
                                    //'max_size' => '3000',
                            );

                            $response = $this->uploadFile($config);
                            if ($response['status'] == "success") {
                                $g['url'] = $response['name'];
                            } else {
                                //$this->session->set_flashdata('flash_message_error', $response['msg']);
                                //redirect('admin/cleaners/add');
                            }
                        }
                        $g['created_dt'] = time();
                        $g['created_by'] = $this->login_user_id;
                        $galleryArr[] = $g;
                    }
                }

                $media = array_merge($youtubeArr, $galleryArr);

                if (!empty($media)) {
                    $this->db->insert_batch('event_media', $media);
                }
            }
            $this->session->set_flashdata('flash_message_success', $this->config->item('event_create'));
            redirect('admin/events');
        }

        $data['content'] = 'admin/events/add';
        $this->load->view('admin/layouts/main', $data);
    }

    public function edit($id) {
        $data['page_title'] = 'Edit Event';
        $data['id'] = $id;
        $data['event'] = $this->Events_model->get_event($id);
        $data['youtube'] = $this->Events_model->get_medias($id, 'YOUTUBE');
        $data['galleries'] = $this->Events_model->get_medias($id, 'IMAGE');

        $galleries = $data['galleries'];

        $initialViewConfig = array();
        $initialViewArr = array();
        if ($galleries != FALSE) {
            for ($i = 0; $i < sizeof($galleries); $i++) {
                $dumy['caption'] = $data['event']['title'] . '_' . $i;
                $dumy['url'] = base_url() . 'admin/events/deleteGallery/' . $galleries[$i]['id'];
                $dumy['key'] = $galleries[$i]['url'];
                $initialViewConfig[] = $dumy;
                $img = base_url() . 'uploads/events/' . $galleries[$i]['url'];
                $initialViewArr[] = $img;
            }
        }
        $data['galleryConfig'] = json_encode($initialViewConfig);
        $data['galleryImgs'] = "[]";
        if (!empty($initialViewArr)) {
            $galleryImgs = implode('","', $initialViewArr);
            $data['galleryImgs'] = '["' . $galleryImgs . '"]';
        }

        if (isset($_POST) && !empty($_POST)) {
            $params['title'] = $this->input->post('title');
            $params['description'] = $this->input->post('description');
            $params['location'] = $this->input->post('address');
            $params['lat'] = $this->input->post('lat');
            $params['lng'] = $this->input->post('lng');
            $params['event_date'] = date('Y-m-d', strtotime($this->input->post('event_date')));

            $event_id = $this->Events_model->update_event($id, $params);

            if ($event_id != "") {
                $this->Events_model->delete_youtube_url($id);
                $youtube = $this->input->post('youtube');
                $youtubeArr = array();
                if (!empty($youtube)) {
                    for ($i = 0; $i < sizeof($youtube); $i++) {
                        if ($youtube[$i] != "") {
                            $y['event_id'] = $id;
                            $y['type'] = "YOUTUBE";
                            $y['url'] = $youtube[$i];
                            $y['created_dt'] = time();
                            $y['created_by'] = $this->login_user_id;
                            $youtubeArr[] = $y;
                        }
                    }
                }

                $galleryArr = array();
                if (!empty($_FILES['gallery']['name']) && $_FILES['gallery']['name'][0] != "") {
                    $imageCount = sizeof($_FILES['gallery']['name']);
                    for ($j = 0; $j < $imageCount; $j++) {
                        $g = array();
                        $g['event_id'] = $id;
                        $g['type'] = "IMAGE";
                        if ($_FILES['gallery']['name'][$j] != "") {

                            $_FILES['oneimages']['name'] = $_FILES['gallery']['name'][$j];
                            $_FILES['oneimages']['type'] = $_FILES['gallery']['type'][$j];
                            $_FILES['oneimages']['tmp_name'] = $_FILES['gallery']['tmp_name'][$j];
                            $_FILES['oneimages']['error'] = $_FILES['gallery']['error'][$j];
                            $_FILES['oneimages']['size'] = $_FILES['gallery']['size'][$j];

                            $config = array(
                                'upload_path' => realpath('./uploads/events/'),
                                'field' => 'oneimages',
                                'allowed_types' => 'gif|jpg|jpeg|png',
                                    //'max_size' => '3000',
                            );

                            $response = $this->uploadFile($config);
                            if ($response['status'] == "success") {
                                $g['url'] = $response['name'];
                            } else {
                                //$this->session->set_flashdata('flash_message_error', $response['msg']);
                                //redirect('admin/cleaners/add');
                            }
                        }
                        $g['created_dt'] = time();
                        $g['created_by'] = $this->login_user_id;
                        $galleryArr[] = $g;
                    }
                }

                $media = array_merge($youtubeArr, $galleryArr);

                if (!empty($media)) {
                    $this->db->insert_batch('event_media', $media);
                }
            }
            $this->session->set_flashdata('flash_message_success', $this->config->item('user_update'));
            redirect('admin/events');
        }

        $data['content'] = 'admin/events/edit';
        $this->load->view('admin/layouts/main', $data);
    }

    public function delete($id) {
        $this->Events_model->delete_event($id);
        $this->session->set_flashdata('flash_message_success', $this->config->item('event_delete'));
        redirect('admin/events');
    }

    public function shareEvents($id) {
        $data['id'] = $id;
        $data['cities'] = $this->model_name->getCities();

        $data['content'] = 'admin/events/shareEvent';
        $this->load->view('admin/layouts/main', $data);
    }

    public function get_user_lists() {
        $sLimit = "";
        $start = 0;
        $length = 10;
        $draw = 1;

        if (isset($_POST['start']) && $_POST['length'] != '-1') {
            $start = $_POST['start'];
            $length = $_POST['length'];
        }

        if (isset($_POST['draw'])) {
            $draw = $_POST['draw'];
        }

        $sWhere = $_POST['search']['value'];

        $dataList = $this->Events_model->get_user_datatables("", $sWhere, $start, $length, "u.id", "DESC");
        $dataListCount = $this->Events_model->get_user_datatables("", $sWhere);

        $data = array();

        $no = $_POST['start'];
        foreach ($dataList as $r) {
            $no++;
            $row = array();

            $checkBox = '<input type="checkbox" name="users[]" class="Child" value="' . $r['id'] . '">';
            $row[] = $checkBox;
            $row[] = $r['id'];
            $row[] = $r['first_name'] . ' ' . $r['last_name'];
            $row[] = $r['gender'];
            $row[] = $r['city'];
            $row[] = $r['email_address'];
            $row[] = $r['mobile'];

            $data[] = $row;
        }
        $output = array(
            "draw" => $draw,
            "recordsTotal" => intval(count($dataListCount)),
            "recordsFiltered" => intval(count($dataListCount)),
            "data" => $data
        );

        echo json_encode($output);
        exit();
    }

    public function sendToUsers($id) {
        if (isset($_POST['users']) && !empty($_POST['users'])) {
            $users = $_POST['users'];
            $event_users = array();
            for ($i = 0; $i < sizeof($users); $i++) {
                $e['user_id'] = $users[$i];
                $e['event_id'] = $id;
                $e['created_dt'] = time();
                $e['created_by'] = $this->login_user_id;
                $event_users[] = $e;
            }
            if (!empty($event_users)) {
                $this->db->insert_batch('user_events', $event_users);
            }
            $userIds = implode('","', $users);
            $userData = $this->session->userdata('backEndLogin');
            $text_msg = "Admin " . $userData['first_name'] . ' ' . $userData['last_name'] . " has shared Event";
            /* send notification to android */
            $androidToken = $this->model_name->getUserAccessToken($userIds, "Android");
            if (!empty($androidToken)) {
                $event = $this->Events_model->get_event($id);
                $eventImg = $this->db->get_where('event_media', array('event_id' => $id, 'type' => 'IMAGE'))->row_array();
                $event_img_url = "";
                if (!empty($eventImg)) {
                    $event_img_url = base_url('uploads/events/' . $eventImg['url']);
                }
                $android_devicetoken = array_column($androidToken, 'device_token');
                $message = array("notification" => $text_msg, 'event_id' => $id, 'title' => $event['title'], 'location' => $event['location'], 'lat' => $event['lat'], 'lng' => $event['lng'], 'imgUrl' => $event_img_url);
                $this->model_name->send_android_notification($message, $android_devicetoken);
            }

            $this->session->set_flashdata('flash_message_success', $this->config->item('event_shared'));
            redirect('admin/events');
        } else {
            $this->session->set_flashdata('flash_message_error', $this->config->item('select_one_user'));
            redirect('admin/shareEvents/' . $id);
        }
    }

    public function deleteGallery($id = "") {
        $img = $_REQUEST['key'];
        if ($id != "") {
            if ($this->Events_model->delete_event_gallery($id)) {
                unlink('./uploads/events/' . $img);
            }
        }
        $output = array('deleted' => 'OK');
        echo json_encode($output);
    }

    public function viewShared($id) {
        $data['page_title'] = 'Event Shared To';
        $data['id'] = $id;

        $data['content'] = 'admin/events/sharedUsers';
        $this->load->view('admin/layouts/main', $data);
    }

    public function get_shared_user_lists($id) {
        $sLimit = "";
        $start = 0;
        $length = 10;
        $draw = 1;

        if (isset($_POST['start']) && $_POST['length'] != '-1') {
            $start = $_POST['start'];
            $length = $_POST['length'];
        }

        if (isset($_POST['draw'])) {
            $draw = $_POST['draw'];
        }

        $sWhere = $_POST['search']['value'];

        $searchWhere = "ue.event_id = '" . $id . "'";

        $dataList = $this->Events_model->get_shared_users($searchWhere, $sWhere, $start, $length, "ue.id", "DESC");
        $dataListCount = $this->Events_model->get_shared_users($searchWhere, $sWhere);

        $data = array();

        $no = $_POST['start'];
        foreach ($dataList as $r) {
            $no++;
            $row = array();

            $action = '<a title="Delete" href="javascript:void()" data-href="' . base_url() . 'admin/events/deleteSharedEvent/' . $r['user_id'] . '?event_id=' . $id . '" class="btn btn-danger btn-xs delete"><i class="fa fa-trash"></i></a>';

            $row[] = $r['id'];
            $row[] = $r['first_name'] . ' ' . $r['last_name'];
            $row[] = $r['gender'];
            $row[] = $r['city'];
            $row[] = $r['email_address'];
            $row[] = $r['mobile'];
            $row[] = $action;

            $data[] = $row;
        }
        $output = array(
            "draw" => $draw,
            "recordsTotal" => intval(count($dataListCount)),
            "recordsFiltered" => intval(count($dataListCount)),
            "data" => $data
        );

        echo json_encode($output);
        exit();
    }

    public function deleteSharedEvent($id) {
        $event_id = $_REQUEST['event_id'];
        $this->Events_model->deleteSharedEvent($id, $event_id);

        $this->session->set_flashdata('flash_message_success', $this->config->item('delete_shared_user'));
        redirect('admin/events/viewShared/' . $event_id);
    }

}
