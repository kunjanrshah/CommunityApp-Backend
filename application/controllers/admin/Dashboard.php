<?php

require(APPPATH . '/libraries/Custom_Controller.php');

class Dashboard extends Custom_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('users_model', 'ci_model');
        $this->load->model('Events_model');

        $this->load->model(array('State_model', 'City_model', 'SubCommunity_model',
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
            'Subcast_model','Committee_model','CurrentActivity_model','Designation_model'));
    }

    function index() {
        ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if( $this->session->userdata('role') != 'SUPERADMIN') {
            redirect('admin/users');
        }
        $data['page_title'] = 'Dashboard';
        $data['users'] = $this->ci_model->get_head_counts();
        $data['states'] = $this->State_model->get_datatables();
        $data['committee'] = $this->Committee_model->get_datatables();
        $data['currentactivity'] = $this->CurrentActivity_model->get_datatables();
        $data['designation'] = $this->Designation_model->get_datatables();
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
        $data['cities'] = $this->City_model->get_datatables();
        $data['relations'] = $this->Relation_model->get_datatables();

        $data['content'] = 'admin/dashboard';
        $this->load->view('admin/layouts/main', $data);
    }

    function profile() {
        $data['page_title'] = 'Profile';
        $userData = $this->session->userdata('backEndLogin');
        $data['user'] = $this->ci_model->get_user($userData['id']);

        $data['content'] = 'admin/profile';
        $this->load->view('admin/layouts/main', $data);
    }

    function updateProfile($id) {
        if (isset($_POST) && !empty($_POST)) {
            $data = array();
            $userData = $this->session->userdata('backEndLogin');
            $data['first_name'] = $this->input->post('first_name');
            $data['last_name'] = $this->input->post('last_name');
            $data['email_address'] = $this->input->post('email_address');
            if (isset($_POST['password']) && $_POST['password'] != "") {
                $data['password'] = sha1($this->input->post('password'));
                $data['plain_password'] = $this->input->post('password');
            }
            $data['mobile'] = $this->input->post('mobile');
            $data['gender'] = $this->input->post('gender');
            if ($this->input->post('birth_date') != "") {
                $data['birth_date'] = $this->ci_model->convertDate($this->input->post('birth_date'));
            }
            if (!empty($_FILES['profile_pic']) && $_FILES['profile_pic']['name'] != "") {

                $config = array(
                    'upload_path' => realpath('./uploads/users/original/'),
                    'thumb_path' => realpath('./uploads/users/thumb/'),
                    'field' => 'profile_pic',
                    'allowed_types' => 'jpg|jpeg|png',
                    'max_size' => '3000',
                );

                $response = $this->uploadFile($config);
                if ($response['status'] == "success") {
                    $data['profile_pic'] = $response['name'];
                    $userData['profile_pic'] = $response['name'];
                } else {
                    $this->session->set_flashdata('flash_message_error', $response['msg']);
                    redirect('admin/dashboard/profile');
                }
            }
            $menu = $this->ci_model->updateUser($id, $data);

            $userData['email_address'] = $this->input->post('email_address');
            $userData['first_name'] = $this->input->post('first_name');
            $userData['last_name'] = $this->input->post('last_name');
            $this->session->set_userdata('backEndLogin', $userData);

            if ($menu) {
                $this->session->set_flashdata('flash_message_success', $this->config->item('profile_update'));
                redirect('admin/dashboard');
            }
        }
    }

}
