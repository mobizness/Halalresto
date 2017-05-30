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
	body.login{background: #ffffff!important;}
	.constrctionsite{position: relative; text-align: center; margin-top: 15%;}
</style>


<div class="constrctionsite">
	<div class="container">
		<img title="underconstruction" alt="underconstruction" src="<?php echo $siteUrl; ?>/img/underconstruction.png">
	</div>
</div>

<?php
if (Configure::read('debug') > 0 ):
	echo $this->element('exception_stack_trace');
endif;
?>
