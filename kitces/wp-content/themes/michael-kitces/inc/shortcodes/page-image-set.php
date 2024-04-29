<?php

function page_image_set($atts)
{

    $images = get_field('images_for_shortcode_on_this_page', get_the_ID());
    
    
    
    if (!empty($images)) {
        ob_start(); ?> 
        <div class="page-img-set-wrap">
            <?php
            $count = 0;
            foreach ($images as $i) {
                $id = $i['image'];
                $image = wp_get_attachment_image_url($id, 'medium_large');
                $pop = $i['pop_up_on_click'];
                $pop_text = $i['pop_up_on_click_text'];
                if (!empty($image)) { 
                    if ( $pop && ! empty( $pop_text ) ) { ?>
                        <a href="#pop-click-<?php echo $count ?>" id="fancy-inline">
                            <img src="<?php echo $image ?>">
                        </a>
                        <div id="pop-click-<?php echo $count ?>" class="page-img-set-pop-cont" style="display:none;">
                            <?php echo $pop_text ?>
                        </div>
                        <?php							
                    } else { ?>
                        <img src="<?php echo $image ?>">
                    <?php
                    }
                }
                $count += 1;
            }
            ?>
        </div><?php
        return ob_get_clean();
    } else {
        return "";
    }

}

add_shortcode('page-img-set-wide', 'page_image_set');

function page_image_set_column($atts)
{
    $images = get_field('images_for_column_of_images_shortcode', get_the_ID());
    
    if (!empty($images)) {
        ob_start(); ?> 
        <div class="page-img-set-column-wrap">
            <?php
            $count = 0;
            foreach ($images as $i) {
                $id = $i['image']['ID'];
                $image = wp_get_attachment_image_url($id, 'medium_large');
                $pop = $i['pop_up_on_click'];
                $pop_text = $i['pop_up_on_click_text'];
                if (!empty($image)) { ?>
                    <div>
                    <?php
                    if ( $pop && ! empty( $pop_text ) ) { ?>
                        <a href="#pop-click-<?php echo $count ?>" id="fancy-inline">
                            <img src="<?php echo $image ?>">
                        </a>
                        <div id="pop-click-<?php echo $count ?>" class="page-img-set-pop-cont" style="display:none;">
                            <?php echo $pop_text ?>
                        </div>
                        <?php							
                    } else { ?>
                        <img src="<?php echo $image ?>">
                    <?php
                    } ?>
                    </div>
                    <?php
                }
                $count += 1;
            }
            ?>
        </div><?php
        return ob_get_clean();
    } else {
        return "";
    }

}

add_shortcode('page-img-set-column', 'page_image_set_column');