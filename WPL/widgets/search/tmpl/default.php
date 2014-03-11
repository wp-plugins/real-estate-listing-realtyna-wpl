<?php
defined('_WPLEXEC') or die('Restricted access');

/** add Layout js **/
$js[] = (object) array('param1'=>'chosen.jQuery', 'param2'=>'js/chosen.jQuery/chosen.jquery.min.js');
$js[] = (object) array('param1'=>'jquery.checkbox', 'param2'=>'js/jquery.ui/checkbox/jquery.checkbox.js');
foreach ($js as $javascript)
    wpl_extensions::import_javascript($javascript);

include _wpl_import('widgets.search.scripts.js', true, true);
?>
<script type="text/javascript">
wplj(document).ready(function()
{
	wplj('.wpl_search_from_box .more_search_option').click(function()
	{
		if(wplj(this).hasClass('active'))
		{
			wplj(this).removeClass('active');
			wplj('.wpl_search_from_box .wpl_search_from_box_bot').slideUp("fast");
			wplj('.wpl_search_from_box .wpl_search_from_box_bot .wpl_search_field_container').animate({ marginLeft : 100 + 'px', opacity : 1 });
			wplj(this).text("<?php echo __('More options', WPL_TEXTDOMAIN); ?>");
		}
		else
		{
			wplj(this).addClass('active');
			wplj('.wpl_search_from_box .wpl_search_from_box_bot').fadeIn();
			wplj('.wpl_search_from_box .wpl_search_from_box_bot .wpl_search_field_container').animate({ marginLeft : 0 + 'px', opacity : 1 });
			wplj(this).text("<?php echo __('Less options', WPL_TEXTDOMAIN); ?>");
		}
	})
});
</script>
<form action="<?php echo wpl_property::get_property_listing_link(); ?>" id="wpl_search_form_<?php echo $widget_id; ?>" method="GET" onsubmit="return wpl_do_search_<?php echo $widget_id; ?>();" class="wpl_search_from_box clearfix">
	<div id="wpl_searchwidget_<?php echo $widget_id; ?>" class="clearfix">
		<?php
		$top_div = '';
		$bott_div = '';
		$top_array = array(41, 3, 6, 8, 9, 2);

		foreach($this->rendered as $data)
		{
			if(in_array($data['id'], $top_array)) $top_div .= $data['html'];
			else $bott_div .= $data['html'];
		}
	    ?>
	    <div class="wpl_search_from_box_top">
	    	<?php echo $top_div; ?>
	    	<div class="search_submit_box">
		    	<input id="wpl_search_widget_submit<?php echo $widget_id; ?>" class="wpl_search_widget_submit" type="submit" value="<?php echo __('Search', WPL_TEXTDOMAIN); ?>" />
		    </div>
	    </div>
	    <div class="wpl_search_from_box_bot">
	    	<?php echo $bott_div; ?>
	    </div>	    
	</div>
    <?php if($bott_div){ ?>
	<div class="more_search_option"><?php echo __('More options', WPL_TEXTDOMAIN); ?></div>
    <?php } ?>
</form>
