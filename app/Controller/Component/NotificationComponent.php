<?php

/**
 * @author Rajaram.R
 * @copyright 2015
 */

App::uses('Component', 'Controller'); 
App::uses('Pusher', 'Vendor');

class NotificationComponent extends Component
{
    /**
     * Global Push Notification using socket io
     * 
     * @param Notification Message 
     */
    public function pushNotification($message) {
        $pusher = new Pusher('81b504477dcc75f482fe', '7c915029b0de3ab3772f', '146768');
        $data = array('message' => htmlspecialchars($message));

        $pusher->trigger('my_notifications', 'notification', $data);
    }
}