<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$this->html_element_id = isset($params['html_element_id']) ? $params['html_element_id'] : 'wpl_file';
$this->js_function = isset($params['js_function']) ? $params['js_function'] : $this->html_element_id . '_upload';
$this->element_class = isset($params['element_class']) ? $params['element_class'] : 'wpl-button button-1';
$this->html_path_message = isset($params['html_path_message']) ? $params['html_path_message'] : '.wpl_show_message';
$this->html_ajax_loader = isset($params['html_ajax_loader']) ? $params['html_ajax_loader'] : '#wpl_file_ajax_loader';
$this->img_ajax_loader = isset($params['img_ajax_loader']) ? $params['img_ajax_loader'] : 'ajax-loader3.gif';
$this->request_str = isset($params['request_str']) ? $params['request_str'] : '';
$this->valid_extensions = (isset($params['valid_extensions']) and is_array($params['valid_extensions'])) ? $params['valid_extensions'] : array('jpg', 'gif', 'png');
$this->footer_js = isset($params['footer_js']) ? $params['footer_js'] : true;
$this->js_callback = isset($params['js_callback']) ? $params['js_callback'] : false;

/** importing js codes **/
$this->_wpl_import($this->tpl_path.'.scripts.js', true, $this->footer_js);
?>
<div class="file-upload-wp">
    <div class="wpl-button button-1 button-upload">
        <span><?php echo __('Select Your File', WPL_TEXTDOMAIN); ?></span>
        <!-- The file input field used as target for the file upload widget -->
        <input id="<?php echo $this->html_element_id; ?>" name="<?php echo $this->html_element_id; ?>" onchange="return <?php echo $this->js_function; ?>();" class="<?php echo $this->element_class; ?>" type="file" autocomplete="off" />
    </div>
</div>