<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$this->_wpl_import($this->tpl_path . '.scripts.css');
$this->_wpl_import($this->tpl_path . '.scripts.js');
?>
<div class="wrap wpl-wp flex-wp<?php echo ($this->kind == 2 ? ' user-flex': ''); ?>">
    <header>
        <div id="icon-flex" class="icon48"></div>
        <h2><?php echo sprintf(__('%s Data Structure', WPL_TEXTDOMAIN), ucfirst($this->kind_label)); ?></h2>
    </header>
    <?php $this->include_tabs(); ?>
    <div class="wpl_flex_list"><div class="wpl_show_message"></div></div>
    <div class="sidebar-wp">
        <!-- sidebar1 -->
        <div class="wpl-side-2 side-tabs-wp">
            <ul>
                <?php foreach ($this->field_categories as $category): ?>
				<li><a href="#<?php echo $category->id; ?>" class="wpl_slide_label wpl_slide_label_prefix_<?php echo $category->prefix; ?>" id="wpl_slide_label_id<?php echo $category->id; ?>" onclick="rta.internal.slides.open('<?php echo $category->id; ?>', '.side-tabs-wp', '.wpl_slide_container', 'currentTab');"><?php echo __($category->name, WPL_TEXTDOMAIN); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="wpl-side-9 side-content-wp flex-content wpl-util-no-padding">
            <!-- sidebar2 -->
            <div class="wpl_sidebar2" style="width: 100%;">
                <?php foreach ($this->field_categories as $category): ?>
                    <div class="wpl_slide_container" id="wpl_slide_container_id<?php echo $category->id; ?>">
                        <?php $this->generate_slide($category); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="side-3 flex-right-panel wpl-util-no-padding">
            <?php $this->generate_sidebar(3); ?>
        </div>
    </div>
    <div id="wpl_flex_edit_div" class="wpl_hidden_element"></div>
    <footer>
        <div class="logo"></div>
    </footer>
</div>