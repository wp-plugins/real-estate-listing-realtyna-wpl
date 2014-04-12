<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$html_element_id = isset($params['html_element_id']) ? $params['html_element_id'] : 'wpl_file';
$js_function = isset($params['js_function']) ? $params['js_function'] : $html_element_id . '_upload';
$element_class = isset($params['element_class']) ? $params['element_class'] : '';
$html_path_message = isset($params['html_path_message']) ? $params['html_path_message'] : '.wpl_show_message';
$html_ajax_loader = isset($params['html_ajax_loader']) ? $params['html_ajax_loader'] : '#wpl_file_ajax_loader';
$img_ajax_loader = isset($params['img_ajax_loader']) ? $params['img_ajax_loader'] : 'ajax-loader3.gif';
$request_str = isset($params['request_str']) ? $params['request_str'] : '';
$valid_extensions = (isset($params['valid_extensions']) and is_array($params['valid_extensions'])) ? $params['valid_extensions'] : array('jpg', 'gif', 'png');
?>
<div class="file-upload-wp">
    <div class="wpl-button button-1 button-upload">
        <span><?php echo __('Select Your File', WPL_TEXTDOMAIN); ?></span>
        <!-- The file input field used as target for the file upload widget -->
        <input id="<?php echo $html_element_id; ?>" name="<?php echo $html_element_id; ?>" onchange="return <?php echo $js_function; ?>();" class="<?php echo $element_class; ?>" type="file" autocomplete="off" />
    </div>
</div>
<script type="text/javascript">
function <?php echo $js_function; ?>()
{
	filename = wplj("#<?php echo $html_element_id; ?>").val();

	ext = filename.split('.').pop();
	ext = ext.toLowerCase();

	if(<?php $i = 1; $count = count($valid_extensions); foreach($valid_extensions as $valid_extension){ echo "ext != '".$valid_extension."'".($i < $count ? ' && ' : ''); $i++; } ?>)
	{
		wpl_show_messages('<?php echo __('File extension does not match.', WPL_TEXTDOMAIN); ?>', '<?php echo $html_path_message; ?>', 'wpl_red_msg');
		return false;
	}

	ajax_loader_element = '<?php echo $html_ajax_loader; ?>';
	wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/'.$img_ajax_loader); ?>" />');

	request_str = '<?php echo $request_str; ?>';
	require([rta.config.JSes.ajaxFileUpload], function ()
	{
		wplj.ajaxFileUpload(
		{
			url: request_str,
			secureuri: false,
			fileElementId: '<?php echo $html_element_id; ?>',
			dataType: 'json',
			success: function (data, status)
			{
				wplj(ajax_loader_element).html('');
				data = wplj.parseJSON(data);
                
				if (data.error != '')
				{
					<?php if(trim($html_path_message) != ''): ?>
					rta.util.showMessage(data.error);
					<?php else: ?>
					wpl_alert(data.error);
					<?php endif; ?>
				}
				else
				{
					window.location.reload();
				}

				/** reset the value **/
				wplj("#<?php echo $html_element_id; ?>").val('');
			},
			error: function (data, status, e)
			{
				<?php if(trim($html_path_message) != ''): ?>
				rta.util.showMessage(e, '<?php echo $html_path_message; ?>', 'wpl_red_msg')
				<?php else: ?>
				wpl_alert(e);
				<?php endif; ?>

				/** reset the value **/
				wplj("#<?php echo $html_element_id; ?>").val('');
			}
		})
	});
}
</script>