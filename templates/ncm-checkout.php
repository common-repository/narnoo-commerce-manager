
<?php global $ncm_shortcode,$ncm_payment_gateways,$ncm_post_id, $ncm_controls, $ncm_product;
?>







<div class="inner-box wrapper-form-custom">



    <h3><?php _e("Customer Information", NCM_txt_domain); ?></h3><hr/>



    <div class="ncm-row">



        <?php 
        $dis_comm_html = "";
        $count_order = 0;
        foreach ($ncm_user_fields as $ncm_field) 
        {
            $count_order++;

            if($ncm_field['label']=="Comment")
            {
                $dis_comm_html.= '<div class="ncm-col-md-12">';
                $dis_comm_html.= '<div class="form-group">';
                    $dis_comm_html.= '<div class="ncm-col-sm-4 ncm-col-md-4 d-inline-block">';
                        $dis_comm_html.= '<label class="control-label" for="ncm-first_name" style="display:none !important;">'.$ncm_field['label'].'</label>';
                    $dis_comm_html.= '</div>';
                    $dis_comm_html.= '<div class="ncm-col-sm-8 ncm-col-md-8 d-inline-block">';
                        $dis_comm_html.= '<div class="field-wrapper-50">'.$ncm_field['control'].'<p class="text-danger"></p></div>';
                    $dis_comm_html.= '</div>';
                $dis_comm_html.= '</div>';
                $dis_comm_html.= '</div>';

            }
            else
            {

            if($count_order==9)
            {
               echo $dis_comm_html; 
            }
         ?>

            <div class="ncm-col-md-12">



                <div class="form-group">



                    <div class="ncm-col-sm-4 ncm-col-md-4 d-inline-block">



                        <label class="control-label" for="ncm-first_name"><?php echo $ncm_field['label']; ?></label>



                    </div>



                    <div class="ncm-col-sm-8 ncm-col-md-8 d-inline-block">



                        <div class="field-wrapper-50">



                            <?php echo $ncm_field['control']; ?>



                            <p class="text-danger"></p>



                        </div>



                    </div>



                </div>



            </div>
            <?php
            }
        }

        ?>



    </div>



</div>







<?php do_action('ncm_checkout_product_passenger'); ?>







<div class="inner-box">



    <div class="ncm-col-md-12">



        <div class="panel-group" id="accordion">



            <?php do_action('ncm_checkout_payment'); ?>



        </div> 



    </div>



</div> 







<div class="ncm-col-xs-12 ncm-col-md-12">



    <?php do_action('ncm_submit_button'); ?>



</div>