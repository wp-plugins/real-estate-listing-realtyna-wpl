<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'upload' and !$done_this)
{
    /** import library **/
    _wpl_import('libraries.items');

    $upload_params = $options['params'];
    $upload_params['html_element_id'] = 'wpl_c_' . $field->id;
    $upload_params['html_ajax_loader'] = '#wpl_upload_saved_span_' . $field->id;

    $upload_params['request_str'] = str_replace('[html_element_id]', $upload_params['html_element_id'], $upload_params['request_str']);
    $upload_params['request_str'] = str_replace('[item_id]', $item_id, $upload_params['request_str']);

    $activity_layout = isset($options['layout']) ? $options['layout'] : 'default';
?>
<label for="wpl_c_<?php echo $field->id; ?>"><?php echo __($label, WPL_TEXTDOMAIN); ?><?php if (in_array($mandatory, array(1, 2))): ?><span class="wpl_red_star">*</span><?php endif; ?></label>
<?php wpl_global::import_activity('ajax_file_upload:' . $activity_layout, '', $upload_params); ?>
<span id="wpl_upload_saved_span_<?php echo $field->id; ?>" class="wpl_listing_saved_span"></span>
<?php if($options['preview'] and trim($value) != ''): ?>
<div class="upload-preview-wp preview_upload" id="preview_upload<?php echo $field->id; ?>">
    <div class="upload-preview">
        <img src="<?php echo wpl_items::get_folder($item_id, $field->kind) . $value; ?>" />
        <div class="preview-remove-button">
            <span class="action-btn icon-recycle" onclick="wpl_remove_upload<?php echo $field->id; ?>();"></span>
        </div>
    </div>
</div>
<script type="text/javascript">
function wpl_remove_upload<?php echo $field->id; ?>()
{
    request_str = '<?php echo str_replace('[item_id]', $item_id, $options['remove_str']); ?>&field_id=<?php echo $field->id; ?>';

    /** run ajax query **/
    ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str);
    ajax.success(function(data)
    {
        if (data.success == 1)
        {
            wplj("#preview_upload<?php echo $field->id; ?>").remove();
        }
        else if (data.success != 1)
        {
        }
    });
}
</script>
<?php endif; ?>
<?php
    $done_this = true;
}