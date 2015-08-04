<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$this->_wpl_import($this->tpl_path . '.scripts.css');
$this->_wpl_import($this->tpl_path . '.scripts.js');
$this->finds = array();
?>
<div class="wrap wpl-wp pwizard-wp wpl_view_container">
    <header>
        <div id="icon-pwizard" class="icon48"></div>
        <h2><?php echo sprintf(__('Add/Edit %s', WPL_TEXTDOMAIN), __(ucfirst($this->kind_label), WPL_TEXTDOMAIN)); ?></h2>
    </header>

    <div class="wpl_listing_list"><div class="wpl_show_message"></div></div>

    <div class="finilize-message <?php echo ($this->finalized ? 'hide' : ''); ?>" id="wpl_listing_remember_to_finalize" title="<?php echo __('Click to finalize property ...', WPL_TEXTDOMAIN); ?>" onclick="wplj('#wpl_slide_label_id10000').trigger('click');">
        <i class="icon-warning"></i>
        <span><?php echo __('Remember to finalize!', WPL_TEXTDOMAIN); ?></span>
    </div>
    
    <div class="sidebar-wp">
        <div class="side-2 side-tabs-wp">
            <ul>

                <?php if($this->mode == 'add'): ?>
                    <li class="wpl-listing-discard-btn">
                        <a href="#" id="wpl_listing_discard" title="<?php echo __('Click to discard property', WPL_TEXTDOMAIN); ?>" onclick="wpl_discard('<?php echo $this->property_id; ?>', 0);">
                            <span id="wpl_listing_discard_loading"><?php echo __('Discard', WPL_TEXTDOMAIN); ?></span>
                            <i class="fa fa-times"></i>
                        </a>
                    </li>
                <?php endif; ?>

                <li class="finilized">
                    <a href="#10000" class="tab-finalize wpl_slide_label_id10000" id="wpl_slide_label_id10000" onclick="wpl_finalize(10000, '<?php echo $this->property_id; ?>');">
                        <span><?php echo __('Finalize', WPL_TEXTDOMAIN); ?></span>
                        <i class="icon-finalize"></i>
                    </a>        
                </li>
                
                <?php
                $category_listing_specific_array = array();
                $category_property_type_specific_array = array();

                foreach($this->field_categories as $category)
				{
                    $display = '';

                    if(trim($category->listing_specific) != '')
					{
                        $category_listing_specific_array[$category->id] = array();
                        
                        if(substr($category->listing_specific, 0, 5) == 'type=')
                        {
                            $specified_listings = wpl_global::get_listing_types_by_parent(substr($category->listing_specific, 5));
                            foreach($specified_listings as $listing_type)
                                $category_listing_specific_array[$category->id][] = $listing_type["id"];
                        }
                        else
                        {
                            $specified_listings = explode(',', trim($category->listing_specific, ', '));
                            $category_listing_specific_array[$category->id] = $specified_listings;
                        }
                        
                        if(!in_array($this->values['listing'], $category_listing_specific_array[$category->id]))
                            $display = "display:none;";
                    }
                    elseif(trim($category->property_type_specific) != '')
					{
                        $category_property_type_specific_array[$category->id] = array();
                        
                        if(substr($category->property_type_specific, 0, 5) == 'type=')
                        {
                            $specified_property_types = wpl_global::get_property_types_by_parent(substr($category->property_type_specific,5));
                            foreach($specified_property_types as $property_type) 
                                $category_property_type_specific_array[$category->id][] = $property_type["id"];
                        }
                        else
                        {
                            $specified_property_types = explode(',', trim($category->property_type_specific, ', '));
                            $category_property_type_specific_array[$category->id] = $specified_property_types;
                        }
                        
                        if(!in_array($this->values['property_type'], $category_property_type_specific_array[$category->id])) 
                            $display = "display:none;";
                    }
                    ?>
                    <li>
                        <a style="<?php echo $display; ?>" href="#<?php echo $category->id; ?>" class="wpl_slide_label wpl_slide_label_prefix_<?php echo $category->prefix; ?>" id="wpl_slide_label_id<?php echo $category->id; ?>" onclick="rta.internal.slides.open('<?php echo $category->id; ?>', '.side-tabs-wp', '.wpl_slide_container', 'currentTab');" >
							<?php echo __($category->name, WPL_TEXTDOMAIN); ?>
                        </a>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
        <div class="side-12 side-content-wp">
            <?php 
            foreach($this->field_categories as $category)
            {
                $display = true;
                
                if(trim($category->listing_specific) != '' && !in_array($this->values['listing'], $category_listing_specific_array[$category->id])) 
                {
                    $display = "display:none;";
                }
                elseif(trim($category->property_type_specific) != '' && !in_array($this->values['property_type'], $category_property_type_specific_array[$category->id]))
                {
                    $display = "display:none;";
                }
                ?>
                <div class="pwizard-panel wpl_slide_container wpl_slide_container<?php echo $category->id; ?>" id="wpl_slide_container_id<?php echo $category->id; ?>" style="<?php echo $display; ?>">
                    <?php $this->generate_slide($category); ?>
                </div>
                <?php 
            } 
            ?>
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
                                <?php
                                    $listing_target_page = wpl_global::get_client() == 1 ? wpl_global::get_setting('backend_listing_target_page') : NULL;
                                    
                                    $property_link = wpl_property::get_property_link('', $this->property_id, $listing_target_page);
                                    $new_link = wpl_global::remove_qs_var('pid', wpl_global::get_full_url());
                                    if($this->kind) $new_link = wpl_global::add_qs_var('kind', $this->kind, $new_link);
                                    
                                    if(wpl_global::get_client() == 1) $manager_link = wpl_global::add_qs_var('kind', $this->kind, wpl_global::get_wpl_admin_menu('wpl_admin_listings'));
                                    else $manager_link = wpl_global::add_qs_var('kind', $this->kind, wpl_global::remove_qs_var('wplmethod', wpl_global::remove_qs_var('pid')));
                                ?>
                                <a class="wpl-button button-2" target="_blank" href="<?php echo $property_link; ?>"><?php echo __('View this listing', WPL_TEXTDOMAIN); ?></a>
                                <a class="wpl-button button-2" href="<?php echo $new_link; ?>"><?php echo __('Add new listing', WPL_TEXTDOMAIN); ?></a>
                                <a class="wpl-button button-2" href="<?php echo $manager_link; ?>"><?php echo sprintf(__('%s Manager', WPL_TEXTDOMAIN), __($this->kind_label, WPL_TEXTDOMAIN)); ?></a>
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
    foreach($category_listing_specific_array as $id => $cat_arr)
    {
        if(count($cat_arr)>0)
        {
            $cond = array();
            foreach ($cat_arr as $cati) $cond[] = 'id == ' . $cati;
            echo "if(" . implode('||', $cond) . ') wplj("#wpl_slide_label_id' . $id . '").slideDown(500); else wplj("#wpl_slide_label_id' . $id . '").slideUp(500);';
        }
    }

    /** Fields **/
    foreach(wpl_flex::$category_listing_specific_array as $id => $fld_arr)
    {
        if(count($fld_arr)>0)
        {
            $cond = array();
            foreach ($fld_arr as $fldi) $cond[] = 'id == ' . $fldi;
            echo "if(" . implode('||', $cond) . ') wplj("#wpl_listing_field_container' . $id . '").slideDown(500); else wplj("#wpl_listing_field_container' . $id . '").slideUp(500);';
        }
    }
    ?>
}

function wpl_property_type_changed(id)
{
    <?php
    /** Tabs **/
    foreach($category_property_type_specific_array as $id => $cat_arr)
    {
        if(count($cat_arr)>0)
        {
            $cond = array();
            foreach ($cat_arr as $cati) $cond[] = 'id == ' . $cati;
            echo "if(" . implode('||', $cond) . ') wplj("#wpl_slide_label_id' . $id . '").slideDown(500); else wplj("#wpl_slide_label_id' . $id . '").slideUp(500);';
        }
    }

    /** Fields **/
    foreach(wpl_flex::$category_property_type_specific_array as $id => $fld_arr)
    {
        if(count($fld_arr)>0)
        {
            $cond = array();
            foreach ($fld_arr as $fldi) $cond[] = 'id == ' . $fldi;
            echo "if(" . implode('||', $cond) . ') wplj("#wpl_listing_field_container' . $id . '").slideDown(500); else wplj("#wpl_listing_field_container' . $id . '").slideUp(500);';
        }
    }
    ?>
}

function wpl_get_tinymce_content(html_element_id)
{
    if(wplj("#wp-" + html_element_id + "-wrap").hasClass("tmce-active"))
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
    if(!wpl_validation_check()) return;

    /** Hide Discard Button **/
    wplj(".wpl-listing-discard-btn").hide();
    
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

<?php if($this->mode == 'add'): ?>
function wpl_discard(item_id, confirmed)
{
	var message_path = '.wpl_listing_list .wpl_show_message';
	if(!confirmed)
	{
		message = "Are you sure you want to remove this listing?&nbsp;";
		message += '<span class="wpl_actions" onclick="wpl_discard(\''+item_id+'\', 1);">Yes</span>&nbsp;<span class="wpl_actions" onclick="wpl_remove_message(\'' + message_path + '\');">No</span>';
		
		wpl_show_messages(message, message_path);
		return false;
	}
	else if(confirmed) wpl_remove_message(message_path);
	
	Realtyna.ajaxLoader.show("#wpl_listing_discard_loading", 'tiny', 'rightOut');
   	
	request_str = "wpl_format=b:listings:ajax&wpl_function=purge_property&pid="+item_id;
    
    ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str);
    ajax.success(function(data)
	{
		if(data.success == 1)
		{
			window.location = "<?php echo $manager_link; ?>";
		}
		else if(data.success != 1)
		{
			wpl_show_messages(data.message, '.wpl_listing_list .wpl_show_message', 'wpl_red_msg');
		}
    });
}
<?php endif; ?>

function wpl_validation_check()
{
    <?php
    foreach(wpl_flex::$wizard_js_validation as $js_validation)
    {
        if(trim($js_validation) == '') continue;
        echo $js_validation;
    }
    ?>
    return true;
}
</script>