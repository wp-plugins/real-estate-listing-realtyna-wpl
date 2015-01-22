<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<table class="widefat page" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>
                <?php echo __('Name', WPL_TEXTDOMAIN); ?>
            </th>
            <th>
                <?php echo __('Type', WPL_TEXTDOMAIN); ?>
            </th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody class="sortable">
        <?php foreach ($this->fields as $field): ?>
            <tr id="item_row_<?php echo $field->id; ?>">
                <?php
                //enable fields
                $wpl_field_enable_class = $field->enabled == 1 ? "wpl_show" : "wpl_hidden";
                $wpl_field_disable_class = $field->enabled == 0 ? "wpl_show" : "wpl_hidden";
                $wpl_field_always_enable_class = $field->enabled == 2 ? "wpl_show" : "wpl_hidden";

                //mandatori fields
                $wpl_field_mandatory_class = $field->mandatory == 1 ? "wpl_show" : "wpl_hidden";
                $wpl_field_mandatory_disable_class = $field->mandatory == 0 ? "wpl_show" : "wpl_hidden";
                $wpl_field_mandatory_always_class = $field->mandatory == 2 ? "wpl_show cursor-none" : "wpl_hidden";
                $wpl_field_mandatory_never_class = $field->mandatory == 3 ? "wpl_show cursor-none" : "wpl_hidden";

                //editable fields
                $wpl_field_editable_class = $field->editable == 1 ? "wpl_show" : "wpl_hidden";
                $wpl_field_editable_dis_class = $field->editable == 0 ? "wpl_show" : "wpl_hidden";

                //deletable fields
                $wpl_field_deletable_class = $field->deletable == 1 ? "wpl_show" : "wpl_hidden";
                $wpl_field_deletable_dis_class = $field->deletable == 0 ? "wpl_show" : "wpl_hidden";
                ?>

                <td><?php echo __($field->name, WPL_TEXTDOMAIN); ?></td>
                <td><?php echo $field->type; ?></td>

                <td class="wpl_manager_td">
                    <span id="wpl_flex_remove_ajax_loader<?php echo $field->id; ?>"></span>
                    <span class="wpl_ajax_loader" id="wpl_flex_ajax_loader_<?php echo $field->id; ?>"></span>
                </td>
                <td class="wpl_manager_td">
                    <span data-realtyna-lightbox data-realtyna-href="#wpl_flex_edit_div" class="action-btn icon-gear" onclick="wpl_generate_params_page('<?php echo $field->id; ?>');"></span>
                </td>
                <td class="wpl_manager_td">
                    <span class="action-btn icon-star disable <?php echo $wpl_field_mandatory_disable_class; ?>" id="wpl_flex_field_mandatory_dis_span<?php echo $field->id; ?>" onclick="wpl_dbst_mandatory(<?php echo $field->id; ?>, 1);"></span>
                    <span class="action-btn icon-star <?php echo $wpl_field_mandatory_class; ?>" id="wpl_flex_field_mandatory_span<?php echo $field->id; ?>" onclick="wpl_dbst_mandatory(<?php echo $field->id; ?>, 0);"></span>

                    <span class="action-btn icon-star <?php echo $wpl_field_mandatory_always_class; ?>" id="wpl_flex_field_mandatory_always_span<?php echo $field->id; ?>" title="<?php echo __('This field is always mandatory.', WPL_TEXTDOMAIN); ?>"></span>
                    <span class="action-btn icon-star disable <?php echo $wpl_field_mandatory_never_class; ?>" id="wpl_flex_field_mandatory_never_span<?php echo $field->id; ?>" title="<?php echo __('This field is never mandatory.', WPL_TEXTDOMAIN); ?>" ></span>
                </td>
                <td class="wpl_manager_td">
                    <span data-realtyna-lightbox data-realtyna-href="#wpl_flex_edit_div" class="action-btn icon-edit <?php echo $wpl_field_editable_class ?>" onclick="generate_modify_page(<?php echo $field->id; ?>, '<?php echo $field->type; ?>');"></span>
                    <span class="action-btn icon-edit disable <?php echo $wpl_field_editable_dis_class ?> cursor-none"></span>
                </td>
                <td class="wpl_manager_td">
                    <span class="action-btn icon-recycle <?php echo $wpl_field_deletable_class; ?>" onclick="wpl_remove_dbst(<?php echo $field->id; ?>, 0);"></span>
                    <span class="action-btn icon-recycle disable <?php echo $wpl_field_deletable_dis_class; ?> cursor-none"></span>
                </td>
                <td class="wpl_manager_td">
                    <span class="action-btn icon-disabled <?php echo $wpl_field_disable_class; ?>" id="wpl_flex_field_disable_span<?php echo $field->id; ?>" onclick="wpl_dbst_enabled(<?php echo $field->id; ?>, 1);"></span>
                    <span class="action-btn icon-enabled <?php echo $wpl_field_enable_class; ?>" id="wpl_flex_field_enable_span<?php echo $field->id; ?>" onclick="wpl_dbst_enabled(<?php echo $field->id; ?>, 0);"></span>
                    <span class="action-btn icon-enabled disable <?php echo $wpl_field_always_enable_class; ?> cursor-none" id="wpl_flex_field_enable_dis_span<?php echo $field->id; ?>"></span>
                </td>
                <td class="wpl_manager_td">
                    <span class="action-btn icon-move" id="extension_move_<?php echo $field->id ?>"></span>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>