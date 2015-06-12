<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$wpl_properties   = isset($params['wpl_properties']) ? $params['wpl_properties'] : array();
$property_id      = isset($wpl_properties['current']['data']['id']) ? $wpl_properties['current']['data']['id'] : NULL;
$property_link    = urlencode($wpl_properties['current']['property_link']);

$show_facebook    = (isset($params['facebook']) and $params['facebook']) ? 1 : 0;
$show_google_plus = (isset($params['google_plus']) and $params['google_plus']) ? 1 : 0;
$show_twitter     = (isset($params['twitter']) and $params['twitter']) ? 1 : 0;
$show_pinterest   = (isset($params['pinterest']) and $params['pinterest']) ? 1 : 0;
$show_favorite    = (isset($params['favorite']) and $params['favorite']) ? 1 : 0;
$show_pdf         = (isset($params['pdf']) and $params['pdf']) ? 1 : 0;
$show_abuse       = (isset($params['report_abuse']) and $params['report_abuse']) ? 1 : 0;
$show_crm         = (isset($params['crm']) and $params['crm']) ? 1 : 0;

$this->_wpl_import($this->tpl_path.'.scripts.js', true, true, true);
?>
<div class="wpl_listing_links_container" id="wpl_listing_links_container<?php echo $property_id; ?>">
	<ul>
        <?php if($show_facebook): ?>
		<li class="facebook_link">
			<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $property_link; ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=500,width=600'); return false;">
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
			<a href="http://pinterest.com/pin/create/link/?url=<?php echo $property_link; ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=500,width=600'); return false;"><?php echo __('Pin it', WPL_TEXTDOMAIN); ?></a>
		</li>
        <?php endif; ?>

        <?php if($show_pdf): ?>
		<li class="pdf_link">
			<a href="<?php echo wpl_property::get_property_pdf_link($property_id); ?>" target="_blank"><?php echo __('PDF', WPL_TEXTDOMAIN); ?></a>
		</li>
        <?php endif; ?>

        <?php if($show_favorite): ?>
        <?php $find_favorite_item = in_array($property_id, wpl_addon_pro::favorite_get_pids()); ?>
        <li class="favorite_link<?php echo ($find_favorite_item ? ' added' : '') ?>">
            <a href="#" style="<?php echo ($find_favorite_item ? 'display: none;' : '') ?>" id="wpl_favorite_add_<?php echo $property_id; ?>" onclick="return wpl_favorite_control(<?php echo $property_id; ?>, 1);"><?php echo __('Add to list', WPL_TEXTDOMAIN); ?></a>
            <a href="#" style="<?php echo (!$find_favorite_item ? 'display: none;' : '') ?>" id="wpl_favorite_remove_<?php echo $property_id; ?>" onclick="return wpl_favorite_control(<?php echo $property_id; ?>, 0);"><?php echo __('Remove from list', WPL_TEXTDOMAIN); ?></a>
        </li>
        <?php endif; ?>
        
        <?php if($show_abuse): ?>
        <li class="report_abuse_link">
            <a data-realtyna-lightbox href="#wpl_pshow_lightbox_content_container" onclick="return wpl_report_abuse_get_form(<?php echo $property_id; ?>);"><?php echo __('Report Abuse', WPL_TEXTDOMAIN); ?></a>
		</li>
        <?php endif; ?>

        <?php if($show_crm): _wpl_import('libraries.addon_crm'); $crm = new wpl_addon_crm(); ?>
        <li class="crm_link">
            <a href="<?php echo $crm->URL('form'); ?>&pid=<?php echo $property_id; ?>" target="_blank"><?php echo __('Contact for this Property', WPL_TEXTDOMAIN); ?></a>
        </li>
        <?php endif; ?>
	</ul>
</div>