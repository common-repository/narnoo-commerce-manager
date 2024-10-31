<?php

/*
* This function controls Textbox, Selectbox, Textarea, Checkbox and Radio button.
*/

if( !class_exists ( 'NCM_Controls' ) ) {

class NCM_Controls {

    function __construct(){

    }

    function ncm_default_attr( ) {
        $ncm_attr = array();
        $ncm_attr["type"] = "text";
        $ncm_attr["name"] = "_ncm_";
        $ncm_attr["id"] = "";
        $ncm_attr["value"] = "";
        $ncm_attr["class"] = "";
        $ncm_attr["placeholder"] = "";
        $ncm_attr["style"] = "";
        $ncm_attr["autocomplete"] = "";
        $ncm_attr["options"] = array( "0" => $ncm_attr["name"] );
        return $ncm_attr;
    }

    function ncm_template_attr( ) {
        $ncm_attr = array();
        $ncm_attr["type"] = "template";
        $ncm_attr["template_slug"] = "";
        $ncm_attr['copy_link'] = "";
        $ncm_attr['delete_link'] = "";
        $ncm_attr["copy_del_temp_theme_btn"] = false;
        $ncm_attr["view_hide_template_btn"] = false;
        $ncm_attr["email_template_design"] = false;
        return $ncm_attr;
    }

    function ncm_control( $attr = array() ) {
        $type = isset($attr['type']) ? $attr['type'] : 'text';
        switch ($type) {
            case 'checkbox':
            case 'radio':
                return $this->ncm_checkbox_radio($attr);
                break;
            case 'select':
                return $this->ncm_combobox($attr);
            case 'textarea':
                return $this->ncm_textarea($attr);
            case 'template':
                return $this->ncm_template($attr);
            default:
                return $this->ncm_input($attr);
                break;
        }
    }

    function ncm_input( $attr = array() ) {
        $defualt_attr = $this->ncm_default_attr( );
        $attr = array_merge( $defualt_attr, $attr );
        $attr_options = isset($attr['options']) ? $attr['options'] : array();
        unset($attr['options']);
        $attr['id'] = empty($attr['id']) ? $attr['name'] : $attr['id'];
        $content = '';

        $content .= '<input ';
        foreach ($attr as $a_key => $a_value) {
            $content .= ' '.$a_key.'="'.$a_value.'"';
        }
        $content .= ' />';
        return $content;
    }

    function ncm_textarea ($attr = array() ) {
        $defualt_attr = $this->ncm_default_attr( );
        $attr = array_merge( $defualt_attr, $attr );
        $attr_options = isset($attr['options']) ? $attr['options'] : array();
        unset($attr['options']);
        $attr_value = isset($attr['value']) ? $attr['value'] : '';
        $attr['id'] = empty($attr['id']) ? $attr['name'] : $attr['id'];
        $content = '';

        $content .= '<textarea ';
        foreach ($attr as $a_key => $a_value) {
            $content .= ' '.$a_key.'="'.$a_value.'"';
        }
        $content .= ' >'.$attr_value.'</textarea>';
        return $content;
    }

    function ncm_checkbox_radio( $attr = array() ) {
        $defualt_attr = $this->ncm_default_attr( );
        $attr = array_merge( $defualt_attr, $attr );
        $attr_options = $attr['options'];
        $attr_values = explode(',', $attr['value']);
        $attr_hide_label = isset( $attr['hide_label'] ) ? $attr['hide_label'] : false;
        unset($attr['options']);
        unset($attr['value']);

        $attr['id'] = empty($attr['id']) ? $attr['name'] : $attr['id'];
        $content = '';

        if( is_array($attr_options) && !empty($attr_options) ) {
            foreach ($attr_options as $o_key => $o_value) {
                $content .= '<label for="'.$attr['id'].'_'.$o_key.'">';
                $content .= '<input value='.$o_key;
                $content .= (in_array($o_key, $attr_values)) ? ' checked="checked" ' : '';
                foreach ($attr as $a_key => $a_value) {
                    switch ($a_key) {
                        case 'id':
                            $content .= ' '.$a_key.'="'.$attr['id'].'_'.$o_key  .'"';
                            break;
                        default:
                            $content .= ' '.$a_key.'="'.$a_value.'"';
                            break;
                    }
                }
                $content .= ' /> ';
                $content .= (!$attr_hide_label) ? $o_value : '';
                $content .= '</label>';
            }
        } 
        return $content;
    }

    function ncm_combobox( $attr = array() ) {
        $defualt_attr = $this->ncm_default_attr( );
        $attr = array_merge( $defualt_attr, $attr );
        $attr_options = isset($attr['options']) ? $attr['options'] : array();
        $attr_sub_options = isset($attr['sub_options']) ? $attr['sub_options'] : array();
        $merge_opt_subopt = isset($attr['merge_opt_subopt']) ? $attr['merge_opt_subopt'] : false;
        $selected_value = isset($attr['value']) ? $attr['value'] : array();
        unset($attr['options']);
        unset($attr['sub_options']);
        unset($attr['merge_opt_subopt']);
        unset($attr['value']);
        unset($attr['placeholder']);
        $attr['id'] = empty($attr['id']) ? $attr['name'] : $attr['id'];
        $content = '';

        $content .= '<select data-search_result = "-1" ';
        foreach ($attr as $a_key => $a_value) {
            $content .= ' '.$a_key.'="'.$a_value.'"';
        }
        $content .= '>';


        if( !empty($attr_sub_options) ) {
            foreach ($attr_options as $o_key => $o_value) { 
                if( in_array( $o_key, array_keys( $attr_sub_options ) ) && is_array($attr_sub_options[$o_key]) ) {
                    $content .= '<optgroup label="'.$o_value.'">';
                    foreach ($attr_sub_options[$o_key] as $s_key => $s_value) {
                        $selected = '';
                        if( !empty($selected_value) && $s_key == $selected_value ) {
                            $selected = ' selected="selected" ';
                        }
                        $value = ($merge_opt_subopt) ? $c_key.':'.$s_key : $s_key;
                        $content .= '<option value="'.$value.'" '.$selected.'>'.$s_value.'</option>';
                    }
                    $content .= '</optgroup>';
                } else {
                    $selected = '';
                    if( !empty($selected_value) && $o_key == $selected_value ) {
                        $selected = ' selected="selected" ';
                    }
                    $content .= '<option value="'.$o_key.'" '.$selected.'>'.$o_value.'</option>';
                }
            }
        } else {

            foreach ($attr_options as $o_key => $o_value) { 
                $selected = '';
                if( !empty($selected_value) && $o_key == $selected_value ) {
                    $selected = ' selected="selected" ';
                }
                if(!empty($o_value[1])){
                    $DataPickPrice = "data-pick-price='".$o_value[1]."'";
                }else{
                    $DataPickPrice = "data-pick-price='0'";
                }

                if(sizeof($o_value) >= 2 ){
                    $content .= '<option value="'.$o_key.'" '.$selected.' '.$DataPickPrice.'>'.$o_value[0].'</option>';
                }else{
                    $content .= '<option value="'.$o_key.'" '.$selected.' >'.$o_value.'</option>';
                }

            }
        }
        $content .= '</select>';
        return $content;
    }

    function ncm_template( $attr = array() ) {
        global $ncm_template;
        $defualt_attr = $this->ncm_template_attr();
        $attr = array_merge( $defualt_attr, $attr );
        $template_slug = $attr['template_slug'];
        if(empty($template_slug)) { return false; }
        $query_param = $_REQUEST;
        $query_param['template_slug'] = $template_slug;

        $template_files = $ncm_template->ncm_template_files();
        if( ! isset( $template_files[$template_slug] ) ) { return false; }
        $template = $template_files[$template_slug];


        $temp_content = '';
        $file_exists_theme = false;
        $file_content_url = $template['template_file'];
        $textarea_attr = ' disabled ';
        if( file_exists( $template['template_theme_file'] ) ) {
            $query_param['remove_template'] = $template_slug;
            $file_exists_theme = true;
            $file_content_url = $template['template_theme_file'];
            $textarea_attr = '';
            $temp_content .= '<div class="ncm_template_content">';
            $temp_content .= __('This template has been overridden by your theme and can be found in: ', NCM_txt_domain);
            $temp_content .= ' <code>'.$template['template_theme_url'].'</code>.';
            $temp_content .= '</div>';
        } else {
            $query_param['move_template'] = $template_slug;
            $file_exists_theme = false;
            $file_content_url = $template['template_file'];
            $textarea_attr = ' disabled ';
            $temp_content .= '<div class="ncm_template_content">';
            $temp_content .= __('To override and edit this email template copy', NCM_txt_domain);
            $temp_content .= ' <code>'.$template["template_url"].'</code> ';
            $temp_content .= __('to your theme folder:', NCM_txt_domain);
            $temp_content .= ' <code>'.$template["template_theme_url"].'</code>.';
            $temp_content .= '</div>';
        }
        
        $content = '';
        $content .= '<div class="ncm_template">';
        $content .= $temp_content;

            $template_link = admin_url( 'admin.php?'.http_build_query($query_param));
            if( $attr["copy_del_temp_theme_btn"] ) {
                if( $file_exists_theme ) {
                    $content .= '<a href="'.$template_link.'" class="button ncm_template_copy_button">';
                    $content .= __('Delete template file', NCM_txt_domain).'</a>';
                } else {
                    $content .= '<a href="'.$template_link.'" class="button ncm_template_copy_button">';
                    $content .= __('Copy file to theme', NCM_txt_domain).'</a>';
                }
            }

            if( $attr["view_hide_template_btn"] ) {
                $content .= '<a href="#" class="button ncm_template_toggle_editor">';
                $content .= __('View template',NCM_txt_domain).'</a>';
                $content .= '<div class="ncm_template_editor">';
                $content .= '<textarea name="'.$template_slug.'" class="ncm_template_textarea" '.$textarea_attr.'>';
                if( file_exists( $file_content_url ) ) {
                    $content .= file_get_contents($file_content_url);
                }
                $content .= '</textarea>';
                $content .= $this->ncm_control( 
                                array( 
                                        "type"=>"hidden", 
                                        "name"=>"ncm_file_slug[]", 
                                        "value"=>$template_slug 
                                    )
                            );
                $content .= '</div>';
            }

        $content .= '</div>';
        return $content;
    }

    function ncm_get_fields_default_val( $field_arr = array() ) {
        $return = array();
        if( !empty($field_arr) && is_array($field_arr) ) {
            foreach ($field_arr as $f_key => $f_value) {
                $return[$f_key] = isset($f_value['value']) ? $f_value['value'] : '';
            }
        }
        return $return;
    }

}

global $ncm_controls;
$ncm_controls = new NCM_Controls();

}


if ( ! function_exists( 'ncm_set_attribute' ) ) :
    function ncm_set_attribute ( $attr = array(), $can_echo = false ) {
        $content = '';
        foreach ( $attr as $a_key => $a_value ) {
            $content .= ' '.$a_key.'="'.$a_value.'" ';
        }
        if( $can_echo ) {
            echo $content;
        } else {
            return $content;
        }
    }
endif;


?>