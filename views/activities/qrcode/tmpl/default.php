<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$wpl_properties = isset($params['wpl_properties']) ? $params['wpl_properties'] : array();
$property_id = isset($wpl_properties['current']['data']['id']) ? $wpl_properties['current']['data']['id'] : NULL;

// Property Link
$this->url = $wpl_properties['current']['property_link'];

$picture_width = isset($params['picture_width']) ? $params['picture_width'] : 80;
$picture_height = isset($params['picture_height']) ? $params['picture_height'] : 80;
$outer_margin = isset($params['outer_margin']) ? $params['outer_margin'] : 2;
$qrfile_prefix = isset($params['qrfile_prefix']) ? $params['qrfile_prefix'] : 'qr_';
$size = isset($params['size']) ? $params['size'] : 4;
$size = in_array($size, array(1,2,3,4,5,6,7,8,9,10)) ? $size : 4;

$qr_image = $this->get_qr_image($qrfile_prefix, $size, $outer_margin);

global $pdfflyer;
if(isset($pdfflyer) and $pdfflyer) $qr_image = wpl_pdf::get_fixed_url($qr_image);
?>
<div class="wpl_qrcode_container" id="wpl_qrcode_container<?php echo $property_id; ?>">
	<img src="<?php echo $qr_image; ?>" width="<?php echo $picture_width; ?>" height="<?php echo $picture_height; ?>" alt="<?php echo __('QR Code', WPL_TEXTDOMAIN); ?>" />
</div>