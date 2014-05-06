<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'date' and !$done_this)
{
    _wpl_import('libraries.render');
	wp_enqueue_script('jquery-ui-datepicker');

    $style = (object) array('param1'=>'jquery-ui-css', 'param2'=>'js/jquery.ui/jquery.ui.start.css');
    wpl_extensions::import_style($style);

    $date_format_arr = explode(':', wpl_global::get_setting('main_date_format'));
    $jqdate_format = $date_format_arr[1];

    if($options['minimum_date'] == 'minimum_date') $options['minimum_date'] = date("Y-m-d");
    if($options['maximum_date'] == 'now') $options['maximum_date'] = date("Y-m-d");

    $mindate = explode('-', $options['minimum_date']);
    $maxdate = explode('-', $options['maximum_date']);
    $mindate[1] = intval($mindate[1]);
    $mindate[2] = intval($mindate[2]);
    $maxdate[1] = intval($maxdate[1]);
    $maxdate[2] = intval($maxdate[2]);
?>
<div class="date-wp">
    <label for="wpl_c_<?php echo $field->id; ?>"><?php echo __($field->name, WPL_TEXTDOMAIN); ?><?php if (in_array($field->mandatory, array(1, 2))): ?><span class="required-star">*</span><?php endif; ?></label>
    <input type="text" id="wpl_c_<?php echo $field->id; ?>" value="<?php echo wpl_render::render_date($value); ?>" onchange="ajax_save('<?php echo $field->table_name; ?>', '<?php echo $field->table_column; ?>', this.value, '<?php echo $item_id; ?>', '<?php echo $field->id; ?>');" <?php echo ($options['readonly'] == 1 ? 'disabled="disabled"' : ''); ?> />
    <span id="wpl_listing_saved_span_<?php echo $field->id; ?>" class="ajax-inline-save"></span>
</div>
<?php
    echo '<script type="text/javascript">
		wplj(document).ready( function ()
		{
			wplj("#wpl_c_' . $field->id . '").datepicker(
			{ 
				dayNamesMin: ["' . __('SU', WPL_TEXTDOMAIN) . '", "' . __('MO', WPL_TEXTDOMAIN) . '", "' . __('TU', WPL_TEXTDOMAIN) . '", "' . __('WE', WPL_TEXTDOMAIN) . '", "' . __('TH', WPL_TEXTDOMAIN) . '", "' . __('FR', WPL_TEXTDOMAIN) . '", "' . __('SA', WPL_TEXTDOMAIN) . '"],
				dayNames: 	 ["' . __('Sunday', WPL_TEXTDOMAIN) . '", "' . __('Monday', WPL_TEXTDOMAIN) . '", "' . __('Tuesday', WPL_TEXTDOMAIN) . '", "' . __('Wednesday', WPL_TEXTDOMAIN) . '", "' . __('Thursday', WPL_TEXTDOMAIN) . '", "' . __('Friday', WPL_TEXTDOMAIN) . '", "' . __('Saturday', WPL_TEXTDOMAIN) . '"],
				monthNames:  ["' . __('January', WPL_TEXTDOMAIN) . '", "' . __('February', WPL_TEXTDOMAIN) . '", "' . __('March', WPL_TEXTDOMAIN) . '", "' . __('April', WPL_TEXTDOMAIN) . '", "' . __('May', WPL_TEXTDOMAIN) . '", "' . __('June', WPL_TEXTDOMAIN) . '", "' . __('July', WPL_TEXTDOMAIN) . '", "' . __('August', WPL_TEXTDOMAIN) . '", "' . __('September', WPL_TEXTDOMAIN) . '", "' . __('October', WPL_TEXTDOMAIN) . '", "' . __('November', WPL_TEXTDOMAIN) . '", "' . __('December', WPL_TEXTDOMAIN) . '"],
				dateFormat: "' . $jqdate_format . '",
				gotoCurrent: true,
				minDate: new Date(' . $mindate[0] . ', ' . $mindate[1] . '-1, ' . $mindate[2] . '),
				maxDate: new Date(' . $maxdate[0] . ', ' . $maxdate[1] . '-1, ' . $maxdate[2] . '),
				changeYear: true,
				yearRange: "' . $mindate[0] . ':' . $maxdate[0] . '",
				showOn: "both",
				buttonImage: "' . wpl_global::get_wpl_asset_url('img/system/calendar2.png') . '",
				buttonImageOnly: false,
				buttonImageOnly: true,
				firstDay: 1,
				onSelect: function(dateText, inst) 
				{
					ajax_save("' . $field->table_name . '","' . $field->table_column . '",dateText,' . $item_id . ',' . $field->id . ');
				}
			});
		});
	</script>';

    $done_this = true;
}