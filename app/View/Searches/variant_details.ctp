 <div class="detailPopCont_top">
    <div class="detailPopCont_top">
        <div class="price_height">
            <h5 class="addcart_popup_head"><?php echo __('Menu Prix'); ?> :</h5>
            <h2> <?php
                echo html_entity_decode($this->Number->currency($productVariantDetails['ProductDetail']['orginal_price'],
                 $siteCurrency)); ?>
            </h2>
        </div>
        <div class="row">
            <div class="col-md-8">
                <input id="quantity" class="form-control text-center" type="text" value="">
            </div>
        </div><?php
        if (!empty($productVariantDetails['Product']['product_description'])) {?>
            <h5 class="addcart_popup_head"><?php echo  __('Product Description'); ?> :</h5>
            <p><?php echo $productVariantDetails['Product']['product_description'];?></p> <?php 
        } ?>
    </div>
</div>