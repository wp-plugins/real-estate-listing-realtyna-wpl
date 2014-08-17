<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'listings' and !$done_this)
{
	$listings = wpl_global::get_listings();
	$current_user = wpl_users::get_wpl_user();
	$lrestrict = $current_user->maccess_lrestrict;
	$rlistings = explode(',', $current_user->maccess_listings);
?>
<label for="wpl_c_<?php echo $field->id; ?>"><?php echo __($field->name, WPL_TEXTDOMAIN); ?><?php if(in_array($field->mandatory, array(1, 2))): ?><span class="required-star">*</span><?php endif; ?></label>
<select class="wpl_c_<?php echo $field->table_column; ?>" id="wpl_c_<?php echo $field->id; ?>" onchange="wpl_listing_changed(this.value); ajax_save('<?php echo $field->table_name; ?>', '<?php echo $field->table_column; ?>', this.value, '<?php echo $item_id; ?>', '<?php echo $field->id; ?>');">
    <option value="-1"><?php echo __('Select', WPL_TEXTDOMAIN); ?></option>
    <?php foreach($listings as $listing): if($lrestrict and !in_array($listing['id'], $rlistings)) continue; ?>
    <option value="<?php echo $listing['id']; ?>" <?php if($listing['id'] == $value) echo 'selected="selected"'; ?>><?php echo __($listing['name'], WPL_TEXTDOMAIN); ?></option>
    <?php endforeach; ?>
</select>
<span id="wpl_listing_saved_span_<?php echo $field->id; ?>" class="wpl_listing_saved_span"></span>
<?php
	$done_this = true;
}