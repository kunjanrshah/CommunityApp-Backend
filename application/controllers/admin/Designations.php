<?php

require_once(APPPATH . '/libraries/Custom_Controller.php');

class Designations extends Custom_Controller {

    public function __construct() {

        parent::__construct();

        $this->load->model('Designation_model');

        $this->controllerName = "designations";
        $this->singular = "Designation";
        $this->plural = "Designations";
    }

    public function index($id = "") {
        $data['page_title'] = $this->plural;
        $data['controller'] = $this->controllerName;
        $data['singular'] = $this->singular;

        if ($id == "") {
            $data['form_title'] = "Add " . $this->singular;
            $data['action'] = base_url("admin/" . $this->controllerName . "/add");
        } else {
            $data['form_title'] = "Edit " . $this->singular;
            $data['action'] = base_url("admin/" . $this->controllerName . "/edit/" . $id);
            $data['item'] = $this->Designation_model->get_record($id);
        }

        $data['content'] = 'admin/' . $this->controllerName . '/index';
        $this->load->view('admin/layouts/main', $data);
    }

    public function data_list() {

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

        $dataList = $this->Designation_model->get_datatables("", $sWhere, $start, $length, "id", "DESC");
        $dataListCount = $this->Designation_model->get_datatables("", $sWhere);

        $data = array();

        $no = $_POST['start'];
        foreach ($dataList as $r) {
            $no++;
            $row = array();

            $action = '<a title="Edit" href="' . base_url() . 'admin/' . $this->controllerName . '/index/' . $r['id'] . '" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></a>&nbsp;';
            $action .= '<a title="Delete" href="javascript:void()" data-href="' . base_url() . 'admin/' . $this->controllerName . '/delete/' . $r['id'] . '" class="btn btn-danger btn-xs delete"><i class="fa fa-trash"></i></a>';

            $checkBox = '<input type="checkbox" name="id[]" class="Child" value="' . $r['id'] . '">';
            $row[] = $checkBox;
            $row[] = $r['name'];
            $row[] = date('Y-m-d',strtotime($r['updated']));
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
        $data['page_title'] = 'Add ' . $this->singular;

        if (isset($_POST) && !empty($_POST)) {

            $data = array();

            $data = $_POST;
            $data['created_on'] = date('Y-m-d H:i:s');
            $data['created_by'] = $this->login_user_id;
            $data['updated_on'] = date('Y-m-d H:i:s');
            $data['updated_by'] = $this->login_user_id;
            
            $menu = $this->Designation_model->add_record($data);

            if ($menu != "") {

                $this->session->set_flashdata('flash_message_success', $this->config->item('record_add'));

                redirect('admin/' . $this->controllerName);
            }
        }

        $data['content'] = 'admin/' . $this->controllerName . '/add';

        $this->load->view('admin/layouts/main', $data);
    }

    public function edit($id) {

        $data['item'] = $this->Designation_model->get_record($id);
        $data['page_title'] = 'Edit ' . $this->singular;
        $data['id'] = $id;

        if (isset($_POST) && !empty($_POST)) {
            $data = array();
            $data = $_POST;
            $data['updated_on'] = date('Y-m-d H:i:s');
            $data['updated_by'] = $this->login_user_id;


            $menu = $this->Designation_model->update_record($id, $data);

            if ($menu) {

                $this->session->set_flashdata('flash_message_success', $this->config->item('record_update'));

                redirect('admin/' . $this->controllerName);
            }
        }

        $data['content'] = 'admin/' . $this->controllerName . '/edit';

        $this->load->view('admin/layouts/main', $data);
    }

    public function delete($id) {

        $this->Designation_model->delete_record($id);

        $this->session->set_flashdata('flash_message_success', $this->config->item('record_delete'));

        redirect('admin/' . $this->controllerName);
    }

    public function deleteMultiple() {
        if (isset($_POST['id'])) {
            $ids = $_POST['id'];
            $this->Designation_model->delete_multiple($ids);
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
