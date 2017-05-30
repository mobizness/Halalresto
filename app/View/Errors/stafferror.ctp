<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.View.Errors
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>

<style type="text/css">
	.page-footer,.logo{display: none;}
	body.login{background: #f2f2f2!important;}
	.errorPage{position: relative; text-align: center;}
	.errorBanner{overflow: hidden; position: relative; text-align: center; width: 100%;}
	.errorBanner img{bottom: -70px; left: 0; position: absolute; width: 100%;}
	.errorPage .errorLink{bottom: 40px; font: 16px Montserrat; margin-left: 10%; position: absolute; text-transform: uppercase; width: 100%;}
	.errorPage .errorLink a{text-transform: lowercase; color: #cc2637; text-decoration: underline; margin-left:10px; font: 17px Montserrat;}
</style>


<div class="errorPage">
	<div class="container">
		<div class="errorBanner">
			<img title="404" alt="404" src="<?php echo $siteUrl; ?>/img/denied.jpg">
			<div class="errorLink">
				Link : <a href="http://foodorderingsystem.com" title="foodorderingsystem">http://foodorderingsystem.com</a>
			</div>
		</div>	
	</div>
</div>


<?php
if (Configure::read('debug') > 0 ):
	echo $this->element('exception_stack_trace');
endif;
?>
