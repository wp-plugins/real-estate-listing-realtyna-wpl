<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

if($type == 'gallery' and !$done_this)
{
    _wpl_import('libraries.items');

    $extentions = explode(',', $options['ext_file']);
	$ext_str = '';
    foreach($extentions as $extention) $ext_str .= $extention . '|';

    // remove last |
    $ext_str = substr($ext_str, 0, -1);
    $ext_str = rtrim($ext_str, ';');
    $max_size = $options['file_size'];

    // Load Handlebars Templates
    echo wpl_global::load_js_template('dbst-wizard-gallery');
?>
<div class="video-tabs-wp" id="gallery-tabs-wp-container">
	<ul>
        <li id="wpl_gallery_uploader_tab" onclick="wpl_gallery_select_tab('wpl_gallery_uploader_tab', 'wpl_gallery_uploader'); return false;" class="active"><a href="#wpl_gallery_uploader"><?php echo __('Image uploader', WPL_TEXTDOMAIN); ?></a></li>
		<li id="wpl_gallery_external_tab" onclick="wpl_gallery_select_tab('wpl_gallery_external_tab', 'wpl_gallery_external'); return false;"><a href="#wpl_gallery_external"><?php echo __('External images', WPL_TEXTDOMAIN); ?></a></li>
	</ul>
</div>

<div class="gallary-btn-wp">
    <div id="wpl_gallery_uploader" class="wpl_gallery_method_container">
        <div class="wpl-button button-1 button-upload">
            <span><?php echo __('Select files', WPL_TEXTDOMAIN); ?></span>
            <!-- The file input field used as target for the file upload widget -->
            <input id="fileupload" type="file" name="files[]" multiple="multiple" />
        </div>
        <div class="field-desc">
            <?php echo __('To select images click on the "Select files" button.', WPL_TEXTDOMAIN); ?>
        </div>
    </div>
    <div id="wpl_gallery_external" class="wpl_gallery_method_container" style="display: none;">
        <?php if(!wpl_global::check_addon('pro')): ?>
        <div class="field-desc">
            <?php echo __('Pro addon must be installed for this!', WPL_TEXTDOMAIN); ?>
        </div>
        <?php else: ?>
        <button class="wpl-button button-1" onclick="add_external_image();"><?php echo __('Add image', WPL_TEXTDOMAIN) ?></button>
        <div class="field-desc">
            <?php echo __('To insert images click on the "Add image" button.', WPL_TEXTDOMAIN); ?>
        </div>
        <div id="wpl_gallery_external_cnt" style="margin-top: 10px; display: none;">
            <div class="gallery-external-wp" id="gallery-external-cnt">
                <div class="row">
                    <label for="gallery_external_link"><?php echo __('Image links', WPL_TEXTDOMAIN); ?></label>
                    <textarea name="gallery_external_link${count}" rows="8" cols="50" id="gallery_external_link" placeholder="<?php echo __('Enter each image link in a new line', WPL_TEXTDOMAIN); ?>"></textarea>
                    <button class="wpl-button button-1" onclick="wpl_gallery_external_save();"><?php echo __('Save', WPL_TEXTDOMAIN); ?></button>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- The global progress bar -->
<div id="progress_img">
    <div id="progress" class="progress progress-success progress-striped">
        <div class="bar"></div>
    </div>
</div>

<div class="error_uploaded_message" id="error_ajax_img">
</div>

<!-- The container for the uploaded files -->
<div id="files" class="gallary-images-wp wpl_files_container">
    <ul class="ui-sortable" id="ajax_gal_sortable">
        <?php
        // get uploaded images and show them
        $gall_items = wpl_items::get_items($item_id, 'gallery', $this->kind, '', '');
		
        $image_folder = wpl_items::get_folder($item_id, $this->kind);
        $image_path = wpl_items::get_path($item_id, $this->kind);
        $image_categories = wpl_items::get_item_categories('gallery', $this->kind);
        $max_img_index = 0;
		
        foreach($gall_items as $image)
		{
            $image->index = intval($image->index);
            if($max_img_index < $image->index) $max_img_index = $image->index;
			
			/** set resize method parameters **/
			$params = array();
			$params['image_name'] = $image->item_name;
			$params['image_parentid'] = $image->parent_id;
			$params['image_parentkind'] = $image->parent_kind;
			$params['image_source'] = $image_path.$image->item_name;
			
			$image_thumbnail_url = wpl_images::create_gallary_image(80, 60, $params, 1, 0);
            if($image->item_cat == 'external') $image_thumbnail_url = $image->item_extra3;
            ?>

            <li class="ui-state-default" id="ajax_gallery<?php echo $image->index; ?>" >
                <input type="hidden" class="gal_name" value="<?php echo $image->item_name; ?>" />
                <div class="image-box-wp">
                    <div class="image-wp">
                        <img src="<?php echo $image_thumbnail_url; ?>" width="80" height="60" />
                    </div>
                    <div class="info-wp">
                        <div class="row">
                            <label for=""><?php echo __('Image Title', WPL_TEXTDOMAIN) ?>:</label>
                            <input type="text" class="gal_title" value="<?php echo $image->item_extra1; ?>" onchange="ajax_gallery_title_update('<?php echo $image->item_name; ?>', this.value);" size="20" />
                        </div>
                        <div class="row">
                            <label for=""><?php echo __('Image Description', WPL_TEXTDOMAIN); ?>:</label>
                            <input type="text" class="gal_desc" value="<?php echo $image->item_extra2; ?>" onchange="ajax_gallery_desc_update('<?php echo $image->item_name; ?>', this.value);" size="50" />
                        </div>
                        <div class="row">
                            <label for=""><?php echo __('Image Category', WPL_TEXTDOMAIN); ?>:</label>
                            <select name="img_cat" class="gal_cat" onchange="ajax_gallery_cat_update('<?php echo $image->item_name; ?>', this.value);">
							<?php
								foreach($image_categories as $img_cat)
								{
									echo '<option value="'.$img_cat->category_name.'"';
									if($image->item_cat == $img_cat->category_name) echo ' selected="selected"';
									echo '>'.$img_cat->category_name.'</option>';
								}
                            ?>
                            </select>
                        </div>
                    </div>
                    <div class="actions-wp">
                        <div class="action-gal-btn">
                            <i class="action-btn icon-move"></i>
                        </div>
                        <div class="action-gal-btn ajax_gallery_middle_td" onclick="ajax_gallery_image_delete('<?php echo $image->item_name; ?>', 'ajax_gallery<?php echo $image->index ?>');" >
                            <i class="action-btn icon-recycle"></i>
                        </div>
                        <?php
                        if($image->enabled) echo '<div class="action-gal-btn" id="active_image_tag_' . $image->index . '" onclick="wpl_image_enabled(\'' . $image->item_name . '\',' . $image->index . ');"><i class="action-btn icon-enabled" title="'.__('Enabled', WPL_TEXTDOMAIN).'"></i></div>';
                        else echo '<div class="action-gal-btn" id="active_image_tag_' . $image->index . '" onclick="wpl_image_enabled(\'' . $image->item_name . '\',' . $image->index . ');"><i class="action-btn icon-disabled" title="'.__('Disabled', WPL_TEXTDOMAIN).'"></i></div>';
                        ?>
                        <input type="hidden" id="enabled_image_field_<?php echo $image->index; ?>" value="<?php echo $image->enabled; ?>"/>
                    </div>  
                </div>
            </li>
            <?php
        }
        ?>
    </ul>
    <?php
		$image_categories_html = '';
		foreach ($image_categories as $img_cat) {
			$image_categories_html .= ' <option value="' . $img_cat->category_name . '">' . __($img_cat->category_name, WPL_TEXTDOMAIN) . '</option>';
		}
    ?>
</div>

<script type="text/javascript">
wplj(document).ready(function()
{
	wplj("#ajax_gal_sortable").sortable(
	{
		placeholder: "wpl-sortable-placeholder",
        opacity: 0.7,
        forcePlaceholderSize: true,
        cursor: "move",
        axis: "y",
		stop: function(event, ui)
		{
			sort_str = "";
			wplj("#ajax_gal_sortable .gal_name").each(function(ind, elm) {
				sort_str += elm.value + ",";
			});
	
			wplj.post("<?php echo wpl_global::get_full_url(); ?>", "&wpl_format=b:listing:gallery&wpl_function=sort_images&pid=<?php echo $item_id; ?>&order=" + sort_str, function(data) {
			});
		}
	});
});

var img_counter = parseInt(<?php echo $max_img_index ?>) + 1;

wplj(document).ready(function()
{
	var url = '<?php echo wpl_global::get_full_url(); ?>&wpl_format=b:listing:gallery&wpl_function=upload&pid='+<?php echo $item_id; ?>+'&kind=<?php echo $this->kind; ?>&type=gallery';

    wplj('#fileupload').fileupload({
        url: url,
        acceptFileTypes: /(<?php echo $ext_str; ?>)$/i,
        dataType: 'json',
        maxFileSize:<?php echo $max_size * 1000; ?>,
        done: function(e, data)
        {
            wplj(data.result.files).each(function(index, file)
            {
                if (file.error !== undefined)
                {
                    wplj('<div class="row"/>').text(file.error).appendTo('#files');
                }
                else if (file.thumbnailUrl !== undefined) {

                    var hbSource   = wplj("#dbst-wizard-gallery").html();
                    var hbTemplate = Handlebars.compile(hbSource);
                    var hbHTML     = hbTemplate({
                        index: img_counter,
                        name: file.name,
                        enabled_title: "<?php echo addslashes(__('Enabled', WPL_TEXTDOMAIN)) ?>",
                        selectOptions: "<?php echo addslashes($image_categories_html) ?>",
                        imageFolder: "<?php echo addslashes($image_folder); ?>",
                        lblImageTitle: "<?php echo addslashes(__('Image Title', WPL_TEXTDOMAIN)); ?>",
                        lblImageDesc: "<?php echo addslashes(__('Image Description', WPL_TEXTDOMAIN)); ?>",
                        lblImageCat: "<?php echo addslashes(__('Image Category', WPL_TEXTDOMAIN)); ?>"
                    });

                    wplj(hbHTML).hide().appendTo('#ajax_gal_sortable').slideDown();
                    img_counter++;
                }
                else
                    wplj('<div class="row"/>').text(file.name).appendTo('#files');
            }).promise().done(function () {

                wplj('#progress_img').hide();

            });


        },
        progressall: function(e, data)
        {
            wplj('#progress_img').show();
            var progress = parseInt(data.loaded / data.total * 100, 10);
            wplj('#progress_img #progress .bar').css(
                    'width',
                    progress + '%'
                    );
            wplj("#error_ajax_img").html("");
        },
        processfail: function(e, data)
        {
            wplj('#progress_img').hide();
            wplj("#error_ajax_img").html("<span color='red'><?php echo addslashes(__('Error occured', WPL_TEXTDOMAIN)) ?> : " + data.files[data.index].name + " " + data.files[data.index].error + "</span>");
            wplj("#error_ajax_img").show('slow');
        }
    });
});

function ajax_gallery_title_update(image, value)
{
	ajax = wpl_run_ajax_query("<?php echo wpl_global::get_full_url(); ?>", "wpl_format=b:listing:gallery&wpl_function=title_update&pid=<?php echo $item_id; ?>&image="+image+"&value="+value+"&kind=<?php echo $this->kind; ?>");
}

function ajax_gallery_desc_update(image, value)
{
	ajax = wpl_run_ajax_query("<?php echo wpl_global::get_full_url(); ?>", "wpl_format=b:listing:gallery&wpl_function=desc_update&pid=<?php echo $item_id; ?>&image="+image+"&value="+value+"&kind=<?php echo $this->kind; ?>");
}

function ajax_gallery_cat_update(image, value)
{
	ajax = wpl_run_ajax_query("<?php echo wpl_global::get_full_url(); ?>", "wpl_format=b:listing:gallery&wpl_function=cat_update&pid=<?php echo $item_id ?>&image="+image+"&value="+value+"&kind=<?php echo $this->kind; ?>");
}

function ajax_gallery_image_delete(image, id)
{
	if(!confirm("<?php echo addslashes(__('Are you sure?', WPL_TEXTDOMAIN)); ?>")) return;
	
    ajax = wpl_run_ajax_query("<?php echo wpl_global::get_full_url(); ?>", "wpl_format=b:listing:gallery&wpl_function=delete_image&pid=<?php echo $item_id; ?>&image="+encodeURIComponent(image)+"&kind=<?php echo $this->kind; ?>");
    ajax.success(function()
    {
        wplj("#" + id).slideUp(400, function(){
            wplj(this).remove();
        });
    });
}

function wpl_image_enabled(gallery, id)
{
	var status = Math.abs(wplj("#enabled_image_field_" + id).val() - 1);
	wplj("#enabled_image_field_" + id).val(status);
    
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', "wpl_format=b:listing:gallery&wpl_function=change_status&pid=<?php echo $item_id; ?>&image="+encodeURIComponent(gallery)+"&enabled="+status+"&kind=<?php echo $this->kind; ?>");
	ajax.success(function(data)
	{
		if (status == 0)
			wplj("#active_image_tag_" + id).html('<i class="action-btn icon-disabled" title="<?php echo addslashes(__('Disabled', WPL_TEXTDOMAIN)); ?>"></i>');
		else
			wplj("#active_image_tag_" + id).html('<i class="action-btn icon-enabled" title="<?php echo addslashes(__('Enabled', WPL_TEXTDOMAIN)); ?>"></i>');
	});
}

function wpl_gallery_select_tab(tab_id, container_id)
{
    wplj('#gallery-tabs-wp-container li').removeClass('active');
    wplj('#gallery-tabs-wp-container li#'+tab_id).addClass('active');
	
    wplj('.wpl_gallery_method_container').hide();
    wplj('#'+container_id).show();
}

function add_external_image(i)
{
    wplj('#wpl_gallery_external_cnt').show();
}

function wpl_gallery_external_save()
{
    var external_link = wplj('#gallery_external_link').val();
    
    ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', "wpl_format=b:listing:gallery&wpl_function=save_external_images&pid=<?php echo $item_id; ?>&kind=<?php echo $this->kind; ?>&links="+external_link);
	ajax.success(function (data)
    {
        var url = '<?php echo wpl_global::add_qs_var('pid', $item_id, wpl_global::get_full_url()); ?>';
        window.location = url;
	});
}
</script>
<?php
    $done_this = true;
}