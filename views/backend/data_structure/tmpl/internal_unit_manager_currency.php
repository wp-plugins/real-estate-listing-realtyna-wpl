<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
$this->_wpl_import($this->tpl_path.'.scripts.internal_unit_manager_js');
?>
<table class="widefat page" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th scope="col" class="manage-column" width="50"><?php echo __('Enabled', WPL_TEXTDOMAIN); ?></th>
			<th scope="col" class="manage-column" width="50"><?php echo __('Name', WPL_TEXTDOMAIN); ?></th>
			<th scope="col" class="manage-column"><?php echo __('3digit sep', WPL_TEXTDOMAIN); ?></th>
			<th scope="col" class="manage-column"><?php echo __('Desimal sep', WPL_TEXTDOMAIN); ?></th>
			<th scope="col" class="manage-column"><?php echo __('Cur. after/before', WPL_TEXTDOMAIN); ?></th>
			<th scope="col" class="manage-column">
				<?php echo __('Exchange rate', WPL_TEXTDOMAIN); ?>
				<span class="action-btn icon-recycle-2" onclick="wpl_update_exchange_rates();"></span>
				<span class="ajax-inline-save" id="wpl_ajax_loader_exchanges"></span>
			</th>
			<th scope="col" class="manage-column"><?php echo __('Move', WPL_TEXTDOMAIN); ?></th>
		</tr>
	</thead>
	<tbody class="sortable_unit">
		<?php foreach($this->units as $id => $unit): ?>
			<tr id="item_row_<?php echo $unit['id']; ?>">
				<td>
					<span class="action-btn enabled_check <?php echo $unit['enabled'] == 1 ? "icon-enabled" : "icon-disabled"; ?>" onclick="wpl_unit_enabled_change(<?php echo $unit['id']; ?>);" id="wpl_ajax_flag_<?php echo $unit['id']; ?>"></span>
					<span class="wpl_ajax_loader" id="wpl_ajax_loader_<?php echo $unit['id']; ?>"></span>
				</td>
				<td>
					<input type="text" value="<?php echo __($unit['name'], WPL_TEXTDOMAIN); ?>" onchange="wpl_change_currency_name(<?php echo $unit['id']; ?>,this.value)"/>
					<span class="wpl_ajax_loader_name" id="wpl_ajax_loader_name_<?php echo $unit['id']; ?>"></span>
					<span><?php echo __($unit['extra3'], WPL_TEXTDOMAIN); ?> ( <?php echo __($unit['extra'], WPL_TEXTDOMAIN); ?> ) </span>
				</td>
				<td>
					<select class="selectbox" onchange="change_3digit_seperator(<?php echo $unit['id']; ?>,this.value);">
						<option value=""><?php echo __('No separator', WPL_TEXTDOMAIN); ?></option>
						<option value="," <?php if($unit['seperator'] == ',') echo 'selected="selected"'; ?>>, (<?php echo __('Comma', WPL_TEXTDOMAIN); ?>)</option>
						<option value="." <?php if($unit['seperator'] == '.') echo 'selected="selected"'; ?>>. (<?php echo __('Point', WPL_TEXTDOMAIN); ?>)</option>
					</select>
					<span class="wpl_ajax_loader" id="wpl_ajax_loader_3digit_<?php echo $unit['id']; ?>" ></span>
				</td>
				<td>
					<select class="selectbox" onchange="change_decimal_seperator(<?php echo $unit['id']; ?>,this.value);">
						<option value=""><?php echo __('No decimal', WPL_TEXTDOMAIN); ?></option>
						<option value="," <?php if($unit['d_seperator'] == ',') echo 'selected="selected"'; ?>>, (<?php echo __('Comma', WPL_TEXTDOMAIN); ?>)</option>
						<option value="." <?php if($unit['d_seperator'] == '.') echo 'selected="selected"'; ?>>. (<?php echo __('Point', WPL_TEXTDOMAIN); ?>)</option>
					</select>
					<span class="wpl_ajax_loader" id="wpl_ajax_loader_d_sep_<?php echo $unit['id']; ?>"></span>
				</td>
				<td>
					<select class="selectboxmini" onchange="after_before_change(<?php echo $unit['id']; ?>,this.value);">
						<option value="0"><?php echo __('Before', WPL_TEXTDOMAIN); ?></option>
						<option value="1" <?php if($unit['after_before'] == 1) echo 'selected="selected"'; ?>><?php echo __('After', WPL_TEXTDOMAIN); ?></option>
					</select>
					<span class="wpl_ajax_loader" id="wpl_ajax_loader_after_before_<?php echo $unit['id']; ?>"></span>
				</td>
				<td>
					<input type="text" id="wpl_unit_tosi_<?php echo $unit['id']; ?>" value="<?php echo $unit['tosi']; ?>" onchange="wpl_exchange_rate_manual(<?php echo $unit['id']; ?>,this.value)" />
					<span class="action-btn icon-recycle-2" onclick="wpl_update_a_exchange_rate(<?php echo $unit['id']; ?>,'<?php  echo $unit['extra'] ?>');"></span>
					<span class="wpl_ajax_loader_tosi" id="wpl_ajax_loader_exchange_rate_<?php echo $unit['id']; ?>"></span>
				</td>
				<td class="wpl_manager_td">
					<span class="action-btn icon-move" id="extension_move_1"></span>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>