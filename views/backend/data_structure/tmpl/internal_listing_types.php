<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
$this->_wpl_import($this->tpl_path.'.scripts.internal_listing_types_js');
?>
<table class="widefat page">
    <thead>
        <tr>
        	<th scope="col" class="size-1 manage-column" colspan="2"><?php echo __('Listing Types', WPL_TEXTDOMAIN); ?></th>
            <th colspan="5">
                <div class="actions-wp">
                    <a data-realtyna-lightbox data-realtyna-lightbox-opts="reloadPage:true" class="action-btn icon-plus" href="#wpl_data_structure_edit_div" onclick="wpl_generate_new_page_listing_type()"></a>
                </div>
            </th>
    	</tr>
    </thead>
    <tbody class="sortable_list_type">
        <?php foreach($this->listing_types as $id=> $wp_listing_type): ?>
            <tr id="item_row_<?php echo $wp_listing_type['id']; ?>">
                <td class="size-1"><?php echo $wp_listing_type['id']; ?></td>
                <td><?php echo __($wp_listing_type['name'], WPL_TEXTDOMAIN); ?></td>
                <td class="manager-wp">
                    <span class="wpl_ajax_loader" id="wpl_ajax_loader_<?php echo $wp_listing_type['id']; ?>"></span>
                </td>
                <td class="manager-wp">
                    <?php if(($wp_listing_type['editable'] == 1) || ($wp_listing_type['editable'] == 2)): ?>
                    <a data-realtyna-lightbox href="#wpl_data_structure_edit_div" class="action-btn icon-edit" onclick="wpl_generate_edit_page_listing_type(<?php echo $wp_listing_type['id']; ?>);"></a>
                    <?php endif; ?>
                </td>
                <td class="manager-wp">
                    <?php if($wp_listing_type['editable'] == 2): ?>
                    <span id="wpl_listing_type_remove<?php echo $wp_listing_type['id']; ?>" data-realtyna-href="#wpl_data_structure_edit_div" class="action-btn icon-recycle" onclick="wpl_remove_listing_type(<?php echo $wp_listing_type['id']; ?>, 0);"></span>
                    <?php endif; ?>
                </td>
                <td class="manager-wp">
                    <?php
                    if($wp_listing_type['enabled'] == 1)
                    {
                        $listing_type_enable_class = "wpl_show";
                        $listing_type_disable_class = "wpl_hidden";
                    }else
                    {
                        $listing_type_enable_class = "wpl_hidden";
                        $listing_type_disable_class = "wpl_show";
                    }
                    ?>
                    <span class="action-btn icon-disabled <?php echo $listing_type_disable_class; ?>" id="listing_types_disable_<?php echo $wp_listing_type['id']; ?>" onclick="wpl_set_enabled_listing_type(<?php echo $wp_listing_type['id'] ?>, 1);"></span>
                    <span class="action-btn icon-enabled <?php echo $listing_type_enable_class; ?>" id="listing_types_enable_<?php echo $wp_listing_type['id']; ?>" onclick="wpl_set_enabled_listing_type(<?php echo $wp_listing_type['id'] ?>, 0);"></span>
                </td>
                <td class="manager-wp">
                    <span class="action-btn icon-move wpl_actions_icon_move" id="extension_move_1"></span>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div class="wpl_data_structure_list_gicon"><div class="wpl_show_message"></div></div>
<table class="widefat page widefat-margint10" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>
                <?php echo __('Google Icon', WPL_TEXTDOMAIN); ?>
            </th>
        </tr>

    </thead>
    <tbody>
        <tr>
            <td>
                <?php
                $params = array('html_element_id'=>'wpl_gicon_file', 'html_path_message'=>'.wpl_data_structure_list_gicon .wpl_show_message', 'html_ajax_loader'=>'#wpl_gicon_ajax_loader', 'request_str'=>'admin.php?wpl_format=b:data_structure:ajax_listing_types&wpl_function=gicon_upload_file');
                wpl_global::import_activity('ajax_file_upload:default', '', $params);
                ?>
                <span class="ajax-inline-save" id="wpl_gicon_ajax_loader"></span>
            </td>
        </tr>
        <tr>
            <td class="gmarker-icon-wp" id="wpl_gicon_listing">
                <?php foreach($this->listing_gicons as $index=> $listing_gicon): ?>
                    <div class="gmarker-icon" id="gicon<?php echo $index ?>">
                        <img src="<?php echo wpl_global::get_wpl_asset_url('img/listing_types/gicon/'.$listing_gicon); ?>" alt="" />
                        <?php if(wpl_users::is_super_admin()): ?><span class="action-btn icon-recycle-3" onclick="wpl_gicon_delete('<?php echo $listing_gicon; ?>', 0, '<?php echo $index; ?>')"></span><?php endif; ?>
                        <span class="ajax-inline-save" id="wpl_gicon_ajax_loader_<?php echo __($index, WPL_TEXTDOMAIN); ?>"></span>
                    </div>
                <?php endforeach; ?>
            </td>
        </tr>
    </tbody>
</table>