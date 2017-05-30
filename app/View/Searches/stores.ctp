
<style>
    .thumbnail img {
        height: 253px !important;
    }
    .thumbnail{padding:0; text-align:center; border-radius:0; border:none; box-shadow:0 2px 5px 0 rgba(0,0,0,.16),0 2px 10px 0 rgba(0,0,0,.12)}
    .thumbnail>img{width:100%; display:block}
    .thumbnail h3{font-size:26px}
    .thumbnail h4,.card-description{margin:0; padding:8px 0; border-bottom:solid 1px #eee;}
    .thumbnail h4{color: red;}
    .thumbnail p{padding-top:0px; font-size:14px; margin: 0 0 5px;}
    .thumbnail .btn{border-radius:0; box-shadow:0 2px 5px 0 rgba(0,0,0,.16),0 2px 10px 0 rgba(0,0,0,.12); font-size:20px}
    @media(min-width: 768px){
        .cards{
            padding: 20px 50px;
        }
    }

    header {

        background-image:url("https://halal-resto.fr/frontend/images/searchbanner.jpg");
        max-height: 400px;
        min-height: 300px;
        width: 100%;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        overflow: hidden;
        position: relative;
    }
    .group h1{
        background-color: rgba(255,255,255,0.5);
        color: black;
        font-size: 45px;
        font-weight: bold;
    }
    .group {
        color: white;
        position: absolute;
        width: 100%;
        bottom: 40%;
        margin: 0 auto;
        text-align: center;
    }
    body{
        margin: 0;
    }
    @media(max-width: 767px){
        .group{
            bottom: 0%;
        }
        .group h1{
            font-size: 30px;
        }
    }
    @media(max-width: 640px){
        #filter-search{
            margin-top: 20%;
            margin-bottom: 20%;
        }
        .group{
            bottom: 0%;
        }
        .group h1{
            font-size: 30px;
        }

    }
</style>

<div class="outersec searchshopContent">

    <header>
        <div class="container group">
            <div style="padding-left: 10%; padding-right: 10%;">
                <h1 style="text-transform: uppercase;padding:10px;">Les meilleurs restaurants de Paris et sa banlieue</h1>
            </div>
        </div>
    </header>
<?php  if (!empty($storeList)) { ?>
    <div class="container cards">
        <div class="dropdown">
            <button class="btn btn-default dropdown-toggle" type="button" id="filter-search" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                <span class="glyphicon glyphicon-tasks" style="margin-right: 8px;"></span> Filters
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" aria-labelledby="filter-search">
                <div class="col-xs-12">

                    <div class="cuisinefil">
                        <h4><?php echo __('Filter Search'); ?></h4> 
                        <div class="col-xs-12 cuisinelabel">
                            <div class="col-sm-12 no-padding">
                                <input class="searchbytypeinput" type="radio" name="select_type" id="pickup" value="pickup" >
                                <label for="pickup" class="searchbytypebtn"><?php echo __('Pickup');?></label>
                                <input class="searchbytypeinput" type="radio" name="select_type" id="delivery" value="delivery" >
                                <label for="delivery" class="searchbytypebtn"><?php echo __('Delivery');?></label>
                                <input class="searchbytypeinput" type="radio" name="select_type" id="book" value="bookatable" >
                                <label for="book" class="searchbytypebtn"><?php echo __('Bookatable');?></label>
                            </div> 
                        </div>                     
                    </div>
                    
                    <div class="cuisinefil">
                        <h4>Spécialités culinaires</h4> <?php
                    foreach ($getStoreCuisine as $key => $val) {

                        $cuisines   = preg_replace('/\s+/', '', $val['Cuisine']['cuisine_name']);
                        $cuisines   =  str_replace("'", '',$cuisines); ?>
                        <div class="col-xs-12 cuisinelabel">
                            <div class="checkbox checkbox-inline">
                                <input type="hidden" name="address"
                                       id="searchAddress" value="<?php echo $serachAddress; ?>">
                                <input type="checkbox" class="cuisineId" name="cuisine"
                                       id="cuisine_<?php echo $key; ?>" value="<?php echo $cuisines; ?>" >
                                <label for="cuisine_<?php echo $key; ?>"><?php echo $val['Cuisine']['cuisine_name']; ?> <span class="pull-right margin-left-15">(<?php echo $val[0]['cuisineCount']; ?>)</span></label>
                            </div>
                        </div> <?php
                    } ?>
                    </div>

                    <div class="cuisinefil">
                        <h4>Type de restaurants</h4> 
                        <div class="col-xs-12 cuisinelabel">
                            <div class="checkbox checkbox-inline"><?php
                                $types = array(__('Family') => 'family', __('Friends') => 'friends',__('Couple') => 'couple',__('Business') => 'business');
                                foreach ($types as $key => $val) {  ?>
                                <div class="col-sm-12 no-padding">
                                    <input type="checkbox" class="resTypes" name="resTypes" id="type_<?php echo $val; ?>" value="<?php echo $val; ?>" >
                                    <label for="type_<?php echo $val; ?>"><?php echo $key; ?> </label>
                                </div> <?php
                                } ?>                                
                            </div>
                        </div>                     
                    </div>

                    <div class="cuisinefil">
                        <h4>Certificateur(s)</h4> <?php
                    foreach ($meat_issuer_name as $key => $val) {
?>
                        <div class="col-xs-12 cuisinelabel">
                            <div class="checkbox checkbox-inline">
                                <input type="checkbox" class="cuisineId" name="meat_issuer"
                                       id="meat_issuer_<?php echo $val['mi']['id']; ?>" value="<?php echo $val['s']['meat_issuer_name']; ?>" >
                                <label for="meat_issuer_<?php echo $val['mi']['id']; ?>"><?php echo $val['s']['meat_issuer_name']; ?> <span class="pull-right margin-left-15">(<?php echo $val[0]['total'] ?>)</span></label>
                            </div>
                        </div> <?php
                    } ?>
                    </div>
                </div>
            </ul>
        </div>
        <div class="row">

        </div>
    </div>

    <div class="container cards">
        <div class="row">
            <div id="nostore" align="center" class="alert alert-danger col-xs-12" style="display: none;"><?php echo __('No restaurant found');?></div>

            <?php
                if (!empty($storeList)) {
        foreach ($storeList as $key => $value) { 
            $cuisine = $actCuisine = '';
            foreach ($value['StoreCuisine'] as $k => $v) {
                $cuisine    .= $cuisineName[$v['cuisine_id']].', ';
                $cuisineNames = preg_replace('/\s+/', '', $cuisineName[$v['cuisine_id']]);
                $actCuisine .=  str_replace("'", '',$cuisineNames).' ';

            } 

            $types  = ($value['Store']['family'] == '1') ? 'family ' : '';
            $types  .= ($value['Store']['friends'] == '1') ? 'friends ' : '';
            $types  .= ($value['Store']['couple'] == '1') ? 'couple ' : '';
            $types  .= ($value['Store']['business'] == '1') ? 'business ' : '';

            $coll  = ($value['Store']['collection'] == 'Yes') ? ' pickup ' : '';
            $coll .= ($value['Store']['delivery'] == 'Yes') ? ' delivery ' : '';
            $coll .= ($value['Store']['bookatable'] == 'Yes') ? ' bookatable ' : '';
?>
            <div class="col-sm-6 col-md-4 flower" data-category="<?php echo $actCuisine.' '.$types.' '.$coll . ' ' . $value['Store']['meat_issuer_name'] ; ?>">
                <div class="thumbnail search_thumbnail">
                    <a href="<?php echo $siteUrl.'/shop/'.$value['Store']['seo_url'].'/'.base64_encode($value['Store']['id']); ?>"><img style="width:100%" src="<?php echo $siteUrl.'/storelogos/'.$value['Store']['store_logo']; ?>" alt="<?php echo $value['Store']['store_name']; ?>"  onerror="this.onerror=null;this.src='https://halal-resto.fr/frontend/images/no_store.jpg'"></a>
                    <div class="caption">
                        <a href="<?php echo $siteUrl.'/shop/'.$value['Store']['seo_url'].'/'.base64_encode($value['Store']['id']); ?>"><h4><?php echo $value['Store']['store_name']; ?></h4></a>
                        <p><?php
                                        if ($siteSetting['Sitesetting']['address_mode'] != 'Google') {
                                            echo $value['Store']['street_address'] . ', ' .
                                                    $storeArea[$value['Store']['store_zip']] . ', ' .
                                                    $storeCity[$value['Store']['store_city']] . ', ' .
                                                    $storeState[$value['Store']['store_state']];
                                        } else {
                                            echo $value['Store']['address'];
                                        }
                                        ?></p>
                        <p> <?php echo trim($cuisine, ', '); ?></p>
                        <span class="rating_star">
                            <span class="rating_star_gold" style="width:<?php echo $value['Store']['rating']; ?>%"></span>
                        </span>
                        <a href="<?php echo $siteUrl.'/shop/'.$value['Store']['seo_url'].'/'.base64_encode($value['Store']['id']); ?>" style="display:block;" class="<?php echo ($value['Store']['status'] == 'Order') ? 'btn btn-primary' : 'btn btn-info btn_red_color'; ?>">
                            <span> <?php
                                    echo ($value['Store']['status'] == 'Order')
                                        ? 'Commander Maintenant' : 'PRE COMMANDE'; ?>
                            </span>
                        </a>

                    </div>
                </div>
            </div>
    <?php
                }
                
            }     
            ?>

        </div>
    </div>
<?php } else {?>
    <div class="row">
    </div>
    <div class="row">
        <div class="col-sm-12">
            <h4 class="alert alert-danger"><center>Aucun restaurant trouvé</center></h4>
        </div>
    </div>
<?php }?>
</div>