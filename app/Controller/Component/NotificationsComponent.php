<?php
/* MN */

App::uses('Component', 'Controller'); 
App::uses('Pusher', 'Vendor');

class NotificationsComponent extends Component
{
    /**
     * Global Push Notification using socket io
     * 
     * @param Notification Message 
     */
    public function pushNotification($message, $channels) {

        $pusherKey    = Configure::read('Pusher.pusherKey');
        $pusherSecret = Configure::read('Pusher.pusherSecret');
        $pusherId     = Configure::read('Pusher.pusherId');

        //$pusher = new Pusher('14981e60a57230c9e7da', 'a70f8070a3ca5b1ab6b3', '218782');
        $pusher = new Pusher($pusherKey, $pusherSecret, $pusherId);
        $data   = array('message' => htmlspecialchars($message));
        $pusher->trigger($channels, 'notification', $data);
    }
}