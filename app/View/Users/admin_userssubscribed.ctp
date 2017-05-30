<div class="page-content-wrapper">
    <div class="">
        <h3 class="page-title">
            Information News letter User's subscribed list.
        </h3>
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="<?php echo $siteUrl.'/admin/dashboards/index';?>">Accueil</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a href="javascript:void(0);">Information News letter User's subscribed list.</a>
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
                            <i class="fa fa-globe"></i>Information News letter User's subscribed list.
                        </div>
                        <div class="tools">
                            <a href="javascript:void(0);" class="collapse"></a>
                            <a href="javascript:void(0);" class="reload"></a>
                            <a href="javascript:void(0);" class="remove"></a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <table class="table table-striped table-bordered table-hover" id="sample_12">
                            <thead>
                                <tr>
                                    <th class="no-sort">S-No</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody><?php 
                        for ($i = 0; $i<sizeof($users); $i++) {?>
                                <tr class="odd gradeX">
                                    <td><?php echo $users[$i]['Newsletters_user']['id'];?></td>
                                    <td><?php echo $users[$i]['Newsletters_user']['email'];?></td>
                                    <td>
                                        <a class="buttonAction" href="javascript:void(0);" onclick="deleteSubscribedUser(<?php echo ( $users[$i]['Newsletters_user']['id'] ); ?>, 'delete');"><i class="fa fa-trash-o"></i></a>
                                    </td>
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
