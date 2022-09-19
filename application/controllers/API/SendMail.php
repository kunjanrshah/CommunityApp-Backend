<?php
/**
 *
 * User: satish4820
 * Date: 3/1/2018
 * Time: 11:33 PM
 */
error_reporting(0);
require(APPPATH . '/libraries/REST_Controller.php');

class SendMail extends REST_Controller
{
    function index() 
    {
        $data = $this->request_paramiters;
        if (isset($data['subject']) && isset($data['body']) && isset($data['to_email'])) {
            $this->load->library('email');
            $config['protocol'] = 'sendmail';
            $config['mailtype'] = 'html';
            $config['charset'] = 'utf-8';
            $config['wordwrap'] = TRUE;
            $this->email->initialize($config);

            $subject = $data['subject'];
            $message = $data['body'];
            $email = $data['to_email'];
            // $from = $data['subject'];
            // $from_name = $data['subject'];
            // $headers = 'From:demo@muslimghanchisamaj.in' . "\r\n" .
            //     'Reply-To:kunjan@srbrothersinfotech.com' . "\r\n" .
            //     'X-Mailer: PHP/' . phpversion();
                
            $this->email->from('demo@muslimghanchisamaj.in', 'muslimghanchisamaj');
            $this->email->to($email);
            $this->email->subject($subject);
            $this->email->message($message);
            
            if($this->email->send()){
                $response['success'] = 'success';
                $response['message'] = 'Email sent';
                echo json_encode($response);exit;
            }else{
                $response['success'] = 'error';
                $response['message'] = 'Email not sent';
                echo json_encode($response);exit;
            }
        }
    }

}