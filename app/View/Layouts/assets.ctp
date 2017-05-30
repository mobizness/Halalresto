<!DOCTYPE html>
<html lang="en">
    <head>
        <script type="text/javascript">
            if (top != self) {
                top.location = self.location;
            }
        </script>
        <meta charset="utf-8"/>
        <title> <?php echo $title_for_layout; ?> </title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
        <meta http-equiv="X-FRAME-OPTIONS" content="DENY"> <?php
	echo $this->Html->meta('icon', $this->Html->url($siteUrl.'/siteicons/fav.ico')); ?>
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css">
        <link href="<?php echo $siteUrl.'/assets/css/font-awesome.min.css';?>" rel="stylesheet" type="text/css">
        <link href="<?php echo $siteUrl.'/assets/css/bootstrap.min.css';?> "
              rel="stylesheet" type="text/css">
        <link href="<?php echo $siteUrl.'/assets/css/uniform.default.css';?>" rel="stylesheet" type="text/css">
        <link href="<?php echo $siteUrl.'/assets/css/bootstrap-switch.min.css';?>" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo $siteUrl.'/assets/css/select2.css';?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo $siteUrl.'/assets/css/dataTables.bootstrap.css';?>"/>
        <link href="<?php echo $siteUrl.'/assets/css/plugins.css';?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo $siteUrl.'/assets/css/components.css';?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo $siteUrl.'/assets/css/layout.css';?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo $siteUrl.'/assets/css/custom.css';?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo $siteUrl.'/assets/css/jquery.ui.datepicker.css';?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo $siteUrl.'/assets/css/common_new.css';?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo $siteUrl.'/assets/css/bootstrap-datetimepicker.min.css';?>" rel="stylesheet" type="text/css"/>
    </head>
    <body class="page-boxed page-header-fixed page-container-bg-solid page-sidebar-closed-hide-logo ">
        <div class="page-header navbar navbar-fixed-top">
            <div class="page-header-inner">
                <div class="page-logo">
                    <a h href="<?php echo $siteUrl.'/store/dashboards/index'; ?>"><?php echo $siteSetting['Sitesetting']['site_name']; ?></a>
                    <div class="menu-toggler sidebar-toggler"></div>
                </div>
                <a href="javascript:void(0);" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"></a>
                <div class="page-top">
                    <div class="top-menu">
                        <ul class="nav navbar-nav pull-right">
                            <li>
                                <a href="<?php echo $siteUrl.'/store/users/storeLogout'; ?>">
                                    <i class="fa fa-sign-out"></i>
                                    <div class="logoutTxt">Logout</div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
	<?php echo $this->element('storeadmin/sidebar');?>
	<?php echo $this->Session->flash(); ?>
	<?php echo $this->fetch('content'); ?>
        <div class="page-footer">
            <div class="page-footer-inner">2017 &copy; <?php echo $siteSetting['Sitesetting']['site_name']; ?> par <?php echo $siteSetting['Sitesetting']['site_name']; ?></div>
            <div class="scroll-to-top"><i class="icon-arrow-up"></i></div>
        </div>
        <div class="ui-loadercont">
            <div class="spinner_new">
                <div class="spinner-icon_new"></div>
            </div>
        </div>
        <script>
            var rp = "<?php echo $siteUrl; ?>";
            var countryCode = "<?php echo $siteSetting['Country']['iso']; ?>";
            var addressMode = "<?php echo $siteSetting['Sitesetting']['address_mode']; ?>";
        </script>
        <script src="<?php echo $siteUrl.'/assets/js/jquery-1.11.0.min.js';?>" type="text/javascript"></script>
        <script src="<?php echo $siteUrl.'/assets/js/jquery-migrate-1.2.1.min.js';?>" type="text/javascript"></script>
        <script src="<?php echo $siteUrl.'/assets/js/jquery-ui-1.10.3.custom.min.js';?>" type="text/javascript"></script>
        <script src="<?php echo $siteUrl.'/assets/js/bootstrap.min.js';?>" type="text/javascript"></script>
        <script src="<?php echo $siteUrl.'/assets/js/bootstrap-hover-dropdown.min.js';?>" type="text/javascript"></script>
        <script src="<?php echo $siteUrl.'/assets/js/moment.min.js';?>" type="text/javascript"></script>

        <script src="<?php echo $siteUrl.'/assets/js/jquery.uniform.min.js';?>" type="text/javascript"></script>
        <script src="<?php echo $siteUrl.'/assets/js/bootstrap-switch.min.js';?>" type="text/javascript"></script>
        <script type="text/javascript" src="<?php echo $siteUrl.'/assets/js/jquery.dataTables.min.js';?>"></script>
        <script type="text/javascript" src="<?php echo $siteUrl.'/assets/js/dataTables.bootstrap.js';?>"></script>
        <script src="https://code.highcharts.com/highcharts.src.js"></script>
        <script src="<?php echo $siteUrl.'/assets/js/product_mgnt.js';?>" type="text/javascript"></script>

        <script src="<?php echo $siteUrl.'/assets/js/storeSetting.js';?>" type="text/javascript"></script>

        <script src="<?php echo $siteUrl.'/assets/js/metronic.js';?>" type="text/javascript"></script>
        <script src="<?php echo $siteUrl.'/assets/js/layout.js';?>" type="text/javascript"></script>
        <script src="<?php echo $siteUrl.'/assets/js/demo.js';?>" type="text/javascript"></script>	
        <script src="<?php echo $siteUrl.'/assets/js/jquery.validate.min.js';?>" type="text/javascript"></script>
        <script src="<?php echo $siteUrl.'/assets/js/dispatch1.js';?>" type="text/javascript"></script>
        <script src="<?php echo $siteUrl.'/assets/js/daterangepicker.js';?>" type="text/javascript"></script>
        <script src="<?php echo $siteUrl.'/assets/js/onlyaddress.js';?>" type="text/javascript"></script>
	<?php
		if (!empty($loggedUser)) {
		echo $this->Html->script(array(
	        '//js.pusher.com/2.2/pusher.min.js',
	        'jquery.gritter.min.js',
	        'PusherNotifier.js'
	   	));
	} ?>

        <script>

            jQuery(document).ready(function () {
               $('.ui-loadercont').hide();
                Metronic.init(); // init metronic core components
                Layout.init(); // init current layout
                Demo.init(); // init demo features
                clearConsole();
                jQuery(".gestionPlatTable").DataTable();
                jQuery("input[name='status']").change(function () {
                    var value = (jQuery('input[name="status"]:checked').val());
                    if (value == "0") {
                        window.location.href = "https://halal-resto.fr/store/Categories/index?status=0";
                    } else if (value == "1") {
                        window.location.href = "https://halal-resto.fr/store/Categories/index?status=1";
                    } else {
                        window.location.href = "https://halal-resto.fr/store/Categories/index";
                    }
                });
            });
            <?php

        if(strtolower($this->request->params['controller']) == 'orders' && 
        strtolower($this->request->params['action']) == 'store_orderindex' ) { ?>

            setTimeout(function () {
                // window.location.reload();
            }, 15000);
            <?php

		
        
    }

        if (!empty($loggedUser)) { ?>
            //Pusher Notification
            var pusher = new Pusher('<?php echo $pusherKey; ?>');
            var channel = pusher.subscribe('Restaurant_<?php echo $loggedUser['Store']['id']; ?>');
            var notifier = new PusherNotifier(channel);
            <?php
        } ?>

        </script>
    </body>
    <!-- END BODY -->
</html>