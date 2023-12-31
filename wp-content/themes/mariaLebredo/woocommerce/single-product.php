<?php get_header(); ?>
<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<?php while(have_posts()){the_post(); ?>
  <?php global $woocommerce, $product, $post; ?>
  <?php if( !$product->is_on_backorder() AND !$product->is_in_stock() ){ $is_out_of_stock = true; } ?>



  <article class="singleProductMain">
      <div class="gallery" id="gallery">

          <?php $attachment_ids = $product->get_gallery_attachment_ids(); ?>


            <img class="element rowcol1 lazy Obse" data-observe="#obseTest" data-unobserve="false" onclick="altClassFromSelector('alt','#gallery')" data-url="<?php echo get_the_post_thumbnail_url(get_the_ID()); ?>" alt="Gallery left Handler">
            <?php $count=0; foreach( $attachment_ids as $attachment_id ) { ?>
              <img class="element rowcol1 lazy" onclick="altClassFromSelector('alt','#gallery')" data-url="<?php echo $image_link = wp_get_attachment_url( $attachment_id ); ?>" alt="Gallery right Handler">
            <?php $count++; } ?>








        <div class="singleProductsgalleryBtnsContainer">
          <button class="singleProductsGalleryBtns" id="nextButton" aria-label="Slider left handler">
            <svg class="singleProductArrowSVG" width="14" height="25" viewBox="0 0 14 25" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" clip-rule="evenodd" d="M12.2956 0L0 12.2956L0.0593109 12.3549L0 12.4142L12.2956 24.7098L13.7098 23.2956L2.76912 12.3549L13.7098 1.41421L12.2956 0Z" fill="black"/>
            </svg>
          </button>
          <button class="singleProductsGalleryBtns" id="prevButton" aria-label="Slider right handler">
            <svg class="singleProductArrowSVG" width="14" height="25" viewBox="0 0 14 25" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" clip-rule="evenodd" d="M1.41421 24.7098L13.7098 12.4142L13.6505 12.3549L13.7098 12.2956L1.41421 0L0 1.41421L10.9407 12.3549L0 23.2956L1.41421 24.7098Z" fill="black"/>
            </svg>
          </button>
        </div>
      </div>

      <div class="SingleProductInteraction">
        <h1 class="singleSideTitle"><?php the_title(); ?></h1>

        <?php if($product->is_on_backorder()){ ?>
          <p>Hi! We ran out of stock of this product, but you can still preorder it and receive it in 10-15 days :)</p>
        <?php } ?>
        <?php // var_dump($product->get_attributes( 'Talla' )); ?>
        <!-- TODO: mostrar precio dinamico con la seleccion de la variacion -->
        <!-- <p class="singleSidePrice" id="singleSidePrice"><?php // if($product->is_type( 'simple' )){echo $product->get_price_html();} ?></p> -->

        <!-- <p class="singleSidePrice" id="singleSidePrice"><?php // if($product->is_type( 'simple' )){echo $product->get_price_html();} ?></p> -->

        <p class="singleSidePrice" id="singleSidePrice"><?php echo $product->get_price_html(); ?></p>
        <?php
    			// $product = wc_get_product();
    			if ( $product->is_type( 'variable' ) AND !$is_out_of_stock ) {
            $variations = $product->get_available_variations();

            // THIS BLOCK DEFINES THE ARRAY: "$myAttributes"
            // WE WILL USE THIS ARRAY LATER
            $myAttributes = array();
            foreach ($variations as $key => $value) {
              foreach ($value['attributes'] as $j => $var) {
                if ( ! array_key_exists ( $j, $myAttributes ) ) {
                  $myAttributes[$j] = array($var => array($value['variation_id']));
                } else {
                  $myAttributes[$j][$var][] = $value['variation_id'];
                }
              }
            }
            // var_dump($myAttributes);

            $first = true;
            foreach ($myAttributes as $key => $value) {
              $slug = preg_replace("/attribute_/i", "", $key);
              $name = ucfirst($slug);
              ?>

              <div class="selectBox" tabindex="1" id="selectBox<?php echo $name; ?>">
                <div class="selectBoxButton">
                  <p class="selectBoxPlaceholder"><?php echo $name; ?></p>
                  <p class="selectBoxCurrent" id="selectBoxCurrent<?php echo $name; ?>"></p>
                </div>
                <div class="selectBoxList">
                  <label for="nul<?php echo $name; ?>" class="selectBoxOption">
                    <input
                      class="selectBoxInput"
                      id="nul<?php echo $name; ?>"
                      type="radio"
                      data-ids=""
                      name="filter_<?php echo $slug; ?>"
                      onclick="selectBoxControler('','#selectBox<?php echo $name; ?>','#selectBoxCurrent<?php echo $name; ?>')"
                      value="0"
                      checked
                    >
                    <!-- <span class="checkmark"></span> -->
                    <p class="colrOptP"></p>
                  </label>
                  <?php foreach ($value as $i => $var) { ?>
                    <!-- <p class="colrOptP"><?php var_dump($var['attributes']); ?></p> -->
                  <?php // foreach ($value['options'] as $key => $var) { ?>
                    <label for="<?php echo $i; ?>" class="selectBoxOption<?php if(!$first){echo ' hidden';} ?>">
                      <input
                        class="selectBoxInput"
                        id="<?php echo $i; ?>"
                        data-ids="<?php
                          foreach ($var as $j => $x) {
                            echo $x;
                            if($j<count($var)-1){ echo ', '; }
                          }
                        ?>"
                        type="radio"
                        name="filter_<?php echo $slug; ?>"
                        onclick="selectBoxControler('<?php echo $i; ?>', '#selectBox<?php echo $name; ?>', '#selectBoxCurrent<?php echo $name; ?>')"
                        value="<?php echo $i; ?>"
                        <?php // if($_GET['filter_'.$term->slug]==$var->slug){echo "selected";} ?>
                      >
                      <!-- <span class="checkmark"></span> -->
                      <!-- <p class="colrOptP"><?php var_dump($var[0]); ?></p> -->
                      <p class="colrOptP"><?php echo $i; ?></p>
                    </label>
                  <?php } ?>
                </div>
              </div>
            <?php $first = false; ?>
            <?php } ?>





          <?php } ?>
          <!-- esto tiene un bug, testear extensivamente y asegurarse de que anda bien antes de poner en produccion -->
          <!-- placeholder="Cantidad" -->
          <!-- <div class="addToCartQntContainer">
            <input class="addToCartQnt" id="addToCartQantity" type="number" value="1" min="1">
            <button class="addToCartQntBtn" onclick="changeQuantity(-1)">-</button>
            <button class="addToCartQntBtn" onclick="changeQuantity(+1)">+</button>
          </div> -->

          <button
            class="btn"
            id="myAddToCart"
            data-product-id="<?php echo get_the_id(); ?>"
            data-product-type="<?php echo $product->get_type(); ?>"
            data-quantity="1"
            data-variation-description=""
            data-buy="later"
            <?php if($product->is_on_backorder()){ ?>
              data-preorder="true"
            <?php } else { ?>
              data-preorder="false"
            <?php } ?>
            <?php if( $is_out_of_stock ){ echo 'disabled'; } ?>
          >
            <?php
            if( $is_out_of_stock ){
              echo 'OUT OF STOCK';
            } else {
              echo 'ADD TO CART';
            }
            ?>
          </button>
          
          <button
            class="btn"
            id="myBuyNow"
            data-product-id="<?php echo get_the_id(); ?>"
            data-product-type="<?php echo $product->get_type(); ?>"
            data-quantity="1"
            data-variation-description=""
            data-buy="now"
            <?php if($product->is_on_backorder()){ ?>
              data-preorder="true"
            <?php } else { ?>
              data-preorder="false"
            <?php } ?>
            <?php if( $is_out_of_stock ){ echo 'disabled'; } ?>
          >
            <?php
            if( $is_out_of_stock ){
              echo 'OUT OF STOCK';
            } else {
              if($product->is_on_backorder()){ 
                echo 'ORDER NOW';
              } else {
                echo 'BUY NOW';
              }
            }
            ?>
          </button>


    </div>

      <div class="singleProductDescription">
        <div class="singleProductTxt"><?php echo get_the_content(); ?></div>
      </div>
  </article>

  <div class="relatedProducts">
    <?php

    $meta_query  = WC()->query->get_meta_query();
    $tax_query   = WC()->query->get_tax_query();
    $tax_query[] = array(
                              'taxonomy' => 'product_visibility',
                              'field'    => 'name',
                              'terms'    => 'featured',
                              'operator' => 'IN',
                          );

    $args = array(
      'post_type'=>'product',
      'posts_per_page'=>4,
      'meta_query'          => $meta_query,
      'tax_query'           => $tax_query,
    );

    $blogPosts=new WP_Query($args); ?>

    <h3 class="sliderTitle title">YOU MAY ALSO LIKE.</h3>
    <?php while($blogPosts->have_posts()){$blogPosts->the_post(); ?>
      <?php global $product; ?>

      <figure class="card" id="card<?php echo get_the_id();?>">
        <a class="cardImg" href="<?php echo get_permalink(); ?>">
          <img class="cardImg lazy" data-url="<?php echo get_the_post_thumbnail_url(get_the_ID()); ?>" alt="Product Image">
        </a>
        <figcaption class="cardCaption">
          <p class="cardTitle"><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></p>
          <p class="productCardPrice"><a href="<?php echo get_permalink(); ?>"> <?php echo $product->get_price_html(); ?> </a></p>
        </figcaption>
      </figure>
    <?php } ?>
  </div>


<?php } wp_reset_query(); ?>
<?php get_footer(); ?>
