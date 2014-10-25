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
    request_str = 'wpl_format=b:settings:ajax&wpl_function=remove_upload&setting_name=<?php echo $setting_record->setting_name; ?>';

    /** run ajax query **/
    ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str);
    ajax.success(function(data)
    {
        if (data.success == 1)
        {
            wplj("#wpl_st_<?php echo $setting_record->id; ?> .upload-preview").remove();
        }
        else if (data.success != 1)
        {
        }
    });
}
</script>
<?php
    $done_this = true;
}