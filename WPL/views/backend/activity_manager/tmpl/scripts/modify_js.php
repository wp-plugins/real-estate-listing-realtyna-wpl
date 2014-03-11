<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
wplj(document).ready(function()
{
	wplj(function()
	{
		wplj('#save_activity_form').submit(function(e)
		{
			e.preventDefault();
			
			ajax_loader_element = "#wpl_activity_modify_ajax_loader";
			wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
			wplj("#wpl_activity_submit_button").prop("disabled", "disabled");
			
			request_str = 'wpl_format=b:activity_manager:ajax&wpl_function=save_activity&' + wplj(this).serialize();
			ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str, ajax_loader_element, 'HTML', 'POST');
			
			ajax.success(function(data)
			{
				wplj(ajax_loader_element).html('');
				wplj("#wpl_activity_submit_button").removeAttr("disabled");
				
				wplj.fancybox.close();
			});
		});
	});
});
</script>