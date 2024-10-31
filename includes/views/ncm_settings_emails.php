<?php
global $wpdb, $ncm, $ncm_settings, $ncm_email, $ncm_controls, $ncm_tmp_section;
$ncm_tmp_section = (isset($_REQUEST['section']) && $_REQUEST['section']!='') ? $_REQUEST['section'] : '';
$ncm_email_fields = $ncm_email->ncm_default_email_template();

?>

<div class="cmrc-table">
<?php if( !empty($ncm_tmp_section) && isset($ncm_email_fields[$ncm_tmp_section]) && !empty($ncm_email_fields[$ncm_tmp_section]) ) { 
    
    /********************* Email notifications Section Start ********************/
    $section_option = $ncm_settings->ncm_get_settings_func( $current_tab, $ncm_tmp_section );
    $ncm_fields = $ncm_email_fields[$ncm_tmp_section];
    $back_link = ' <a href="'.admin_url( "admin.php?page=".$ncm->ncm_setting."&tab=".$current_tab."&section=".$current_tab).'"><i class=" ncm_fa ncm_fa-back"></i></a>';

    echo isset($ncm_fields['name']) ? '<h2>'.$ncm_fields['name'].$back_link.'</h2>' : $back_link;
    echo isset($ncm_fields['desc']) ? '<p>'.$ncm_fields['desc'].'</p>' : ''; 
    if(isset($ncm_fields['fields']) && is_array($ncm_fields['fields']) && !empty($ncm_fields['fields'])){echo '<table class="form-table">';
        echo '<tbody>';

        foreach ($ncm_fields['fields'] as $e_key => $e_value) {
            $e_value['value'] = $section_option[$e_key]; 
            echo '<tr>';
                echo '<th>';
                    echo '<label for="'.$e_key.'">';
                    echo isset($e_value['label']) ? $e_value['label'] : $e_key;
                    echo '</label>';
                echo '</th>';
                echo '<td>';
                    echo '<fieldset>';
                        echo $ncm_controls->ncm_control($e_value);
                    echo '</fieldset>';
                echo '</td>';
            echo '</tr>';
        }
        echo $ncm_controls->ncm_control( array( "type"=>"hidden", "name"=>"ncm_section", "value"=>$ncm_tmp_section ) );
        echo '</tbody>';
        echo '</table>';
    }
    /********************* Email notifications Section Start ********************/ 


 } else {


    /********************* Email notifications Section Start ********************/
    $general_option = $ncm_settings->ncm_get_settings_func( $current_tab );
    extract($general_option);
    ?>
    <h2><?php _e('Email notifications', NCM_txt_domain); ?></h2>
    <p><?php _e('Email notifications sent from are listed below. Click an email to configure it.',NCM_txt_domain);?></p>
    <table class="ncm_emails widefat" cellspacing="0">
        <thead>
            <tr>
                <th class="ncm-email-settings-table-status"></th>
                <th><?php _e('Email', NCM_txt_domain); ?></th>
                <th><?php _e('Content type', NCM_txt_domain); ?></th>
                <th><?php _e('Recipient(s)', NCM_txt_domain); ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($ncm_email_fields as $key => $e_value) {
                $email_default_vals = $ncm_controls->ncm_get_fields_default_val($e_value['fields']);
                $email_vals =  get_option($key);
                $vals = shortcode_atts( $email_default_vals, $email_vals );
                ?>
                <tr>
                    <td class="ncm-email-settings-table-status"><i class="ncm_fa ncm_fa-2x ncm_fa-check-circle"></i></td>
                    <td><a href="<?php echo admin_url( 'admin.php?page='.$ncm->ncm_setting.'&tab='.$current_tab.'&section='.$key ); ?>"><?php echo $e_value['name']; ?></a></td>
                    <td><?php echo $vals['ncm_email_type']; ?></td>
                    <td><?php echo isset($vals['ncm_recipient']) ? $vals['ncm_recipient'] : __('Customer', NCM_txt_domain); ?></td>
                    <td><a class="button alignright ncm_fa ncm_fa-2x ncm_fa-cog" href="<?php echo admin_url( 'admin.php?page='.$ncm->ncm_setting.'&tab='.$current_tab.'&section='.$key ); ?>"></a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php /********************* Email notifications Section End ********************/ ?>


    <?php /********************* Email Sender Options Section Start ********************/ ?>
    <h2><?php _e('Email sender options', NCM_txt_domain); ?></h2>
    <table class="form-table">
        <tbody>
            <tr>
                <th><label for="ncm_email_from_name"><?php _e('"From" name', NCM_txt_domain); ?></label></th>
                <td>
                    <input name="ncm_email_from_name" id="ncm_email_from_name" type="text" value="<?php echo $ncm_email_from_name; ?>"/>
                </td>
            </tr>
            <tr>
                <th><label for="ncm_email_from_address"><?php _e('"From" address', NCM_txt_domain); ?></label></th>
                <td>
                    <input name="ncm_email_from_address" id="ncm_email_from_address" type="email" value="<?php echo $ncm_email_from_address; ?>" multiple="multiple">
                </td>
            </tr>
        </tbody>
    </table>
    <?php /********************* Email Sender Options Section End ********************/ ?>


    <?php /********************* Email Template Section Start ********************/ ?>
    <h2><?php _e('Email template', NCM_txt_domain); ?></h2>
    <p>
        <?php _e('You can change the', NCM_txt_domain); ?> <?php echo NCM_PLUGIN_NM; ?> <?php _e('emails using below section.', NCM_txt_domain); ?> 
        <a href="<?php echo $ncm->ncm_site_url().'?ncm_mail_preview=ncm_yes'; ?>" target="_blank"><?php _e('Click here to preview', NCM_txt_domain); ?></a>.
    </p>
    <table class="form-table">
        <tbody>
            <?php /* <tr>
                <th><label for="ncm_email_header_image"><?php _e('Header image', NCM_txt_domain); ?></label></th>
                <td>
                    <input name="ncm_email_header_image" id="ncm_email_header_image" type="text" value="<?php echo $ncm_email_header_image; ?>">
                </td>
            </tr> */ ?>
            <tr>
                <th><label for="ncm_email_footer_text"><?php _e('Footer text', NCM_txt_domain); ?></label></th>
                <td>
                    <textarea name="ncm_email_footer_text" id="ncm_email_footer_text" placeholder="N/A"><?php echo $ncm_email_footer_text; ?></textarea>
                </td>
            </tr>
            <tr>
                <th><label for="ncm_email_base_color">Base color</label></th>
                <td>
                    <input name="ncm_email_base_color" id="ncm_email_base_color" type="text" class="ncm_color extra_small" value="<?php echo $ncm_email_base_color; ?>" />
                </td>
            </tr>
            <tr>
                <th><label for="ncm_email_background_color">Background color</label></th>
                <td>
                    <input name="ncm_email_background_color" id="ncm_email_background_color" type="text" class="ncm_color extra_small" value="<?php echo $ncm_email_background_color; ?>" />
                </td>
            </tr>
            <tr>
                <th><label for="ncm_email_body_background_color">Body background color</label></th>
                <td>
                    <input name="ncm_email_body_background_color" id="ncm_email_body_background_color" type="text" class="ncm_color extra_small" value="<?php echo $ncm_email_body_background_color; ?>" />
                </td>
            </tr>
            <tr>
                <th><label for="ncm_email_text_color">Body text color</label></th>
                <td>
                    <input name="ncm_email_text_color" id="ncm_email_text_color" type="text" class="ncm_color extra_small" value="<?php echo $ncm_email_text_color; ?>" />
                </td>
            </tr>
        </tbody>
    </table>
    <?php /********************* Email Template Section End ********************/ ?>


<?php } ?>


</div>