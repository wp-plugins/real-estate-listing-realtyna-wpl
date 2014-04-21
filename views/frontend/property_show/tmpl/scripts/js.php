<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
wplj(document).ready(function()
{
	wplj('.wpl_prp_show_tabs .tabs_container div.tabs_contents').hide();
	wplj('.wpl_prp_show_tabs .tabs_container div.tabs_contents:first').show();
	wplj('.wpl_prp_show_tabs ul.tabs li:first').addClass('active');
 
	wplj('.wpl_prp_show_tabs ul.tabs li a').click(function()
	{
		wplj('.wpl_prp_show_tabs ul.tabs li').removeClass('active');
		wplj(this).parent().addClass('active');
        
		var currentTab = wplj(this).attr('href');
		wplj('.wpl_prp_show_tabs .tabs_container div.tabs_contents').hide();
		wplj(currentTab).show();
		
		if(typeof wpl_pshow_map_init == 'function')
		{
			wpl_pshow_map_init();
		}
		
		return false;
	});
});
</script>