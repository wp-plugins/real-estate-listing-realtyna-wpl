<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
try{videojs.options.flash.swf = "<?php echo wpl_global::get_wpl_asset_url('packages/video-js/video-js.swf'); ?>";}
catch(err){}
</script>