<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

if($type == 'gallery' and !$done_this)
{
?>
<div class="search-field-wp search-field-gallery <?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" data-field-id="<?php echo $field->id; ?>" data-status="<?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" data-field-name="<?php echo __($field->name, WPL_TEXTDOMAIN); ?>">

	<input type="hidden" id="field_sort_<?php echo $field->id; ?>" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][sort]" value="<?php echo (isset($value['sort']) ? $value['sort'] : ''); ?>" />
	<input type="hidden" id="field_enable_<?php echo $field->id; ?>" onchange="elementChanged(true);" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][enable]" value="<?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" />
	
	<h4><span><?php echo __($field->name, WPL_TEXTDOMAIN); ?></span></h4>

	<div class="field-body">
		<div class="erow">
			<?php echo __('No Option Available', WPL_TEXTDOMAIN); ?>
		</div>
	</div>
</div>
<?php
    $done_this = true;
}
elseif($type == 'date' and !$done_this)
{
?>
<div class="search-field-wp search-field-date <?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" data-field-id="<?php echo $field->id; ?>" data-status="<?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" data-field-name="<?php echo __($field->name, WPL_TEXTDOMAIN); ?>">

	<input type="hidden" id="field_sort_<?php echo $field->id; ?>" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][sort]" value="<?php echo (isset($value['sort']) ? $value['sort'] : ''); ?>" />
	<input type="hidden" id="field_enable_<?php echo $field->id; ?>" onchange="elementChanged(true);" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][enable]" value="<?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" />
	
	<h4><span><?php echo __($field->name, WPL_TEXTDOMAIN); ?></span></h4>

	<div class="field-body">
		<div class="erow">
			<select name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][type]">
				<option value="datepicker" <?php if (isset($value['type']) and $value['type'] == 'datepicker') echo 'selected="selected"'; ?>><?php echo __('Datepicker', WPL_TEXTDOMAIN); ?></option>
			</select>
		</div>
		<div class="erow">
            <input type="text" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][extoption]" value="<?php echo (isset($value['extoption']) ? $value['extoption'] : ''); ?>" placeholder="<?php echo __('Min,Max,Icon like 1999-01-01,2020-01-01,0', WPL_TEXTDOMAIN); ?>" title="<?php echo __('Min,Max like 1999-01-01,2020-01-01', WPL_TEXTDOMAIN); ?>" />
		</div>
	</div>
</div>
<?php
    $done_this = true;
}
elseif($type == 'feature' and !$done_this)
{
?>
<div class="search-field-wp search-field-feature <?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" data-field-id="<?php echo $field->id; ?>" data-status="<?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" data-field-name="<?php echo __($field->name, WPL_TEXTDOMAIN); ?>">

	<input type="hidden" id="field_sort_<?php echo $field->id; ?>" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][sort]" value="<?php echo (isset($value['sort']) ? $value['sort'] : ''); ?>" />
	<input type="hidden" id="field_enable_<?php echo $field->id; ?>" onchange="elementChanged(true);" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][enable]" value="<?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" />
	
	<h4><span><?php echo __($field->name, WPL_TEXTDOMAIN); ?></span></h4>

	<div class="field-body">
		<div class="erow">
			<select name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][type]">
				<option value="checkbox" <?php if (isset($value['type']) and $value['type'] == "checkbox") echo 'selected="selected"'; ?>><?php echo __('Check box', WPL_TEXTDOMAIN); ?></option>
				<option value="yesno" <?php if (isset($value['type']) and $value['type'] == "yesno") echo 'selected="selected"'; ?>><?php echo __('Any/Yes', WPL_TEXTDOMAIN); ?></option>
				<option value="select" <?php if (isset($value['type']) and $value['type'] == "select") echo 'selected="selected"'; ?>><?php echo __('Select box', WPL_TEXTDOMAIN); ?></option>
                <?php if(isset($options['values'])): ?>
                <option value="option_single" <?php if (isset($value['type']) and $value['type'] == "option_single") echo 'selected="selected"'; ?>><?php echo __('Single Option', WPL_TEXTDOMAIN); ?></option>
                <option value="option_multiple" <?php if (isset($value['type']) and $value['type'] == "option_multiple") echo 'selected="selected"'; ?>><?php echo __('Multiple Options', WPL_TEXTDOMAIN); ?></option>
                <?php endif; ?>
			</select>
		</div>
	</div>
</div>
<?php
    $done_this = true;
}
elseif(($type == 'checkbox' or $type == 'tag') and !$done_this)
{
?>
<div class="search-field-wp search-field-checkbox <?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" data-field-id="<?php echo $field->id; ?>" data-status="<?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" data-field-name="<?php echo __($field->name, WPL_TEXTDOMAIN); ?>">

	<input type="hidden" id="field_sort_<?php echo $field->id; ?>" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][sort]" value="<?php echo (isset($value['sort']) ? $value['sort'] : ''); ?>" />
	<input type="hidden" id="field_enable_<?php echo $field->id; ?>" onchange="elementChanged(true);" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][enable]" value="<?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" />
	
	<h4><span><?php echo __($field->name, WPL_TEXTDOMAIN); ?></span></h4>

	<div class="field-body">
		<div class="erow">
			<select name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][type]">
				<option value="checkbox" <?php if (isset($value['type']) and $value['type'] == "checkbox") echo 'selected="selected"'; ?>><?php echo __('Check box', WPL_TEXTDOMAIN); ?></option>
				<option value="yesno" <?php if (isset($value['type']) and $value['type'] == "yesno") echo 'selected="selected"'; ?>><?php echo __('Any/Yes', WPL_TEXTDOMAIN); ?></option>
				<option value="select" <?php if (isset($value['type']) and $value['type'] == "select") echo 'selected="selected"'; ?>><?php echo __('Select box', WPL_TEXTDOMAIN); ?></option>
			</select>
		</div>
	</div>
</div>
<?php
    $done_this = true;
}
elseif($type == 'listings' and !$done_this)
{
    $listings = wpl_global::get_listings();
?>
<div class="search-field-wp search-field-listing <?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" data-field-id="<?php echo $field->id; ?>" data-status="<?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" data-field-name="<?php echo __($field->name, WPL_TEXTDOMAIN); ?>">

	<input type="hidden" id="field_sort_<?php echo $field->id; ?>" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][sort]" value="<?php echo (isset($value['sort']) ? $value['sort'] : ''); ?>" />
	<input type="hidden" id="field_enable_<?php echo $field->id; ?>" onchange="elementChanged(true);" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][enable]" value="<?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" />
	
	<h4><span><?php echo __($field->name, WPL_TEXTDOMAIN); ?></span></h4>

	<div  class="field-body">
		<div class="erow">
			<select name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][type]" onchange="selectChange(this,'listings');" >
				<option value="select" <?php if (isset($value['type']) and $value['type'] == "select") echo 'selected="selected"'; ?> ><?php echo __('Select box', WPL_TEXTDOMAIN); ?></option>
				<option value="multiple" <?php if (isset($value['type']) and $value['type'] == "multiple") echo 'selected="selected"'; ?>><?php echo __('Multiple SelectBox', WPL_TEXTDOMAIN); ?></option>
				<option value="checkboxes" <?php if (isset($value['type']) and $value['type'] == "checkboxes") echo 'selected="selected"'; ?>><?php echo __('Check boxes', WPL_TEXTDOMAIN); ?></option>
				<option value="radios" <?php if (isset($value['type']) and $value['type'] == "radios") echo 'selected="selected"'; ?>><?php echo __('Radio Buttons', WPL_TEXTDOMAIN); ?></option>
				<option value="radios_any" <?php if (isset($value['type']) and $value['type'] == "radios_any") echo 'selected="selected"'; ?>><?php echo __('Radio buttons with any', WPL_TEXTDOMAIN); ?></option>
				<option value="predefined" <?php if (isset($value['type']) and $value['type'] == "predefined") echo 'selected="selected"'; ?>><?php echo __('Predefined', WPL_TEXTDOMAIN); ?></option>
				<option value="select-predefined" <?php if (isset($value['type']) and $value['type'] == "select-predefined") echo 'selected="selected"'; ?>><?php echo __('Select Box from predefined items', WPL_TEXTDOMAIN); ?></option>
			</select>
		</div>
		<span class="erow wpl_extoptions_span <?php echo (isset($value['type']) ? $value['type'] : ''); ?>" id="wpl_extoptions_span_<?php echo $field->id; ?>_1">
			<select name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][extoption][]" id="wpl_extoptions_select_<?php echo $field->id; ?>" <?php if (isset($value['type']) and $value['type'] == "select-predefined") echo 'multiple="multiple"'; ?>>
				<?php foreach ($listings as $list): ?>
					<option <?php if (isset($value['extoption']) and in_array($list['id'], $value['extoption'])) echo 'selected="selected"'; ?> value="<?php echo $list['id']; ?>"><?php echo $list['name']; ?></option>
				<?php endforeach; ?>
			</select>
		</span>

	</div>
</div>
<?php
    $done_this = true;
}
elseif($type == 'locations' and !$done_this)
{
?>
<div class="search-field-wp search-field-locations <?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" data-field-id="<?php echo $field->id; ?>" data-status="<?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" data-field-name="<?php echo __($field->name, WPL_TEXTDOMAIN); ?>">

	<input type="hidden" id="field_sort_<?php echo $field->id; ?>" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][sort]" value="<?php echo (isset($value['sort']) ? $value['sort'] : ''); ?>" />
	<input type="hidden" id="field_enable_<?php echo $field->id; ?>" onchange="elementChanged(true);" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][enable]" value="<?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" />
	
    <h4><span><?php echo __($field->name, WPL_TEXTDOMAIN); ?></span></h4>

	<div class="field-body">
		<div class="erow">
			<select name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][type]" onchange="selectChange(this, 'locations');">
				<option value="simple" <?php if (isset($value['type']) and $value['type'] == 'simple') echo 'selected="selected"' ?>><?php echo __('simple', WPL_TEXTDOMAIN); ?></option>
                <option value="locationtextsearch" <?php if (isset($value['type']) and $value['type'] == 'locationtextsearch') echo 'selected="selected"' ?>><?php echo __('Location textsearch', WPL_TEXTDOMAIN); ?></option>
                <?php if(wpl_global::check_addon('pro')): ?>
				<option value="radiussearch" <?php if (isset($value['type']) and $value['type'] == 'radiussearch') echo 'selected="selected"' ?>><?php echo __('Radius Search', WPL_TEXTDOMAIN); ?></option>
                <option value="googleautosuggest" <?php if (isset($value['type']) and $value['type'] == 'googleautosuggest') echo 'selected="selected"' ?>><?php echo __('Google Auto Suggest', WPL_TEXTDOMAIN); ?></option>
                <?php endif; ?>
			</select>
		</div>
        <div class="erow wpl_extoptions_span <?php echo (isset($value['type']) ? $value['type'] : ''); ?>">
			<input type="text" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][extoption]" value="<?php echo isset($value['extoption']) ? $value['extoption'] : ''; ?>" placeholder="<?php echo __('Location place-holder', WPL_TEXTDOMAIN); ?>" title="<?php echo __('Location place-holder', WPL_TEXTDOMAIN); ?>" />
		</div>
	</div>
</div>
<?php
    $done_this = true;
}
elseif($type == 'neighborhood' and !$done_this)
{
?>
<div class="search-field-wp search-field-neighbornhood <?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" data-field-id="<?php echo $field->id; ?>" data-status="<?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" data-field-name="<?php echo __($field->name, WPL_TEXTDOMAIN); ?>">

	<input type="hidden" id="field_sort_<?php echo $field->id; ?>" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][sort]" value="<?php echo (isset($value['sort']) ? $value['sort'] : ''); ?>" />
	<input type="hidden" id="field_enable_<?php echo $field->id; ?>" onchange="elementChanged(true);" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][enable]" value="<?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" />
	
	<h4><span><?php echo __($field->name, WPL_TEXTDOMAIN); ?></span></h4>

	<div class="field-body">
		<div class="erow">
			<select name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][type]">
				<option value="checkbox" <?php if (isset($value['type']) and $value['type'] == 'checkbox') echo 'selected="selected"'; ?>><?php echo __('Check box', WPL_TEXTDOMAIN); ?></option>
				<option value="yesno" <?php if (isset($value['type']) and $value['type'] == 'yesno') echo 'selected="selected"'; ?>><?php echo __('Any/Yes', WPL_TEXTDOMAIN); ?></option>
				<option value="select" <?php if (isset($value['type']) and $value['type'] == 'select') echo 'selected="selected"'; ?>><?php echo __('Select box', WPL_TEXTDOMAIN); ?></option>
			</select>
		</div>
	</div>
</div>
<?php
    $done_this = true;
}
elseif($type == 'number' and !$done_this)
{
?>
<div class="search-field-wp search-field-number <?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" data-field-id="<?php echo $field->id; ?>" data-status="<?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" data-field-name="<?php echo __($field->name, WPL_TEXTDOMAIN); ?>">

	<input type="hidden" id="field_sort_<?php echo $field->id; ?>" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][sort]" value="<?php echo (isset($value['sort']) ? $value['sort'] : ''); ?>" />
	<input type="hidden" id="field_enable_<?php echo $field->id; ?>" onchange="elementChanged(true);" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][enable]" value="<?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" />
	
	<h4><span><?php echo __($field->name, WPL_TEXTDOMAIN); ?></span></h4>

	<div class="field-body">
		<div class="erow">
			<select name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][type]" onchange="selectChange(this, 'number');">
				<option value="text" <?php if(isset($value['type']) and $value['type'] == 'text') echo 'selected="selected"'; ?>><?php echo __('Text', WPL_TEXTDOMAIN); ?></option>
				<option value="exacttext" <?php if(isset($value['type']) and $value['type'] == 'exacttext') echo 'selected="selected"'; ?>><?php echo __('Exact Text', WPL_TEXTDOMAIN); ?></option>
				<option value="minmax" <?php if(isset($value['type']) and $value['type'] == 'minmax') echo 'selected="selected"'; ?>><?php echo __('Min/Max textbox', WPL_TEXTDOMAIN); ?></option>
				<option value="minmax_slider" <?php if(isset($value['type']) and $value['type'] == 'minmax_slider') echo 'selected="selected"'; ?>><?php echo __('Min/Max Slider', WPL_TEXTDOMAIN); ?></option>
				<option value="minmax_selectbox" <?php if(isset($value['type']) and $value['type'] == 'minmax_selectbox') echo 'selected="selected"'; ?>><?php echo __('Min/Max SelectBox', WPL_TEXTDOMAIN); ?></option>
				<option value="minmax_selectbox_plus" <?php if(isset($value['type']) and $value['type'] == 'minmax_selectbox_plus') echo 'selected="selected"'; ?>><?php echo __('SelectBox+', WPL_TEXTDOMAIN); ?></option>
                <option value="minmax_selectbox_minus" <?php if(isset($value['type']) and $value['type'] == 'minmax_selectbox_minus') echo 'selected="selected"'; ?>><?php echo __('SelectBox-', WPL_TEXTDOMAIN); ?></option>
                <option value="minmax_selectbox_range" <?php if(isset($value['type']) and $value['type'] == 'minmax_selectbox_range') echo 'selected="selected"'; ?>><?php echo __('Range', WPL_TEXTDOMAIN); ?></option>
			</select>
		</div>
		<div class="erow wpl_extoptions_span <?php echo (isset($value['type']) ? $value['type'] : ''); ?>">
			<input type="text" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][extoption]" value="<?php echo isset($value['extoption']) ? $value['extoption'] : ''; ?>" placeholder="<?php echo __('min,max,increment like 0,10,1', WPL_TEXTDOMAIN); ?>" title="<?php echo __('min,max,increment like 0,10,1', WPL_TEXTDOMAIN); ?>" />
		</div>
	</div>
</div>
<?php
    $done_this = true;
}
elseif($type == 'property_types' and !$done_this)
{
    $listings = wpl_global::get_property_types();
?>
<div class="search-field-wp search-field-property-type <?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" data-field-id="<?php echo $field->id; ?>" data-status="<?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" data-field-name="<?php echo __($field->name, WPL_TEXTDOMAIN); ?>">

	<input type="hidden" id="field_sort_<?php echo $field->id; ?>" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][sort]" value="<?php echo (isset($value['sort']) ? $value['sort'] : ''); ?>" />
	<input type="hidden" id="field_enable_<?php echo $field->id; ?>" onchange="elementChanged(true);" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][enable]" value="<?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" />
	
	<h4><span><?php echo __($field->name, WPL_TEXTDOMAIN); ?></span></h4>
    
	<div class="field-body">
		<div class="erow">
			<select name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][type]" onchange="selectChange(this,'property_types');" >
				<option value="select" <?php if (isset($value['type']) and $value['type'] == "select") echo 'selected="selected"'; ?> ><?php echo __('Select box', WPL_TEXTDOMAIN); ?></option>
				<option value="multiple" <?php if (isset($value['type']) and $value['type'] == "multiple") echo 'selected="selected"'; ?>><?php echo __('Multiple SelectBox', WPL_TEXTDOMAIN); ?></option>
				<option value="checkboxes" <?php if (isset($value['type']) and $value['type'] == "checkboxes") echo 'selected="selected"'; ?>><?php echo __('Check boxes', WPL_TEXTDOMAIN); ?></option>
				<option value="radios" <?php if (isset($value['type']) and $value['type'] == "radios") echo 'selected="selected"'; ?>><?php echo __('Radio Buttons', WPL_TEXTDOMAIN); ?></option>
				<option value="radios_any" <?php if (isset($value['type']) and $value['type'] == "radios_any") echo 'selected="selected"'; ?>><?php echo __('Radio buttons with any', WPL_TEXTDOMAIN); ?></option>
				<option value="predefined" <?php if (isset($value['type']) and $value['type'] == "predefined") echo 'selected="selected"'; ?>><?php echo __('Predefined', WPL_TEXTDOMAIN); ?></option>
				<option value="select-predefined" <?php if (isset($value['type']) and $value['type'] == "select-predefined") echo 'selected="selected"'; ?>><?php echo __('Select Box from predefined items', WPL_TEXTDOMAIN); ?></option>
			</select>
		</div>
		<div class="erow wpl_extoptions_span <?php echo (isset($value['type']) ? $value['type'] : ''); ?>" id="wpl_extoptions_span_<?php echo $field->id; ?>_1">
			<select name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][extoption][]" id="wpl_extoptions_select_<?php echo $field->id; ?>" <?php if (isset($value['type']) and $value['type'] == "select-predefined") echo 'multiple="multiple"'; ?>>
				<?php foreach ($listings as $list): ?>
					<option <?php if (isset($value['extoption']) and in_array($list['id'], $value['extoption'])) echo 'selected="selected"'; ?> value="<?php echo $list['id']; ?>"><?php echo $list['name']; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
</div>
<?php
    $done_this = true;
}
elseif($type == 'text' and !$done_this)
{
?>
<div class="search-field-wp search-field-text <?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" data-field-id="<?php echo $field->id; ?>" data-status="<?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" data-field-name="<?php echo __($field->name, WPL_TEXTDOMAIN); ?>">

	<input type="hidden" id="field_sort_<?php echo $field->id; ?>" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][sort]" value="<?php echo (isset($value['sort']) ? $value['sort'] : ''); ?>" />
	<input type="hidden" id="field_enable_<?php echo $field->id; ?>" onchange="elementChanged(true);" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][enable]" value="<?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" />
	
	<h4><span><?php echo __($field->name, WPL_TEXTDOMAIN); ?></span></h4>

	<div class="field-body">
		<div class="erow">
			<select name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][type]">
				<option value="text" <?php if(isset($value['type']) and $value['type'] == 'text') echo 'selected="selected"'; ?> ><?php echo __('Text', WPL_TEXTDOMAIN); ?></option>
				<option value="exacttext" <?php if(isset($value['type']) and $value['type'] == 'exacttext') echo 'selected="selected"'; ?> ><?php echo __('Exact text', WPL_TEXTDOMAIN); ?></option>
			</select>
		</div>
	</div>
</div>
<?php
    $done_this = true;
}
elseif($type == 'select' and !$done_this)
{
?>
<div class="search-field-wp search-field-select <?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" data-field-id="<?php echo $field->id; ?>" data-status="<?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" data-field-name="<?php echo __($field->name, WPL_TEXTDOMAIN); ?>">

	<input type="hidden" id="field_sort_<?php echo $field->id; ?>" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][sort]" value="<?php echo (isset($value['sort']) ? $value['sort'] : ''); ?>" />
	<input type="hidden" id="field_enable_<?php echo $field->id; ?>" onchange="elementChanged(true);" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][enable]" value="<?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" />
	
	<h4><span><?php echo __($field->name, WPL_TEXTDOMAIN); ?></span></h4>

	<div class="field-body">
		<div class="erow">
			<select name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][type]" onchange="selectChange(this, 'select');">
				<option value="select" <?php if (isset($value['type']) and $value['type'] == 'select') echo 'selected="selected"'; ?>><?php echo __('Select box', WPL_TEXTDOMAIN); ?></option>
				<option value="multiple" <?php if (isset($value['type']) and $value['type'] == 'multiple') echo 'selected="selected"'; ?>><?php echo __('Multiple SelectBox', WPL_TEXTDOMAIN); ?></option>
				<option value="radios" <?php if (isset($value['type']) and $value['type'] == 'radios') echo 'selected="selected"'; ?>><?php echo __('Radio Buttons', WPL_TEXTDOMAIN); ?></option>
				<option value="radios_any" <?php if (isset($value['type']) and $value['type'] == 'radios_any') echo 'selected="selected"'; ?>><?php echo __('Radio buttons with any', WPL_TEXTDOMAIN); ?></option>
				<option value="checkboxes" <?php if (isset($value['type']) and $value['type'] == 'checkboxes') echo 'selected="selected"'; ?>><?php echo __('Check boxes', WPL_TEXTDOMAIN); ?></option>
				<option value="predefined" <?php if (isset($value['type']) and $value['type'] == 'predefined') echo 'selected="selected"'; ?>><?php echo __('Predefined', WPL_TEXTDOMAIN); ?></option>
			</select>
		</div>
		<div class="erow wpl_extoptions_span <?php echo (isset($value['type']) ? $value['type'] : ''); ?>">
			<select multiple="multiple" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][extoption][]">
				<?php
				$options = $field->options;
				$options = json_decode($options, true);
				$options = $options['params'];

				foreach ($options as $option)
				{
					?>
					<option <?php if (isset($value['extoption']) and in_array($option['key'], $value['extoption'])) echo 'selected="selected"'; ?> value="<?php echo $option['key']; ?>"><?php echo $option['value']; ?></option>
					<?php
				}
				?>
			</select>
		</div>
	</div>
</div>
<?php
    $done_this = true;
}
elseif(in_array($type, array('user_type', 'user_membership')) and !$done_this)
{
?>
<div class="search-field-wp search-field-select <?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" data-field-id="<?php echo $field->id; ?>" data-status="<?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" data-field-name="<?php echo __($field->name, WPL_TEXTDOMAIN); ?>">

	<input type="hidden" id="field_sort_<?php echo $field->id; ?>" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][sort]" value="<?php echo (isset($value['sort']) ? $value['sort'] : ''); ?>" />
	<input type="hidden" id="field_enable_<?php echo $field->id; ?>" onchange="elementChanged(true);" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][enable]" value="<?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" />
	
	<h4><span><?php echo __($field->name, WPL_TEXTDOMAIN); ?></span></h4>

	<div class="field-body">
		<div class="erow">
			<select name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][type]" onchange="selectChange(this, 'select');">
				<option value="select" <?php if (isset($value['type']) and $value['type'] == 'select') echo 'selected="selected"'; ?>><?php echo __('Select box', WPL_TEXTDOMAIN); ?></option>
				<option value="multiple" <?php if (isset($value['type']) and $value['type'] == 'multiple') echo 'selected="selected"'; ?>><?php echo __('Multiple SelectBox', WPL_TEXTDOMAIN); ?></option>
				<option value="radios" <?php if (isset($value['type']) and $value['type'] == 'radios') echo 'selected="selected"'; ?>><?php echo __('Radio Buttons', WPL_TEXTDOMAIN); ?></option>
				<option value="radios_any" <?php if (isset($value['type']) and $value['type'] == 'radios_any') echo 'selected="selected"'; ?>><?php echo __('Radio buttons with any', WPL_TEXTDOMAIN); ?></option>
				<option value="checkboxes" <?php if (isset($value['type']) and $value['type'] == 'checkboxes') echo 'selected="selected"'; ?>><?php echo __('Check boxes', WPL_TEXTDOMAIN); ?></option>
			</select>
		</div>
	</div>
</div>
<?php
    $done_this = true;
}
elseif($type == 'textarea' and !$done_this)
{
?>
<div class="search-field-wp search-field-textarea <?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" data-field-id="<?php echo $field->id; ?>" data-status="<?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" data-field-name="<?php echo __($field->name, WPL_TEXTDOMAIN); ?>">

	<input type="hidden" id="field_sort_<?php echo $field->id; ?>" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][sort]" value="<?php echo (isset($value['sort']) ? $value['sort'] : ''); ?>" />
	<input type="hidden" id="field_enable_<?php echo $field->id; ?>" onchange="elementChanged(true);" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][enable]" value="<?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" />
	
	<h4><span><?php echo __($field->name, WPL_TEXTDOMAIN); ?></span></h4>

	<div class="field-body">
		<div class="erow">
			<?php echo __('No Option Available', WPL_TEXTDOMAIN); ?>
		</div>
	</div>
</div>
<?php
    $done_this = true;
}
elseif(($type == 'area' or $type == 'price' or $type == 'length' or $type == 'volume') and !$done_this)
{
?>
<div class="search-field-wp search-field-units <?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" data-field-id="<?php echo $field->id; ?>" data-status="<?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" data-field-name="<?php echo __($field->name, WPL_TEXTDOMAIN); ?>">

	<input type="hidden" id="field_sort_<?php echo $field->id; ?>" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][sort]" value="<?php echo (isset($value['sort']) ? $value['sort'] : ''); ?>" />
	<input type="hidden" id="field_enable_<?php echo $field->id; ?>" onchange="elementChanged(true);" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][enable]" value="<?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" />
	
	<h4><span><?php echo __($field->name, WPL_TEXTDOMAIN); ?></span></h4>

	<div class="field-body">
		<div class="erow">
			<select name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][type]" onchange="selectChange(this, '<?php echo $type; ?>');">
				<option value="minmax" <?php if(isset($value['type']) and $value['type'] == 'minmax') echo 'selected="selected"' ?>><?php echo __('Min/Max textbox', WPL_TEXTDOMAIN); ?></option>
				<option value="minmax_slider" <?php if(isset($value['type']) and $value['type'] == 'minmax_slider') echo 'selected="selected"' ?>><?php echo __('Min/Max Slider', WPL_TEXTDOMAIN); ?></option>
				<option value="minmax_selectbox" <?php if(isset($value['type']) and $value['type'] == 'minmax_selectbox') echo 'selected="selected"' ?>><?php echo __('Min/Max SelectBox', WPL_TEXTDOMAIN); ?></option>
				<option value="minmax_selectbox_plus" <?php if(isset($value['type']) and $value['type'] == 'minmax_selectbox_plus') echo 'selected="selected"' ?>><?php echo __('SelectBox+', WPL_TEXTDOMAIN); ?></option>
                <option value="minmax_selectbox_minus" <?php if(isset($value['type']) and $value['type'] == 'minmax_selectbox_minus') echo 'selected="selected"'; ?>><?php echo __('SelectBox-', WPL_TEXTDOMAIN); ?></option>
                <option value="minmax_selectbox_range" <?php if(isset($value['type']) and $value['type'] == 'minmax_selectbox_range') echo 'selected="selected"'; ?>><?php echo __('Range', WPL_TEXTDOMAIN); ?></option>
			</select>
		</div>
		<div class="erow wpl_extoptions_span <?php echo (isset($value['type']) ? $value['type'] : ''); ?>">
			<input type="text" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][extoption]" value="<?php echo (isset($value['extoption']) ? $value['extoption'] : ''); ?>" placeholder="<?php _e('min,max,increment like 0,10,1', WPL_TEXTDOMAIN); ?>" title="<?php _e('min,max,increment like 0,10,1', WPL_TEXTDOMAIN); ?>" />
		</div>
	</div>
</div>
<?php
    $done_this = true;
}
elseif($type == 'textsearch' and !$done_this)
{
?>
<div class="search-field-wp search-field-textsearch <?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" data-field-id="<?php echo $field->id; ?>" data-status="<?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" data-field-name="<?php echo __($field->name, WPL_TEXTDOMAIN); ?>">

	<input type="hidden" id="field_sort_<?php echo $field->id; ?>" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][sort]" value="<?php echo (isset($value['sort']) ? $value['sort'] : ''); ?>" />
	<input type="hidden" id="field_enable_<?php echo $field->id; ?>" onchange="elementChanged(true);" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][enable]" value="<?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" />
	
	<h4><span><?php echo __($field->name, WPL_TEXTDOMAIN); ?></span></h4>

	<div class="field-body">
		<div class="erow">
			<select name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][type]">
				<option value="textbox" <?php if (isset($value['type']) and $value['type'] == 'textbox') echo 'selected="selected"'; ?>><?php echo __('Textbox', WPL_TEXTDOMAIN); ?></option>
				<option value="textarea" <?php if (isset($value['type']) and $value['type'] == 'textarea') echo 'selected="selected"'; ?>><?php echo __('Textarea', WPL_TEXTDOMAIN); ?></option>
			</select>
		</div>
	</div>
</div>
<?php
    $done_this = true;
}
elseif($type == 'addon_calendar' and !$done_this)
{
?>
<div class="search-field-wp search-field-addon-calendar <?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" data-field-id="<?php echo $field->id; ?>" data-status="<?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" data-field-name="<?php echo __($field->name, WPL_TEXTDOMAIN); ?>">

	<input type="hidden" id="field_sort_<?php echo $field->id; ?>" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][sort]" value="<?php echo (isset($value['sort']) ? $value['sort'] : ''); ?>" />
	<input type="hidden" id="field_enable_<?php echo $field->id; ?>" onchange="elementChanged(true);" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][enable]" value="<?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" />
	<input type="hidden" id="field_type_<?php echo $field->id; ?>" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][type]" value="addon_calendar" />

	<h4><span><?php echo __($field->name, WPL_TEXTDOMAIN); ?></span></h4>

	<div class="field-body">
		<div class="erow">
			<?php echo __('No Option Available', WPL_TEXTDOMAIN); ?>
		</div>
	</div>
</div>
<?php    
    $done_this = true;
}
elseif($type == 'ptcategory' and !$done_this)
{
?>
<div class="search-field-wp search-field-property-type <?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" data-field-id="<?php echo $field->id; ?>" data-status="<?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" data-field-name="<?php echo __($field->name, WPL_TEXTDOMAIN); ?>">

	<input type="hidden" id="field_sort_<?php echo $field->id; ?>" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][sort]" value="<?php echo (isset($value['sort']) ? $value['sort'] : ''); ?>" />
	<input type="hidden" id="field_enable_<?php echo $field->id; ?>" onchange="elementChanged(true);" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][enable]" value="<?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" />
	
	<h4><span><?php echo __($field->name, WPL_TEXTDOMAIN); ?></span></h4>
    
	<div class="field-body">
		<div class="erow">
			<select name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][type]" onchange="selectChange(this,'property_types');" >
				<option value="select" <?php if (isset($value['type']) and $value['type'] == "select") echo 'selected="selected"'; ?> ><?php echo __('Select box', WPL_TEXTDOMAIN); ?></option>
			</select>
		</div>
	</div>
</div>
<?php
    $done_this = true;
}
elseif($type == 'separator' and !$done_this)
{
?>
<div class="search-field-wp search-field-separator <?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" data-field-id="<?php echo $field->id; ?>" data-status="<?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" data-field-name="<?php echo __($field->name, WPL_TEXTDOMAIN); ?> - <?php echo __("Separator", WPL_TEXTDOMAIN); ?>">

	<input type="hidden" id="field_sort_<?php echo $field->id; ?>" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][sort]" value="<?php echo (isset($value['sort']) ? $value['sort'] : ''); ?>" />
	<input type="hidden" id="field_enable_<?php echo $field->id; ?>" onchange="elementChanged(true);" name="<?php echo $this->get_field_name('data'); ?>[<?php echo $field->id; ?>][enable]" value="<?php echo (isset($value['enable']) ? $value['enable'] : ''); ?>" />

	<h4><span><?php echo __($field->name, WPL_TEXTDOMAIN); ?> - <?php echo __("Separator", WPL_TEXTDOMAIN); ?></span></h4>

	<div class="field-body">
		<div class="erow">
			<?php echo __('No Option Available', WPL_TEXTDOMAIN); ?>
		</div>
	</div>
</div>
<?php
    $done_this = true;
}