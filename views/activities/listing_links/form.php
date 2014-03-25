<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

?>
<div class="fanc-row">
    <label for="wpl_o_facebook"><?php echo __('Facebook', WPL_TEXTDOMAIN); ?></label>
    <input <?php if(isset($this->options->facebook) and $this->options->facebook == '1') echo 'checked="checked""'; ?> class="text_box" name="option[facebook]" type="checkbox" id="wpl_o_facebook" value="<?php echo isset($this->options->facebook) ? $this->options->facebook : '1'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_google_plus"><?php echo __('Google plus', WPL_TEXTDOMAIN); ?></label>
    <input <?php if(isset($this->options->google_plus) and $this->options->google_plus == '1') echo 'checked="checked""'; ?> class="text_box" name="option[google_plus]" type="checkbox" id="wpl_o_google_plus" value="<?php echo isset($this->options->google_plus) ? $this->options->google_plus : '1'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_twitter"><?php echo __('Twitter', WPL_TEXTDOMAIN); ?></label>
    <input <?php if(isset($this->options->twitter) and $this->options->twitter == '1') echo 'checked="checked""'; ?> class="text_box" name="option[twitter]" type="checkbox" id="wpl_o_twitter" value="<?php echo isset($this->options->twitter) ? $this->options->twitter : '1'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_pinterest"><?php echo __('Pinterest', WPL_TEXTDOMAIN); ?></label>
    <input <?php if(isset($this->options->pinterest) and $this->options->pinterest == '1') echo 'checked="checked""'; ?> class="text_box" name="option[pinterest]" type="checkbox" id="wpl_o_pinterest" value="<?php echo isset($this->options->pinterest) ? $this->options->pinterest : '1'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_favorite"><?php echo __('Favorite', WPL_TEXTDOMAIN); ?></label>
    
    <?php if(!wpl_global::check_addon('pro')): ?>
	<span id="wpl_o_favorite" class="gray_tip"><?php echo __('Pro addon must be installed for this!', WPL_TEXTDOMAIN); ?></span>
	<?php else: ?>
    <input <?php if(isset($this->options->favorite) and $this->options->favorite == '1') echo 'checked="checked""'; ?> class="text_box" name="option[favorite]" type="checkbox" id="wpl_o_favorite" value="<?php echo isset($this->options->favorite) ? $this->options->favorite : '1'; ?>" />
    <?php endif; ?>
    
</div>