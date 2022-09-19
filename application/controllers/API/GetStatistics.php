<?php
/**
 *
 * User: satish4820
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class GetStatistics extends REST_Controller
{
    function index()
    {
        if (($this->flag) == "1") {

            $data = $this->request_paramiters;

            if (isset($data['city_id'])) {
                
                $city_id = $data['city_id'];
                
                if($city_id > 0) {
                  $cityId = " AND city_id=".$city_id;
                }else{
                    $cityId = "";
                }
                
              $subComm="";
                if(isset($data['sub_community_id'])){
                    $sub_community_id=$data['sub_community_id'];
                    if($sub_community_id > 0) {
                      $subComm = " AND sub_community_id=".$sub_community_id;
                    }else{
                        $subComm = "";
                    }
                }
                
                $localComm="";
                if(isset($data['local_community_id'])){
                    $local_community_id=$data['local_community_id'];
                    if($local_community_id > 0) {
                      $localComm = " AND local_community_id=".$local_community_id;
                    }else{
                        $localComm = "";
                    }
                }
                
                $boy_year= date('Y')-21;
                $boy_date= "'".$boy_year."-01-01'";
                
                $girl_year= date('Y')-18;
                $girl_date= "'".$girl_year."-01-01'";
               
                $TotalVillages = $this->db->query("SELECT DISTINCT(city_id) FROM users WHERE is_expired = '0'".$subComm.$localComm);
                $TotalFamily = $this->db->query("SELECT id FROM users WHERE is_expired = '0'".$cityId." AND head_id=0".$subComm.$localComm);
                $TotalMembers = $this->db->query("SELECT * FROM users WHERE 1=1".$cityId." AND is_expired = '0'".$subComm.$localComm);
                $TotalMale = $this->db->query("SELECT id FROM users WHERE 1=1".$cityId." AND gender='Male' AND is_expired = '0'".$subComm.$localComm);
                $TotalFemale = $this->db->query("SELECT id FROM users WHERE is_expired = '0'".$cityId." AND gender='Female'".$subComm.$localComm);
                $TotalUnmarriedMale = $this->db->query("SELECT id FROM users WHERE is_expired = '0'".$cityId." AND gender='Male' AND marital_status='Unmarried' AND birth_date >= ".$boy_date.$subComm.$localComm);
                $TotalUnmarriedFemale = $this->db->query("SELECT id FROM users WHERE is_expired = '0'".$cityId." AND gender='Female' AND marital_status='Unmarried' AND birth_date >= ".$girl_date.$subComm.$localComm);              
                $TotalInterestedMale = $this->db->query("SELECT id FROM users WHERE is_expired = '0'".$cityId." AND gender='Male' AND matrimony='Yes'".$subComm.$localComm);
                $TotalInterestedFemale = $this->db->query("SELECT id FROM users WHERE is_expired = '0'".$cityId." AND gender='Female' AND matrimony='Yes'".$subComm.$localComm);
               
               
               $sub_community_id="";
                if(isset($data['sub_community_id'])){
                    $sub_community_id=$data['sub_community_id'];
                }
                $local_community_id="";
                if(isset($data['local_community_id'])){
                    $local_community_id=$data['local_community_id'];
                }
                
                $cities = $this->model_name->getStatisticCities($sub_community_id,$local_community_id);
             
                      
              // echo print_r($cities);
               
                $response = array(
                    'TotalVillages' => $TotalVillages->num_rows(),
                    'TotalFamily' => $TotalFamily->num_rows(),
                    'TotalMembers' => $TotalMembers->num_rows(),
                    'TotalMale' => $TotalMale->num_rows(),
                    'TotalFemale' => $TotalFemale->num_rows(),
                    'TotalUnmarriedMale' => $TotalUnmarriedMale->num_rows(),
                    'TotalUnmarriedFemale' => $TotalUnmarriedFemale->num_rows(),
                    'TotalInterestedMale' => $TotalInterestedMale->num_rows(),
                    'TotalInterestedFemale' => $TotalInterestedFemale->num_rows(),
                );
                
                if($city_id > 0) {
                    unset($response['TotalVillages']);
                }
                
                $succes = array('success' => true, 'data' => $response, 'cities' => $cities);
                echo json_encode($succes);
                exit;
            }
        } 
    }
}