<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

$this->_wpl_import($this->tpl_path . '.scripts.js');
$this->_wpl_import($this->tpl_path . '.scripts.css');

$my_profile_top_activities = count(wpl_activity::get_activities('my_profile_top', 1));
$my_profile_bottom_activities = count(wpl_activity::get_activities('my_profile_bottom', 1));
?>
<div class="wrap wpl-wp profile-wp <?php echo wpl_request::getVar('wpl_dashboard', 0) ? '' : 'wpl_view_container'; ?>">
    <header>
        <div id="icon-profile" class="icon48"></div>
        <h2><?php echo __('Profile', WPL_TEXTDOMAIN); ?></h2>
    </header>
    <div class="wpl_user_profile"><div class="wpl_show_message"></div></div>
    
    <?php if($my_profile_top_activities): ?>
    <div id="my_profile_top_container">
        <?php
            $activities = wpl_activity::get_activities('my_profile_top', 1);
            foreach($activities as $activity)
            {
                $content = wpl_activity::render_activity($activity, array('user_data'=>$this->user_data));
                if(trim($content) == '') continue;
        ?>
        <div class="panel-wp margin-top-1p">
            <?php if($activity->show_title and trim($activity->title) != ''): ?>
            <h3><?php echo __($activity->title, WPL_TEXTDOMAIN); ?></h3>
            <?php endif; ?>
            <div class="panel-body"><?php echo $content; ?></div>
        </div>
        <?php
            }
        ?>
    </div>
    <?php endif; ?>
    
    <div class="panel-wp margin-top-1p">
        <h3><?php echo __('Profile', WPL_TEXTDOMAIN); ?></h3>
        <div class="panel-body">
            <div class="pwizard-panel">
                <div class="pwizard-section">
                    <?php
                        $wpl_flex = new wpl_flex();
                        $wpl_flex->kind = $this->kind;
                        $wpl_flex->generate_wizard_form($this->user_fields, $this->user_data, $this->user_data['id']);
                    ?>
                </div>
                <div class="text-left finilize-btn">
                    <button class="wpl-button button-1" onclick="wpl_profile_finalize(<?php echo $this->user_data['id']; ?>);" id="wpl_profile_finalize_button" type="button" class="button button-primary"><?php echo __('Finalize', WPL_TEXTDOMAIN); ?></button>
                    <span id="wpl_profile_wizard_ajax_loader"></span>
                </div>
            </div>
        </div>
    </div>
    
    <?php if($my_profile_bottom_activities): ?>
    <div id="my_profile_bottom_container">
        <?php
            $activities = wpl_activity::get_activities('my_profile_bottom', 1);
            foreach($activities as $activity)
            {
                $content = wpl_activity::render_activity($activity, array('user_data'=>$this->user_data));
                if(trim($content) == '') continue;
        ?>
        <div class="panel-wp margin-top-1p">
            <?php if($activity->show_title and trim($activity->title) != ''): ?>
            <h3><?php echo __($activity->title, WPL_TEXTDOMAIN); ?></h3>
            <?php endif; ?>
            <div class="panel-body"><?php echo $content; ?></div>
        </div>
        <?php
            }
        ?>
    </div>
    <?php endif; ?>
    
    <footer>
        <div class="logo"></div>
    </footer>
</div>

<script type="text/javascript">
function wpl_profile_finalize(item_id)
{
	/** validate form **/
	if (!wpl_validation_check()) return;
	
	var ajax_loader_element = '#wpl_profile_wizard_ajax_loader';
	wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
	wplj("#wpl_profile_finalize_button").attr("disabled", "disabled");
	
	var request_str = 'wpl_format=b:users:ajax&wpl_function=finalize&item_id=' + item_id;

	/** run ajax query **/
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str);
	ajax.success(function(data)
	{
		wplj("#wpl_profile_finalize_button").removeAttr("disabled");
		wplj(ajax_loader_element).html('');
		
		if (data.success == 1)
		{
		}
		else if (data.success != 1)
		{
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