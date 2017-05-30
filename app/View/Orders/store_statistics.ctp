<div class="page-content-wrapper">
    <div class="page-content">
        <h3 class="page-title">Statistique</h3>
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li><i class="fa fa-home"></i><a href="<?php echo $siteUrl.'/store/dashboards/index';?>">Accueil</a><i class="fa fa-angle-right"></i></li>

                <li><a href="#">Statistique</a></li>
            </ul>
        </div>
        <div class="alert alert-success" id="orderMessage" style="display:none;"></div>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet box grey-cascade">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-globe"></i>Statistique
                        </div>
                        <div class="tools">									
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="">
                            <label>Please choose a date </label>
                            <input type="text" class="city_sales" placeholder="Please select a date" />
                            <div id="city_sales">

                            </div>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="">
                            <label>Please choose a date </label>
                            <input type="text" class="average_budget" placeholder="Please select a date" />
                            <div id="average_budget">

                            </div>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="">
                            <label>Please choose a date </label>
                            <input type="text" class="most_requested_item" placeholder="Please select a date" />
                            <div id="most_requested_item">

                            </div>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="">
                            <label>Please choose a date </label>
                            <input type="text" class="most_requested_delivery_item" placeholder="Please select a date" />
                            <div id="most_requested_delivery_item">

                            </div>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="">
                            <label>Please choose a date </label>
                            <input type="text" class="most_requested_collection_item" placeholder="Please select a date" />
                            <div id="most_requested_collection_item">

                            </div>
                        </div>
                    </div>

                    <div class="portlet-body">
                        <div class="">
                            <label>Please choose a date </label>
                            <input type="text" class="time_more_money" placeholder="Please select a date" />
                            <div id="time_more_money">

                            </div>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="">
                            <div id="store_reservations">

                            </div>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="">
                            <div id="break_down_revenue">

                            </div>
                        </div>
                    </div>

                    <div class="portlet-body">
                        <div class="">
                            <div id="total_orders_month">

                            </div>
                        </div>
                    </div>

                    <div class="portlet-body">
                        <div class="">
                            <div id="total_revenue_month">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="storeId" value="<?php echo $storeId;?>">
</div>