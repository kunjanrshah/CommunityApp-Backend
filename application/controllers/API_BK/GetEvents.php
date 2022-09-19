<?php

/**
 *
 * User: satish4820
 * Date: 3/9/2018
 * Time: 10:52 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class GetEvents extends REST_Controller {

    function index() {

        if (($this->flag) == "1") {

            $user_id = $this->user_id;

            $request = $this->request_paramiters;

            $event_date = (isset($request['event_date'])) ? $request['event_date'] : "";

            $page = (array_key_exists('page', $request)) ? $request['page'] : '';

            if (!empty($user_id)) {
                $events = $this->model_name->getEvents($user_id, $event_date, $page);
                $totalEvents = count($this->model_name->getEvents($user_id, $event_date, $page));
                if (!empty($events)) {
                    $succes = array('success' => true, 'message' => $this->config->item('events_retried'), 'data' => $events, 'totalRecords' => $totalEvents);
                    echo json_encode($succes);
                    exit;
                } else {
                    $error = array('success' => false, 'message' => $this->config->item('events_not_found'));
                    echo json_encode($error);
                    exit;
                }
            } else {
                $error = array('success' => false, 'message' => $this->config->item('user_id_missing'));
                echo json_encode($error);
                exit;
            }
        }
    }

}
