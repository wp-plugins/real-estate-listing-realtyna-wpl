<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$this->_wpl_import($this->tpl_path . '.scripts.css');
$this->_wpl_import($this->tpl_path . '.scripts.js');
$this->finds = array();
?>
<div class="wrap wpl-wp pwizard-wp">
    <header>
        <div id="icon-pwizard" class="icon48">
        </div>
        <h2><?php echo __('Add/Edit '.ucfirst($this->kind_label), WPL_TEXTDOMAIN); ?></h2>
    </header>

    <div class="wpl_listing_list"><div class="wpl_show_message"></div></div>

    <div class="finilize-message <?php echo ($this->finalized ? 'hide' : ''); ?>" id="wpl_listing_remember_to_finalize" title="<?php echo __('Click to finalize property ...', WPL_TEXTDOMAIN); ?>" onclick="wplj('#wpl_slide_label_id10000').trigger('click');">
        <i class="icon-warning"></i>
        <span><?php echo __('Remember to finalize!', WPL_TEXTDOMAIN); ?></span>
    </div>

    <div class="sidebar-wp">
        <div class="side-2 side-tabs-wp">
            <ul>
                <li class="finilized">
                    <a href="#10000" class="tab-finalize wpl_slide_label wpl_slide_label_id10000" id="wpl_slide_label_id10000" onclick="wpl_finalize(10000, '<?php echo $this->property_id; ?>');">
                        <span><?php echo __('Finalize', WPL_TEXTDOMAIN); ?></span>
                        <i class="icon-finalize"></i>
                    </a>        
                </li>
                <?php
                $category_listing_specific_array = array();
                $category_property_type_specific_array = array();

                foreach ($this->field_categories as $category)
				{
                    $display = '';

                    if (trim($category->listing_specific) != '')
					{
                        $specified_listings = explode(',', trim($category->listing_specific, ', '));
                        $category_listing_specific_array[$category->id] = $specified_listings;
                        if(!in_array($this->values['listing'], $specified_listings)) $display = 'class="hide"';
                    }
                    elseif(trim($category->property_type_specific) != '')
					{
                        $specified_property_types = explode(',', trim($category->property_type_specific, ', '));
                        $category_property_type_specific_array[$category->id] = $specified_property_types;
                        if(!in_array($this->values['property_type'], $specified_property_types)) $display = 'class="hide"';
                    }
                    ?>
                    <li>
                        <a <?php echo $display; ?> href="#<?php echo $category->id; ?>" class="wpl_slide_label wpl_slide_label_id<?php echo $category->id; ?>" id="wpl_slide_label_id<?php echo $category->id; ?>" onclick="rta.internal.slides.open('<?php echo $category->id; ?>', '.side-tabs-wp', '.wpl_slide_container', 'currentTab');">
							<?php echo __($category->name, WPL_TEXTDOMAIN); ?>
                        </a>
                    </li>
                    <?php
                }
                ?>

            </ul>

        </div>
        <div class="side-12 side-content-wp">
            <?php foreach ($this->field_categories as $category): ?>
                <div class="pwizard-panel wpl_slide_container wpl_slide_container<?php echo $category->id; ?>" id="wpl_slide_container_id<?php echo $category->id; ?>">
                    <?php $this->generate_slide($category); ?>
                </div>
            <?php endforeach; ?>
            <div class="wpl_slide_container wpl_slide_container10000" id="wpl_slide_container_id10000">
                <div id="wpl_slide_container_id10000_befor_save" class="hide"><img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" /></div>
                <div id="wpl_slide_container_id10000_after_save" class="hide">
                    <div class="after-finilize-wp">
                        <div class="finilize-icon"></div>
                        <div class="message-wp">
                            <span>
                                <?php echo __('Your property successfully finalized!', WPL_TEXTDOMAIN); ?>
                            </span>
                            <div class="finilize-btn-wp">
                                <a class="wpl-button button-2" href="<?php echo wpl_property::get_property_link('', $this->property_id); ?>"><?php echo __('View this listing', WPL_TEXTDOMAIN); ?></a>
                                <a class="wpl-button button-2" href="<?php echo wpl_global::remove_qs_var('pid', wpl_global::get_full_url()); ?>"><?php echo __('Add new listing', WPL_TEXTDOMAIN); ?></a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
    <div id="wpl_listing_edit_div" class="wpl_lightbox fanc-box-wp wpl_hidden_element"></div>
    <footer>
        <div class="logo"></div>
    </footer>
</div>
<script type="text/javascript">
    var finalized = <?php echo $this->finalized; ?>;

    function wpl_listing_changed(id)
    {
<?php
/** Tabs **/
foreach ($category_listing_specific_array as $id => $cat_arr) {
    $cond = array();
    foreach ($cat_arr as $cati)
        $cond[] = 'id == ' . $cati;

    echo "if(" . implode('||', $cond) . ') wplj("#wpl_slide_label_id' . $id . '").slideDown(500); else wplj("#wpl_slide_label_id' . $id . '").slideUp(500);';
}

/** Fields **/
foreach (wpl_flex::$category_listing_specific_array as $id => $fld_arr) {
    $cond = array();
    foreach ($fld_arr as $fldi)
        $cond[] = 'id == ' . $fldi;

    echo "if(" . implode('||', $cond) . ') wplj("#wpl_listing_field_container' . $id . '").slideDown(500); else wplj("#wpl_listing_field_container' . $id . '").slideUp(500);';
}
?>
    }

    function wpl_property_type_changed(id)
    {
<?php
/** Tabs **/
foreach ($category_property_type_specific_array as $id => $cat_arr) {
    $cond = array();
    foreach ($cat_arr as $cati)
        $cond[] = 'id == ' . $cati;

    echo "if(" . implode('||', $cond) . ') wplj("#wpl_slide_label_id' . $id . '").slideDown(500); else wplj("#wpl_slide_label_id' . $id . '").slideUp(500);';
}

/** Fields **/
foreach (wpl_flex::$category_property_type_specific_array as $id => $fld_arr) {
    $cond = array();
    foreach ($fld_arr as $fldi)
        $cond[] = 'id == ' . $fldi;

    echo "if(" . implode('||', $cond) . ') wplj("#wpl_listing_field_container' . $id . '").slideDown(500); else wplj("#wpl_listing_field_container' . $id . '").slideUp(500);';
}
?>
    }

    function wpl_get_tinymce_content(html_element_id)
    {
        if (wplj("#wp-" + html_element_id + "-wrap").hasClass("tmce-active"))
        {
            return tinyMCE.activeEditor.getContent();
        }
        else
        {
            return wplj("#" + html_element_id).val();
        }
    }

    function wpl_finalize(slide_id, item_id)
    {
        /** validate form **/
        if (!wpl_validation_check()) return;

        //wpl_go_slide(slide_id);
        rta.internal.slides.open(slide_id, '.side-tabs-wp', '.wpl_slide_container', 'currentTab');

        wplj("#wpl_slide_container_id10000_befor_save").show();
        wplj("#wpl_slide_container_id10000_after_save").hide();

        request_str = 'wpl_format=b:listing:ajax&wpl_function=finalize&item_id=' + item_id + '&mode=<?php echo $this->mode; ?>';

        /** run ajax query **/
        ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str);
        ajax.success(function(data)
        {
            if (data.success == 1)
            {
                wplj("#wpl_slide_container_id10000_befor_save").hide();
                wplj("#wpl_slide_container_id10000_after_save").show();

                finalized = 1;
                wplj("#wpl_listing_remember_to_finalize").hide();
            }
            else if (data.success != 1)
            {
                wplj("#wpl_slide_container_id10000_befor_save").hide();
                wplj("#wpl_slide_container_id10000_after_save").show();
            }
        });
    }

    function wpl_validation_check()
    {
<?php
foreach (wpl_flex::$wizard_js_validation as $js_validation) {
    if (trim($js_validation) == '')
        continue;
    echo $js_validation;
}
?>
        return true;
    }
</script>
