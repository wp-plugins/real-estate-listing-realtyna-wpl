<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'checkbox' and !$done_this)
{
?>
<div class="prow wpl_st_type<?php echo $setting_record->type; ?> wpl_st_<?php echo $setting_record->setting_name; ?>" id="wpl_st_<?php echo $setting_record->id; ?>">
    <div class="checkbox-wp" >
        <label for="wpl_st_form_element<?php echo $setting_record->id; ?>"><?php echo $setting_title; ?>&nbsp;<span class="wpl_st_citation">:</span></label>
        <input type="checkbox" name="wpl_st_form<?php echo $setting_record->id; ?>" id="wpl_st_form_element<?php echo $setting_record->id; ?>" autocomplete="off" <?php if($value) echo 'checked="checked"'; ?> onchange="wpl_setting_save('<?php echo $setting_record->id; ?>', '<?php echo $setting_record->setting_name; ?>', this.value, '<?php echo $setting_record->category; ?>');" />
        
		<?php if(isset($params['tooltip'])): ?>
        <span class="wpl_setting_form_tooltip wpl_help" id="wpl_setting_form_tooltip_container<?php echo $setting_record->id; ?>">
            <span class="wpl_help_description" style="display: none;"><?php echo __($params['tooltip'], WPL_TEXTDOMAIN); ?></span>
        </span>
        <?php endif; ?>
        
        <span class="ajax-inline-save" id="wpl_ajax_loader_<?php echo $setting_record->id; ?>"></span>
    </div>
</div>
<?php
    $done_this = true;
}
elseif($type == 'text' and !$done_this)
{
?>
<div class="prow wpl_setting_form_container wpl_st_type<?php echo $setting_record->type; ?> wpl_st_<?php echo $setting_record->setting_name; ?>" id="wpl_st_<?php echo $setting_record->id; ?>">
	<div class="text-wp">
		<label for="wpl_st_form_element<?php echo $setting_record->id; ?>"><?php echo $setting_title; ?>&nbsp;<span class="wpl_st_citation">:</span></label>
        <input class="<?php echo isset($params['html_class']) ? $params['html_class'] : ''; ?>" type="text" name="wpl_st_form<?php echo $setting_record->id; ?>" id="wpl_st_form_element<?php echo $setting_record->id; ?>" value="<?php echo htmlentities($setting_record->setting_value, ENT_COMPAT, "UTF-8"); ?>" placeholder="<?php echo  ((isset($params['placeholder']) and $params['placeholder']) ? __($params['placeholder'], WPL_TEXTDOMAIN) : ''); ?>" onchange="<?php if(isset($options['show_shortcode']) and $options['show_shortcode']): ?>wpl_setting_show_shortcode('<?php echo $setting_record->id; ?>', '<?php echo $options['shortcode_key']; ?>', this.value);<?php endif; ?> wpl_setting_save('<?php echo $setting_record->id; ?>', '<?php echo $setting_record->setting_name; ?>', this.value, '<?php echo $setting_record->category; ?>');" autocomplete="off" <?php echo isset($params['readonly']) ? 'readonly="readonly"' : ''; ?> />

		<?php if(isset($options['show_shortcode'])): ?>
        <div class="shortcode-wp" id="wpl_setting_form_shortcode_container<?php echo $setting_record->id; ?>">
            <span title="<?php echo __('Shortcode', WPL_TEXTDOMAIN); ?>" id="wpl_st_<?php echo $setting_record->id; ?>_shortcode_value"><?php echo $options['shortcode_key'] . '="' . $value . '"'; ?></span>
        </div>
		<?php endif; ?>

		<?php if(isset($params['tooltip'])): ?>
        <span class="wpl_setting_form_tooltip wpl_help" id="wpl_setting_form_tooltip_container<?php echo $setting_record->id; ?>">
            <span class="wpl_help_description" style="display: none;"><?php echo __($params['tooltip'], WPL_TEXTDOMAIN); ?></span>
        </span>
		<?php endif; ?>

		<span class="ajax-inline-save" id="wpl_ajax_loader_<?php echo $setting_record->id; ?>"></span>
	</div>
</div>
<?php
    $done_this = true;
}
elseif($type == 'separator' and !$done_this)
{
?>
<div class="prow wpl_setting_form_container wpl_st_type<?php echo $setting_record->type; ?> wpl_st_<?php echo $setting_record->setting_name; ?>" id="wpl_st_<?php echo $setting_record->id; ?>">
    <h3 class="separator-name"><?php echo $setting_title; ?></h3>
	<hr />
</div>
<?php
    $done_this = true;
}
elseif($type == 'select' and !$done_this)
{
	$show_empty = isset($options['show_empty']) ? $options['show_empty'] : NULL;
	$show_shortcode = isset($options['show_shortcode']) ? $options['show_shortcode'] : NULL;
    $values = isset($options['query']) ? wpl_db::select($options['query'], 'loadAssocList') : $options['values'];
?>
<div class="prow wpl_setting_form_container wpl_st_type<?php echo $setting_record->type; ?> wpl_st_<?php echo $setting_record->setting_name; ?>" id="wpl_st_<?php echo $setting_record->id; ?>">
    <div class="select-wp">
        <label for="wpl_st_form_element<?php echo $setting_record->id; ?>"><?php echo $setting_title; ?>&nbsp;<span class="wpl_st_citation">:</span></label>
        <select name="wpl_st_form<?php echo $setting_record->id; ?>" id="wpl_st_form_element<?php echo $setting_record->id; ?>" onchange="<?php if($show_shortcode): ?>wpl_setting_show_shortcode('<?php echo $setting_record->id; ?>', '<?php echo $options['shortcode_key']; ?>', this.value);<?php endif; ?> wpl_setting_save('<?php echo $setting_record->id; ?>', '<?php echo $setting_record->setting_name; ?>', this.value, '<?php echo $setting_record->category; ?>');" <?php if(isset($params['width'])): ?>data-chosen-opt="width: <?php echo $params['width']; ?>"<?php endif; ?> autocomplete="off">
            <?php if($show_empty): ?><option value="">---</option><?php endif; ?>
            <?php foreach ($values as $value_array): ?>
            <option value="<?php echo $value_array['key']; ?>" <?php if($value_array['key'] == $value) echo 'selected="selected"' ?>><?php echo $value_array['value']; ?></option>
            <?php endforeach; ?>
        </select>

        <?php if($show_shortcode): ?>
        <div class="shortcode-wp" id="wpl_setting_form_shortcode_container<?php echo $setting_record->id; ?>">
            <span title="<?php echo __('Shortcode', WPL_TEXTDOMAIN); ?>" id="wpl_st_<?php echo $setting_record->id; ?>_shortcode_value"><?php echo $options['shortcode_key'] . '="' . $value . '"'; ?></span>
        </div>
        <?php endif; ?>

        <?php if(isset($params['tooltip'])): ?>
        <span class="wpl_setting_form_tooltip wpl_help" id="wpl_setting_form_tooltip_container<?php echo $setting_record->id; ?>">
            <span class="wpl_help_description" style="display: none;"><?php echo __($params['tooltip'], WPL_TEXTDOMAIN); ?></span>
        </span>
        <?php endif; ?>

        <span class="ajax-inline-save" id="wpl_ajax_loader_<?php echo $setting_record->id; ?>"></span>
    </div>
</div>
<?php
	$done_this = true;
}
elseif($type == 'sort_option' and !$done_this)
{
    $kind = trim($options['kind']) != '' ? $options['kind'] : 1;
    _wpl_import('libraries.sort_options');
    $sort_options = wpl_sort_options::get_sort_options($options['kind'], 1); /** getting enaled sort options **/
?>
<div class="prow wpl_setting_form_container wpl_st_type<?php echo $setting_record->type; ?> wpl_st_<?php echo $setting_record->setting_name; ?>" id="wpl_st_<?php echo $setting_record->id; ?>">
	<div class="select-wp">
		<label for="wpl_st_form_element<?php echo $setting_record->id; ?>"><?php echo $setting_title; ?><span class="wpl_st_citation">:</span></label>
		<select name="wpl_st_form<?php echo $setting_record->id; ?>" id="wpl_st_form_element<?php echo $setting_record->id; ?>" onchange="wpl_setting_show_shortcode('<?php echo $setting_record->id; ?>', '<?php echo $options['shortcode_key']; ?>', this.value);
				wpl_setting_save('<?php echo $setting_record->id; ?>', '<?php echo $setting_record->setting_name; ?>', this.value, '<?php echo $setting_record->category; ?>');" autocomplete="off">
					<?php foreach ($sort_options as $value_array): ?>
				<option value="<?php echo $value_array['field_name']; ?>" <?php if($value_array['field_name'] == $value) echo 'selected="selected"' ?>><?php echo $value_array['name']; ?></option>
			<?php endforeach; ?>
		</select>

		<?php if(isset($options['show_shortcode'])): ?>
        <div class="shortcode-wp" id="wpl_setting_form_shortcode_container<?php echo $setting_record->id; ?>">
            <span title="<?php echo __('Shortcode', WPL_TEXTDOMAIN); ?>" id="wpl_st_<?php echo $setting_record->id; ?>_shortcode_value"><?php echo $options['shortcode_key'] . '="' . $value . '"'; ?></span>
        </div>
		<?php endif; ?>

		<?php if(isset($params['tooltip'])): ?>
        <span class="wpl_setting_form_tooltip wpl_help" id="wpl_setting_form_tooltip_container<?php echo $setting_record->id; ?>">
            <span class="wpl_help_description" style="display: none;"><?php echo __($params['tooltip'], WPL_TEXTDOMAIN); ?></span>
        </span>
		<?php endif; ?>

		<span class="ajax-inline-save" id="wpl_ajax_loader_<?php echo $setting_record->id; ?>"></span>
	</div>
</div>
<?php
    $done_this = true;
}
elseif($type == 'wppages' and !$done_this)
{
	$show_empty = isset($options['show_empty']) ? $options['show_empty'] : NULL;
	$wp_pages = wpl_global::get_wp_pages();
?>
<div class="prow wpl_setting_form_container wpl_st_type<?php echo $setting_record->type; ?> wpl_st_<?php echo $setting_record->setting_name; ?>" id="wpl_st_<?php echo $setting_record->id; ?>">
    <div class="select-wp">
        <label for="wpl_st_form_element<?php echo $setting_record->id; ?>"><?php echo $setting_title; ?>&nbsp;<span class="wpl_st_citation">:</span></label>
        <select name="wpl_st_form<?php echo $setting_record->id; ?>" id="wpl_st_form_element<?php echo $setting_record->id; ?>" onchange="wpl_setting_save('<?php echo $setting_record->id; ?>', '<?php echo $setting_record->setting_name; ?>', this.value, '<?php echo $setting_record->category; ?>');" autocomplete="off">
            <?php if($show_empty): ?><option value="">---</option><?php endif; ?>
            <?php foreach ($wp_pages as $wp_page): ?>
            <option value="<?php echo $wp_page->ID; ?>" <?php if($wp_page->ID == $value) echo 'selected="selected"'; ?>><?php echo $wp_page->post_title; ?></option>
            <?php endforeach; ?>
        </select>

        <?php if(isset($params['tooltip'])): ?>
        <span class="wpl_setting_form_tooltip wpl_help" id="wpl_setting_form_tooltip_container<?php echo $setting_record->id; ?>">
            <span class="wpl_help_description" style="display: none;"><?php echo __($params['tooltip'], WPL_TEXTDOMAIN); ?></span>
        </span>
        <?php endif; ?>

        <span class="ajax-inline-save" id="wpl_ajax_loader_<?php echo $setting_record->id; ?>"></span>
    </div>
</div>
<?php
	$done_this = true;
}
elseif($type == 'upload' and !$done_this)
{
    $src = wpl_global::get_wpl_asset_url('img/system/' . $setting_record->setting_value);
    $activity_params = array('html_element_id'=>$params['html_element_id'], 'html_ajax_loader'=>'#wpl_ajax_loader_'.$setting_record->id, 'request_str'=>$params['request_str']);
?>
<div class="prow wpl_setting_form_container wpl_st_type<?php echo $setting_record->type; ?> wpl_st_<?php echo $setting_record->setting_name; ?>" id="wpl_st_<?php echo $setting_record->id; ?>">
	<div class="upload-wp">
		<label for="wpl_st_form_element<?php echo $setting_record->id; ?>"><?php echo $setting_title; ?><span class="wpl_st_citation">:</span></label>
		<?php wpl_global::import_activity('ajax_file_upload', '', $activity_params); ?>
		<span class="ajax-inline-save" id="wpl_ajax_loader_<?php echo $setting_record->id; ?>"></span>
        <?php if($setting_record->setting_value): ?>
		<div class="upload-preview wpl-upload-setting">
			<img id="wpl_upload_image<?php echo $setting_record->id; ?>" src="<?php echo $src; ?>" />
            <div class="preview-remove-button">
                <span class="action-btn icon-recycle" onclick="wpl_remove_upload<?php echo $setting_record->id; ?>();"></span>
            </div>
		</div>
        <?php endif; ?>
	</div>
</div>
<script type="text/javascript">
function wpl_remove_upload<?php echo $setting_record->id; ?>()
{
    request_str = 'wpl_format=b:settings:ajax&wpl_function=remove_upload&setting_name=<?php echo addslashes($setting_record->setting_name); ?>';

    /** run ajax query **/
    ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str);
    ajax.success(function(data)
    {
        if(data.success == 1)
        {
            wplj("#wpl_st_<?php echo $setting_record->id; ?> .upload-preview").remove();
        }
        else if(data.success != 1)
        {
        }
    });
}
</script>
<?php
    $done_this = true;
}
elseif($type == 'textarea' and !$done_this)
{
?>
<div class="prow wpl_setting_form_container wpl_st_type<?php echo $setting_record->type; ?> wpl_st_<?php echo $setting_record->setting_name; ?>" id="wpl_st_<?php echo $setting_record->id; ?>">
	<div class="text-wp">
		<label for="wpl_st_form_element<?php echo $setting_record->id; ?>"><?php echo $setting_title; ?>&nbsp;<span class="wpl_st_citation">:</span></label>
        <textarea class="long" name="wpl_st_form<?php echo $setting_record->id; ?>" id="wpl_st_form_element<?php echo $setting_record->id; ?>" onchange="wpl_setting_save('<?php echo $setting_record->id; ?>', '<?php echo $setting_record->setting_name; ?>', this.value, '<?php echo $setting_record->category; ?>');"><?php echo $setting_record->setting_value; ?></textarea>

		<?php if(isset($params['tooltip'])): ?>
        <span class="wpl_setting_form_tooltip wpl_help" id="wpl_setting_form_tooltip_container<?php echo $setting_record->id; ?>">
            <span class="wpl_help_description" style="display: none;"><?php echo __($params['tooltip'], WPL_TEXTDOMAIN); ?></span>
        </span>
		<?php endif; ?>

		<span class="ajax-inline-save" id="wpl_ajax_loader_<?php echo $setting_record->id; ?>"></span>
	</div>
</div>
<?php
    $done_this = true;
}
elseif($type == 'pattern' and !$done_this)
{
?>
<div class="prow wpl_setting_form_container wpl_st_type<?php echo $setting_record->type; ?> wpl_st_<?php echo $setting_record->setting_name; ?>" id="wpl_st_<?php echo $setting_record->id; ?>">
	<div class="text-wp">
		<label for="wpl_st_form_element<?php echo $setting_record->id; ?>"><?php echo $setting_title; ?>&nbsp;<span class="wpl_st_citation">:</span></label>
        <input class="long" type="text" name="wpl_st_form<?php echo $setting_record->id; ?>" id="wpl_st_form_element<?php echo $setting_record->id; ?>" value="<?php echo $setting_record->setting_value; ?>" placeholder="<?php echo  ((isset($params['placeholder']) and $params['placeholder']) ? __($params['placeholder'], WPL_TEXTDOMAIN) : ''); ?>" onchange="<?php if($options['show_shortcode']): ?>wpl_setting_show_shortcode('<?php echo $setting_record->id; ?>', '<?php echo $options['shortcode_key']; ?>', this.value);<?php endif; ?> wpl_setting_save('<?php echo $setting_record->id; ?>', '<?php echo $setting_record->setting_name; ?>', this.value, '<?php echo $setting_record->category; ?>');" autocomplete="off" />

		<?php if(isset($options['show_shortcode'])): ?>
        <div class="shortcode-wp" id="wpl_setting_form_shortcode_container<?php echo $setting_record->id; ?>">
            <span title="<?php echo __('Shortcode', WPL_TEXTDOMAIN); ?>" id="wpl_st_<?php echo $setting_record->id; ?>_shortcode_value"><?php echo $options['shortcode_key'] . '="' . $value . '"'; ?></span>
        </div>
		<?php endif; ?>

		<?php if(isset($params['tooltip'])): ?>
        <span class="wpl_setting_form_tooltip wpl_help" id="wpl_setting_form_tooltip_container<?php echo $setting_record->id; ?>">
            <span class="wpl_help_description" style="display: none;"><?php echo __($params['tooltip'], WPL_TEXTDOMAIN); ?></span>
        </span>
		<?php endif; ?>

		<span class="ajax-inline-save" id="wpl_ajax_loader_<?php echo $setting_record->id; ?>"></span>
	</div>
</div>
<?php
    $done_this = true;
}
elseif($type == 'multiple' and !$done_this)
{
    $items = json_decode($setting_record->setting_value, true);
?>
<div class="prow wpl_setting_form_container wpl_st_type<?php echo $setting_record->type; ?> wpl_st_<?php echo $setting_record->setting_name; ?>" id="wpl_st_<?php echo $setting_record->id; ?>">
    <div class="text-wp">
        <label for="wpl_st_form_element<?php echo $setting_record->id; ?>"><?php echo $setting_title; ?>&nbsp;<span class="wpl_st_citation">:</span></label>
       
        <input class="<?php echo isset($params['html_class']) ? $params['html_class'] : ''; ?>" type="text"
            name="wpl_st_form<?php echo $setting_record->id; ?>" id="wpl_st_form_element<?php echo $setting_record->id; ?>" 
            placeholder="<?php echo  ((isset($params['placeholder']) and $params['placeholder']) ? __($params['placeholder'], WPL_TEXTDOMAIN) : ''); ?>"
            onchange="wpl_setting_save('<?php echo $setting_record->id; ?>', '<?php echo $setting_record->setting_name; ?>', this.value, '<?php echo $setting_record->category; ?>');"
            autocomplete="off" value="<?php echo htmlentities($setting_record->setting_value); ?>" data-realtyna-tagging />

        <?php if(isset($params['tooltip'])): ?>
        <span class="wpl_setting_form_tooltip wpl_help" id="wpl_setting_form_tooltip_container<?php echo $setting_record->id; ?>">
            <span class="wpl_help_description" style="display: none;"><?php echo __($params['tooltip'], WPL_TEXTDOMAIN); ?></span>
        </span>
        <?php endif; ?>

        <span class="ajax-inline-save" id="wpl_ajax_loader_<?php echo $setting_record->id; ?>"></span>
    </div>
</div>
<?php
    $done_this = true;
}