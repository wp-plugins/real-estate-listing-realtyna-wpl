<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if(in_array($mandatory, array(1, 2)))
{
    $options = json_decode($field->options);
    if(isset($options->advanced_editor) and $options->advanced_editor == '1')
    {
        if($field->multilingual == 1 and wpl_global::check_multilingual_status())
        {
            $default_language = wpl_addon_pro::get_default_language();
            $field_id = 'tinymce_wpl_c_'.$field->id.'_'.strtolower($default_language);
            
            $js_string .=
            '
            if (wplj("#wp-'.$field_id.'-wrap").hasClass("tmce-active"))
                txtarea_val = tinyMCE.get("'.$field_id.'").getContent();
            else
                txtarea_val = wplj("#'.$field_id.'").val();
            
            if(wplj.trim(txtarea_val) == "" && wplj("#wpl_listing_field_container'.$field->id.'").css("display") != "none")
            {
                wpl_alert("'.sprintf(__('Enter a valid %s for %s!', WPL_TEXTDOMAIN), __($label, WPL_TEXTDOMAIN), $default_language).'");
                return false;
            }
            ';
        }
        else
        {
            $field_id = 'tinymce_wpl_c_'.$field->id;
            $js_string .=
            '
            if (wplj("#wp-'.$field_id.'-wrap").hasClass("tmce-active"))
                txtarea_val = tinyMCE.get("'.$field_id.'").getContent();
            else
                txtarea_val = wplj("#'.$field_id.'").val();

            if(wplj.trim(txtarea_val) == "" && wplj("#wpl_listing_field_container'.$field->id.'").css("display") != "none")
            {
                wpl_alert("'.__('Enter a valid', WPL_TEXTDOMAIN).' '.__($label, WPL_TEXTDOMAIN).'!");
                return false;
            }
            ';
        }
    }
    else
    {
        if($field->multilingual == 1 and wpl_global::check_multilingual_status())
        {
            $default_language = wpl_addon_pro::get_default_language();
            $field_id = 'wpl_c_'.$field->id.'_'.strtolower($default_language);
            
            $js_string .=
            '
            if(wplj.trim(wplj("#'.$field_id.'").val()) == "" && wplj("#wpl_listing_field_container'.$field->id.'").css("display") != "none")
            {
                wpl_alert("'.sprintf(__('Enter a valid %s for %s!', WPL_TEXTDOMAIN), __($label, WPL_TEXTDOMAIN), $default_language).'");
                return false;
            }
            ';
        }
        else
        {
            $field_id = 'wpl_c_'.$field->id;
            $js_string .=
            '
            if(wplj.trim(wplj("#'.$field_id.'").val()) == "" && wplj("#wpl_listing_field_container'.$field->id.'").css("display") != "none")
            {
                wpl_alert("'.__('Enter a valid', WPL_TEXTDOMAIN).' '.__($label, WPL_TEXTDOMAIN).'!");
                return false;
            }
            ';
        }
    }
}