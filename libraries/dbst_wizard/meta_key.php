<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'meta_key' and !$done_this)
{
?>
<label
    for="wpl_c_<?php echo $field->id; ?>"><?php echo __($field->name, WPL_TEXTDOMAIN); ?><?php if (in_array($field->mandatory, array(1, 2))): ?>
        <span class="wpl_red_star">*</span><?php endif; ?></label>

<div id="wpl_c_<?php echo $field->id; ?>_container" class="wpl-meta-wp">
    <div class="wpl-top-row-wp">
        <input type="checkbox" id="wpl_c_<?php echo $field->id; ?>_manual"
               onchange="meta_key_manual();" <?php if (isset($values['meta_keywords_manual']) and $values['meta_keywords_manual']) echo 'checked="checked"'; ?> />
        <label for="wpl_c_<?php echo $field->id; ?>_manual">
            <?php echo __('Manually insert meta keywords', WPL_TEXTDOMAIN); ?>
        </label>
    </div>

    <textarea id="wpl_c_<?php echo $field->id; ?>" rows="<?php echo $options['rows']; ?>"
              cols="<?php echo $options['cols']; ?>"
              onchange="metatag_key_creator();" <?php echo(($options['readonly'] == 1 and (!isset($values['meta_keywords_manual']) or (isset($values['meta_keywords_manual']) and !$values['meta_keywords_manual']))) ? 'disabled="disabled"' : ''); ?>><?php echo $value; ?></textarea>
</div>
<span id="wpl_c_<?php echo $field->id; ?>_message"></span>
<span id="wpl_listing_saved_span_<?php echo $field->id; ?>" class="wpl_listing_saved_span"></span>
<script type="text/javascript">
wplj(document).ready(function ()
{
    metatag_key_creator();
    <?php
        $array = array('#wpl_listing_location1_select','#wpl_listing_location2_select','#wpl_listing_location3_select','#wpl_listing_location4_select','#wpl_listing_location5_select','#wpl_listing_location6_select','#wpl_listing_location7_select','#wpl_listing_locationzips_select','.wpl_c_listing','.wpl_c_mls_id','.wpl_c_bedrooms','.wpl_c_rooms','.wpl_c_property_type','.wpl_c_field_54','.wpl_c_field_55','.wpl_c_bathrooms','.wpl_c_field_42');
        foreach($array as $arr) echo 'wplj("'.$arr.'").change( function(){ metatag_key_creator()});'."\n";
    ?>
});

function metatag_key_creator()
{
    var meta = '';
    var start = '';
    var address = '';

    /** Don't regenerate meta keywords if user want to manually insert it **/
    if (wplj("#wpl_c_<?php echo $field->id; ?>_manual").is(':checked'))
    {
        meta = wplj("#wpl_c_<?php echo $field->id; ?>").val();
        ajax_save('<?php echo $field->table_name; ?>', '<?php echo $field->table_column; ?>', meta, '<?php echo $item_id; ?>', '<?php echo $field->id; ?>');
    
        return true;
    }

    for (i = 7; i >= 1; i--)
    {
        try {
            if (wplj("#wpl_listing_location" + i + "_select").val() != '0' && wplj.trim(wplj("#wpl_listing_location" + i + "_select").val()) != '') {
                if (!isNaN(wplj("#wpl_listing_location" + i + "_select").val()))
                    address += wplj("#wpl_listing_location" + i + "_select :selected").text() + ', ';
                else
                    address += wplj("#wpl_listing_location" + i + "_select").val() + ', ';
            }
        }
        catch (err) {
        }
    }

    // Zipcode
    try {
        if (wplj("#wpl_listing_locationzips_select").val() != '0' && wplj.trim(wplj("#wpl_listing_locationzips_select").val()) != '') {
            if (wplj("#wpl_listing_locationzips_select").prop('tagName').toLowerCase() == 'select')
                address += wplj("#wpl_listing_locationzips_select :selected").text() + ', ';
            else
                address += wplj("#wpl_listing_locationzips_select").val() + ', ';
        }
    }
    catch (err) {
    }

    // bedrooms
    try {
        if (wplj.trim(wplj(".wpl_c_bedrooms").val()) != '0' && wplj.trim(wplj(".wpl_c_bedrooms").val()) != '')
            start = wplj(".wpl_c_bedrooms").val() + ' <?php echo __('Bedrooms', WPL_TEXTDOMAIN);?>, ';
    }
    catch (err) {
    }

    // rooms
    try {
        if (wplj.trim(wplj(".wpl_c_rooms").val()) != '0' && wplj.trim(wplj(".wpl_c_rooms").val()) != '')
            start += wplj(".wpl_c_rooms").val() + ' <?php echo __('Rooms', WPL_TEXTDOMAIN);?>, ';
    }
    catch (err) {
    }

    // property type
    try {
        if (wplj.trim(wplj(".wpl_c_property_type").val()) != '0' || wplj.trim(wplj(".wpl_c_property_type").val()) != '-1')
            start += wplj(".wpl_c_property_type :selected").text() + ', ';
    }
    catch (err) {
    }

    // listintg type
    try {
        if (wplj.trim(wplj(".wpl_c_listing").val()) != '0' || wplj.trim(wplj(".wpl_c_listing").val()) != '-1')
            start += wplj(".wpl_c_listing :selected").text() + ', ';
    }
    catch (err) {
    }

    // building name
    try {
        if (wplj.trim(wplj(".wpl_c_field_54").val()) != '')
            start += wplj(".wpl_c_field_54").val() + ', ';
    }
    catch (err) {
    }

    // street
    try {
        if (wplj.trim(wplj(".wpl_c_field_42").val()) != '')
            start += wplj(".wpl_c_field_42").val() + ', ';
    }
    catch (err) {
    }

    // floor
    try {
        if (wplj.trim(wplj(".wpl_c_field_55").val()) != '0' && wplj.trim(wplj(".wpl_c_field_55").val()) != '')
            start += number_to_th(wplj(".wpl_c_field_55").val()) + " <?php echo __('Floor', WPL_TEXTDOMAIN); ?>, ";
    }
    catch (err) {
    }

    // bathrooms
    try {
        if (wplj.trim(wplj(".wpl_c_bathrooms").val()) != '0' && wplj.trim(wplj(".wpl_c_bathrooms").val()) != '')
            start += wplj(".wpl_c_bathrooms").val() + " <?php echo __('Bathrooms', WPL_TEXTDOMAIN); ?>, ";
    }
    catch (err) {
    }

    // Listing id
    try {
        if (wplj.trim(wplj(".wpl_c_mls_id").val()) != '0' || wplj.trim(wplj(".wpl_c_mls_id").val()) != '-1')
            start += "<?php echo __('Listing ID', WPL_TEXTDOMAIN); ?> " + wplj(".wpl_c_mls_id").val();
    }
    catch (err) {
    }

    meta = start;
    if (address != '') meta += ', ' + address;
    meta = wplj.trim(meta);

    wplj("#wpl_c_<?php echo $field->id; ?>").val(meta);
    ajax_save('<?php echo $field->table_name; ?>', '<?php echo $field->table_column; ?>', meta, '<?php echo $item_id; ?>', '<?php echo $field->id; ?>');
}

var meta_key_pro_addon = <?php echo (wpl_global::check_addon('pro') ? '1' : '0'); ?>;
function meta_key_manual()
{
    if (!wplj("#wpl_c_<?php echo $field->id; ?>_manual").is(':checked'))
    {
        wplj("#wpl_c_<?php echo $field->id; ?>").attr('disabled', 'disabled');

        if (meta_key_pro_addon) {
            ajax_save('<?php echo $field->table_name; ?>', 'meta_keywords_manual', '0', '<?php echo $item_id; ?>', '<?php echo $field->id; ?>');
            metatag_key_creator();
        }

        return false;
    }

    if (!meta_key_pro_addon)
    {
        wpl_show_messages("<?php echo __('Pro addon must be installed for this!', WPL_TEXTDOMAIN); ?>", '#wpl_c_<?php echo $field->id; ?>_message', 'wpl_red_msg');
        setTimeout(function () {
            wpl_remove_message('#wpl_c_<?php echo $field->id; ?>_message');
        }, 3000);

        wplj("#wpl_c_<?php echo $field->id; ?>_manual").removeAttr('checked');
        return false;
    }

    wplj("#wpl_c_<?php echo $field->id; ?>").removeAttr('disabled');
    ajax_save('<?php echo $field->table_name; ?>', 'meta_keywords_manual', '1', '<?php echo $item_id; ?>', '<?php echo $field->id; ?>');
}
</script>
<?php
    $done_this = true;
}