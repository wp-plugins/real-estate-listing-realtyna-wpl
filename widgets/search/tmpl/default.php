<?php
defined('_WPLEXEC') or die('Restricted access');
?>
<form action="<?php echo wpl_property::get_property_listing_link(); ?>" id="wpl_search_form_<?php echo $widget_id; ?>" method="GET" onsubmit="return wpl_do_search_<?php echo $widget_id; ?>();" class="wpl_search_from_box clearfix wpl_search_kind<?php echo $this->kind; ?> <?php echo $this->css_class; ?>">

    <!-- Do not change the ID -->
    <div id="wpl_searchwidget_<?php echo $widget_id; ?>" class="clearfix">
		<?php
        $top_div = '';
        $bott_div = '';
        $bott_div_open = false;
        
        $is_separator = false;
        $top_array = array();
        
        $counter = 1;
        foreach($this->rendered as $data)
        {
            if(($data['field_data']['type'] == 'separator')&& $counter > 1)
            {
                $is_separator = true;
                break;
            }
            
            $counter++;
        }
        
        $counter = 1;
        if(!$is_separator) $top_array = array(41, 3, 6, 8, 9, 2);
        
        foreach($this->rendered as $data)
        {
            if($is_separator or (!$is_separator and in_array($data['id'], $top_array))) $top_div .= $data['html'];
            else
            {
                if(is_string($data['current_value']) and trim($data['current_value']) and $data['current_value'] != '-1') $bott_div_open = true;
                $bott_div .= $data['html'];
            }
            
            if($data['field_data']['type'] == 'separator' and $counter > 1) $is_separator = false;
            $counter++;
        }
	    ?>
	    <div class="wpl_search_from_box_top">
	    	<?php echo $top_div; ?>
	    	<div class="search_submit_box">
		    	<input id="wpl_search_widget_submit<?php echo $widget_id; ?>" class="wpl_search_widget_submit" type="submit" value="<?php echo __('Search', WPL_TEXTDOMAIN); ?>" />
		    </div>
	    </div>
        <div class="wpl_search_from_box_bot" id="wpl_search_from_box_bot<?php echo $widget_id; ?>">
	    	<?php echo $bott_div; ?>
        </div>
	</div>
    
    <?php if($bott_div): ?>
	<div class="more_search_option" data-widget-id="<?php echo $widget_id; ?>" id="more_search_option<?php echo $widget_id; ?>"><?php echo __('More options', WPL_TEXTDOMAIN); ?></div>
    <?php endif; ?>
</form>
<?php include _wpl_import('widgets.search.scripts.js', true, true); ?>