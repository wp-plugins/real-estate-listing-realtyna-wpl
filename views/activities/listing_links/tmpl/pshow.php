<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$wpl_properties = isset($params['wpl_properties']) ? $params['wpl_properties'] : array();

$property_link = urlencode($wpl_properties['current']['property_link']);
$property_id = isset($wpl_properties['current']['data']['id']) ? $wpl_properties['current']['data']['id'] : NULL;

$show_facebook = (isset($params['facebook']) and $params['facebook']) ? 1 : 0;
$show_google_plus = (isset($params['google_plus']) and $params['google_plus']) ? 1 : 0;
$show_twitter = (isset($params['twitter']) and $params['twitter']) ? 1 : 0;
$show_pinterest = (isset($params['pinterest']) and $params['pinterest']) ? 1 : 0;
$show_favorite = (isset($params['favorite']) and $params['favorite']) ? 1 : 0;
?>
<div class="wpl_listing_links_container" id="wpl_listing_links_container<?php echo $property_id; ?>">
	<ul>
        <?php if($show_facebook): ?>
		<li class="facebook_link">
			<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $property_link; ?>" target="_blank">
			<?php echo __('Share on Facebook', WPL_TEXTDOMAIN); ?>
			</a>
		</li>
        <?php endif; ?>
        <?php if($show_google_plus): ?>
		<li class="google_plus_link">
            <a href="https://plus.google.com/share?url=<?php echo $property_link; ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=500'); return false;"><?php echo __('Google Plus', WPL_TEXTDOMAIN); ?></a>
		</li>
        <?php endif; ?>
        <?php if($show_twitter): ?>
		<li class="twitter_link">
			<a href="https://twitter.com/share?url=<?php echo $property_link; ?>" target="_blank"><?php echo __('Tweet', WPL_TEXTDOMAIN); ?></a>
		</li>
        <?php endif; ?>
        <?php if($show_pinterest): ?>
		<li class="pinterest_link">
			<a href="http://pinterest.com/pin/create/link/?url=<?php echo $property_link; ?>" target="_blank"><?php echo __('Pin it', WPL_TEXTDOMAIN); ?></a>
		</li>
        <?php endif; ?>
	</ul>
    <?php if($show_favorite): ?>
        <?php        
        echo '<input id="wpl_property_title_'.$property_id.'" type="hidden" value="'.$wpl_properties['current']['location_text'].' - '.$wpl_properties['current']['rendered_raw'][3]['value'].' - '.$wpl_properties['current']['rendered_raw'][2]['value'].'" />';
        echo '<a style="display:none;" id="property_link_id_'.$property_id.'" href="'.$property_link.'"></a>';
        ?>
        <?php $find_favorite_item = in_array($property_id, wpl_addon_pro::favorite_load_added_item_id()); ?>
        <a href="#" class="icon-Send" <?php echo $find_favorite_item ? 'style="display: none;"' : '' ?> id="wpl_favorite_add_<?php echo $property_id; ?>" onclick="return wpl_favorite_control(<?php echo $property_id; ?>, 1, true);"><?php echo __('Add to list', WPL_TEXTDOMAIN); ?></a>
        <a href="#" class="icon-recycle" <?php echo!$find_favorite_item ? 'style="display: none;"' : '' ?> id="wpl_favorite_remove_<?php echo $property_id; ?>" onclick="return wpl_favorite_control(<?php echo $property_id; ?>, 0);"><?php echo __('Remove from list', WPL_TEXTDOMAIN); ?></a>
    <?php endif; ?>
</div>