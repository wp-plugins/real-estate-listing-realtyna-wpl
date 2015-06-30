<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
function <?php echo $this->js_function; ?>()
{
	filename = wplj("#<?php echo $this->html_element_id; ?>").val();

	ext = filename.split('.').pop();
	ext = ext.toLowerCase();
    
    if(ext == filename) ext = '';
    
	if(<?php $i = 1; $count = count($this->valid_extensions); foreach($this->valid_extensions as $valid_extension){ echo "ext != '".$valid_extension."'".($i < $count ? ' && ' : ''); $i++; } ?>)
	{
		wpl_show_messages('<?php echo __('File extension does not match.', WPL_TEXTDOMAIN); ?>', '<?php echo $this->html_path_message; ?>', 'wpl_red_msg');
		return false;
	}

	ajax_loader_element = '<?php echo $this->html_ajax_loader; ?>';
	wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/'.$this->img_ajax_loader); ?>" />');

	request_str = '<?php echo $this->request_str; ?>';

    wplj.ajaxFileUpload(
    {
        url: request_str,
        secureuri: false,
        fileElementId: '<?php echo $this->html_element_id; ?>',
        dataType: 'json',
        success: function(data, status)
        {
            wplj(ajax_loader_element).html('');
            data = wplj.parseJSON(data);
            
            if(data.error != '')
            {
                <?php if(trim($this->html_path_message) != ''): ?>
                wpl_show_messages(data.error, '<?php echo $this->html_path_message; ?>', 'wpl_red_msg');
                <?php else: ?>
                wpl_alert(data.error);
                <?php endif; ?>
            }
            else
            {
                <?php if($this->js_callback): echo $this->js_callback; ?>
                <?php else: ?>
                window.location.reload();
                <?php endif; ?>
            }

            /** reset the value **/
            wplj("#<?php echo $this->html_element_id; ?>").val('');
        },
        error: function(data, status, e)
        {
            <?php if(trim($this->html_path_message) != ''): ?>
            wpl_show_messages(e, '<?php echo $this->html_path_message; ?>', 'wpl_red_msg');
            <?php else: ?>
            wpl_alert(e);
            <?php endif; ?>

            /** reset the value **/
            wplj("#<?php echo $this->html_element_id; ?>").val('');
        }
    });
}
</script>