<?php

class Users_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->table = "users";
    }

    function get_datatables($searchWhere = "", $searchParamVal = "", $start = "", $length = "", $orderBy = "", $orderByVal = "") {
        $userData = $this->session->userdata('backEndLogin');
        //define columns for searching
        $aColumns = array("users.id", "users.member_code", "users.first_name", "users.last_name", "users.email_address", "users.mobile", "users.area", "users.role", "cities.city", "states.state");

        // $this->db->select('tu.*, tm.*, tc.countryName, tc.countryCode,tu.createdDate as registeredDate');
        $this->db->select("users.member_code,users.profile_pic,users.id,users.first_name, (select name from sub_casts where id = users.sub_cast_id) as last_name, users.email_address,users.mobile,users.area,users.role,users.status,cities.city,states.state,sc.name as sub_community,lc.name as local_community");
        $this->db->from($this->table);
        $this->db->join('cities', 'cities.id = users.city_id', 'left');
        $this->db->join('states', 'states.id = users.state_id', 'left');
        $this->db->join('sub_community sc', 'sc.id = users.sub_community_id', 'left');
        $this->db->join('local_community lc', 'lc.id = users.local_community_id', 'left');

        // Addtitional Filtering
        $sWhere = "";
        if ($searchParamVal != "") {
            $sWhere = "(";
            for ($i = 0; $i < count($aColumns); $i++) {
                $sWhere .= $aColumns[$i] . " LIKE '%" . $searchParamVal . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }


        if (!empty($sWhere)) {
            $this->db->where($sWhere);
        }

        if (!empty($searchWhere)) {
            $this->db->where($searchWhere);
        }

        if ($userData['role'] == "SUB_ADMIN" || $userData['role'] == "LOCAL_ADMIN") {
            if ($userData['role'] == "SUB_ADMIN") {
                $this->db->where('users.sub_community_id', $userData['sub_community_id']);
            }
            if ($userData['role'] == "LOCAL_ADMIN") {
                $this->db->where('users.local_community_id', $userData['local_community_id']);
            }
            $this->db->where('users.id != ', $userData['id']);
        }

        $this->db->where('users.head_id', '0');
        $this->db->where('users.role IN ("USER","SUB_ADMIN","LOCAL_ADMIN")');

        // $this->db->group_by('shortId');
        if ($start >= 0 && $length > 0) {
            $this->db->limit($length, $start);
        }
        if (!empty($orderBy) && !empty($orderByVal)) {
            $this->db->order_by($orderBy, $orderByVal);
        }

        $posts = $this->db->get()->result_array();

        //  echo $this->db->last_query();exit;

        if (count($posts)) {
            foreach ($posts as $k => $v) {
                $posts[$k]['members'] = $this->getMembers($posts[$k]['id'], 1);
            }
            return $posts;
        } else {
            return array();
        }
    }

    function user_validate($userName, $password) {

        $this->db->select('*, (select name from local_community where id = users.local_community_id) as local_community_name, (select name from sub_community where id = users.sub_community_id) as sub_community_name, (select name from sub_casts where id = users.sub_cast_id) as last_name');
        $this->db->from('users');
        $this->db->where('mobile', $userName);
        $this->db->where('password', sha1($password));
        $this->db->where("status",1);

        $query = $this->db->get()->result_array();

        if (!empty($query)) {
            return $query;
        } else {
            return false;
        }
    }
    
    function validate($userName, $password) {
        $this->db->where('mobile', $userName);
        $this->db->where('password', sha1($password));
        $this->db->where("role IN ('SUB_ADMIN','LOCAL_ADMIN','SUPERADMIN')");

        $query = $this->db->get('users')->result_array();

        if (!empty($query)) {
            //echo "True";
            return $query;
        } else {
            //echo "Flase";
            return false;
        }
    }

    function getRows() {
        $this->db->select('u.*');
        $this->db->from('users u');
        $this->db->order_by('u.id', 'DESC');
        $query = $this->db->get();
        return ($query->num_rows() > 0) ? $query->result_array() : FALSE;
    }

    function findUserByMobile($mobile, $head = 1, $id = "") {
        $this->db->select('u.*');
        $this->db->from('users u');
        $this->db->where('u.mobile', $mobile);
        $this->db->where('u.role != "SUPERADMIN"');
        if ($head == 1) {
            $this->db->where('u.head_id', '0');
        }
        if ($id != "") {
            $this->db->where('u.id != ', $id);
        }
        $query = $this->db->get();
        return $query->row_array();
    }

    function loginByMobile($mobile) {
        $this->db->select('u.*');
        $this->db->from('users u');
        $this->db->where('u.mobile', $mobile);
        $query = $this->db->get();
        return $query->row_array();
    }

    function findUserByEmail($email, $id = "") {
        $this->db->select('u.*');
        $this->db->from('users u');
        $this->db->where('u.email_address', $email);
        if ($id != "") {
            $this->db->where('u.id != ', $id);
        }
        $query = $this->db->get();
        $result = $query->row_array();
        
        return $result;
    }

    function getMembers($head_id, $admin = 0) {
        $this->db->select('u.*, (select name from sub_casts where id = u.sub_cast_id) as last_name,  r.name as relation');
        $this->db->from('users u');
        $this->db->join('relations r', 'r.id = u.relation_id', 'left');
        $this->db->where('u.head_id', $head_id);
        if ($admin != 1) {
            $this->db->or_where('u.id', $head_id);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    function getUsersByRole($role) {
        $this->db->select('u.*');
        $this->db->from('users u');
        $this->db->where('u.role IN (' . $role . ')');
        $this->db->order_by('u.id', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function user_by_user_name($UserName) {
        $this->db->from('users');
        $this->db->where('email', $UserName);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getUser($id) {
        $select = "u.*,
                r.name as relation,
                sca.name as sub_cast,
                bc.name as business_category,
                bsc.name as business_sub_category,
                c.name as committee,
                ca.activity as current_activity,
                d.name as designation,
                e.name as education,
                g.gotra,
                lc.name as local_community,
                di.name as distinct,
                n.native as native_place,
                m.name as mosaad,
                o.occupation,
                sc.name as sub_community";
        $this->db->select($select);
        $this->db->from('users u');
        $this->db->join('relations r', 'r.id = u.relation_id', 'left');
        $this->db->join('sub_community sc', 'sc.id = u.sub_community_id', 'left');
        $this->db->join('sub_casts sca', 'sca.id = u.sub_cast_id', 'left');
        $this->db->join('local_community lc', 'lc.id = u.local_community_id', 'left');
        $this->db->join('committees c', 'c.id = u.committee_id', 'left');
        $this->db->join('designations d', 'd.id = u.designation_id', 'left');
        $this->db->join('distincts di', 'di.id = u.distinct_id', 'left');
        $this->db->join('native n', 'n.id = u.native_place_id', 'left');
        $this->db->join('current_activity ca', 'ca.id = u.current_activity_id', 'left');
        $this->db->join('gotra g', 'g.id = u.gotra_id', 'left');
        $this->db->join('mosaads m', 'm.id = u.mosaad_id', 'left');
        $this->db->join('business_categories bc', 'bc.id = u.business_category_id', 'left');
        $this->db->join('business_sub_categories bsc', 'bsc.id = u.business_sub_category_id', 'left');
        $this->db->join('educations e', 'e.id = u.education_id', 'left');
        $this->db->join('occupation o', 'o.id = u.occupation_id', 'left');
        $this->db->where('u.id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getUsersExportData($state_id='', $city_id='', $sub_community_id='', $local_community_id='') {
        $select = "u.*,
                r.name as relation,
                sca.name as sub_cast,
                bc.name as business_category,
                bsc.name as business_sub_category,
                c.city,
                s.state,
                ca.activity as current_activity,
                e.name as education,
                lc.name as local_community,
                di.name as district,
                n.native as native_place,
                o.occupation,
                sc.name as sub_community";
        $this->db->select($select);
        $this->db->from('users u');
        $this->db->join('relations r', 'r.id = u.relation_id', 'left');
        $this->db->join('sub_community sc', 'sc.id = u.sub_community_id', 'left');
        $this->db->join('local_community lc', 'lc.id = u.local_community_id', 'left');
        $this->db->join('sub_casts sca', 'sca.id = u.sub_cast_id', 'left');
        $this->db->join('distincts di', 'di.id = u.distinct_id', 'left');
        $this->db->join('cities c', 'c.id = u.city_id', 'left');
        $this->db->join('states s', 's.id = u.state_id', 'left');
        $this->db->join('native n', 'n.id = u.native_place_id', 'left');
        $this->db->join('current_activity ca', 'ca.id = u.current_activity_id', 'left');
        $this->db->join('business_categories bc', 'bc.id = u.business_category_id', 'left');
        $this->db->join('business_sub_categories bsc', 'bsc.id = u.business_sub_category_id', 'left');
        $this->db->join('educations e', 'e.id = u.education_id', 'left');
        $this->db->join('occupation o', 'o.id = u.occupation_id', 'left');
        $this->db->where('u.head_id', 0);
        $this->db->where('u.role != "SUPERADMIN"');

        if(isset($state_id) && $state_id != ''){
            $this->db->where("u.state_id", $state_id);
        }

        if(isset($city_id) && $city_id != ''){
            $this->db->where("u.city_id", $city_id);
        }

        if(isset($sub_community_id) && $sub_community_id != ''){
            $this->db->where("u.sub_community_id", $sub_community_id);
        }

        if(isset($local_community_id) && $local_community_id != ''){
            $this->db->where("u.local_community_id", $local_community_id);
        }

        $query = $this->db->get();
        $posts = $query->result_array();

        $exportArr = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $d['ID'] = $post['id'];
                $d['Head ID'] = $post['head_id'];
                $d['Relation'] = $post['relation'];
                $d['Sub Community'] = $post['sub_community'];
                $d['Local Community'] = $post['local_community'];
                $d['First Name'] = $post['first_name'];
                $d['Last Name'] = $post['last_name'];
                $d['Sub Cast'] = $post['sub_cast'];
                $d['Gender'] = $post['gender'];
                $d['Email Address'] = $post['email_address'];
                $d['Is Live?'] = ($post['is_expired'] == 1) ? "Yes" : "No";
                $d['Expire Date'] = $post['expire_date'];
                $d['Address'] = $post['address'];
                $d['Local Address'] = $post['local_address'];
                $d['State'] = $post['state'];
                $d['City'] = $post['city'];
                $d['Area'] = $post['area'];
                $d['Pincode'] = $post['pincode'];
                $d['Mobile'] = $post['mobile'];
                $d['Phone'] = $post['phone'];
                $d['Native District'] = $post['district'];
                $d['Native Village'] = $post['native_place'];
                $d['Current Activity'] = $post['current_activity'];
                $d['Education'] = $post['education'];
                $d['Blood Group'] = $post['blood_group'];
                $d['Is Blood Donor'] = ($post['is_donor'] == 1) ? "Yes" : "No";
                $d['Marital Status'] = $post['marital_status'];
                $d['Marriage Date'] = $post['marriage_date'];
                $d['Business Category'] = $post['business_category'];
                $d['Business Sub Category'] = $post['business_sub_category'];
                $d['Company Name'] = $post['company_name'];
                $d['Company Address'] = $post['business_address'];
                $d['Website'] = $post['website'];
                $d['Occupation'] = $post['occupation'];
                $d['Work Details'] = $post['work_details'];
                $d['Interested in Matrimony?'] = $post['matrimony'];
                $d['Birth Date'] = $post['birth_date'];
                $d['Birth Time'] = $post['birth_time'];
                $d['Birth Place'] = $post['birth_place'];
                $d['Weight'] = $post['weight'];
                $d['Height'] = $post['height'];
                $d['Is Spect?'] = ($post['is_spect'] == 1) ? "Yes" : "No";
                $d['About Me'] = $post['about_me'];
                $d['Hobby'] = $post['hobby'];
                $d['Expectation'] = $post['expectation'];
                $d['Social Profile URL'] = $post['facebook_profile'];
                $exportArr[] = $d;
                $members = $this->getMembersForExport($post['id']);
                if (!empty($members)) {
                    foreach ($members as $member) {
                        $exportArr[] = $member;
                    }
                }
            }
        }

        return $exportArr;
    }

    function getMembersForExport($id) {
        $select = "u.*,
                r.name as relation,
                sca.name as sub_cast,
                bc.name as business_category,
                bsc.name as business_sub_category,
                c.city,
                s.state,
                ca.activity as current_activity,
                e.name as education,
                lc.name as local_community,
                di.name as district,
                n.native as native_place,
                o.occupation,
                sc.name as sub_community";
        $this->db->select($select);
        $this->db->from('users u');
        $this->db->join('relations r', 'r.id = u.relation_id', 'left');
        $this->db->join('sub_community sc', 'sc.id = u.sub_community_id', 'left');
        $this->db->join('local_community lc', 'lc.id = u.local_community_id', 'left');
        $this->db->join('sub_casts sca', 'sca.id = u.sub_cast_id', 'left');
        $this->db->join('distincts di', 'di.id = u.distinct_id', 'left');
        $this->db->join('cities c', 'c.id = u.city_id', 'left');
        $this->db->join('states s', 's.id = u.state_id', 'left');
        $this->db->join('native n', 'n.id = u.native_place_id', 'left');
        $this->db->join('current_activity ca', 'ca.id = u.current_activity_id', 'left');
        $this->db->join('business_categories bc', 'bc.id = u.business_category_id', 'left');
        $this->db->join('business_sub_categories bsc', 'bsc.id = u.business_sub_category_id', 'left');
        $this->db->join('educations e', 'e.id = u.education_id', 'left');
        $this->db->join('occupation o', 'o.id = u.occupation_id', 'left');
        $this->db->where('u.head_id', $id);
        $query = $this->db->get();
        $posts = $query->result_array();

        $exportMembers = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $d['ID'] = $post['id'];
                $d['Head ID'] = $post['head_id'];
                $d['Relation'] = $post['relation'];
                $d['Sub Community'] = $post['sub_community'];
                $d['Local Community'] = $post['local_community'];
                $d['First Name'] = $post['first_name'];
                $d['Last Name'] = $post['last_name'];
                $d['Sub Cast'] = $post['sub_cast'];
                $d['Gender'] = $post['gender'];
                $d['Email Address'] = $post['email_address'];
                $d['Is Live?'] = ($post['is_expired'] == 1) ? "Yes" : "No";
                $d['Expire Date'] = $post['expire_date'];
                $d['Address'] = $post['address'];
                $d['Local Address'] = $post['local_address'];
                $d['State'] = $post['state'];
                $d['City'] = $post['city'];
                $d['Area'] = $post['area'];
                $d['Pincode'] = $post['pincode'];
                $d['Mobile'] = $post['mobile'];
                $d['Phone'] = $post['phone'];
                $d['Native District'] = $post['district'];
                $d['Native Village'] = $post['native_place'];
                $d['Current Activity'] = $post['current_activity'];
                $d['Education'] = $post['education'];
                $d['Blood Group'] = $post['blood_group'];
                $d['Is Blood Donor'] = ($post['is_donor'] == 1) ? $post['is_donor'] : "";
                $d['Marital Status'] = $post['marital_status'];
                $d['Marriage Date'] = $post['marriage_date'];
                $d['Business Category'] = $post['business_category'];
                $d['Business Sub Category'] = $post['business_sub_category'];
                $d['Company Name'] = $post['company_name'];
                $d['Company Address'] = $post['business_address'];
                $d['Website'] = $post['website'];
                $d['Occupation'] = $post['occupation'];
                $d['Work Details'] = $post['work_details'];
                $d['Interested in Matrimony?'] = $post['matrimony'];
                $d['Birth Date'] = $post['birth_date'];
                $d['Birth Time'] = $post['birth_time'];
                $d['Birth Place'] = $post['birth_place'];
                $d['Weight'] = $post['weight'];
                $d['Height'] = $post['height'];
                $d['Is Spect?'] = ($post['is_spect'] == 1) ? "Yes" : "No";
                $d['About Me'] = $post['about_me'];
                $d['Hobby'] = $post['hobby'];
                $d['Expectation'] = $post['expectation'];
                $d['Social Profile URL'] = $post['facebook_profile'];
                $exportMembers[] = $d;
            }
        }

        return $exportMembers;
    }

    function send_emailsimple($subject, $to, $from, $body) {

        $this->load->library('email');
        $this->email->clear();

        $config['protocol'] = 'sendmail';
        $config['mailpath'] = '/usr/sbin/sendmail';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $config['mailtype'] = 'html';
        $this->email->initialize($config);
        //$this->email->set_newline("\r\n");
        $this->email->from($from, 'one-99'); // change it to yours
        $this->email->to($to); // change it to yours
        $this->email->subject($subject);
        $this->email->message($body);
        //$this->email->set_mailtype("html");
        if ($this->email->send()) {
            return '1';
        } else {
            return '0';
        }
    }

    function get_user($userID) {

        $this->db->select('u.*, (select name from sub_casts where id = u.sub_cast_id) as last_name');
        $this->db->from('users u');
        $this->db->where('u.id', $userID);
        $query = $this->db->get();
        return $query->row_array();
    }

    function add_user($params) {
        $this->db->insert('users', $params);
        return $this->db->insert_id();
    }

    function updateUser($userID, $params) {
        $this->db->where('id', $userID);
        return $this->db->update('users', $params);
    }

    function delete_user($userID) {
        $user = $this->session->userdata('frontLogin');
        $this->db->delete('users', array('id' => $userID));
        if (!empty($user) && $user['id'] == $userID) {
            $this->db->delete('users', array('head_id' => $userID));
        }
    }

    function delete_multiple($ids) {
        foreach ($ids as $id) {
            $user = $this->session->userdata('frontLogin');
            $this->db->delete('users', array('id' => $id));
            $this->db->delete('users', array('head_id' => $id));
        }
    }

    public function checkOldPass($oldpassword, $adminID) {
        $this->db->where('password', sha1($oldpassword));
        $this->db->where('id', $adminID);
        $query = $this->db->count_all_results('users');

        if ($query > 0)
            return 1;
        else
            return 0;
    }

    function update_user($fieldname, $id, $data, $table) {
        $this->db->where($fieldname, $id);
        $this->db->update($table, $data);
        return 1;
    }

    function get_db_session_data() {
        $query = $this->db->select('user_data')->get('ci_sessions');

        $user = array(); /* array to store the user data we fetch */
        foreach ($query->result() as $row) {
            $udata = unserialize($row->user_data);
            /* put data in array using username as key */
            $user['userName'] = $udata['userName'];
            $user['adminID'] = $udata['adminID'];
            $user['is_logged_in'] = $udata['is_logged_in'];
        }
        return $user;
    }

    function checkEmailExists($email) {
        $row = $this->db->get_where('users', array('email' => $email))->row_array();
        if (!empty($row)) {
            return $row;
        }
        return FALSE;
    }

    function convertDate($date) {
        $dateArr = explode('/', $date);

        return $dateArr[2] . '-' . $dateArr[1] . '-' . $dateArr[0];
    }

    function getCommitteeMembers($id) {
        
        $sql = "SELECT committee_member.*, users.first_name, users.last_name, users.email_address, users.mobile, (SELECT name FROM local_community WHERE local_community.id = users.local_community_id) as community_name, (SELECT name FROM committees WHERE committees.id = committee_member.committee_id) as committee_name FROM committee_member JOIN users ON users.id = committee_member.user_id WHERE committee_member.user_id IN (SELECT id FROM users WHERE id = $id OR head_id = $id)";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getHeadMembers($id) {
        $sql = "SELECT users.id, CONCAT(users.first_name,' ',users.last_name) as user_name FROM  users WHERE users.id = $id OR head_id = $id";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function get_record($id) {

        $sql = "SELECT id, user_id, committee_id, DATE_FORMAT(start_date, '%d/%m/%Y') as start_date, committee_id, DATE_FORMAT(end_date, '%d/%m/%Y') as end_date FROM committee_member WHERE id = $id";

        $query = $this->db->query($sql);
        return $query->row_array();
    }

    function add_committee_member($params) {
        $this->db->insert('committee_member', $params);
        return $this->db->insert_id();
    }

    function update_committee_member($id, $params) {

        $this->db->where('id', $id);

        return $this->db->update('committee_member', $params);
    }

    function delete_committee_member($id) {
        $this->db->delete('committee_member', array('id' => $id));
    }

    function delete_multiple_committee_member($ids) {
        $this->db->where_in('id', $ids);
        $this->db->delete('committee_member');
    }
}
