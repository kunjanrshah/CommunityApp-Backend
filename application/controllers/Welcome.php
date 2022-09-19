<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

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
        $this->load->view('welcome_message');
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

}
