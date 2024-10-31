<div class="ncm-row ncm_product_list" >
    <div class="ncm-col-md-12">
        
        <?php if( isset( $ncm_search ) && !empty( $ncm_search ) ) { ?> 
            <div class="ncm-col-md-3">
                <div class="form-group">
                    <label class="form-control-label" for="date"><?php _e("Search", NCM_txt_domain);?></label>
                    <?php echo $ncm_search; ?>
                </div>
            </div>
        <?php } ?>

        <?php if( isset( $ncm_start_time ) && !empty( $ncm_start_time ) ) { ?> 
            <div class="ncm-col-md-3">
                <div class="form-group">
                    <label class="form-control-label" for="attraction"><?php _e("Start Time", NCM_txt_domain); ?></label>
                    <?php echo $ncm_start_time; ?>
                </div>
            </div>
        <?php } ?>

        <?php if( isset( $ncm_end_time ) && !empty( $ncm_end_time ) ) { ?> 
            <div class="ncm-col-md-3">
                <div class="form-group">
                    <label class="form-control-label" for="attraction"><?php _e("End Time", NCM_txt_domain); ?></label>
                    <?php echo $ncm_end_time; ?>
                </div>
            </div>
        <?php } ?>

        <div class="ncm-col-md-3">
            <button class="btn btn-success btn-block ncm_search_product" style="font-size: 14px; padding: 15px; margin-top: 25px;">
                <i class="fa fa-search"></i> 
                <?php _e("Search", NCM_txt_domain); ?>
            </button>
        </div>
    </div>
</div>