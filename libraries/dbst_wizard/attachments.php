<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

if($type == 'attachments' and !$done_this)
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
    echo wpl_global::load_js_template('dbst-wizard-attachment');
?>
<div class="attach-btn-wp">
	<div class="wpl-button button-1 button-upload">
		<span><?php echo __('Select files', WPL_TEXTDOMAIN); ?></span>
		<!-- The file input field used as target for the file upload widget -->
		<input id="attachment_upload" type="file" name="files[]" multiple>
	</div>
	<div class="field-desc">
		<?php echo __('To attach files click on the "Select files" button.', WPL_TEXTDOMAIN); ?>
	</div>
</div>

<!-- The global progress bar -->
<div id="progress_att">
	<div id="progress" class="progress progress-success progress-striped">
		<div class="bar"></div>
	</div>
</div>

<div class="error_uploaded_message" id="error_ajax_att">
</div>

<!-- The container for the uploaded files -->
<div id="attaches" class="attachment-wp wpl_files_container">
	<ul class="ui-sortable" id="ajax_att_sortable">
	<?php
    // get uploaded attachments and show them
    $att_items = wpl_items::get_items($item_id, 'attachment', $this->kind, '', '');

    $att_folder = wpl_items::get_folder($item_id, $this->kind);
    $attachment_categories = wpl_items::get_item_categories('attachment', $this->kind);
    $max_index_att = 0;

    foreach ($att_items as $attachment)
    {
        $attachment->index = intval($attachment->index);
        if($max_index_att < $attachment->index) $max_index_att = $attachment->index;
        ?>
        <li class="ui-state-default" id="ajax_attachment<?php echo $attachment->index; ?>">
            <input type="hidden" class="att_name" value="<?php echo $attachment->item_name ?>"/>

            <div class="image-box-wp">
                <div class="icon-wp">
                    <div class="wpl-attach-icon wpl-att-<?php echo pathinfo($attachment->item_name, PATHINFO_EXTENSION); ?>"></div>
                </div>
                <div class="info-wp">
                    <div class="row">
                        <label for=""><?php echo __('Attachment Title', WPL_TEXTDOMAIN) ?>:</label>
                        <input type="text" class="att_title" value="<?php echo $attachment->item_extra1; ?>" onchange="ajax_attachment_title_update('<?php echo $attachment->item_name; ?>', this.value);" size="20" />
                    </div>
                    <div class="row">
                        <label for=""><?php echo __('Attachment Description', WPL_TEXTDOMAIN) ?>:</label>
                        <input type="text" class="att_desc" value="<?php echo $attachment->item_extra2; ?>" onchange="ajax_attachment_desc_update('<?php echo $attachment->item_name; ?>', this.value);" size="50" />
                    </div>
                    <div class="row">
                        <label for=""><?php echo __('Attachment Category', WPL_TEXTDOMAIN) ?>:</label>
                        <select name="att_cat" class="att_cat" onchange="ajax_attachment_cat_update('<?php echo $attachment->item_name; ?>', this.value);">
                            <?php
                            foreach ($attachment_categories as $att_cat)
                            {
                                echo ' <option value="' . $att_cat->category_name . '"';
                                if($attachment->item_cat == $att_cat->category_name) echo ' selected="selected"';
                                echo '>' . $att_cat->category_name . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="actions-wp">
                    <div class="action-gal-btn ajax_attachment_move_icon">
                        <i class="action-btn icon-move wpl_actions_btn"></i>
                    </div>
                    <div class="action-gal-btn ajax_gallery_middle_td " onclick="ajax_attachment_delete('<?php echo $attachment->item_name; ?>','ajax_attachment<?php echo $attachment->index; ?>');" >
                        <i class="action-btn icon-recycle"></i>
                    </div>

                    <?php
                    if($attachment->enabled) echo '<div class="action-gal-btn" id="active_attachment_tag_' . $attachment->index . '" onclick="wpl_attachment_enabled(\'' . $attachment->item_name . '\',' . $attachment->index . ');"><i class="action-btn icon-enabled wpl_actions_btn wpl_show" title="'.__('Enabled', WPL_TEXTDOMAIN).'"></i></div>';
                    else echo '<div class="action-gal-btn" id="active_attachment_tag_' . $attachment->index . '" onclick="wpl_attachment_enabled(\'' . $attachment->item_name . '\',' . $attachment->index . ');"><i class="action-btn icon-disabled wpl_actions_btn  wpl_show" title="'.__('Disabled', WPL_TEXTDOMAIN).'"></i></div>';
                    ?>

                    <input type="hidden" id="enabled_attachment_field_<?php echo $attachment->index; ?>" value="<?php echo $attachment->enabled; ?>"/>
                </div>
            </div>
        </li>
		<?php
    }
    ?>
	</ul>
	<?php
	$attachment_categories_html = '';
	foreach ($attachment_categories as $att_cat)
	{
		$attachment_categories_html .= ' <option value="' . $att_cat->category_name . '">' . __($att_cat->category_name) . '</option>';
	}
	?>
</div>
<script type="text/javascript">
wplj(document).ready(function()
{
	wplj("#ajax_att_sortable").sortable(
	{
        placeholder: "wpl-sortable-placeholder",
        opacity: 0.7,
        forcePlaceholderSize: true,
        cursor: "move",
        axis: "y",
		stop: function (event, ui)
		{
			sort_str = "";
			wplj("#ajax_att_sortable .att_name").each(function (ind, elm)
			{
				sort_str += elm.value + ",";
			});

			wplj.post("<?php echo wpl_global::get_full_url(); ?>", "&wpl_format=b:listing:attachments&wpl_function=sort_attachments&pid=<?php echo $item_id; ?>&order="+sort_str+"&kind=<?php echo $this->kind; ?>", function (data) {});
		}
	});
});

var att_counter = parseInt(<?php echo $max_index_att ?>) + 1;
wplj(document).ready(function()
{
	var url = '<?php echo wpl_global::get_full_url(); ?>&wpl_format=b:listing:attachments&wpl_function=upload&pid='+<?php echo $item_id; ?>+'&kind=<?php echo $this->kind; ?>&type=attachment';

    wplj('#attachment_upload').fileupload(
    {
        url: url,
        acceptFileTypes: /(<?php echo $ext_str; ?>)$/i,
        dataType: 'json',
        maxFileSize:<?php echo $max_size * 1000; ?>,
        done: function (e, data)
        {
            wplj(data.result.files).each(function (index, file)
            {
                if (file.error !== undefined)
                {
                    wplj('<p/>').text(file.error).appendTo('#attaches');
                }
                else
                {

                    var hbSource   = wplj("#dbst-wizard-attachment").html();
                    var hbTemplate = Handlebars.compile(hbSource);
                    var hbHTML     = hbTemplate({
                        att_counter: att_counter,
                        fileName: file.name,
                        enabled_title: "<?php echo addslashes(__('Enabled', WPL_TEXTDOMAIN)); ?>",
                        subFileName: file.name.substr((file.name.lastIndexOf('.') + 1)),
                        lblTitle: "<?php echo addslashes(__('Attachment Title', WPL_TEXTDOMAIN)); ?>",
                        lblDesc: "<?php echo addslashes(__('Attachment Description', WPL_TEXTDOMAIN)); ?>",
                        lblCat: "<?php echo addslashes(__('Attachment Category', WPL_TEXTDOMAIN)); ?>",
                        attachCat: "<?php echo addslashes($attachment_categories_html); ?>"
                    });

                    wplj(hbHTML).hide().appendTo('#ajax_att_sortable').slideDown();

                    att_counter++;
                }

                rta.internal.initChosen();

            }).promise().done(function () {

                wplj('#progress_att').hide();

            });
        },
        progressall: function (e, data)
        {
            wplj("#progress_att").show('fast');
            var progress = parseInt(data.loaded / data.total * 100, 10);

            wplj('#progress_att #progress .bar').css
            (
                'width',
                progress + '%'
            );

            wplj("#error_ajax_att").html("");
            wplj("#error_ajax_att").hide('slow');
        },
        processfail: function (e, data)
        {
            wplj("#progress_att").hide('slow');
            wplj("#error_ajax_att").html("<span color='red'><?php echo __('Error occured', WPL_TEXTDOMAIN) ?> : " + data.files[data.index].name + " " + data.files[data.index].error + "</span>");
            wplj("#error_ajax_att").show('slow');
        }
    });
});

function ajax_attachment_title_update(attachment, value)
{
	ajax = wpl_run_ajax_query("<?php echo wpl_global::get_full_url(); ?>", "wpl_format=b:listing:attachments&wpl_function=title_update&pid=<?php echo $item_id; ?>&attachment="+attachment+"&value="+value+"&kind=<?php echo $this->kind; ?>");
}

function ajax_attachment_desc_update(attachment, value)
{
	ajax = wpl_run_ajax_query("<?php echo wpl_global::get_full_url(); ?>", "wpl_format=b:listing:attachments&wpl_function=desc_update&pid=<?php echo $item_id; ?>&attachment="+attachment+"&value="+value+"&kind=<?php echo $this->kind; ?>");
}

function ajax_attachment_cat_update(attachment, value)
{
	ajax = wpl_run_ajax_query("<?php echo wpl_global::get_full_url(); ?>", "wpl_format=b:listing:attachments&wpl_function=cat_update&pid=<?php echo $item_id ?>&attachment="+attachment+"&value="+value+"&kind=<?php echo $this->kind; ?>");
}

function ajax_attachment_delete(attachment, id)
{
	if(confirm('<?php _e('Are you sure?', WPL_TEXTDOMAIN) ?>'))
	{
		ajax = wpl_run_ajax_query("<?php echo wpl_global::get_full_url(); ?>", "wpl_format=b:listing:attachments&wpl_function=delete_attachment&pid=<?php echo $item_id; ?>&attachment="+encodeURIComponent(attachment)+"&kind=<?php echo $this->kind; ?>");
		ajax.success(function (data)
		{
			wplj("#" + id).slideUp(function(){
                wplj(this).remove();
            });
		});
	}
}

function wpl_attachment_enabled(attachment, id)
{
	var status = Math.abs(wplj("#enabled_attachment_field_" + id).val() - 1);
	wplj("#enabled_attachment_field_" + id).val(status);
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', "wpl_format=b:listing:attachments&wpl_function=change_status&pid=<?php echo $item_id; ?>&attachment="+encodeURIComponent(attachment)+"&enabled="+status+"&kind=<?php echo $this->kind; ?>");

	ajax.success(function (data)
	{
		if (status == 0) wplj("#active_attachment_tag_" + id).html('<i class="action-btn icon-disabled wpl_actions_btn wpl_show" title="<?php echo addslashes(__('Disabled', WPL_TEXTDOMAIN)); ?>"></i>');
		else wplj("#active_attachment_tag_" + id).html('<i class="action-btn icon-enabled wpl_actions_btn wpl_show" title="<?php echo addslashes(__('Enabled', WPL_TEXTDOMAIN)); ?>"></i>');
	});
}
</script>
<?php
    $done_this = true;
}