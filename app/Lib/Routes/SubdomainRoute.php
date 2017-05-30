<?php
App::uses('User', 'Model');

class SubdomainRoute extends CakeRoute {

    /**
     * Name of the subdomain to use
     * 
     * @var
     */
    private $subdomain = NULL;

    /**
     * Overrides the routes constructor not to use templates
     */
    public function __construct() {

        $this->subdomain = Configure::read('SubdomainHTTP.subdomain');

        if ($this->subdomain != false) {
            $this->setSubdomain();
        }
    }

    /**
     * Determine subdomain (assoc_id), set to global var
     *
     * @return null
     */
    private function setSubdomain() {

        
        /*$Subdomain = new User();
       // $subdomain = $Subdomain->find("first", array('conditions' => array('User.domain_name' => $this->subdomain)));
        
        if (!isset($subdomain['Subdomain']['sub_bus_id'])) {
            throw new BadRequestException('The subdomain specified does not exist.');
        }
        */
        Configure::write('Subdomain', array(
            'sub_name' =>$subdomain['Subdomain']['sub_name'],
            'sub_template' => $subdomain['Subdomain']['sub_template']
                )
        );
    }
}
