<?php
if(!empty($dealProducts)) { ?>
    <div class="products-category mainCatProduct" id="Deal">
        <header class="products-header">
            <h4 class="category-name">
                <span> <?php echo __('Deals', true); ?></span>
            </h4>
        </header>
        <h5></h5> <?php
        $main = $count = 0; ?>
        <div class="products-category mainCatProduct" id="<?php echo $value['MainProduct']['MainCategory']['category_name']; ?>">
            <ul class="products productsCat<?php echo $count; ?>"><?php
                foreach ($dealProducts as $key => $value) {
                    $nextValue = $key + 1;
                    $imageSrc = $siteUrl . '/stores/' . $value['Deal']['store_id'] . '/products/home/' . $value['MainProduct']['product_image'];
                    $imageSrcSub = $siteUrl . '/stores/' . $value['Deal']['store_id'] . '/products/scrollimg/' . $value['SubProduct']['product_image']; ?>

                    <li class="product searchresulttoshow searchresulttoshow<?php echo $count; ?>">
                        <div class="product__inner">
                            <figure class="product__image cell-table" onclick="productDetails(<?php echo $value['MainProduct']['id']; ?>);">
                                <span class="ribn-red onsale"><span><?php echo $value['Deal']['deal_name']; ?></span></span>
                                <img data-cart="<?php echo $key; ?>" width="80px" src="<?php echo $imageSrc; ?>"
                                     onerror="this.onerror=null;this.src='<?php echo $siteUrl . "/images/no-imge.jpg"; ?>'"
                                     alt="<?php echo $value['MainProduct']['product_name']; ?>"
                                     title="<?php echo $value['MainProduct']['product_name']; ?>">
                                <figcaption hidden>
                                    <div class="product-addon">
                                        <a href="javascript:void(0);" class="yith-wcqv-button"><span></span><i
                                                class="fa fa-plus"></i></a>
                                    </div>
                                </figcaption>
                                    <span class="free_section">
                                        <h5>Free</h5>
                                        <img width="50" class="image-lazy image-loaded"
                                             onerror="this.onerror=null;this.src='<?php echo $siteUrl . "/images/no-imge.jpg"; ?>'"
                                             src="<?php echo $imageSrcSub; ?>"
                                             alt="<?php echo $value['SubProduct']['product_name']; ?>"
                                             title="<?php echo $value['SubProduct']['product_name']; ?>">
                                    </span>
                            </figure>
                            <div class="product__detail cell-table col-xs-9">
                                <div class="product__detail_inner">
                                    <div class="top-section cell-table col-sm-12 col-md-9">
                                        <h2 class="product__detail-title"><a
                                                href="javascript:void(0);"><?php echo $value['MainProduct']['product_name'] . '+' . $value['SubProduct']['product_name']; ?></a>
                                            <div class="pull-right"><?php
                                                if ($value['MainProduct']['spicy_dish'] == 'Yes') { ?>
                                                    <span class="spicy_food"></span> <?php
                                                }
                                                if ($value['MainProduct']['popular_dish'] == 'Yes') { ?>
                                                    <span class="popular_food"></span> <?php
                                                }

                                                if ($value['MainProduct']['product_type'] == 'veg') { ?>
                                                    <span class="veg_food"></span> <?php
                                                }
                                                if ($value['MainProduct']['product_type'] == 'nonveg') { ?>
                                                    <span class="non_veg_food"></span> <?php
                                                } ?>
                                            </div>
                                        </h2>
                                        <div class="product__detail-category">
                                            <a href="javascript:void(0);"
                                               rel="tag"><?php echo $value['MainProduct']['product_description']; ?></a>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="bottom-section cell-table col-sm-12 col-md-3">
                                        <span class="price product__detail-price"> <?php
                                                echo html_entity_decode($this->Number->currency($value['MainProduct']['ProductDetail'][0]['orginal_price'], $siteCurrency)); ?>
                                        </span>
                                        <div
                                            class="product__detail-action<?php echo ($value['MainProduct']['price_option'] == 'single' && $value['MainProduct']['ProductDetail'][0]['quantity'] == 0) ? ' out-stock' : ''; ?>">
                                            <a href="javascript:void(0);" rel="nofollow" class="button add_to_cart_button"> <?php
                                                if ($value['MainProduct']['price_option'] == 'single' &&
                                                                    $value['MainProduct']['product_addons'] == 'No') { ?>
                                                    <div class="add_btn">
                                                        <i onclick="addToCart(<?php echo $value['MainProduct']['ProductDetail'][0]['id'].','.$key; ?>);"
                                                            class="fa fa-plus plushide"></i></div> <?php
                                                } else { ?>
                                                    <div class="add_btn"><i
                                                            onclick="productDetails(<?php echo $value['MainProduct']['id']; ?>);"
                                                            class="fa fa-plus plushide"></i></div> <?php
                                                } ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li><?php
                } ?>
            </ul>
        </div>
    </div> <?php
}

$main = 0;
foreach ($productList as $key => $value) {
    $nextValue = $key+1;
    if ($value['MainCategory']['id'] != $main) {
        $main = $value['MainCategory']['id']; ?>
        <div class="products-category mainCatProduct" id="<?php echo $value['MainCategory']['category_name']; ?>">
            <header class="products-header">
                <h4 class="category-name">
                    <?php echo $value['MainCategory']['category_name']; ?>
                </h4>
            </header>
             <ul class="products productsCat<?php echo $count; ?>"> <?php
    }
                $imageName = (isset($value['Product']['product_image'])) ? $value['Product']['product_image'] : '';
                $imageSrc = $siteUrl.'/stores/'.$value['Product']['store_id'].'/products/home/'.$imageName;  ?>

                <li class="product searchresulttoshow searchresulttoshow<?php echo $count; ?>">
                    <div class="product__inner">
                        <figure  class="product__image cell-table" onclick="productDetails(<?php echo $value['Product']['id']; ?>);">
                            <!-- <span class="onsale">Sale!</span> -->
                            <img data-cart="<?php echo $value['ProductDetail'][0]['id']; ?>" width="80px" src="<?php echo $imageSrc; ?>" onerror="this.onerror=null;this.src='<?php echo $siteUrl."/images/no-imge.jpg"; ?>'" alt="<?php echo $value['Product']['product_name']; ?>" title="<?php echo $value['Product']['product_name']; ?>">
                            <figcaption hidden>
                                <div class="product-addon">
                                    <a href="javascript:void(0);" class="yith-wcqv-button"><span></span><i class="fa fa-plus"></i></a>
                                </div>
                            </figcaption>
                        </figure>
                        <div class="product__detail cell-table col-xs-9">
                            <div class="product__detail_inner">
                                <div class="top-section cell-table col-sm-12 col-md-9">
                                    <h2 class="product__detail-title"><a href="javascript:void(0);"><?php 
                                        echo $value['Product']['product_name']; ?></a>
                                        <div class="pull-right"><?php
                                            if ($value['Product']['spicy_dish'] == 'Yes') { ?>
                                                <span class="spicy_food"></span> <?php
                                            }
                                            if ($value['Product']['popular_dish'] == 'Yes') { ?>
                                                <span class="popular_food"></span> <?php
                                            }
                                            if ($value['Product']['product_type'] == 'veg') { ?>
                                                <span class="veg_food"></span> <?php
                                            }
                                            if ($value['Product']['product_type'] == 'nonveg') { ?>
                                                <span class="non_veg_food"></span> <?php
                                            } ?>
                                        </div>
                                        </h2>
                                    <div class="product__detail-category">
                                        <a href="javascript:void(0);" rel="tag"><?php 
                                            echo $value['Product']['product_description']; ?></a>
                                    </div> 
                                </div>
                                <div class="bottom-section cell-table col-sm-12 col-md-3">
                                    <span class="price product__detail-price"> <?php
                                        echo html_entity_decode($this->Number->currency($value['ProductDetail'][0]['orginal_price'], $siteCurrency)); ?>

                                    </span>
                                    <div class="product__detail-action">
                                        <a href="javascript:void(0);" rel="nofollow" class="button add_to_cart_button "> <?php 
                                            if ($value['Product']['price_option'] == 'single' && 
                                                            $value['Product']['product_addons'] == 'No') { ?>
                                                <div class="add_btn">
                                                    <i onclick="addToCart(<?php echo $value['ProductDetail'][0]['id'].','.$value['ProductDetail'][0]['id']; ?>);" class="fa fa-plus plushide"></i>
                                                </div> <?php
                                            } else { ?>
                                                <div class="add_btn"><i onclick="productDetails(<?php echo $value['Product']['id']; ?>);" class="fa fa-plus plushide"></i></div> <?php
                                            } ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li> <?php
    if (!isset($productList[$nextValue]['MainCategory']['id']) || 
                    $productList[$nextValue]['MainCategory']['id'] != $main) { ?>
        </div>
        <div class="clr"></div> <?php
    }
} ?>