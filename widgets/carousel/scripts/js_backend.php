<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
    var wpl_carousel_property_ids<?php echo $this->widget_id; ?> = false;

    function random_clicked<?php echo $this->widget_id; ?>() {
        if (!wpl_carousel_property_ids<?php echo $this->widget_id; ?>)
            wpl_carousel_property_ids<?php echo $this->widget_id; ?> = wplj("#<?php echo $this->get_field_id('data_property_ids'); ?>").val();

        if (wplj('#<?php echo $this->get_field_id('data_random'); ?>').is(':checked')) {
            wpl_carousel_property_ids<?php echo $this->widget_id; ?> = wplj("#<?php echo $this->get_field_id('data_property_ids'); ?>").val();
            wplj("#<?php echo $this->get_field_id('data_property_ids'); ?>").val('');
        }
        else {
            wplj("#<?php echo $this->get_field_id('data_property_ids'); ?>").val(wpl_carousel_property_ids<?php echo $this->widget_id; ?>);
        }
    }


    wplj(function () {

        var carouselForms = wplj('.wpl_carousel_widget_backend_form');

        //region + Carousel Init

        // Remove underline _ from Layout names
        wplj('.wpl-carousel-widget-layout').find('option').each(function(){
            var text = wplj(this).text().replaceAll('_', ' ');
            wplj(this).text(text);
        });


        //endregion

        //region + Carousel Options
        function _showCarouselCorrectOptions(formObj){
            //Show related options & Hide irrelevant options
            var layoutValue = formObj.find('.wpl-carousel-widget-layout').val();
            formObj.find('.wpl-carousel-opt').not('[data-wpl-carousel-type="general"]').hide().filter('[data-wpl-carousel-type*='+ layoutValue +']').show();

            // Placeholder setter
            formObj.find('.wpl-carousel-opt[data-wpl-pl-init]').each(function () {
                var phValues = Realtyna.options2JSON(wplj(this).attr('data-wpl-pl-init'));

                if(phValues.hasOwnProperty(layoutValue)){
                    wplj(this).find('input[type=text]').attr('placeholder',phValues[layoutValue]);
                }
            });
        }

        carouselForms.each(function () {
            _showCarouselCorrectOptions(wplj(this));
        });

        carouselForms.find('.wpl-carousel-widget-layout').off('change.wpl-carousel').on('change.wpl-carousel',function(){
            _showCarouselCorrectOptions(wplj('.wpl-carousel-widget-' + wplj(this).attr('data-wpl-carousel-id')));
        });
        //endregion

    });

    (function ($) {$(function () {isWPL();})})(jQuery);
</script>