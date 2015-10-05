<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">

    (function($){

        $(function(){

            $('#bxslider_<?php echo $this->property_id.'_'.$this->activity_id; ?>').bxSlider({
                mode: 'fade',
                pause : 6000,
                auto: <?php echo (($this->autoplay) ? 'true' : 'false'); ?>,
                captions: false,
                controls: true,
                adaptiveHeight: true,
                pagerCustom: '#bx-pager-<?php echo $this->activity_id; ?>'
            });

        });

    })(jQuery);

</script>