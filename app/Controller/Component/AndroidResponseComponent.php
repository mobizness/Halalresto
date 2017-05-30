<?php

/**
 * @author Rajaram.R
 * @copyright 2015
 */

App::uses('Component', 'Controller'); 
class AndroidResponseComponent extends Component
{
    public $components = array('Session');
    public $uses       = array('user', 'Statuses');
        
    
    /**
     * Send New Order By GCM
     * @param GCM Id, Message, Order Details
     * 
     */
    public function sendOrderByGCM($message, $gcmId) {
        
        
        // Set POST variables
        $url = 'https://android.googleapis.com/gcm/send';
        
        $fields = array(
            'registration_ids' => array($gcmId),
            'data' => $message,
        );

        $headers = array(
            'Authorization: key=' . 'AIzaSyAWl9CSGsW2k-b-zHtyy9OtUXl_5hcjJlE',
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);
        return $result;
    }
    /**
     * AndroidResponseComponent::sendResponseToNativeSite()
     * 
     * @param mixed $serviceUrl
     * @param mixed $message
     * @return
     */
    public function sendResponseToNativeSite($serviceUrl, $message) {
        
        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_URL => $serviceUrl,
                                    CURLOPT_USERAGENT => 'Dispatch System',
                                    CURLOPT_POST => true,
                                    CURLOPT_POSTFIELDS => array('response' => json_encode($message))
                                )
                         );
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        
        $info = curl_getinfo($curl);
        
        // Close request to clear up some resources
        curl_close($curl);
        return $info;

    }
}