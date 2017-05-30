<div class="page-content-wrapper">
    <div class="">
        <h3 class="page-title">
            Information Newsletters
        </h3>
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="<?php echo $siteUrl.'/admin/dashboards/index';?>">Accueil</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a href="javascript:void(0);">Information Newsletters</a>
                </li>
            </ul>
            <div class="page-toolbar">
            </div>
        </div>
        <!-- END PAGE HEADER-->
        <!-- BEGIN PAGE CONTENT-->
        <div class="row">
            <div class="col-md-12">
                <div class="portlet box grey-cascade">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-globe"></i>Information Newsletters
                        </div>
                        <div class="tools">
                            <a href="javascript:void(0);" class="collapse"></a>
                            <a href="javascript:void(0);" class="reload"></a>
                            <a href="javascript:void(0);" class="remove"></a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="btn-group pull-right" style="margin-bottom:4px;">
                            <?php
                            echo $this->Html->link('Ajouter Nouveau <i class="fa fa-plus"></i>',
                                                array('controller'=>'users',
                                                           'action'=>'sendnw'),
                                               	array('class'=>'btn green',
						'escape'=>false)); ?>


                        </div>
                        <table class="table table-striped table-bordered table-hover" id="sample_12">
                            <thead>
                                <tr>
                                    <th class="no-sort">S-No</th>
                                    <th>Subject</th>
                                    <th>Body</th>
                                </tr>
                            </thead>
                            <tbody><?php 
                        for ($i = 0; $i<sizeof($newsletters); $i++) {?>
                                <tr class="odd gradeX">
                                    <td><?php echo $newsletters[$i]['Newsletter']['id'];?></td>
                                    <td><?php echo $newsletters[$i]['Newsletter']['subject'];?></td>
                                    <td><?php echo $newsletters[$i]['Newsletter']['body'];?></td>
                                </tr><?php 
                                       
                                        }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
