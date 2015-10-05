<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'parent' and !$done_this)
{
    /** converts property id to listing id **/
    if($value) $value = wpl_property::listing_id($value);
    
    $parent_kind = isset($options['parent_kind']) ? $options['parent_kind'] : 0;
    $replace = isset($options['replace']) ? $options['replace'] : 1;
    $parent_key = isset($options['key']) ? $options['key'] : 'parent';
    
    wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-autocomplete');
?>
<label for="wpl_c_<?php echo $field->id; ?>"><?php echo __($label, WPL_TEXTDOMAIN); ?><?php if(in_array($mandatory, array(1, 2))): ?><span class="required-star">*</span><?php endif; ?></label>
<input type="text" class="wpl_c_<?php echo $field->table_column; ?>" id="wpl_c_<?php echo $field->id; ?>" name="<?php echo $field->table_column; ?>" value="<?php echo $value; ?>" <?php echo ((isset($options['readonly']) and $options['readonly'] == 1) ? 'disabled="disabled"' : ''); ?> />
<span id="wpl_listing_saved_span_<?php echo $field->id; ?>" class="wpl_listing_saved_span"></span>
<script type="text/javascript">
wplj(document).ready(function()
{
    wplj("#wpl_c_<?php echo $field->id; ?>").autocomplete(
    {
        source: "<?php echo wpl_global::add_qs_var('wpl_format', 'b:listing:ajax', wpl_global::get_full_url()); ?>&wpl_function=get_parents&kind=<?php echo $parent_kind; ?>&exclude=<?php echo $item_id; ?>",
        minLength: 1,
        select: function(event, ui)
        {
            wpl_parent_is_selected<?php echo $field->id; ?>(ui.item.id);
        },
        change: function(event, ui)
        {
            if(ui.item == null)
            {
                wplj("#wpl_c_<?php echo $field->id; ?>").val(0);
                ajax_save('<?php echo $field->table_name; ?>', '<?php echo $field->table_column; ?>', 0, '<?php echo $item_id; ?>', '<?php echo $field->id; ?>');
            }
        }
    });
});

function wpl_parent_is_selected<?php echo $field->id; ?>(parent_id)
{
    var url = "<?php echo wpl_global::add_qs_var('pid', $item_id, wpl_global::get_full_url()); ?>";
    
    ajax = wpl_run_ajax_query("<?php echo wpl_global::get_full_url(); ?>", "wpl_format=b:listing:ajax&wpl_function=set_parent&item_id=<?php echo $item_id; ?>&parent_id="+parent_id+"&kind=<?php echo $this->kind; ?>&replace=<?php echo $replace; ?>&key=<?php echo $parent_key; ?>");
    ajax.success(function()
    {
        <?php if($replace): ?>window.location.href = url;<?php endif; ?>
    });
}
</script>
<?php
	$done_this = true;
}