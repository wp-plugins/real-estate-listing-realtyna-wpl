<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$wpl_properties = isset($params['wpl_properties']) ? $params['wpl_properties'] : array();
$property_id = isset($wpl_properties['current']['data']['id']) ? $wpl_properties['current']['data']['id'] : NULL;

$raw_attachments = isset($wpl_properties['current']['items']['attachment']) ? $wpl_properties['current']['items']['attachment'] : NULL;
$attachments = wpl_items::render_attachments($raw_attachments);

if(!count($attachments) or !is_array($attachments)) return;
?>
<div class="wpl_attachments_container" id="wpl_attachments_container<?php echo $property_id; ?>">
	<ul class="wpl_attachments_list_container clearfix">
		<?php foreach($attachments as $attachment):?>
        <li class="wpl_attachments_room type_<?php echo $attachment['ext']; ?>" id="wpl_attachments_attachment<?php echo $attachment['item_id']; ?>">
			<a class="wpl_attachment_link" href="<?php echo $attachment['url']; ?>"><?php echo $attachment['name']; ?></a>
            <span class="wpl_attachment_size">(<?php echo $attachment['rendered_size']; ?>)</span>
		</li>
        <?php endforeach; ?>
    </ul>
</div>