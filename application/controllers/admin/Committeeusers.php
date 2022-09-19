<?php
require(APPPATH . '/libraries/Custom_Controller.php');

class Committeeusers extends Custom_Controller {

    public function __construct() {

        parent::__construct();

        $this->load->model(array('Users_model', 'SubCommunity_model',
            'LocalCommunity_model',
            'Committee_model',
            'Designation_model'));
    }

    public function users($id) {
        $data['page_title'] = 'Committee Members';
        $data['head'] = $this->Users_model->get_user($id);
        $data['id'] = $id;
        $data['members'] = $this->Users_model->getCommitteeMembers($id);
        $data['head_members'] = $this->Users_model->getHeadMembers($id);
        $data['committee'] = $this->Committee_model->get_allcommitee($id);
        $data['designations'] = $this->Designation_model->get_alldesignations($id);
        $data['content'] = 'admin/users/committee_users';
        $this->load->view('admin/layouts/main', $data);
    }

    public function add($id) {

        if (isset($_POST) && !empty($_POST)) {
            
            $arr = explode('/', $this->input->post('start_date'));
            $start_date = $arr[2].'-'.$arr[1].'-'.$arr[0];

            $arr = explode('/', $this->input->post('end_date'));
            $end_date = $arr[2].'-'.$arr[1].'-'.$arr[0];

            $data = array('user_id' => $this->input->post('user_id'),
                            'committee_id' => $this->input->post('committee_id'),
                            'designation_id' => $this->input->post('designation_id'),
                            'start_date' => $start_date,
                            'end_date' => $end_date);
            
            $response = $this->Users_model->add_committee_member($data);
            if ($response > 0) {
                $this->session->set_flashdata('flash_message_success', $this->config->item('customer_add'));
                redirect('admin/committeeusers/users/'.$id);
            }
            else{
                $this->session->set_flashdata('flash_message_error', $this->config->item('customer_add'));
                redirect('admin/committeeusers/users/'.$id);
            }
        }
    }

    public function get_record($id) {
        $item = $this->Users_model->get_record($id);
        die(json_encode($item));
    }

    public function edit($id) {

        if(isset($_POST) && !empty($_POST)) {
            $arr = explode('/', $this->input->post('start_date'));
            $start_date = $arr[2].'-'.$arr[1].'-'.$arr[0];

            $arr = explode('/', $this->input->post('end_date'));
            $end_date = $arr[2].'-'.$arr[1].'-'.$arr[0];

            $data = array('user_id' => $this->input->post('user_id'),
                            'committee_id' => $this->input->post('committee_id'),
                            'designation_id' => $this->input->post('designation_id'),
                            'start_date' => $start_date,
                            'end_date' => $end_date);

            $menu = $this->Users_model->update_committee_member($this->input->post('hidden_id'), $data);

            if($menu){
                $this->session->set_flashdata('flash_message_success',$this->config->item('record_update'));
            }
            else{
                $this->session->set_flashdata('flash_message_error',$this->config->item('record_update'));
            }
        }

        redirect('admin/committeeusers/users/'.$id);
    }

    public function delete($id) {
        $last_url = $_SERVER['HTTP_REFERER'];

        $this->Users_model->delete_committee_member($id);

        $this->session->set_flashdata('flash_message_success', $this->config->item('record_delete'));

        redirect($last_url);
    }

    public function deleteMultiple() {
        if (isset($_POST['id'])) {
            $ids = $_POST['id'];
            $this->Users_model->delete_multiple_committee_member($ids);
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
}