<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'languages' and !$done_this)
{
    $languages = wpl_addon_pro::get_wpl_languages();
    $lang_options = wpl_addon_pro::get_wpl_language_options();
    $max_langs = max(wpl_global::get_setting('max_wpllangs'), count($languages));
    $wp_pages = wpl_global::get_wp_pages();
?>
<div class="prow wpl_setting_form_container wpl-setting-langs wpl_st_type<?php echo $setting_record->type; ?> wpl_st_<?php echo $setting_record->setting_name; ?>"
    id="wpl_st_<?php echo $setting_record->id; ?>">
    <div class="languages-wp">
        <div class="wpl-btns-wp">
            <button onclick="wpl_languages_save();" class="wpl-button button-1">
                <?php echo __('Save', WPL_TEXTDOMAIN); ?>
                <span class="ajax-inline-save" id="wpl_ajax_loader_<?php echo $setting_record->id; ?>"></span>
            </button>
            <div id="wpl_language_show_message" class="wpl-notify-msg"></div>
        </div>

        <div id="wpl_languages_container" class="wpl-lang-table-wp">

            <ul class="wpl-languages-labels">
                <li><?php echo __('Language Code', WPL_TEXTDOMAIN); ?></li>
                <li><?php echo __('Language Short Code', WPL_TEXTDOMAIN); ?></li>
                <li><?php echo __('Language Main page', WPL_TEXTDOMAIN); ?></li>
            </ul>

            <?php for ($i = 1; $i <= $max_langs; $i++): ?>
                <div class="wpl-language-row">
                    <label for="wpl_language_full_code<?php echo $i; ?>"><?php echo $i; ?></label>

                    <input type="text" id="wpl_language_full_code<?php echo $i; ?>"
                           name="wpllangs[<?php echo $i; ?>][full_code]" class="wpllangs wpl-langs-full"
                           placeholder="<?php echo __('en_US, fr_FR, de_DE', WPL_TEXTDOMAIN); ?>" autocomplete="off"
                           value="<?php echo(isset($lang_options[$i]) ? $lang_options[$i]['full_code'] : ''); ?>"/>

                    <input type="text" id="wpl_language_shortcode<?php echo $i; ?>"
                           name="wpllangs[<?php echo $i; ?>][shortcode]" class="wpllangs wpl-langs-short"
                           placeholder="<?php echo __('en, fr, de', WPL_TEXTDOMAIN                        ); ?>" autocomplete="off"
                    value="<?php echo(isset($lang_options[$i]) ? $lang_options[$i]['shortcode'] : ''); ?>"/>

                    <select name="wpllangs[<?php echo $i; ?>][main_page]" autocomplete="off">
                        <option value="">---</option>
                        <?php foreach($wp_pages as $wp_page): ?>
                        <option value="<?php echo $wp_page->ID; ?>" <?php if(isset($lang_options[$i]) and isset($lang_options[$i]['main_page']) and $wp_page->ID == $lang_options[$i]['main_page']) echo 'selected="selected"'; ?>><?php echo $wp_page->post_title; ?></option>
                        <?php endforeach; ?>
                    </select>

                </div>
            <?php endfor; ?>
        </div>

        <div class="wpl-languages-generate-keywords wpl-btns-wp">
            <span class="wpl-button button-2" onclick="wpl_generate_keywords();">
                <?php echo __('Generate WPL dynamic strings for using in translation plugins like WPML.', WPL_TEXTDOMAIN); ?>
            </span>
            <span id="wpl_languages_generate_keywords_ajax_loader"></span>
        </div>

    </div>
</div>

<script type="text/javascript">
function wpl_generate_keywords()
{
    var ajax_loader_element = "#wpl_languages_generate_keywords_ajax_loader";
	wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
    
    var request_str = 'wpl_format=b:settings:ajax&wpl_function=generate_language_keywords';
	
	/** run ajax query **/
	wplj.ajax(
	{
		type: "POST",
		url: '<?php echo wpl_global::get_full_url(); ?>',
		data: request_str,
        dataType: 'json',
		success: function(data)
		{
            wplj(ajax_loader_element).html('');
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			wplj(ajax_loader_element).html('');
		}
	});
}

function wpl_languages_save()
{
    wpl_remove_message();
    
    var ajax_loader_element = "#wpl_ajax_loader_<?php echo $setting_record->id; ?>";
	wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
    
    var wpllangs = '';
    var request_str = '';
    
    /** general options **/
	wplj("#wpl_languages_container input:text, #wpl_languages_container select").each(function (index, element)
	{
        var value = wplj(element).val();
        if(value == '') return;
        
		wpllangs += element.name+"="+value+"&";
	});
    
    request_str = 'wpl_format=b:settings:ajax&wpl_function=save_languages&'+wpllangs;
	
	/** run ajax query **/
	wplj.ajax(
	{
		type: "POST",
		url: '<?php echo wpl_global::get_full_url(); ?>',
		data: request_str,
        dataType: 'json',
		success: function(data)
		{
            if(data.success)
            {
                wpl_show_messages(data.message, '#wpl_language_show_message', 'wpl_green_msg');
                wplj(ajax_loader_element).html('');
            }
            else
            {
                wpl_show_messages(data.message, '#wpl_language_show_message', 'wpl_red_msg');
                wplj(ajax_loader_element).html('');
            }
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
            wpl_show_messages('<?php echo __('Error Occured.', WPL_TEXTDOMAIN); ?>', '#wpl_language_show_message', 'wpl_red_msg');
			wplj(ajax_loader_element).html('');
		}
	});
}
</script>
<?php
    $done_this = true;
}