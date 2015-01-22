<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'rooms' and !$done_this)
{
    _wpl_import('libraries.items');
    _wpl_import('libraries.room_types');

    $room_items = wpl_items::get_items($item_id, 'rooms', $this->kind);
    $all_room_type = wpl_room_types::get_room_types();
?>
<script type="text/javascript">
function wpl_delete_room(id)
{
    var ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', "wpl_format=b:listing:rooms&wpl_function=delete_room&pid=<?php echo $item_id; ?>&kind=<?php echo $this->kind; ?>&item_id=" + id);
    ajax.success(function(data)
    {
        wplj(".room_" + id).hide(500).remove();
    });
}

function wpl_save_room()
{
    var yroom = wplj("#yroom<?php echo $field->id; ?>").val();
    var xroom = wplj("#xroom<?php echo $field->id; ?>").val();
    var room_type_id = wplj("#room_types<?php echo $field->id; ?>").val();
    var room_name = wplj("#room_types<?php echo $field->id; ?> option:selected").text();

    var ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', "wpl_format=b:listing:rooms&wpl_function=save_room&pid=<?php echo $item_id; ?>&kind=<?php echo $this->kind; ?>" + "&x_param=" + xroom + "&y_param=" + yroom + "&room_type_id=" + room_type_id + "&room_name=" + room_name);
    ajax.success(function(data)
    {
        var added_id = data.data;
        var html = '';
        
        html += '<div class=" room_' + added_id + '">';
        html += '<span class="action-btn icon-recycle wpl_show cursor" onclick="wpl_delete_room(' + added_id + ');"></span>';
        html += '<span class="room-preview"><span>' + room_name + '</span><i>' + xroom + 'x' + yroom + '</i></span>';
        html += '</div>';

        wplj("#xroom<?php echo $field->id; ?>").val('');
        wplj("#yroom<?php echo $field->id; ?>").val('');
        wplj(html).appendTo('#room_list<?php echo $field->id; ?>');
    });
}
</script>
<div class="new-rooms-wp" id="room_add">
    <select id="room_types<?php echo $field->id; ?>">
        <?php foreach($all_room_type as $room_type): ?>
        <option value="<?php echo $room_type['id']; ?>"><?php echo __($room_type['name'], WPL_TEXTDOMAIN); ?></option>
        <?php endforeach; ?>
    </select>
    <input type="text" id="xroom<?php echo $field->id; ?>" name="xroom<?php echo $field->id; ?>" placeholder="<?php echo __('Width', WPL_TEXTDOMAIN); ?>" />
    <input type="text" id="yroom<?php echo $field->id; ?>" name="yroom<?php echo $field->id; ?>" placeholder="<?php echo __('Length', WPL_TEXTDOMAIN); ?>" />
    <button class="wpl-button button-1" onclick="wpl_save_room();"><?php echo __('Add room', WPL_TEXTDOMAIN) ?></button>
</div>
<div class="rooms-list-wp" id="room_list<?php echo $field->id; ?>">
    <?php foreach($room_items as $room_item): ?>
        <div class="new-rooms room_<?php echo $room_item->id; ?>">
            <span class="action-btn icon-recycle wpl_show cursor" onclick="wpl_delete_room(<?php echo $room_item->id; ?>);"></span>
            <span class="room-preview"><?php echo '<span>'.__($room_item->item_name, WPL_TEXTDOMAIN).'</span>'.(($room_item->item_extra1 and $room_item->item_extra2) ? '<i>'.$room_item->item_extra1.'x'.$room_item->item_extra2.'</i>' : ''); ?></span>
        </div>
    <?php endforeach; ?>
</div>
<?php
    $done_this = true;
}