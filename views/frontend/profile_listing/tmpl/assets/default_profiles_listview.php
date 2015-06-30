<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="wpl_sort_options_container">
    <div class="wpl_sort_options_container_title"><?php echo __('Sort Option', WPL_TEXTDOMAIN) ?></div>
    <?php echo $this->model->generate_sorts(); ?>
    <?php if($this->property_css_class_switcher): ?>
    <div class="wpl_list_grid_switcher">
        <div id="grid_view" class="grid_view <?php if($this->property_css_class == 'grid_box') echo 'active'; ?>"></div>
        <div id="list_view" class="list_view <?php if($this->property_css_class == 'row_box') echo 'active'; ?>"></div>
    </div>
    <?php endif; ?>
</div>

<?php echo $this->profiles_str; ?>

<?php if($this->wplpagination != 'scroll'): ?>
<div class="wpl_pagination_container">
    <?php echo $this->pagination->show(); ?>
</div>
<?php endif; ?>