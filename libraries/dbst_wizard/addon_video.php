<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

if($type == 'addon_video' and !$done_this)
{
    _wpl_import('libraries.items');
    
    $ext_str = trim(str_replace(',', '|', $options['ext_file']), '|,; ');
    $max_size = $options['file_size'];

    $vid_embed_items = wpl_items::get_items($item_id, 'video', $this->kind, 'video_embed', 1);
    $vid_embed_count = 1;
?>
<div class="video-tabs-wp" id="video-tabs">
	<ul>
		<li class="active"><a id="embed-tab" href="#embed" onclick="video_select_tab(0); return false;"><?php echo __('Embed code', WPL_TEXTDOMAIN); ?></a></li>
		<?php if(wpl_settings::get('video_uploader')): ?>
		<li><a id="uploader-tab" href="#uploader" onclick="video_select_tab(1); return false;"><?php echo __('Video uploader', WPL_TEXTDOMAIN); ?></a></li>
		<?php endif; ?>
	</ul>
</div>
<div class="video-content-wp">
<div class="content-wp" id="embed">
	<button class="wpl-button button-1" onclick="add_embed_video();"><?php echo __('Add video', WPL_TEXTDOMAIN) ?></button>
	<?php foreach ($vid_embed_items as $vid_embed_item): ?>
		<div class="video-embed-wp" id="video-embed-<?php echo $vid_embed_count; ?>">
			<div class="row">
				<label id="title_label" for="embed_vid_title<?php echo $vid_embed_count; ?>"><?php echo __('Video title', WPL_TEXTDOMAIN); ?></label>
				<input type="text" name="embed_vid_title<?php echo $vid_embed_count; ?>" id="embed_vid_title<?php echo $vid_embed_count; ?>" value="<?php echo $vid_embed_item->item_name; ?>" onblur="video_embed_save(<?php echo $vid_embed_count; ?>);" />
			</div>
			<div class="row">
				<label id="desc_label" for="embed_vid_desc<?php echo $vid_embed_count; ?>"><?php echo __('Video description', WPL_TEXTDOMAIN); ?></label>
				<input type="text" name="embed_vid_desc<?php echo $vid_embed_count; ?>" id="embed_vid_desc<?php echo $vid_embed_count; ?>" value="<?php echo $vid_embed_item->item_extra1; ?>" onblur="video_embed_save(<?php echo $vid_embed_count; ?>);" />
			</div>
			<div class="row">
				<label id="embed_label" for="embed_vid_code<?php echo $vid_embed_count; ?>"><?php echo __('Video embed code', WPL_TEXTDOMAIN); ?></label>
				<textarea name="embed_vid_code<?php echo $vid_embed_count; ?>" rows="5" cols="50" id="embed_vid_code<?php echo $vid_embed_count; ?>" onblur="video_embed_save(<?php echo $vid_embed_count; ?>);"><?php echo $vid_embed_item->item_extra2; ?></textarea>
			</div>
			<div class="actions-wp"><a onclick="embed_video_delete('<?php echo $vid_embed_count; ?>');"><i class="action-btn icon-recycle"></i></a></div>
			<input type="hidden" id="vid_emb<?php echo $vid_embed_count; ?>" value="<?php echo $vid_embed_item->id; ?>" />
		</div>
		<?php $vid_embed_count++; endforeach; ?>
</div>
<script type="text/javascript">
var vid_embed_count = <?php echo $vid_embed_count; ?>;
function add_embed_video()
{
	var embedVideo = rta.template.bind({
		count: vid_embed_count,
		title: '<?php echo __('Video title', WPL_TEXTDOMAIN); ?>',
		desc: '<?php echo __('Video description', WPL_TEXTDOMAIN); ?>',
		embedCode: '<?php echo __('Video embed code', WPL_TEXTDOMAIN); ?>',
		item_name: '',
		item_extra1: '',
		item_extra2: '',
		id: '-1'
	}, 'add-listing-video-embed');
    
	wplj(embedVideo).appendTo('#embed');
	vid_embed_count++;
}

function video_embed_save(id)
{
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', "wpl_format=b:listing:videos&wpl_function=embed_video&pid=<?php echo $item_id; ?>&kind=<?php echo $this->kind; ?>&item_id="+wplj("#vid_emb"+id).val()+"&title="+wplj("#embed_vid_title"+id).val()+"&desc="+wplj("#embed_vid_desc"+id).val()+"&embedcode="+encodeURIComponent(wplj("#embed_vid_code"+id).val()));
	ajax.success(function (data)
    {
		if(wplj("#vid_emb" + id).val() == -1) wplj("#vid_emb" + id).val(data);
	});
}

function embed_video_delete(id)
{
	if (confirm('<?php echo __('Are you sure?', WPL_TEXTDOMAIN); ?>'))
    {
		ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', "wpl_format=b:listing:videos&wpl_function=del_embed_video&pid=<?php echo $item_id; ?>&kind=<?php echo $this->kind; ?>&item_id="+wplj("#vid_emb"+id).val());
		ajax.success(function (data)
        {
			wplj("#video-embed-" + id).fadeOut(500, function ()
            {
				wplj(this).remove();
			});
		});
	}
}

function video_select_tab(id)
{
	wplj('#video-tabs').find('li').removeClass('active').eq(id).addClass('active');
	var _this = wplj('#video-tabs').find('li:eq(' + id + ') > a');
	wplj('.video-content-wp').find('> div').hide().filter(_this.attr('href')).fadeIn(600);
}
</script>
<?php
if(wpl_settings::get('video_uploader'))
{
?>
<div class="content-wp hidden" id="uploader">
	<div class="upload-btn-wp">
		<div class="wpl-button button-1 button-upload">
			<span><?php echo __('Select Files', WPL_TEXTDOMAIN); ?></span>
			<input id="video_upload" type="file" name="files[]" multiple="multiple"/>
		</div>
		<div class="field-desc">
			<?php echo __('Please choose all videos you want. Just click on the "Select Files" button.', WPL_TEXTDOMAIN); ?>
		</div>
	</div>
	<!-- The global progress bar -->
	<div id="progress_vid">
		<div id="progress" class="progress progress-success progress-striped">
			<div class="bar"></div>
		</div>
	</div>
	<div class="error_uploaded_message" id="error_ajax_vid">
	</div>
	<!-- The container for the uploaded files -->
	<div id="video" class="video-list-wp wpl_files_container">
		<ul class="ui-sortable" id="ajax_vid_sortable">
			<?php
			// get uploaded videos and show them
			$vid_items = wpl_items::get_items($item_id, 'video', $this->kind, 'video', '');

			$vid_folder = wpl_items::get_folder($item_id, $this->kind);
			$video_categories = wpl_items::get_item_categories('addon_video', $this->kind);
			$max_index_vid = 0;

			foreach ($vid_items as $video)
			{
				$video->index = intval($video->index);
				if($max_index_vid < $video->index)
					$max_index_vid = $video->index;
				?>
				<li class="ui-state-default" id="ajax_video<?php echo $video->index; ?>">
					<input type="hidden" class="vid_name" value="<?php echo $video->item_name; ?>"/>

					<div class="image-box-wp">
						<div class="info-wp">
							<div class="row">
								<label for=""><?php echo __('Video Title', WPL_TEXTDOMAIN); ?>:</label>
								<input type="text" class="vid_title" value="<?php echo $video->item_extra1; ?>" onchange="ajax_video_title_update('<?php echo $video->item_name; ?>', this.value);" size="20" />
							</div>
							<div class="row">
								<label for=""><?php echo __('Video Description', WPL_TEXTDOMAIN); ?>:</label>
								<input type="text" class="vid_desc" value="<?php echo $video->item_extra2; ?>" onchange="ajax_video_desc_update('<?php echo $video->item_name; ?>', this.value);" size="50" />
							</div>
							<div class="row">
								<label for=""><?php echo __('Video Category', WPL_TEXTDOMAIN); ?>:</label>
								<select name="vid_cat" class="vid_cat" onchange="ajax_video_cat_update('<?php echo $video->item_name; ?>', this.value);">
									<?php
									foreach ($video_categories as $vid_cat)
									{
										echo ' <option value="' . $vid_cat->category_name . '"';
										if($video->item_cat == $vid_cat->category_name)
											echo ' selected="selected"';
										echo '>' . $vid_cat->category_name . '</option>';
									}
									?>
								</select>
							</div>
						</div>
						<div class="actions-wp">
							<div class="action-gal-btn">
								<i class="action-btn icon-move"></i>
							</div>
							<div class="action-gal-btn ajax_gallery_middle_td"
								 onclick="ajax_video_delete('<?php echo $video->item_name; ?>', 'ajax_video<?php echo $video->index; ?>');">
								<i class="action-btn icon-recycle"></i>
							</div>
							<?php
							if($video->enabled)
								echo '<div class="action-gal-btn" id="active_video_tag_' . $video->index . '" onclick="wpl_video_enabled(\'' . $video->item_name . '\',' . $video->index . ');"><i class="action-btn icon-enabled"></i></div>';
							else
								echo '<div class="action-gal-btn" id="active_video_tag_' . $video->index . '" onclick="wpl_video_enabled(\'' . $video->item_name . '\',' . $video->index . ');"><i class="action-btn icon-disabled"></i></div>';
							?>
							<input type="hidden" id="enabled_video_field_<?php echo $video->index; ?>" value="<?php echo $video->enabled; ?>" />
						</div>
					</div>
				</li>
			<?php
			}
			?>
		</ul>
		<?php
		$video_categories_html = '';
		foreach ($video_categories as $vid_cat)
		{
			$video_categories_html .= ' <option value="' . $vid_cat->category_name . '">' . __($vid_cat->category_name, WPL_TEXTDOMAIN) . '</option>';
		}
		?>
	</div>
</div>
<script type="text/javascript">
wplj(document).ready(function()
{
	wplj("#ajax_vid_sortable").sortable(
	{
		placeholder: "ui-state-highlight",
		stop: function (event, ui)
		{
			sort_str = "";
			wplj("#ajax_vid_sortable .vid_name").each(function (ind, elm)
			{
				sort_str += elm.value + ",";
			});
	
			wplj.post("<?php echo wpl_global::get_full_url(); ?>", "&wpl_format=b:listing:videos&wpl_function=sort_videos&pid=<?php echo $item_id; ?>&order="+sort_str+"&kind=<?php echo $this->kind; ?>", function (data)
			{
			});
		}
	});
});

var vid_counter = parseInt(<?php echo $max_index_vid; ?>) + 1;
wplj(document).ready(function()
{
	var url = '<?php echo wpl_global::get_full_url(); ?>&wpl_format=b:listing:videos&wpl_function=upload&pid='+<?php echo $item_id; ?>+'&kind=<?php echo $this->kind; ?>&type=video';

    wplj('#video_upload').fileupload({
        url: url,
        acceptFileTypes: /(<?php echo $ext_str; ?>)$/i,
        dataType: 'json',
        maxFileSize:<?php echo $max_size * 1000; ?>,
        done: function (e, data) {
            wplj.each(data.result.files, function (index, file) {
                if (file.error !== undefined) {
                    wplj('<p/>').text(file.error).appendTo('#video');
                }
                else {
                    wplj(rta.template.bind({
                        vid_counter: vid_counter,
                        lblTitle: '<?php echo __('Video Title', WPL_TEXTDOMAIN); ?>',
                        lblDesc: '<?php echo __('Video Description', WPL_TEXTDOMAIN); ?>',
                        lblCat: '<?php echo __('Video Category', WPL_TEXTDOMAIN); ?>',
                        name: file.name,
                        select: '<?php echo $video_categories_html; ?>'
                    }, 'dbst-wizard-videos')).appendTo('#ajax_vid_sortable');

                    vid_counter++;
                }
            });
        },
        progressall: function (e, data) {
            wplj("#progress_vid").show('fast');
            var progress = parseInt(data.loaded / data.total * 100, 10);
            wplj('#progress_vid #progress .bar').css(
                'width',
                progress + '%'
            );
            wplj("#error_ajax_vid").html("");
            wplj("#error_ajax_vid").hide('slow');
        },
        processfail: function (e, data) {
            wplj("#progress_vid").hide('slow');
            wplj("#error_ajax_vid").html("<span color='red'><?php echo __('Error occured', WPL_TEXTDOMAIN); ?> : " + data.files[data.index].name + " " + data.files[data.index].error + "</span>");
            wplj("#error_ajax_vid").show('slow');
        }
    }); // End of FileUpload
});

function ajax_video_title_update(video, value)
{
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', "wpl_format=b:listing:videos&wpl_function=title_update&pid=<?php echo $item_id; ?>&video="+video+"&value="+value+"&kind=<?php echo $this->kind; ?>");
}

function ajax_video_desc_update(video, value)
{
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', "wpl_format=b:listing:videos&wpl_function=desc_update&pid=<?php echo $item_id; ?>&video="+video+"&value="+value+"&kind=<?php echo $this->kind; ?>");
}

function ajax_video_cat_update(video, value)
{
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', "wpl_format=b:listing:videos&wpl_function=cat_update&pid=<?php echo $item_id; ?>&video="+video+"&value="+value+"&kind=<?php echo $this->kind; ?>");
}

function ajax_video_delete(video, id)
{
	if (confirm('<?php echo __('Are you sure?', WPL_TEXTDOMAIN) ?>'))
	{
		ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', "wpl_format=b:listing:videos&wpl_function=delete_video&pid=<?php echo $item_id; ?>&video="+encodeURIComponent(video)+"&kind=<?php echo $this->kind; ?>");
		ajax.success(function (data)
		{
			wplj("#" + id).fadeOut(function ()
			{
				wplj(this).remove();
			});
		});
	}
}

function wpl_video_enabled(video, id)
{
	var status = Math.abs(wplj("#enabled_video_field_" + id).val() - 1);
	wplj("#enabled_video_field_" + id).val(status);
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', "wpl_format=b:listing:videos&wpl_function=change_status&pid=<?php echo $item_id; ?>&video="+encodeURIComponent(video)+"&enabled="+status+"&kind=<?php echo $this->kind; ?>");
	ajax.success(function (data)
    {
		if (status == 0) wplj("#active_video_tag_" + id).html('<i class="action-btn icon-disabled"></i>');
		else wplj("#active_video_tag_" + id).html('<i class="action-btn icon-enabled"></i>');
	});
}
</script>
<?php
}
?>
</div>
<?php
    $done_this = true;
}