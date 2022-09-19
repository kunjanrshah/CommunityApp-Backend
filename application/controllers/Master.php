<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends CI_Controller {

    public function __construct() {

        parent::__construct();

        $this->load->model(array('SubCommunity_model',
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
            'Gotra_model',
            'Relation_model',
        ));
    }

    public function getSubCommunity() {
        $searchTerm = $this->input->get('term');
        $searchResult = $this->SubCommunity_model->getAutoCompleteData($searchTerm);
        $autoArr = array();
        if (!empty($searchResult) && count($searchResult) > 0) {
            $data = array();
            foreach ($searchResult as $search) {
                $data['id'] = $search['id'];
                $data['value'] = $search['name'];
                array_push($autoArr, $data);
            }
        }
        echo json_encode($autoArr);
        exit;
    }

    public function getLocalCommunity() {
        $searchTerm = $this->input->get('term');
        $searchResult = $this->LocalCommunity_model->getAutoCompleteData($searchTerm);
        $autoArr = array();
        if (!empty($searchResult) && count($searchResult) > 0) {
            $data = array();
            foreach ($searchResult as $search) {
                $data['id'] = $search['id'];
                $data['value'] = $search['name'];
                array_push($autoArr, $data);
            }
        }
        echo json_encode($autoArr);
        exit;
    }

    public function getCommittee() {
        $searchTerm = $this->input->get('term');
        $searchResult = $this->Committee_model->getAutoCompleteData($searchTerm);
        $autoArr = array();
        if (!empty($searchResult) && count($searchResult) > 0) {
            $data = array();
            foreach ($searchResult as $search) {
                $data['id'] = $search['id'];
                $data['value'] = $search['name'];
                array_push($autoArr, $data);
            }
        }
        echo json_encode($autoArr);
        exit;
    }

    public function getDesignation() {
        $searchTerm = $this->input->get('term');
        $searchResult = $this->Designation_model->getAutoCompleteData($searchTerm);
        $autoArr = array();
        if (!empty($searchResult) && count($searchResult) > 0) {
            $data = array();
            foreach ($searchResult as $search) {
                $data['id'] = $search['id'];
                $data['value'] = $search['name'];
                array_push($autoArr, $data);
            }
        }
        echo json_encode($autoArr);
        exit;
    }

    public function getBusinessCategory() {
        $searchTerm = $this->input->get('term');
        $searchResult = $this->BusinessCategory_model->getAutoCompleteData($searchTerm);
        $autoArr = array();
        if (!empty($searchResult) && count($searchResult) > 0) {
            $data = array();
            foreach ($searchResult as $search) {
                $data['id'] = $search['id'];
                $data['value'] = $search['name'];
                array_push($autoArr, $data);
            }
        }
        echo json_encode($autoArr);
        exit;
    }

    public function getBusinessSubCategory() {
        $searchTerm = $this->input->get('term');
        $searchResult = $this->BusinessSubCategory_model->getAutoCompleteData($searchTerm);
        $autoArr = array();
        if (!empty($searchResult) && count($searchResult) > 0) {
            $data = array();
            foreach ($searchResult as $search) {
                $data['id'] = $search['id'];
                $data['value'] = $search['name'];
                array_push($autoArr, $data);
            }
        }
        echo json_encode($autoArr);
        exit;
    }

    public function getEducations() {
        $searchTerm = $this->input->get('term');
        $searchResult = $this->Education_model->getAutoCompleteData($searchTerm);
        $autoArr = array();
        if (!empty($searchResult) && count($searchResult) > 0) {
            $data = array();
            foreach ($searchResult as $search) {
                $data['id'] = $search['id'];
                $data['value'] = $search['name'];
                array_push($autoArr, $data);
            }
        }
        echo json_encode($autoArr);
        exit;
    }

    public function getActivity() {
        $searchTerm = $this->input->get('term');
        $searchResult = $this->CurrentActivity_model->getAutoCompleteData($searchTerm);
        $autoArr = array();
        if (!empty($searchResult) && count($searchResult) > 0) {
            $data = array();
            foreach ($searchResult as $search) {
                $data['id'] = $search['id'];
                $data['value'] = $search['activity'];
                array_push($autoArr, $data);
            }
        }
        echo json_encode($autoArr);
        exit;
    }

    public function getOccupations() {
        $searchTerm = $this->input->get('term');
        $searchResult = $this->Occupation_model->getAutoCompleteData($searchTerm);
        $autoArr = array();
        if (!empty($searchResult) && count($searchResult) > 0) {
            $data = array();
            foreach ($searchResult as $search) {
                $data['id'] = $search['id'];
                $data['value'] = $search['occupation'];
                array_push($autoArr, $data);
            }
        }
        echo json_encode($autoArr);
        exit;
    }

    public function getGotra() {
        $searchTerm = $this->input->get('term');
        $searchResult = $this->Gotra_model->getAutoCompleteData($searchTerm);
        $autoArr = array();
        if (!empty($searchResult) && count($searchResult) > 0) {
            $data = array();
            foreach ($searchResult as $search) {
                $data['id'] = $search['id'];
                $data['value'] = $search['gotra'];
                array_push($autoArr, $data);
            }
        }
        echo json_encode($autoArr);
        exit;
    }

    public function getNatives() {
        $searchTerm = $this->input->get('term');
        $searchResult = $this->Native_model->getAutoCompleteData($searchTerm);
        $autoArr = array();
        if (!empty($searchResult) && count($searchResult) > 0) {
            $data = array();
            foreach ($searchResult as $search) {
                $data['id'] = $search['id'];
                $data['value'] = $search['native'];
                array_push($autoArr, $data);
            }
        }
        echo json_encode($autoArr);
        exit;
    }

    public function getMosaads() {
        $searchTerm = $this->input->get('term');
        $searchResult = $this->Mossad_model->getAutoCompleteData($searchTerm);
        $autoArr = array();
        if (!empty($searchResult) && count($searchResult) > 0) {
            $data = array();
            foreach ($searchResult as $search) {
                $data['id'] = $search['id'];
                $data['value'] = $search['name'];
                array_push($autoArr, $data);
            }
        }
        echo json_encode($autoArr);
        exit;
    }

    public function getRelations() {
        $searchTerm = $this->input->get('term');
        $searchResult = $this->Relation_model->getAutoCompleteData($searchTerm);
        $autoArr = array();
        if (!empty($searchResult) && count($searchResult) > 0) {
            $data = array();
            foreach ($searchResult as $search) {
                $data['id'] = $search['id'];
                $data['value'] = $search['name'];
                array_push($autoArr, $data);
            }
        }
        echo json_encode($autoArr);
        exit;
    }

}
