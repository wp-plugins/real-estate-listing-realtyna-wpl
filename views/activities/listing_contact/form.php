<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="fanc-row">
    <label for="wpl_o_top_comment"><?php echo __('Comment', WPL_TEXTDOMAIN); ?></label>
    <input class="text_box" name="option[top_comment]" type="text" id="wpl_o_top_comment" value="<?php echo isset($this->options->top_comment) ? $this->options->top_comment : ''; ?>" />
</div>