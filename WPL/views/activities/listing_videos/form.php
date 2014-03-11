<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="fanc-row">
    <label for="wpl_o_video_width"><?php echo __('Video width', WPL_TEXTDOMAIN); ?></label>
    <input class="text_box" name="option[video_width]" type="text" id="wpl_o_video_width" value="<?php echo isset($this->options->video_width) ? $this->options->video_width : '640'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_video_height"><?php echo __('Video height', WPL_TEXTDOMAIN); ?></label>
    <input class="text_box" name="option[video_height]" type="text" id="wpl_o_video_height" value="<?php echo isset($this->options->video_height) ? $this->options->video_height : '270'; ?>" />
</div>