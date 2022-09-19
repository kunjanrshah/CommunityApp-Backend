<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH . '/libraries/Front_Controller.php');

class Site extends Front_Controller {
 
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
            'Native_model',
            'Distinct_model',
            'Gotra_model',
            'Relation_model',
            'Subcast_model'));
        $this->load->model('API/API_model', 'model_name');
    }

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function index() {
        
        if($this->session->userdata('backEndLogin') != "" && $this->session->userdata('backEndLogin') != ""){
           redirect('site/members');
        }
        else{
            $this->session->unset_userdata('frontLogin');
            $this->session->unset_userdata('backEndLogin');
        }

        $data['content'] = 'index';
        if (isset($_POST) && !empty($_POST)) { 
            $mobile = $_POST['mobile'];
            $password = $_POST['password'];
            $fine_user_mobile = $this->Users_model->loginByMobile($mobile);
            if (!empty($fine_user_mobile)) {
                
                if($fine_user_mobile['status'] == 1){
                    $user = $this->Users_model->user_validate($mobile,$password);
                    if(!empty($user)){
                        $data = array(
                            'id' => $user[0]['id'],
                            'sub_community_id' => $user[0]['sub_community_id'],
                            'local_community_id' => $user[0]['local_community_id'],
                            'local_community_name' => $user[0]['local_community_name'],
                            'sub_community_name' => $user[0]['sub_community_name'],
                            'first_name' => $user[0]['first_name'],
                            'last_name' => $user[0]['last_name'],
                            'profile_pic' => $user[0]['profile_pic'],
                            'email' => $user[0]['email_address'],
                            'role' => $user[0]['role'],
                            'is_logged_in' => true,
                        );
                        $this->session->set_userdata('frontLogin', $data);

                        if($user[0]['role'] == 'SUPERADMIN' || $user[0]['role'] == 'LOCAL_ADMIN' || $user[0]['role'] == 'SUB_ADMIN'){
                            $this->session->set_userdata('backEndLogin', $data);
                        }
                        redirect('site/members');
                    }
                    else{
                        $this->session->set_flashdata('flash_message_error', "Invalid password");
                        redirect('site');    
                    }
                }
                else{
                    $this->session->set_flashdata('flash_message_error', "Family Head number is not activated by Admin");
                        redirect('site');
                }
            } 
            else {
                
                $this->session->set_flashdata('flash_message_error', "No any family head registered with $mobile mobile number <a href='" . base_url('site/register') . "'>click here</a> for register.");
                redirect('site');
            }
        }
        $this->load->view('layouts/main', $data);
    }

    public function members() {
        $user = $this->session->userdata('frontLogin');
        if (!empty($user)) {
            $data['user'] = $this->Users_model->get_user($user['id']);
            $data['members'] = $this->Users_model->getMembers($user['id']);
        } else {
            redirect('site');
        }
        $data['content'] = 'members';
        $this->load->view('layouts/main', $data);
    }

    public function register() {
        $data['content'] = 'register';

        /* Masters */
        $data['states'] = $this->State_model->getRows();
        $data['subCommunity'] = $this->SubCommunity_model->get_datatables();
        $data['currentActivities'] = $this->CurrentActivity_model->get_datatables();
        $data['educations'] = $this->Education_model->get_datatables();
        $data['businessCategories'] = $this->BusinessCategory_model->get_datatables();
        $data['businessSubCategories'] = $this->BusinessSubCategory_model->get_datatables();
        $data['occupations'] = $this->Occupation_model->get_datatables();
        $data['distincts'] = $this->Distinct_model->get_datatables();
        //$data['nativePlaces'] = $this->Native_model->get_datatables();
        $data['subCasts'] = $this->Subcast_model->get_datatables();
        $data['cities'] = $this->City_model->getCityByState(1);

        $data['admins'] = $this->Users_model->getUsersByRole('"ADMIN","SUPERADMIN"');
        if (isset($_POST) && !empty($_POST)) {
            $params = array();
            $params = $_POST;
            if ($params['email_address'] != "") {
                $checkEmail = $this->Users_model->findUserByEmail($params['email_address']);
                if (!empty($checkEmail)) {
                    $jsonresponse = array(
                        'errorcode' => 0,
                        'msg' => 'Email address already registered.',
                        'action' => 'ERROR'
                    );
                    echo json_encode($jsonresponse);
                    die;
                }
            }

            if(isset($params['mobile']) && $params['mobile'] != ''){
                $checkMobile = $this->Users_model->userMobileExists($params['mobile']);
                // $checkMobile = $this->Users_model->findUserByMobile($params['mobile'], 0);
                if (!empty($checkMobile)) {
                    $jsonresponse = array(
                        'errorcode' => 0,
                        'msg' => 'Mobile number already registered.',
                        'action' => 'ERROR'
                    );
                    echo json_encode($jsonresponse);
                    die;
                }
            }
                
            $params['password'] = sha1($params['pin']);
            $params['plain_password'] = $params['pin'];
            $params['profile_password'] = $params['pin'];
            unset($params['pin']);

            // $params['password'] = sha1($_POST['password']);
            // $params['plain_password'] = $_POST['password'];
            // $params['profile_password'] = $_POST['password'];
            $params = $this->setMastersValue($params);
            
            $params['father_name'] = ($params['father_name']) ? $params['father_name'] : NULL;
            $params['mother_name'] = ($params['mother_name']) ? $params['mother_name'] : NULL;
            
            if ($params['birth_date'] != "") {

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

                $arr = explode('/', $params['marriage_date']);
                $newDate = $arr[2].'-'.$arr[1].'-'.$arr[0];

                $params['marriage_date'] = $newDate;
            }
            else{
                $params['marriage_date'] = NULL;
            }
            
            if ($params['expire_date'] != "") {

                $arr = explode('/', $params['expire_date']);
                $newDate = $arr[2].'-'.$arr[1].'-'.$arr[0];

                $params['expire_date'] = $newDate;
            }
            else{
                $params['expire_date'] = NULL;
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
                    $jsonresponse = array(
                        'errorcode' => 0,
                        'msg' => $response['msg'],
                        'action' => 'ERROR'
                    );
                    echo json_encode($jsonresponse);
                    die;
                }
            } else {
                $params['profile_pic'] = 'default.jpg';
            }

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

                    $jsonresponse = array(
                        'errorcode' => 0,
                        'msg' => $response['msg'],
                        'action' => 'ERROR'
                    );
                    echo json_encode($jsonresponse);
                    die;
                }
            }

            $params['created_dt'] = date('Y-m-d H:i:s');
            $params['relation_id'] = 1;
            $params['status'] = 0;

            $id = $this->Users_model->add_user($params);

            $user_data = $this->Users_model->get_user($id);
            $this->session->set_flashdata('flash_message_success', 'Family Head registered successfully.');

            $sessiondata = array(
                'id' => $id,
                'sub_community_id' => $params['sub_community_id'],
                'local_community_id' => $params['local_community_id'],
                'first_name' => $params['first_name'],
                'last_name' => $user_data['last_name'],
                'profile_pic' => $params['profile_pic'],
                'email' => $params['email_address'],
                'mobile' => $params['mobile']
            );
            $this->session->set_userdata('frontLogin', $sessiondata);

            $url = base_url('site/members');

            $this->sendRegisterNotification($id,$params['local_community_id'],$params['sub_community_id']);

            $jsonresponse = array(
                'errorcode' => 1,
                'action' => 'REDIRECT',
                'url' => $url
            );
            echo json_encode($jsonresponse);
            die;
        }
        $this->load->view('layouts/main', $data);
    }

    public function sendRegisterNotification($userId,$local_community_id,$sub_community_id){
        $GetLocalAdmin = $this->db->query("SELECT user_id,device_token FROM tbl_devices WHERE user_id IN (SELECT id FROM users WHERE local_community_id = '".$local_community_id."' AND role='LOCAL_ADMIN')");
        $LocalAdminRes = $GetLocalAdmin->result();
        
        if(empty($LocalAdminRes)){
         $GetLocalAdmin = $this->db->query("SELECT user_id,device_token FROM tbl_devices WHERE user_id IN (SELECT id FROM users WHERE sub_community_id = '".$sub_community_id."' AND role='SUB_ADMIN')");    
         $LocalAdminRes = $GetLocalAdmin->result();
        
        if(empty($LocalAdminRes)){
            $GetLocalAdmin = $this->db->query("SELECT user_id,device_token FROM tbl_devices WHERE user_id IN (SELECT id FROM users WHERE  role='SUPERADMIN')");    
                $LocalAdminRes = $GetLocalAdmin->result();
            }
        }
        // print_r($LocalAdminRes);die;
        $allToken = [];
        foreach($LocalAdminRes as $row){
            if (strlen($row->device_token)>10) {
                $allToken[] = $row->device_token;
            }
        }

        if (!empty($allToken)) {
            $text_msg = "Please approve " . $data['first_name'] . ' ' . $data['last_name'] . "'s Request ";
            $notify = [
                'user_id'=>$userId,
                'message'=>$text_msg
            ];
            $this->model_name->send_android_notification_registration($notify, $allToken);
        }

    }

    public function add() {
        $data['content'] = 'add';
        $head_id = (isset($_REQUEST['head_id'])) ? $_REQUEST['head_id'] : "";
        
        /* Masters */
        $user = $this->session->userdata('frontLogin');
        if ($head_id!='') {
            $admins = ['SUPERADMIN','SUB_ADMIN','LOCAL_ADMIN'];
            if(!in_array($user['role'], $admins)){
                redirect('site/members');
            }
        }

        // print_r($user['role']);die;
        $data['user'] = $user;
        $data['states'] = $this->State_model->getRows();
        $data['subCommunity'] = $this->SubCommunity_model->get_datatables();
        $data['currentActivities'] = $this->CurrentActivity_model->get_datatables();
        $data['educations'] = $this->Education_model->get_datatables();
        $data['businessCategories'] = $this->BusinessCategory_model->get_datatables();
        $data['businessSubCategories'] = $this->BusinessSubCategory_model->get_datatables();
        $data['occupations'] = $this->Occupation_model->get_datatables();
        $data['distincts'] = $this->Distinct_model->get_datatables();
        $data['cities'] = $this->City_model->getCityByState(1);
        $data['relations'] = $this->Relation_model->get_datatables();
        $data['subCasts'] = $this->Subcast_model->get_datatables();

        if (isset($_POST) && !empty($_POST)) {
            $params = array();
            $params = $_POST;
            if ($params['email_address'] != "") {
                $checkEmail = $this->Users_model->findUserByEmail($params['email_address']);
                if (!empty($checkEmail)) {
                    $jsonresponse = array(
                        'errorcode' => 0,
                        'msg' => 'Email address already registered.',
                        'action' => 'ERROR'
                    );
                    echo json_encode($jsonresponse);
                    die;
                }
            }
            if ($params['mobile'] != "") {
                $checkMobile = $this->Users_model->userMobileExists($params['mobile']);
                if (!empty($checkMobile)) {
                    $jsonresponse = array(
                        'errorcode' => 0,
                        'msg' => 'Mobile number already registered.',
                        'action' => 'ERROR'
                    );
                    echo json_encode($jsonresponse);
                    die;
                }
            }
            $params = $this->setMastersValue($params);
            if ($params['birth_date'] != "") {

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

                $arr = explode('/', $params['marriage_date']);
                $newDate = $arr[2].'-'.$arr[1].'-'.$arr[0];

                $params['marriage_date'] = $newDate;
            } else{
                $params['marriage_date'] = NULL;
            }

            if ($params['expire_date'] != "") {

                $arr = explode('/', $params['expire_date']);
                $newDate = $arr[2].'-'.$arr[1].'-'.$arr[0];
                
                $params['expire_date'] = $newDate;
            } else{
                $params['expire_date'] = NULL;
            }

            if (isset($params['pin']) && $params['pin'] != "") {
                $params['profile_password']   = $params['pin'];                
                unset($params['pin']);
            }
            else{
                unset($params['pin']);
            }
            
            $params['father_name'] = ($params['father_name']) ? $params['father_name'] : NULL;
            $params['mother_name'] = ($params['mother_name']) ? $params['mother_name'] : NULL;

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

                    $jsonresponse = array(
                        'errorcode' => 0,
                        'msg' => $response['msg'],
                        'action' => 'ERROR'
                    );
                    echo json_encode($jsonresponse);
                    die;
                }
            } else {
                $params['profile_pic'] = 'default.jpg';
            }

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
                    $jsonresponse = array(
                        'errorcode' => 0,
                        'msg' => $response['msg'],
                        'action' => 'ERROR'
                    );
                    echo json_encode($jsonresponse);
                    die;
                }
            }

            $params['created_dt'] = date('Y-m-d H:i:s');
            if ($head_id=='') {
                $params['head_id'] = $user['id'];
            }
            // $params['head_id'] = $user['id'];
            $params['sub_community_id'] = $user['sub_community_id'];
            $params['local_community_id'] = $user['local_community_id'];
            $params['status'] = 1;

            $this->Users_model->add_user($params);
            $this->session->set_flashdata('flash_message_success', 'Family member added successfully.');
            $url = base_url('site/members');
            if ($head_id!='') {
                $url = base_url('admin/users/members/'.$head_id);
            }

            $jsonresponse = array(
                'errorcode' => 1,
                'action' => 'REDIRECT',
                'url' => $url
            );
            echo json_encode($jsonresponse);
            die;
        }
        $this->load->view('layouts/main', $data);
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

        $data['imgConfig'] = json_encode(array('caption' => $data['member']['first_name'] . ' ' . $data['member']['last_name'], 'url' => base_url() . 'users/deleteImg/' . $id . '?type=profile'));
        $data['logoConfig'] = json_encode(array('caption' => $data['member']['company_name'], 'url' => base_url() . 'users/deleteImg/' . $id . '?type=logo'));
        $data['id'] = $id;
        if (isset($_POST) && !empty($_POST)) {
            $params = array();
            $params = $_POST;

            if(isset($params['mobile']) && $params['mobile'] != '') {
            
                // $checkMobile = $this->Users_model->findUserByMobile($params['mobile'], 0, $id);
                $checkMobile = $this->Users_model->userMobileExists($params['mobile'], $id);
                if (!empty($checkMobile)) {
                    $this->session->set_flashdata('flash_message_error', "Mobile number already registered.");
                    redirect('site/edit/' . $id);
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

                $arr = explode('/', $params['marriage_date']);
                $newDate = $arr[2].'-'.$arr[1].'-'.$arr[0];
                $params['marriage_date'] = $newDate;
            }
            else{
                $params['marriage_date'] = NULL;
            }

            if (isset($params['expire_date']) && $params['expire_date'] != "") {

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
            // }
            // else{
            //     unset($params['password']);
            // }

            if (isset($params['pin']) && $params['pin'] != "") {
                $params['profile_password']   = $params['pin'];
                if ($data['member']['head_id']==0) {
                    $params['plain_password']   = $params['pin'];
                	$params['profile_password']   = $params['pin'];
                	$params['password']         = sha1($params['pin']);
                }
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
                    redirect(base_url().'site/edit/' . $id);
                }
            }
            unset($params['avatar_removed']);

            if (!empty($_FILES['business_logo']) && $_FILES['business_logo']['name'] != "") {

                $config = array(
                    'upload_path' => realpath('./uploads/logos/'),
                    'field' => 'business_logo',
                    'allowed_types' => 'gif|jpg|jpeg|png',
                    'max_size' => '3000');

                $response = $this->uploadFile($config);
                if ($response['status'] == "success") {
                    $params['business_logo'] = $response['name'];
                } else {
                    $this->session->set_flashdata('flash_message_error', $response['msg']);
                    redirect(base_url().'site/edit/' . $id);
                }
            }

            $params['updated_dt'] = date('Y-m-d H:i:s');

            $this->Users_model->updateUser($id, $params);
            $this->session->set_flashdata('flash_message_success', 'Data has been updated successfully.');
            redirect('site/members');
            
        }

        $data['content'] = 'edit';
        $this->load->view('layouts/main', $data);
    }

    public function view($id) {
        $data['member'] = $this->Users_model->getUser($id);
        $data['states'] = $this->State_model->getRows();
        $data['cities'] = $this->City_model->getCityByState($data['member']['state_id']);

        $data['content'] = 'view';
        $this->load->view('layouts/main', $data);
    }

    public function delete($id) {
        $this->Users_model->delete_user($id);
        $this->session->set_flashdata('flash_message_success', 'Data has been deleted successfully.');
        $user = $this->session->userdata('frontLogin');
        if (!empty($user) && $user['id'] == $userID) {
            redirect('site');
        } else {
            redirect('site/members');
        }
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
                $params['city_id'] = $this->City_model->getRecordId($params['city_other'], $params['state_id']);
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

    public function getCityByState() {
        $state_id = $_REQUEST['key'];

        $cities = $this->City_model->getCityByState($state_id);

        $html = "<option value=''>--Select--</option>";
        if (!empty($cities)) {
            foreach ($cities as $city) {
                $html .= "<option value='" . $city['id'] . "'>" . $city['city'] . "</option>";
            }
        }
        $html .= "<option value='Other'>Other</option>";

        $response = array(
            'errorcode' => 1,
            'html' => $html
        );

        echo json_encode($response);
        die;
    }

    public function getBusinessSubCategory() {
        $business_category_id = $_REQUEST['key'];

        $subCategories = $this->BusinessSubCategory_model->getBusinessSubCategory($business_category_id);

        $html = "<option value=''>--Select--</option>";
        if (!empty($subCategories)) {
            foreach ($subCategories as $data) {
                $html .= "<option value='" . $data['id'] . "'>" . $data['name'] . "</option>";
            }
        }
        $html .= "<option value='Other'>Other</option>";

        $response = array(
            'errorcode' => 1,
            'html' => $html
        );

        echo json_encode($response);
        die;
    }

    public function getNativePlaces() {
        $distinct_id = $_REQUEST['key'];

        $natives = $this->Native_model->getNativeByDistinct($distinct_id);

        $html = "<option value=''>--Select--</option>";
        if (!empty($natives)) {
            foreach ($natives as $native) {
                $html .= "<option value='" . $native['id'] . "'>" . $native['native'] . "</option>";
            }
        }
        $html .= "<option value='Other'>Other</option>";

        $response = array(
            'errorcode' => 1,
            'html' => $html
        );

        echo json_encode($response);
        die;
    }

    public function getLocalCommunity() {
        $sub_community_id = $_REQUEST['key'];

        $localCommunity = $this->LocalCommunity_model->getRecordsBySubcommunity($sub_community_id);

        $html = "<option value=''>--Select--</option>";
        if (!empty($localCommunity)) {
            foreach ($localCommunity as $data) {
                $html .= "<option value='" . $data['id'] . "'>" . $data['name'] . "</option>";
            }
        }

        $response = array(
            'errorcode' => 1,
            'html' => $html
        );

        echo json_encode($response);
        die;
    }

    public function getAdmins() {
        $local_community_id = $_REQUEST['key'];

        $admins = $this->LocalCommunity_model->getAdmins($local_community_id);
		
		$local = $this->LocalCommunity_model->get_record($local_community_id);

        $html = "";
        if (!empty($admins)) {

            $html .= "<p class='text-primary'><b>Please contact bellow admin for " . $local['name'] . " for any query.</b><br>";
            foreach ($admins as $data) {
                $role = "Sub Admin";
                if ($data['role'] == "LOCAL_ADMIN") {
                    $role = "Local Admin";
                }
                $html .= $data['first_name'] . ' ' . $data['last_name'] . " (" . $data['mobile'] . ") - " . $role . "<br>";
            }
            $html .= "</p>";
        }

        $response = array(
            'errorcode' => 1,
            'html' => $html
        );

        echo json_encode($response);
        die;
    }

    public function error404() {
        $data['page_title'] = "Directory | 404";

        $this->load->view('404', $data);
    }

    function getGeoLocations($address = "") {

        $address = "Pine Lake Rd, Improvement District No. 24, AB, Canada";

        $geo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&key=AIzaSyA8wogPKTV_zbvDAvINeWNydzYg_r2LOWI&sensor=false');
        $geo = json_decode($geo, true); // Convert the JSON to an array

        $locations = array();
        if (isset($geo['status']) && ($geo['status'] == 'OK')) {
            $locations['lat'] = $geo['results'][0]['geometry']['location']['lat']; // Latitude
            $locations['lng'] = $geo['results'][0]['geometry']['location']['lng']; // Longitude
        }
        echo "<pre>";
        print_r($geo);
        die;
    }
    
    public function forgotpassword() {
        
        $this->load->library('email');
        $config['protocol'] = 'sendmail';
        $config['mailtype'] = 'html';
        $config['charset'] = 'utf-8';
        $config['wordwrap'] = TRUE;
        $this->email->initialize($config);
        $data['content'] = 'forgotpassword';
        $this->session->unset_userdata('frontLogin');
        if (isset($_POST) && !empty($_POST)) {
            
            $email = $this->input->post('email');
            $mobile_no = $this->input->post('mobile_no');
           
            if($email != '') {
                $user = $this->Users_model->findUserByEmail($email);
                if (!empty($user)) {
                    $subject = 'Your Password';
                    $message = 'YOUR PASSWORD IS : '.$user['plain_password'];
                    // $headers = 'From:demo@muslimghanchisamaj.in' . "\r\n" .
                    //     'Reply-To:kunjan@srbrothersinfotech.com' . "\r\n" .
                    //     'X-Mailer: PHP/' . phpversion();
                        
                    $this->email->from('demo@muslimghanchisamaj.in', 'muslimghanchisamaj');
                    $this->email->to($email);
                    $this->email->subject($subject);
                    $this->email->message($message);
                    
                    if($this->email->send()){
                        $this->session->set_flashdata('flash_message_success', "Your password has been reset. check your mail");
                    }
                    else{
                        $this->session->set_flashdata('flash_message_error', "Opps! Something went wrong. please retry");
                    }
                } 
                else {
                    $this->session->set_flashdata('flash_message_error', "Email Id not exists.");
                }
            }
            
            if($mobile_no != '') {
                $user = $this->Users_model->userMobileExists($mobile_no);
                if (!empty($user)) {
                    $password = $user['plain_password'];
                    
                    $url = "http://bulksms.mysmsmantra.com:8080/WebSMS/SMSAPI.jsp?username=mamajigraph&password=mamaji@70&sendername=mamaji&mobileno=".$mobile_no."&message=Password:".$password;
    
                    $handle = curl_init();
                    curl_setopt($handle, CURLOPT_URL, ($url));
                    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
                    $output = curl_exec($handle);
                    if (curl_errno($handle)) {
                        $this->session->set_flashdata('flash_message_error', "Error Processing Your Request. Try With Your Email");
                    }
                    curl_close($handle);
                    // print_r($output);die;
                    if (strpos($output, 'successfully') !== false) {
                        $this->session->set_flashdata('flash_message_success', "Your password has been sent. Check your inbox");
                    }else {
                        $this->session->set_flashdata('flash_message_error', "Mobile No. not exists.");
                    }
                }else {
                    $this->session->set_flashdata('flash_message_error', "Mobile No. not exists.");
                }
            }
            
        }
        $this->load->view('layouts/main', $data);
    }

    function logout() {
        $this->session->unset_userdata('frontLogin');
        $this->session->unset_userdata('backEndLogin');
        redirect(base_url());
    }
}
