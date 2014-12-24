<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<!DOCTYPE html>
<html>
<head>
    <script type="text/javascript" src="<?php echo wpl_global::get_wp_admin_url(); ?>load-scripts.php?c=1&load[]=jquery-core,jquery-migrate&ver=<?php echo wpl_global::wp_version(); ?>"></script>
    <script type="text/javascript">
    wpl_baseUrl="<?php echo wpl_global::get_wp_site_url(); ?>";
    wpl_baseName="<?php echo WPL_BASENAME; ?>";
    </script>
    <script type="text/javascript" src="<?php echo wpl_global::get_wpl_asset_url('js/prettyJS/jquery.prettyPhoto.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo wpl_global::get_wpl_asset_url('js/libs/bower_components/malihu-custom-scrollbar-plugin-bower/jquery.mCustomScrollbar.concat.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo wpl_global::get_wpl_asset_url('js/libs/bower_components/chosen/public/chosen.jquery.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo wpl_global::get_wp_site_url().'wp-includes/js/tinymce/tiny_mce_popup.js'; ?>"></script>
    <script type="text/javascript" src="<?php echo wpl_global::get_wpl_asset_url('js/qtips/jquery.qtip.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo wpl_global::get_wpl_asset_url('js/backend.min.js'); ?>"></script>
    <link rel="stylesheet" id="wpl_backend_main_style-css" type="text/css" media="all" href="<?php echo wpl_global::get_wpl_asset_url('css/backend.css'); ?>" />
</head>
<body>
	<?php echo $this->$function(); ?>
</body>
</html>