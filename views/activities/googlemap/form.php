<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="fanc-row">
    <label for="wpl_o_googlemap_type"><?php echo __('Map Type', WPL_TEXTDOMAIN); ?></label>
    <select class="text_box" name="option[googlemap_type]" type="text" id="wpl_o_googlemap_type">
	    <option value="0" <?php if(isset($this->options->googlemap_type) && $this->options->googlemap_type == 0) echo __('selected="selected"'); ?>><?php echo __('Typical', WPL_TEXTDOMAIN); ?></option>
        <option value="1" <?php if(isset($this->options->googlemap_type) && $this->options->googlemap_type == 1) echo __('selected="selected"'); ?>><?php echo __('Street View', WPL_TEXTDOMAIN); ?></option>
    </select>
</div>
<div class="fanc-row">
    <label for="wpl_o_default_lt"><?php echo __('Default latitude', WPL_TEXTDOMAIN); ?></label>
    <input class="text_box" name="option[default_lt]" type="text" id="wpl_o_default_lt" value="<?php echo isset($this->options->default_lt) ? $this->options->default_lt : '38.685516'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_default_ln"><?php echo __('Default longitude', WPL_TEXTDOMAIN); ?></label>
    <input class="text_box" name="option[default_ln]" type="text" id="wpl_o_default_ln" value="<?php echo isset($this->options->default_ln) ? $this->options->default_ln : '-101.073324'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_default_zoom"><?php echo __('Default zoom level', WPL_TEXTDOMAIN); ?></label>
    <input class="text_box" name="option[default_zoom]" type="text" id="wpl_o_default_zoom" value="<?php echo isset($this->options->default_zoom) ? $this->options->default_zoom : '4'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_map_height"><?php echo __('Map height', WPL_TEXTDOMAIN); ?></label>
    <input class="text_box" name="option[map_height]" type="text" id="wpl_o_map_height" value="<?php echo isset($this->options->map_height) ? $this->options->map_height : '480'; ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_google_place"><?php echo __('Google place', WPL_TEXTDOMAIN); ?></label>
    <?php if(!wpl_global::check_addon('pro')): ?>
	<span id="wpl_o_google_place" class="gray_tip"><?php echo __('Pro addon must be installed for this!', WPL_TEXTDOMAIN); ?></span>
	<?php else: ?>
    <select class="text_box" name="option[google_place]" type="text" id="wpl_o_google_place">
	    <option value="0" <?php if(isset($this->options->google_place) && $this->options->google_place == 0) echo __('selected="selected"'); ?>><?php echo __('Disable', WPL_TEXTDOMAIN); ?></option>
        <option value="1" <?php if(isset($this->options->google_place) && $this->options->google_place == 1) echo __('selected="selected"'); ?>><?php echo __('Enable', WPL_TEXTDOMAIN); ?></option>
    </select>
    <?php endif; ?>
</div>
<div class="fanc-row">
    <label for="wpl_o_google_place_radius"><?php echo __('Google place radius', WPL_TEXTDOMAIN); ?></label>
    <?php if(!wpl_global::check_addon('pro')): ?>
	<span id="wpl_o_google_place_radius" class="gray_tip"><?php echo __('Pro addon must be installed for this!', WPL_TEXTDOMAIN); ?></span>
	<?php else: ?>
    <input class="text_box" name="option[google_place_radius]" type="text" id="wpl_o_google_place_radius" value="<?php echo isset($this->options->google_place_radius) ? $this->options->google_place_radius : '1000'; ?>" />
    <?php endif; ?>
</div>