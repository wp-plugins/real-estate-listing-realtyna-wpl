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

	wpl_listing_set_js_triggers()
});

/** Complex unit List/Grid View **/
function wpl_set_property_css_class(pcc)
{
	wpl_current_property_css_class = pcc;

	wplj.ajax(
		{
			url: '<?php echo wpl_global::get_full_url(); ?>',
			data: 'wpl_format=f:property_listing:ajax&wpl_function=set_pcc&pcc='+pcc,
			type: 'GET',
			dataType: 'json',
			cache: false,
			success: function(data)
			{
			}
		});
}
function wpl_listing_set_js_triggers()
{
	wplj('.list_view').on('click', function()
	{
		wplj('.grid_view').removeClass('active');
		wplj('.list_view').addClass('active');

		wpl_set_property_css_class('row_box');

		wplj('.wpl-complex-unit-cnt').animate({opacity:0},function()
		{
			wplj(this).removeClass('grid_box').addClass('row_box');
			wplj(this).stop().animate({opacity:1});
		});
	});

	wplj('.grid_view').on('click', function()
	{
		wplj('.list_view').removeClass('active');
		wplj('.grid_view').addClass('active');

		wpl_set_property_css_class('grid_box');

		wplj('.wpl-complex-unit-cnt').animate({opacity:0},function()
		{
			wplj(this).removeClass('row_box').addClass('grid_box');
			wplj(this).stop().animate({opacity:1});
		});
	});

}

</script>