<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if ($type == 'upload' and !$done_this)
{
    $src = wpl_global::get_wpl_asset_url('img/system/' . $setting_record->setting_value);
    $params = array('html_element_id' => 'wpl_watermark_uploader', 'html_ajax_loader' => '#wpl_ajax_loader_' . $setting_record->id, 'request_str' => 'admin.php?wpl_format=b:settings:ajax&wpl_function=save_watermark_image');
?>
<div class="prow wpl_setting_form_container wpl_st_type<?php echo $setting_record->type; ?> wpl_st_<?php echo $setting_record->setting_name; ?>" id="wpl_st_<?php echo $setting_record->id; ?>">
	<div class="upload-wp">
		<label for="wpl_st_form_element<?php echo $setting_record->id; ?>"><?php echo $setting_title; ?><span class="wpl_st_citation">:</span></label>
		<?php wpl_global::import_activity('ajax_file_upload', '', $params); ?>
		<span class="ajax-inline-save" id="wpl_ajax_loader_<?php echo $setting_record->id; ?>"></span>
		<div class="image-wp">
			<img id="wpl_watermark_image" src=<?php echo $src; ?> height="auto" width="200px" />
		</div>
	</div>
</div>
<?php
    $done_this = true;
}