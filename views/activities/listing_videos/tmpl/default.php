<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$this->_wpl_import($this->tpl_path.'.scripts.js', true, true);

$js = (object) array('param1'=>'jquery-video-js-script', 'param2'=>'packages/video-js/video.js');
$style = (object) array('param1'=>'ajax-video-js-style', 'param2'=>'packages/video-js/video-js.min.css');

/** import styles and javascripts **/
wpl_extensions::import_javascript($js);
wpl_extensions::import_style($style);

/** set params **/
$wpl_properties = isset($params['wpl_properties']) ? $params['wpl_properties'] : array();
$property_id = isset($wpl_properties['current']['data']['id']) ? $wpl_properties['current']['data']['id'] : NULL;
$raw_videos = isset($wpl_properties['current']['items']['video']) ? $wpl_properties['current']['items']['video'] : NULL;
$videos = wpl_items::render_videos($raw_videos);

$video_width = isset($params['video_width']) ? $params['video_width'] : 640;
$video_height = isset($params['video_height']) ? $params['video_height'] : 270;

if(!count($videos) or !is_array($videos)) return;
?>
<div class="wpl_videos_container" id="wpl_videos_container<?php echo $property_id; ?>">
	<ul class="wpl_videos_list_container">
		<?php foreach($videos as $video): ?>
        <li class="wpl_videos_video wpl_video_type<?php echo (isset($video['item_cat']) ? $video['item_cat'] : ''); ?>" id="wpl_videos_video<?php echo (isset($video['id']) ? $video['id'] : ''); ?>">
        	<?php if($video['category'] == 'video'): ?>
            <video id="example_video_<?php echo $video['raw']['id']; ?>" class="video-js vjs-default-skin" controls preload="none" width="<?php echo $video_width; ?>" height="<?php echo $video_height; ?>" data-setup="{}">
                <source src="<?php echo $video['url']; ?>" type='video/<?php echo pathinfo($video['url'], PATHINFO_EXTENSION); ?>' />
                <track kind="captions" src="<?php echo wpl_global::get_wpl_asset_url('packages/video-js/demo.captions.vtt'); ?>" srclang="en" label="<?php echo __('English', WPL_TEXTDOMAIN); ?>"></track>
            </video>
            <?php elseif($video['category'] == 'video_embed'): ?>
            <?php echo $video['url']; ?>
            <?php endif; ?>
		</li>
        <?php endforeach; ?>
    </ul>
</div>