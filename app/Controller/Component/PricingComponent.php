<?php
/**
 * PricingComponent
 *
 * A component that get payment Pricing.
 *
 * PHP version 5
 *
 * @package		StripeComponent
 * @author		Suresh
 * 
 */

App::uses('Component', 'Controller');



class PricingComponent extends Component {

   /** @param Controller $controller Instantiating controller
     * @return void
     * @throws CakeException
     * @throws CakeException
     */
    #controller 
    public $controller;
    
	public function getPrice($id) {
	   
       $plansdetails  = ClassRegistry::init('Plan');
       return $plansdetails->findById($id);
	   
    }
    
    public function getStripeKeys()
    {
        
    }   

}