<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>

<style type="text/css">
#wpl_gallery_container<?php echo $this->property_id; ?>{height: <?php echo $this->image_height; ?>px;}
@media (max-width: 1024px)
{
    #wpl_gallery_container<?php echo $this->property_id; ?>{height: auto;}
}
</style>

<script type="text/javascript">

    (function($){

        $(function(){
            $('#wpl_gallery_container<?php echo $this->property_id; ?>').lightSlider(
                {
                    auto: true,
                    mode: 'fade',
                    item: 1,
                    loop: true,
                    onSliderLoad: function(el)
                    {
                        if($('#wpl_gallery_container<?php echo $this->property_id; ?>').find('.gallery_no_image').length == 0)
                        {
                            el.lightGallery(
                                {
                                    selector: '#wpl_gallery_container<?php echo $this->property_id; ?> .lslide'
                                });
                        }
                    }
                }).play();
        });

    })(jQuery);

</script>