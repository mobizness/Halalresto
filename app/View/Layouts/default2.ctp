<?php

/*

For Admin template
 */


?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="utf-8"/>
        <title>
	<?php echo $title_for_layout; ?>
        </title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
            <meta http-equiv="Content-type" content="text/html; charset=utf-8">
                <meta content="" name="description"/>
                <meta content="" name="author"/>
	<?php echo $this->Html->charset(); ?>

                <meta name="robots" content="noindex,nofollow"/>
	<?php echo $this->Html->meta('icon'); ?>

                <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
                <link href="<?php echo $this->webroot;?>assets/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
                <link href="<?php echo $this->webroot;?>assets/css/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
                <link href="<?php echo $this->webroot;?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
                <link href="<?php echo $this->webroot;?>assets/css/uniform.default.css" rel="stylesheet" type="text/css"/>
                <link href="<?php echo $this->webroot;?>assets/css/datepicker3.css" rel="stylesheet" type="text/css"/>
                <link href="<?php echo $this->webroot;?>assets/css/daterangepicker-bs3.css" rel="stylesheet" type="text/css"/>
                <link href="<?php echo $this->webroot;?>assets/css/fullcalendar.min.css" rel="stylesheet" type="text/css"/>
                <link href="<?php echo $this->webroot;?>assets/css/summernote.css" rel="stylesheet" type="text/css">
                    <link href="<?php echo $this->webroot;?>assets/css/dataTables.bootstrap.css" rel="stylesheet" type="text/css"/>
                    <link href="<?php echo $this->webroot;?>assets/css/all.css" rel="stylesheet" type="text/css"/>
                    <link href="<?php echo $this->webroot;?>assets/css/tasks.css" rel="stylesheet" type="text/css"/>
                    <link href="<?php echo $this->webroot;?>assets/css/components.css" id="style_components" rel="stylesheet" type="text/css"/>
                    <link href="<?php echo $this->webroot;?>assets/css/plugins.css" rel="stylesheet" type="text/css"/>
                    <link href="<?php echo $this->webroot;?>assets/css/layout.css" rel="stylesheet" type="text/css"/>
                    <link href="<?php echo $this->webroot;?>assets/css/darkblue.css" rel="stylesheet" type="text/css" id="style_color"/>
                    <link href="<?php echo $this->webroot;?>assets/css/custom.css" rel="stylesheet" type="text/css"/>
                    <link href="<?php echo $this->webroot;?>assets/css/common.css" rel="stylesheet" type="text/css"/>
                    <link href="<?php echo $this->webroot;?>assets/css/jquery.fileupload.css" rel="stylesheet"/>
                    <link href="<?php echo $this->webroot;?>assets/css/jquery.fileupload-ui.css" rel="stylesheet"/>
                    <link href="<?php echo $this->webroot;?>assets/css/login.css" rel="stylesheet" type="text/css"/>
                    <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot;?>assets/css/select2.css"/>
                    <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot;?>assets/js/jstree/dist/themes/default/style.min.css"/>
                    <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot;?>assets/css/jquery.tagsinput.css"/>



                    <!-- Common plugins start -->

                    <script src="<?php echo $this->webroot;?>assets/js/jquery.min.js" type="text/javascript"></script>
                    <script src="<?php echo $this->webroot;?>assets/js/jquery-migrate.min.js" type="text/javascript"></script>
                    <script src="<?php echo $this->webroot;?>assets/js/jquery-migrate.min.js" type="text/javascript"></script>
                    <script src="<?php echo $this->webroot;?>assets/js/jquery.tablednd.js" type="text/javascript"></script>
                    <script src="<?php echo $this->webroot;?>assets/js/bootstrap.min.js" type="text/javascript"></script>

                    <!-- Common plugins End -->

                    <!-- Page level plugins start -->

                    <script type="text/javascript" src="<?php echo $this->webroot;?>assets/js/jquery.dataTables.min.js"></script>
                    <script type="text/javascript" src="<?php echo $this->webroot;?>assets/js/dataTables.bootstrap.js"></script>
                    <script type="text/javascript" src="<?php echo $this->webroot;?>assets/js/bootstrap-datepicker.js"></script>
                    <script type="text/javascript" src="<?php echo $this->webroot;?>assets/js/moment.min.js"></script>
                    <script type="text/javascript" src="<?php echo $this->webroot;?>assets/js/daterangepicker.js"></script>
                    <script src="<?php echo $this->webroot;?>assets/js/jstree.min.js"></script>
                    <script src="<?php echo $this->webroot;?>assets/js/ui-tree.js"></script>
                    <script src="<?php echo $this->webroot;?>assets/js/table-managed.js"></script>
                    <script src="<?php echo $this->webroot;?>assets/js/components-pickers.js"></script>
                    <script src="<?php echo $this->webroot;?>assets/js/amcharts.js" type="text/javascript"></script>
                    <script src="<?php echo $this->webroot;?>assets/js/serial.js" type="text/javascript"></script>
                    <script src="<?php echo $this->webroot;?>assets/js/pie.js" type="text/javascript"></script>
                    <script src="<?php echo $this->webroot;?>assets/js/index.js" type="text/javascript"></script>
                    <script src="<?php echo $this->webroot;?>assets/js/tasks.js" type="text/javascript"></script>
                    <script src="<?php echo $this->webroot;?>assets/js/charts-flotcharts.js"></script>
                    <script src="<?php echo $this->webroot;?>assets/js/charts-amcharts.js"></script>
                    <script src="<?php echo $this->webroot;?>assets/js/highcharts.js"></script>
                    <script src="<?php echo $this->webroot;?>assets/js/jquery.validate.min.js" type="text/javascript"></script>
                    <script src="<?php echo $this->webroot;?>assets/js/summernote.min.js" type="text/javascript"></script>
                    <script src="<?php echo $this->webroot;?>assets/js/components-editors.js"></script>
                    <script src="<?php echo $this->webroot;?>assets/js/form-icheck.js" type="text/javascript"></script>
                    <script src="<?php echo $this->webroot;?>assets/js/jquery.tagsinput.min.js" type="text/javascript"></script>
                    <script src="<?php echo $this->webroot;?>assets/js/components-form-tools.js"></script>
                    <script src="<?php echo $this->webroot;?>assets/js/metronic.js" type="text/javascript"></script>
                    <script src="<?php echo $this->webroot;?>assets/js/layout.js" type="text/javascript"></script>
                    <script src="<?php echo $this->webroot;?>assets/js/demo.js" type="text/javascript"></script>


                    <script src="<?php echo $this->webroot;?>assets/js/components-form-tools.js"></script> 

                    <script>
                        var rp = "<?php echo $this->webroot; ?>";
                    </script>



                    </head>

<?php if(empty($loggedUser['role_id'])) {  ?>
                    <body  class="login">
<?php }else if(isset($loggedUser['role_id']) && ($loggedUser['role_id'] == 1 || $loggedUser['role_id'] == 2 || $loggedUser['role_id'] == 4)) { ?>
                        <body class="page-header-fixed page-quick-sidebar-over-content page-style-square"> 

<?php } ?>

                            <div class="se-pre-con"></div>

                            <script type="text/javascript">
                                $(window).load(function () {
                                    $(".se-pre-con").fadeOut("slow");
                                    ;
                                });
                            </script>

                            <!-- BEGIN LOGIN -->

                            <div class="page-container">
<?php if(empty($loggedUser['role_id'])) {  ?>
                                <div class="logo">
                                    <a href="#">
                                        <img alt="" src="<?php echo $this->webroot;?>assets/img/adminlogo-small.png">
                                    </a>
                                </div>
                                <div class="<?php echo (isset($error)) ? '' : 'content'; ?> ">
<?php
 } else if(($loggedUser['role_id']) && ($loggedUser['role_id'] == 1)) {
echo $this->element('admin/topheader');
echo $this->element('admin/sidebarmenu'); ?>
                                    <div class="page-content-wrapper">
                                        <!-- <div class="page-content clearfix"> -->
	<?php } else {
		 echo $this->element('storeadmin/topheader');
		 echo $this->element('storeadmin/sidebarmenu'); ?>
                                        <div class="page-content-wrapper"> <?php

		} ?>	

                                            <!-- Header Top line ends -->
<?php echo $this->Session->flash(); ?>
<?php echo $this->fetch('content'); ?>
<?php if(empty($loggedUser['role_id'])) {  ?></div><?php } else if(($loggedUser['role_id']) && ($loggedUser['role_id'] == 1 || $loggedUser['role_id'] == 2)) { ?>
                                        <!-- </div>-->
                                        <!--/div-->
<?php } ?>
                                    </div>
                                    <script>
                                        jQuery(document).ready(function () {
                                            Metronic.init(); // init metronic core components
                                            Layout.init(); // init current layout
                                            //Login.init();
                                            Demo.init();
                                            //ComponentsEditors.init();
                                            //ComponentsFormTools.init();
                                            Index.initCharts(); // init index page's custom scripts
                                            ChartsAmcharts.init(); // init demo charts

                                        });
                                        jQuery(window).load(function () {
                                            setTimeout(function () {
                                                $("#flashMessage").hide();

                                            }, 5000);
                                        });
                                    </script>
<?php echo $this->element('sql_dump'); ?>
                                    </body>
                                    </html>
