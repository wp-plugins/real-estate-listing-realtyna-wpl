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
		
        <?php if(isset($this->pshow_googlemap_activity_id)): ?>
        var init_google_map = wplj(this).attr('data-init-googlemap');
		if(init_google_map && typeof wpl_pshow_map_init<?php echo $this->pshow_googlemap_activity_id; ?> == 'function')
		{
			wpl_pshow_map_init<?php echo $this->pshow_googlemap_activity_id; ?>();
		}
		<?php endif; ?>
        
		return false;
	});
});
</script>