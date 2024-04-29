<?php

function icon_content_row( $id = null, $icon = null, $content = null, $green_btn = null, $blue_btn = null, $desc = null ) {
    ?>
    <div class="icon-content-row" id="<?php echo $id ?>">
        <?php if ( ! empty( $icon ) ) : ?>
            <div class="icon-content-icon-wrap">
                <?php echo obj_svg( $icon, false, $desc ); ?>
            </div>
        <?php endif; ?>
        <?php if ( ! empty( $content ) ) : ?>
            <div class="icon-content-content-wrap">
                <?php echo $content ?>
                <?php if( ! empty( $green_btn ) || ! empty( $blue_btn ) ) : ?>
                    <div class="icon-content-footer">
                        <?php if( ! empty( $green_btn ) ) : ?>
                            <?php echo mk_link_html( $green_btn, "button large-button" ); ?>
                        <?php endif; ?>
                        <?php if( ! empty( $blue_btn ) ) : ?>
                            <?php echo mk_link_html( $blue_btn, "button light-blue large-button" ); ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php
}
