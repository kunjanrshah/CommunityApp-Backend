<?php
/**
 *
 * User: satish4820
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class GetCities extends REST_Controller
{
    function index()
    {
        if (($this->flag) == "1") {

            $data = $this->request_paramiters;
            $date = '';
            if (isset($data['date'])) {
                $date = $data['date'];
            }
            if (isset($data['state_id']) && $data['state_id'] > 0) {

                $cities = $this->model_name->getCities($data['state_id'],$date);

                if( $cities && !empty( $cities ) ){
                    foreach ( $cities as $c_key => $city ) {
                        
                        $this->db->select('id');
                        $this->db->from("users");
                        $this->db->where('city_id', $city['id']);
                        $this->db->where('is_expired', '0');
                        $this->db->where('status', '1');
                        
                        if (isset($data['sub_community_id']) && $data['sub_community_id'] > 0) {
                            $this->db->where('sub_community_id', $data['sub_community_id']);
                        }
                        
                        $cities[$c_key]['count'] = count( $this->db->get()->result_array() );
                    }
                }
                
                usort($cities, make_comparer(['count', SORT_DESC], ['name', SORT_ASC]));
                
                $cities = array_filter($cities, function($a) { return ($a['count'] > 0); });
                
                $deletedids = $this->model_name->getDeleted('cities');
                $lastUpdated = $this->model_name->getLastUpdated('cities');
                if ($deletedids) {
                    $deletedids = explode(',', $deletedids['id']);
                }

                if ($date!='' && isset($cities)) {
                    $succes = array('success' => true, 'message' => $this->config->item('cities_retried'), 'data' => $cities,'deleted' => $deletedids,'last_updated' => $lastUpdated);
                    echo json_encode($succes);
                    exit;
                }elseif(!empty($cities)) {
                    $succes = array('success' => true, 'message' => $this->config->item('cities_retried'), 'data' => $cities,'deleted' => $deletedids,'last_updated' => $lastUpdated);
                    echo json_encode($succes);
                    exit;
                }else{
                    $error = array('success' => false, 'message' => $this->config->item('cities_not_found'));
                    echo json_encode($error);
                    exit;
                }
            }
        } 
    }
}

function make_comparer() {
    // Normalize criteria up front so that the comparer finds everything tidy
    $criteria = func_get_args();
    foreach ($criteria as $index => $criterion) {
        $criteria[$index] = is_array($criterion)
            ? array_pad($criterion, 3, null)
            : array($criterion, SORT_ASC, null);
    }

    return function($first, $second) use (&$criteria) {
        foreach ($criteria as $criterion) {
            // How will we compare this round?
            list($column, $sortOrder, $projection) = $criterion;
            $sortOrder = $sortOrder === SORT_DESC ? -1 : 1;

            // If a projection was defined project the values now
            if ($projection) {
                $lhs = call_user_func($projection, $first[$column]);
                $rhs = call_user_func($projection, $second[$column]);
            }
            else {
                $lhs = $first[$column];
                $rhs = $second[$column];
            }

            // Do the actual comparison; do not return if equal
            if ($lhs < $rhs) {
                return -1 * $sortOrder;
            }
            else if ($lhs > $rhs) {
                return 1 * $sortOrder;
            }
        }

        return 0; // tiebreakers exhausted, so $first == $second
    };
}