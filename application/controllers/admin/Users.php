<?php

/* * **************************************
  Users Controller
  Created by: Satish Patel
  /*************************************** */
require(APPPATH . '/libraries/Custom_Controller.php');

class Users extends Custom_Controller {

    public function __construct() { 
        parent::__construct();

        $this->load->model(array('State_model', 'City_model', 'Users_model', 'SubCommunity_model',
            'LocalCommunity_model',
            'Committee_model',
            'Designation_model',
            'BusinessCategory_model',
            'BusinessSubCategory_model',
            'Education_model',
            'CurrentActivity_model',
            'Occupation_model',
            'Mossad_model',
            'Distinct_model',
            'Native_model',
            'Gotra_model',
            'Relation_model',
            'Subcast_model'));
        //$this->load->model('Bookings_model');
        $this->load->model('API/API_model', 'model_name');
    }

    public function index() {
        $userData = $this->session->userdata('backEndLogin');
        
        $data['states'] = $this->State_model->getRows();
        $data['subCommunity'] = $this->SubCommunity_model->get_datatables();
        $data['localCommunity'] = $this->LocalCommunity_model->get_datatables();
        $data['cities'] = $this->City_model->getCityByState("1");

        $data['page_title'] = 'Users';
        //$data['users'] = $this->Users_model->getUsersByRole('ADMIN');
        $data['content'] = 'admin/users/index';
        $data['controller'] = 'users';
        $this->load->view('admin/layouts/main', $data);
    }

    public function data_list() {
        $userData = $this->session->userdata('backEndLogin');
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

        $dataList = $this->Users_model->get_datatables("", $sWhere, $start, $length, "id", "DESC","","");
        $dataListCount = $this->Users_model->get_datatables("", $sWhere,"","","","","","");

        $data = array();

        $no = $_POST['start'];
        foreach ($dataList as $r) {
            $no++;
            $row = array();

            $action = '<div style="margin-bottom: 5px;"><a target="_blank" title="Edit" href="' . base_url() . 'admin/users/edit/' . $r['id'] . '" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></a>&nbsp;';
            $action .= '<a target="_blank" title="committee users" href="' . base_url() . 'admin/committeeusers/users/' . $r['id'] . '" class="btn btn-primary btn-xs"><i class="fa fa-users"></i></a></div>';
            if ($r['status'] == 1) {
                $action .= '<div><a title="Inactive" data-name="' . $r['first_name'] . '" href="javascript:void()" data-href="' . base_url() . 'admin/users/changeStatus/' . $r['id'] . '?status=0" class="btn btn-danger btn-xs changeStatus"><i class="fa fa-ban"></i></a>&nbsp;';
            } else {
                $action .= '<div><a title="Active" data-name="' . $r['first_name'] . '" href="javascript:void()" data-href="' . base_url() . 'admin/users/changeStatus/' . $r['id'] . '?status=1" class="btn btn-success btn-xs changeStatus"><i class="fa fa-check"></i></a>&nbsp;';
            }
            $action .= '<a title="Delete" data-uname="'.$r['first_name'].'" href="javascript:void()" data-href="' . base_url() . 'admin/users/delete/' . $r['id'] . '?tab=users" class="btn btn-danger btn-xs delete"><i class="fa fa-trash"></i></a></div>';


            $changeRole = '<div class="dropdown">
                            <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">' . $r['role'] . '
                            <span class="caret"></span></button>
                            <ul class="dropdown-menu">
                              <li><a href="' . base_url('admin/users/changeRole/' . $r['id'] . '?role=LOCAL_ADMIN&tab=users') . '">LOCAL_ADMIN</a></li>
                              <li><a href="' . base_url('admin/users/changeRole/' . $r['id'] . '?role=SUB_ADMIN&tab=users') . '">SUB_ADMIN</a></li>
                              <li><a href="' . base_url('admin/users/changeRole/' . $r['id'] . '?role=USER&tab=users') . '">USER</a></li>
                            </ul>
                          </div>';
            $checkBox = '<input type="checkbox" name="id[]" class="Child" value="' . $r['id'] . '">';
            $row[] = $checkBox;
            $row[] = $r['id'];
            $r['profile_pic'] = ($r['profile_pic'])?:'noimage.png';
            $row[] = '<a href="' . base_url() . 'uploads/users/original/' . $r['profile_pic'] . '" class="fancybox"><img src="' . base_url() . 'uploads/users/thumb/' . $r['profile_pic'] . '" class="img-circle" height="50" width="50"></a>';
            $row[] = $action;
            $row[] = '<a target="_blank" class="btn btn-info btn-xs" href="' . base_url('admin/users/members/' . $r['id']) . '">View Members (' . $r['memberscount'] . ')</a>';
            if ($userData['role'] == "SUPERADMIN") {
                $row[] = $changeRole;
            }
            $row[] = $r['first_name'] . ' ' . $r['last_name'];
            $row[] = $r['email_address'];
            $row[] = $r['mobile'];
            $row[] = $r['city'];
            $row[] = $r['gender'];
            $row[] = $r['native'];
            $row[] = $r['address'];
            $row[] = $r['local_community'];
            $row[] = $r['sub_community'];
            
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

    public function members($id) {
        $data['page_title'] = 'Members';
        $data['head'] = $this->Users_model->get_user($id);
        $data['id'] = $id;
        $data['members'] = $this->Users_model->getMembers($id, 1);
        $data['content'] = 'admin/users/members';
        $this->load->view('admin/layouts/main', $data);
    }

    public function add() {
        $data['page_title'] = 'Add Users';
        $role = $_GET['role'];
        $data['role'] = $role;
        $error = "";
        if (isset($_POST) && !empty($_POST)) {
            $data = array();
            $data = $_POST;
            if ($this->Users_model->checkEmailExists($this->input->post('email'))) {
                $this->session->set_flashdata('flash_message_error', $this->config->item('user_email_exist'));
                redirect('admin/users/add?role=' . $role);
            }

            if (!empty($_FILES['profile']) && $_FILES['profile']['name'] != "") {

                $config = array(
                    'upload_path' => realpath('./uploads/users/original/'),
                    'thumb_path' => realpath('./uploads/users/thumb/'),
                    'field' => 'profile',
                    'allowed_types' => 'gif|jpg|jpeg|png',
                    'max_size' => '3000',
                );

                $response = $this->uploadFile($config);
                if ($response['status'] == "success") {
                    $data['profile'] = $response['name'];
                } else {
                    $this->session->set_flashdata('flash_message_error', $response['msg']);
                    redirect('admin/users/add?role=' . $role);
                }
            } else {
                $data['profile'] = 'default.jpg';
            }

            if (isset($params['pin']) && $params['pin'] != "") {
                $params['profile_password']   = $params['pin'];
            }
            unset($params['pin']);

            $menu = $this->Users_model->add_user($data);
            if ($menu != "") {

                if ($role == "user") {
                    $this->session->set_flashdata('flash_message_success', $this->config->item('user_add'));
                    redirect('admin/users');
                } else {
                    $this->session->set_flashdata('flash_message_success', $this->config->item('customer_add'));
                    redirect('admin/users/customers');
                }
            }
        }

        $data['content'] = 'admin/users/add';
        $this->load->view('admin/layouts/main', $data);
    }

    public function import() {
        set_time_limit(0);

        $data['page_title'] = 'Import';

        if (isset($_FILES) && !empty($_FILES)) {

            $fileName = $_FILES["importFile"]["tmp_name"];

            if ($_FILES["importFile"]["size"] > 0) {

                if($_FILES["importFile"]["type"] == 'csv'){

                    $file = fopen($fileName, "r");

                    $count = 0;
                    while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {

                        if ($count != 0) {
                            if ($column[5] != "") {
                                $id = $column[0];

                                $param = array(
                                    'head_id' => $column[1],
                                    'relation_id' => $this->Relation_model->getRecordId($column[2]),
                                    'sub_community_id' => ($column[7] != "" || $column[7] != "0") ? $this->SubCommunity_model->getRecordId($column[3]) : "0",
                                    'first_name' => $column[5],
                                    'last_name' => $column[6],
                                    'sub_cast_id' => ($column[7] != "" || $column[7] != "0") ? $this->Subcast_model->getRecordId($column[7]) : "0",
                                    'gender' => $column[8],
                                    'email_address' => $column[9],
                                    'is_expired' => ($column[10] == "Yes") ? 1 : 0,
                                    'expire_date' => ($column[11] != "") ? date('Y-m-d', strtotime($column[11])) : NULL,
                                    'address' => $column[12],
                                    'local_address' => $column[13],
                                    'state_id' => ($column[14] != "" || $column[14] != "0") ? $this->State_model->getRecordId($column[14]) : "0",
                                    'area' => $column[16],
                                    'pincode' => $column[17],
                                    'mobile' => $column[18],
                                    'phone' => $column[19],
                                    'distinct_id' => ($column[20] != "" || $column[20] != "0") ? $this->Distinct_model->getRecordId($column[20]) : "0",
                                    'current_activity_id' => ($column[22] != "" || $column[22] != "0") ? $this->CurrentActivity_model->getRecordId($column[22]) : "0",
                                    'education_id' => ($column[23] != "" || $column[23] != "0") ? $this->Education_model->getRecordId($column[23]) : "0",
                                    'blood_group' => $column[24],
                                    'is_donor' => ($column[25] == "Yes") ? 1 : 0,
                                    'marital_status' => $column[26],
                                    'marriage_date' => ($column[27] != "") ? date('Y-m-d', strtotime($column[27])) : NULL,
                                    'business_category_id' => ($column[28] != "" || $column[28] != "0") ? $this->BusinessCategory_model->getRecordId($column[28]) : "0",
                                    'company_name' => $column[30],
                                    'business_address' => $column[31],
                                    'website' => $column[32],
                                    'occupation_id' => ($column[33] != "" || $column[33] != "0") ? $this->Occupation_model->getRecordId($column[33]) : "0",
                                    'work_details' => $column[34],
                                    'matrimony' => $column[35],
                                    'birth_date' => ($column[36] != "") ? date('Y-m-d', strtotime($column[36])) : NULL,
                                    'birth_time' => $column[37],
                                    'birth_place' => $column[38],
                                    'weight' => $column[39],
                                    'height' => $column[40],
                                    'is_spect' => ($column[41] == "Yes") ? 1 : 0,
                                    'about_me' => $column[42],
                                    'hobby' => $column[43],
                                    'expectation' => $column[44],
                                    'facebook_profile' => $column[45],
                                );
                                if ($column[4] != "") {
                                    $param['local_community_id'] = $this->LocalCommunity_model->getRecordId($column[4], $param['sub_community_id']);
                                }
                                if ($column[15] != "") {
                                    $param['city_id'] = $this->City_model->getRecordId($column[15], $param['state_id']);
                                }

                                if ($column[21] != "") {
                                    $param['native_place_id'] = $this->Native_model->getRecordId($column[21], $param['distinct_id']);
                                }

                                if ($column[29] != "") {
                                    $param['business_sub_category_id'] = $this->BusinessSubCategory_model->getRecordId($column[29], $param['business_category_id']);
                                }

                                if ($id == "") {
                                    $this->Users_model->add_user($param);
                                } else {
                                    $this->Users_model->updateUser($id, $param);
                                }
                            }
                        }
                        $count++;
                    }

                    $this->session->set_flashdata('flash_message_success', 'Sheet imported successfully.');
                    redirect('admin/users');
                }
                else{
                    $this->session->set_flashdata('flash_message_success', 'File type not valid.');
                    redirect('admin/users');
                }
            }
        }

        $data['content'] = 'admin/users/import';
        $this->load->view('admin/layouts/main', $data);
    }

    public function exportData() {
        set_time_limit(0);
        $state_id           = $this->input->post('state_id');    
        $city_id            = $this->input->post('city_id');    
        $sub_community_id   = $this->input->post('sub_community_id');    
        $local_community_id = $this->input->post('local_community_id');

        $users = $this->Users_model->getUsersExportData($state_id, $city_id, $sub_community_id, $local_community_id);

        $timestamp = time();
        $filename = 'Muslin_Ghanchi_' . $timestamp . '.csv';

        function cleanData(&$str) {
            if ($str == 't')
                $str = 'TRUE';
            if ($str == 'f')
                $str = 'FALSE';
            if (preg_match("/^0/", $str) || preg_match("/^\+?\d{8,}$/", $str) || preg_match("/^\d{4}.\d{1,2}.\d{1,2}/", $str)) {
                $str = "$str";
            }
            if (strstr($str, '"'))
                $str = '"' . str_replace('"', '""', $str) . '"';
        }

        //header('Content-type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Type: text/csv');
        header("Content-Disposition: attachment; filename=\"$filename\"");

        $out = fopen("php://output", 'w');
        $flag = false;
        foreach ($users as $row) {
            if (!$flag) {
                // display field/column names as first row
                array_walk($row, __NAMESPACE__ . '\cleanData');
                fputcsv($out, array_keys($row), ',', '"');
                $flag = true;
            }
            array_walk($row, __NAMESPACE__ . '\cleanData');
            fputcsv($out, array_values($row), ',', '"');
        }

        fclose($out);
        exit;
    }

    public function edit($id) {
        $data['member'] = $this->Users_model->getUser($id);

        /* Masters */
        $data['states'] = $this->State_model->getRows();
        $data['subCommunity'] = $this->SubCommunity_model->get_datatables();
        $data['localCommunity'] = $this->LocalCommunity_model->get_datatables();
        $data['currentActivities'] = $this->CurrentActivity_model->get_datatables();
        $data['educations'] = $this->Education_model->get_datatables();
        $data['businessCategories'] = $this->BusinessCategory_model->get_datatables();
        $data['businessSubCategories'] = $this->BusinessSubCategory_model->get_datatables();
        $data['occupations'] = $this->Occupation_model->get_datatables();
        $data['distincts'] = $this->Distinct_model->get_datatables();
        $data['nativePlaces'] = $this->Native_model->get_datatables();
        $data['subCasts'] = $this->Subcast_model->get_datatables();
        $data['cities'] = $this->City_model->getCityByState(($data['member']['state_id'] != "") ? $data['member']['state_id'] : "1");
        $data['relations'] = $this->Relation_model->get_datatables();

        $data['imgConfig'] = json_encode(array('caption' => $data['member']['first_name'] . ' ' . $data['member']['last_name'], 'url' => base_url() . 'admin/users/deleteImg/' . $id . '?type=profile'));
        $data['logoConfig'] = json_encode(array('caption' => $data['member']['company_name'], 'url' => base_url() . 'admin/users/deleteImg/' . $id . '?type=logo'));
        $data['id'] = $id;
        if (isset($_POST) && !empty($_POST)) {
            $params = array();
            $params = $_POST;

            if($params['mobile'] != ''){
                $checkMobile = $this->Users_model->userMobileExists($params['mobile'], $id);
                if (!empty($checkMobile)) {
                    $this->session->set_flashdata('flash_message_error', "Mobile number already registered.");
                    redirect('admin/users/edit/' . $id);
                }
            }
            if(isset($params['email_address']) && $params['email_address'] != '') {
            
                // $checkMobile = $this->Users_model->findUserByMobile($params['mobile'], 0, $id);
                $checkMobile = $this->Users_model->findUserByEmail($params['email_address'], $id);
                if (!empty($checkMobile)) {
                    $this->session->set_flashdata('flash_message_error', "Email  already registered.");
                    redirect('site/edit/' . $id);
                }
            }
                
            $params = $this->setMastersValue($params);
            if ($params['birth_date'] != "") {
                // $params['birth_date'] = date('Y-m-d',strtotime($params['birth_date']));
                $arr = explode('/', $params['birth_date']);
                $newDate = $arr[2].'-'.$arr[1].'-'.$arr[0];
                $params['birth_date'] = $newDate;
            }
            else{
                $params['birth_date'] = NULL;
            }

            if ($params['birth_time'] != "") {
                $params['birth_time'] = date('H:i:s', strtotime($params['birth_time']));
            }
            else{
                $params['birth_time'] = NULL;
            }

            if ($params['marriage_date'] != "") {
                // $params['marriage_date'] = date('Y-m-d',strtotime($params['marriage_date']));
                $arr = explode('/', $params['marriage_date']);
                $newDate = $arr[2].'-'.$arr[1].'-'.$arr[0];
                $params['marriage_date'] = $newDate;
            }
            else{
                $params['marriage_date'] = NULL;
            }

            if ($params['expire_date'] != "") {
                // $params['expire_date'] = date('Y-m-d',strtotime($params['expire_date']));
                $arr = explode('/', $params['expire_date']);
                $newDate = $arr[2].'-'.$arr[1].'-'.$arr[0];
                $params['expire_date'] = $newDate;
            }
            else{
                $params['expire_date'] = NULL;
            }

            // if (isset($params['password']) && $params['password'] != "") {
            //     $params['plain_password']   = $params['password'];
            //     $params['password']         = sha1($params['password']);
            //     unset($params['password']);
            // }
            // else{
            //     unset($params['password']);
            // }

            if (isset($params['pin']) && $params['pin'] != "") {
                $params['plain_password']   = $params['pin'];
                $params['profile_password']   = $params['pin'];
                $params['password']         = sha1($params['pin']);
                unset($params['pin']);
            }
            else{
                unset($params['pin']);
            }

            if (!empty($_FILES['profile_pic']) && $_FILES['profile_pic']['name'] != "") {

                $config = array(
                    'upload_path' => realpath('./uploads/users/original/'),
                    'thumb_path' => realpath('./uploads/users/thumb/'),
                    'field' => 'profile_pic',
                    'allowed_types' => 'gif|jpg|jpeg|png',
                    'max_size' => '3000',
                );

                $response = $this->uploadFile($config);
                if ($response['status'] == "success") {
                    $params['profile_pic'] = $response['name'];
                } else {
                    $this->session->set_flashdata('flash_message_error', $response['msg']);
                    redirect('admin/users/edit/' . $id);
                }
            }else{
                // if (isset($params['avatar_removed']) && $params['avatar_removed']=='yes') {
                //     $params['profile_pic'] = "";
                // }
            }
            // unset($params['avatar_removed']);

            if (!empty($_FILES['business_logo']) && $_FILES['business_logo']['name'] != "") {

                $config = array(
                    'upload_path' => realpath('./uploads/logos/'),
                    'field' => 'business_logo',
                    'allowed_types' => 'gif|jpg|jpeg|png',
                    'max_size' => '3000',
                );

                $response = $this->uploadFile($config);
                if ($response['status'] == "success") {
                    $params['business_logo'] = $response['name'];
                } else {
                    $this->session->set_flashdata('flash_message_error', $response['msg']);
                    redirect('admin/users/edit/' . $id);
                }
            }

            $params['updated_dt'] = date('Y-m-d H:i:s');

            $this->Users_model->updateUser($id, $params);
            $this->session->set_flashdata('flash_message_success', 'Data has been updated successfully.');
            if ($data['member']['head_id'] == "0") {
                redirect('admin/users');
            } else {
                redirect('admin/users/members/' . $data['member']['head_id']);
            }
        }
        $data['content'] = 'admin/users/edit';
        $this->load->view('admin/layouts/main', $data);
    }
    public function memberaddadmin($id){
        
        $data['page_title'] = 'Add Members';
        $data['content'] = 'admin/users/edit';
        $this->load->view('admin/layouts/main', $data);
    }

    public function changeRole($id) {
        $role = $_REQUEST['role'];
        $user = $this->Users_model->get_user($id);
        $head_id = (isset($_REQUEST['head_id'])) ? $_REQUEST['head_id'] : "";
        if ($user['mobile'] != "" || $role == "USER") {
            $params = array(
                'role' => $role,
                'updated_dt' => date('Y-m-d H:i:S')
            );

            $this->Users_model->updateUser($id, $params);
            $this->session->set_flashdata('flash_message_success', 'Role changed successfully.');
        } else {
            $this->session->set_flashdata('flash_message_error', 'Please add mobile number.');
        }
        if ($head_id == "") {
            redirect('admin/users');
        } else {
            redirect('admin/users/members/' . $head_id);
        }
    }

    public function changeStatus($id) {
        $head_id = (isset($_REQUEST['head_id'])) ? $_REQUEST['head_id'] : "";
        $params = array(
            'status' => $_REQUEST['status'],
            'updated_dt' => date('Y-m-d H:i:s')
        );

        if ($_REQUEST['status'] == 0) {
            $msg = "User Deactivated successfully.";
        }else{
            $msg = "User Activated successfully.";
            $user_id = $this->session->userdata('id');
            $this->sendUserNotification($id,$user_id);
        }

        $this->Users_model->updateUser($id, $params);
        $this->session->set_flashdata('flash_message_success', $msg);

        if ($head_id == "") {
            redirect('admin/users');
        } else {
            redirect('admin/users/members/' . $head_id);
        }
    }

    public function sendUserNotification($id,$user_id){
        $TokenData = $this->db->query("SELECT device_token FROM tbl_devices WHERE user_id =".$id);
        $TokenData = $TokenData->row();
        if (strlen($TokenData->device_token)>10) {
            $allToken[] = $TokenData->device_token;
        }
        if (!empty($allToken)) {
            $text_msg = 'Your request for account approval is approved.';
            $notify = [
                'user_id'=>$user_id,
                'message'=>$text_msg,
            ];
            $this->model_name->send_android_notification_registration($notify, $allToken);
        }
    }

    public function delete($id) {
        $head_id = (isset($_REQUEST['head_id'])) ? $_REQUEST['head_id'] : "";
        $this->Users_model->delete_user($id);

        if ($head_id == "") {
            $this->db->delete('users', array('head_id' => $id));
            $this->session->set_flashdata('flash_message_success', $this->config->item('user_delete'));
            redirect('admin/users');
        } else {
            $this->session->set_flashdata('flash_message_success', $this->config->item('customer_delete'));
            redirect('admin/users/members/' . $head_id);
        }
    }

    public function deleteMultiple() {
        if (isset($_POST['id'])) {
            $ids = $_POST['id'];
            $this->Users_model->delete_multiple($ids);
            $response = array(
                'errorcode' => 1,
                'action' => 'MUL_DELETE'
            );
        } else {
            $response = array(
                'errorcode' => 0,
                'msg' => 'Please select any one record.'
            );
        }

        echo json_encode($response);
        die;
    }

    public function deleteImg($id) {
        $user = $this->Users_model->get_user($id);
        unlink('./uploads/users/thumb/' . $user['profilePic']);
        unlink('./uploads/users/original/' . $user['profilePic']);

        $data = array(
            'profile_pic' => 'default.jpg'
        );

        $this->Users_model->updateUser($id, $data);


        if ($this->session->userdata('id') == $id) {
            $this->session->set_userdata('profilePic', 'default.jpg');
        }

        $output = array('deleted' => 'OK');
        echo json_encode($output);
    }

    public function setMastersValue($params) {
        if (array_key_exists('committee_id', $params) && $params['committee_id'] != "") {
            $params['committee_id'] = $this->Committee_model->getRecordId($params['committee_id']);
        }
        if (array_key_exists('designation_id', $params) && $params['designation_id'] != "") {
            $params['designation_id'] = $this->Designation_model->getRecordId($params['designation_id']);
        }
        if (array_key_exists('distinct_id', $params) && $params['distinct_id'] != "") {
            if ($params['distinct_id'] == "Other") {
                $params['distinct_id'] = $this->Distinct_model->getRecordId($params['distinct_other']);
            }
        }
        if (array_key_exists('native_place_id', $params) && $params['native_place_id'] != "") {
            if ($params['native_place_id'] == "Other") {
                $params['native_place_id'] = $this->Native_model->getRecordId($params['native_place_other'], $params['distinct_id']);
            }
        }
        if (array_key_exists('current_activity_id', $params) && $params['current_activity_id'] != "") {
            if ($params['current_activity_id'] == "Other") {
                $params['current_activity_id'] = $this->CurrentActivity_model->getRecordId($params['current_activity_other']);
            }
        }
        if (array_key_exists('gotra_id', $params) && $params['gotra_id'] != "") {
            $params['gotra_id'] = $this->Gotra_model->getRecordId($params['gotra_id']);
        }
        if (array_key_exists('business_category_id', $params) && $params['business_category_id'] != "") {
            if ($params['business_category_id'] == "Other") {
                $params['business_category_id'] = $this->BusinessCategory_model->getRecordId($params['business_category_other']);
            }
        }
        if (array_key_exists('business_sub_category_id', $params) && $params['business_sub_category_id'] != "") {
            if ($params['business_sub_category_id'] == "Other") {
                $params['business_sub_category_id'] = $this->BusinessSubCategory_model->getRecordId($params['business_sub_category_other'], $params['business_category_id']);
            }
        }
        if (array_key_exists('mosaad_id', $params) && $params['mosaad_id'] != "") {
            $params['mosaad_id'] = $this->Mossad_model->getRecordId($params['mosaad_id']);
        }
        if (array_key_exists('education_id', $params) && $params['education_id'] != "") {
            if ($params['education_id'] == "Other") {
                $params['education_id'] = $this->Education_model->getRecordId($params['education_other']);
            }
        }
        if (array_key_exists('occupation_id', $params) && $params['occupation_id'] != "") {
            if ($params['occupation_id'] == "Other") {
                $params['occupation_id'] = $this->Occupation_model->getRecordId($params['occupation_other']);
            }
        }
        if (array_key_exists('relation_id', $params) && $params['relation_id'] != "") {
            if ($params['relation_id'] == "Other") {
                $params['relation_id'] = $this->Relation_model->getRecordId($params['relation_other']);
            }
        }
        if (array_key_exists('city_id', $params) && $params['city_id'] != "") {
            if ($params['city_id'] == "Other") {
                $params['city_id'] = $this->City_model->getRecordId($params['city_other']);
            }
        }
        if (array_key_exists('sub_cast_id', $params) && $params['sub_cast_id'] != "") {
            if ($params['sub_cast_id'] == "Other") {
                $params['sub_cast_id'] = $this->Subcast_model->getRecordId($params['sub_cast_other']);
            }
        }
        unset($params['native_place_other']);
        unset($params['distinct_other']);
        unset($params['city_other']);
        unset($params['occupation_other']);
        unset($params['education_other']);
        unset($params['current_activity_other']);
        unset($params['business_category_other']);
        unset($params['business_sub_category_other']);
        unset($params['relation_other']);
        unset($params['sub_cast_other']);

        return $params;
    }

}
