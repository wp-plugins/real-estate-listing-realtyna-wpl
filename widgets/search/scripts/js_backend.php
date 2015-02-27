<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">


// + DOM ready
wplj(function(){

    // - Bind click event to View fields button
    wplj('#btn-search-<?php echo $this->number ?>').on('click',function(evn){
        evn.preventDefault();

        var width   = Realtyna.getBrowserSizes().browser().width - 170,
            height  = Realtyna.getBrowserSizes().browser().height - 170;

        width = Math.min(Math.max(width, 900),1510);

        wplj._realtyna.lightbox.open(wplj(this), {
            clearContent: false,
            callbacks: {
                beforeOpen: function(){
                    wplj('#wpl_flex_modify_container_<?php echo $this->number ?> .wpl-widget-search-fields-wp').css({
                        maxWidth: '100%',
                        minWidth: width,
                        width   : width,
                        height  : height
                    });

                    if(Realtyna.getBrowserSizes().browser().width > 1400){
                        wplj('#wpl_flex_modify_container_<?php echo $this->number ?>').addClass('wpl-widget-search-4-col');
                    }

                },
                afterOpen : function(){
                    // Init some item in search widget
                    //wplSearchWidget<?php echo $this->number ?>.afterOpened();

                    // + Set content heights base on new sizes

                    wplj('#wpl_view_fields_<?php echo $this->number ?> .fancy-search-body > div').height(height);

                    // - Set Active block height
                    var fields = wplj('#wpl_flex_modify_container_<?php echo $this->number ?> .search-fields-wp'),
                        fieldsHeight = fields.outerHeight(),
                        contentWidth = fields.outerWidth(),
                        contentHeight = fieldsHeight - ( 39 *  wplSearchWidget<?php echo $this->number ?>.tabRows),
                        activeHeight = contentHeight - 65;

                    fields.find('.search-tab-content').css('height', contentHeight);
                    fields.find('.active-block').css('height', activeHeight);
                    fields.find('.inactive-block').css('width', contentWidth);

                    // - Set OrderList block
                    var orderList = wplj('#wpl_flex_modify_container_<?php echo $this->number ?> .order-list-wp'),
                        listHeight = orderList.outerHeight() - 39;

                    orderList.find('.order-list-body').css('height', listHeight);

                    // - Initialize scrollbars
                    wplSearchWidget<?php echo $this->number ?>.setScrolls();
                    wplSearchWidget<?php echo $this->number ?>.addDroppableOverlays();
                    wplSearchWidget<?php echo $this->number ?>.resizeInactiveBlock();


                    /*
                    wplj('#wpl_view_fields_<?php echo $this->number ?> select').chosen(rta.config.chosen);

                    wplj('#wpl_view_fields_<?php echo $this->number ?> .active-block select').on('chosen:showing_dropdown', function () {
                        wplj(wplj('#wpl_view_fields_<?php echo $this->number ?>').attr('data-active-tab')).find('.active-block').mCustomScrollbar("update");
                    });
                    */

                }
            }
        });

    });

});

var wplSearchWidget<?php echo $this->number ?> = (function ($) {


    var W = {},
        selActive = ' .active-block',
        selInactive = ' .inactive-block';


    /**
     * Fields set modules initialize
     */
    var searchConfig = {
        isChanged: false,
        lastOrder: 0,
        templates: {
            move: '<div class="field-btn action-btn icon-move"></div>',
            delete: '<div class="field-btn action-btn icon-disabled"></div>'
        }
    };

    W.afterOpened = function(){
        $('#wpl_view_fields_<?php echo $this->number ?> .fancy-search-body > div').equalHeight();
    }

    W.saveChange = function(that){
        $(that).parents('form').find('.widget-control-save').trigger('click');
    }

    W.saveAndReload = function(that){
        $(that).parents('form').find('.widget-control-save').trigger('click');
        $(that).parents('.widget.open').addClass('wpl-widget-search-must-reload-wp');


        $(document).ajaxComplete(function(){
            $('#btn-search-<?php echo $this->number ?>').off('click').on('click',function(){
                window.location.reload();
            });
        });
    }

    // + Initialize Scrolls
    W.setScrolls = function(){

        // - Actives block scroll
        $('#wpl_view_fields_<?php echo $this->number ?>' + selActive).mCustomScrollbar({
            theme: "minimal-dark",
            scrollInertia: 300,
            autoHideScrollbar: 1
        });

        // - Inactives block scroll
        jQuery('#wpl_view_fields_<?php echo $this->number ?> .wpl-inactive-block-wp').each(function(){

            var $frame  = $(this).children('.wpl-util-scrollbar-frame');
            var $wrap   = $frame.parent();

            $frame.sly({
                horizontal: 1,
                itemNav: 'basic',
                mouseDragging: 1,
                touchDragging: 1,
                releaseSwing: 1,
                startAt: 1,
                scrollSource: $frame.children('div'),
                scrollBar: $wrap.find('.wpl-util-scrollbar-scroll'),
                scrollBy: 1,
                scrollTrap: true,
                syncSpeed: 0.1,
                speed: 300,
                elasticBounds: 1,
                easing: 'easeOutExpo',
                dragHandle: 1,
                dynamicHandle: 1,
                clickBar: 1
            });

        });

        // - Orderlist scroll
        $('#wpl_view_fields_<?php echo $this->number ?> .order-list-body').mCustomScrollbar({
            theme: "minimal-dark",
            scrollInertia: 300
        });

        $(getBlockId('active')).mCustomScrollbar('scrollTo', 'top');
        $(getBlockId('inactive')).mCustomScrollbar('scrollTo', 'left');

    }

    // + Add droppable overlay to ActiveBlocks
    W.addDroppableOverlays = function(){
        var dragOverlayTmpl = '<div class="overlay-wp"><div class="overlay-text"><?php echo __('Drag Here', WPL_TEXTDOMAIN); ?></div></div>';
        $('#wpl_view_fields_<?php echo $this->number ?>' + selActive).prepend(dragOverlayTmpl);
    }

    W.resizeInactiveBlock = function(tabs){

        $('#wpl_view_fields_<?php echo $this->number ?> .inactive-block').each(function(){
            var inactive = $(this),
                frame = inactive.parent(),
                wrap = frame.parent(),
                size = 0,
                items = inactive.find('.search-field-wp');

            // Fix size issue when box is small
            inactive.css({ minWidth: 400 });

            items.each(function(){
                size += $(this).width() + 24;
            });

            if(wrap.width() > size){
                wrap.find('.wpl-util-scrollbar-scroll').fadeOut();
            }else{
                wrap.find('.wpl-util-scrollbar-scroll').fadeIn();
            }

            inactive.css({ width: 'auto', minWidth: size });

            frame.sly('reload');
        });

    }

    // Tools Functions
    function findPos(obj) {
        var curleft = curtop = 0;
        if (obj.offsetParent) {
            curleft = obj.offsetLeft
            curtop = obj.offsetTop
            while (obj = obj.offsetParent) {
                curleft += obj.offsetLeft
                curtop += obj.offsetTop
            }
        }
        return [curtop, curleft];
    }

    function getBlockId(type) {
        var currentTab = $('#wpl_view_fields_<?php echo $this->number ?>').attr('data-active-tab');

        if (type === 'active') {
            return currentTab + selActive;
        } else if (type === 'inactive') {
            return currentTab + selInactive;
        }
    }

    // + OrderList

    // - Update element ordering and apply the changes on Active box elements
    function updateOrderList(baseOnOrderList) {
        var orderOfItems = {};

        if(typeof baseOnOrderList == 'undefined')
            baseOnOrderList = false;

        // Fill list of order
        $('#wpl_view_fields_<?php echo $this->number ?> .order-list-body li').each(function (index) {
            var order   = parseInt($(this).attr('data-field-order')),
                fieldID = parseInt($(this).attr('data-field-id'));

            // - Need for change order of item
            if(!baseOnOrderList){
                orderOfItems[order] = fieldID;
            }else{
                order = index + 1;
                orderOfItems[order] = fieldID;
            }

            $(this).attr('data-field-order', order).find('i').text(order);


        });

        for(var i=1; i <= Object.keys(orderOfItems).length; i++) {

            // Update position of Order List items
            var orderItem = $('#wpl_view_fields_<?php echo $this->number ?> .order-list-body li[data-field-id='+orderOfItems[i]+']'),
                orderItemParent = orderItem.parent();
            orderItem.detach().appendTo(orderItemParent);

            // Update order value of each item
            $('#wpl_view_fields_<?php echo $this->number ?>' + selActive).find('#field_sort_' + orderOfItems[i]).val(i);

            // Apply reordering on Active boxes element
            var mainItem = $('#wpl_view_fields_<?php echo $this->number ?>' + selActive).find('.search-field-wp[data-field-id='+orderOfItems[i]+']'),
                mainItemParent = mainItem.parent();
            mainItem.detach().appendTo(mainItemParent);

        }
    }

    // - Add a new item to OrderList
    function addToOrderList(elm, isOnInit){

        isOnInit = typeof isOnInit == 'undefined'? false : isOnInit;

        var id      = elm.attr('data-field-id'),
            name    = elm.attr('data-field-name');
        var order   = isOnInit ? elm.find('#field_sort_' + id).val() :
                                $('#wpl_view_fields_<?php echo $this->number ?>' + ' .order-list-body li:last').data('field-order') + 1 || 1,
            item = $('<li data-field-id="' + id + '" data-field-order="' + order + '"><i>' + order + '</i><span>' + name + '</span></li>');

        item.hide().appendTo('#wpl_view_fields_<?php echo $this->number ?> .order-list-wp ul');

        item.fadeIn(300,function(){
            jQuery('#wpl_view_fields_<?php echo $this->number ?> .order-list-body').mCustomScrollbar('scrollTo', 'bottom');
        });

    }

    // - Remove an item from the OrderList
    function removeFromOrderList(id){
        $('#wpl_view_fields_<?php echo $this->number ?> .order-list-wp ul').find('li[data-field-id="' + id + '"]').fadeOut(function () {
            $(this).remove();

            updateOrderList(true);
        });
    }



    // + On DOM ready
    $(function () {

        // + InitChosen
        var isChosenInit<?php echo $this->number ?> = false;
        $('#wpl_view_fields_<?php echo $this->number ?>').parents('.widget').find('.widget-title h4').on('click.wpl-search-event',function(){

            if(!isChosenInit<?php echo $this->number ?>){
                $(this).parents('.widget').find('select').chosen(rta.config.chosen);
                isChosenInit<?php echo $this->number ?> = true;

                $('#wpl_view_fields_<?php echo $this->number ?>').parents('.widget').find('.widget-title h4').off('click.wpl-search-event');
            }
        });

        // + Notice

        // - Is seen it before
        var isNeedShowNotice = Realtyna.getCookie('wpl-search-widget-notice');
        if(typeof isNeedShowNotice != 'undefined' && isNeedShowNotice === '1')
            $('.search-msg-wp').hide();

        // - Close notice message
        $('#wpl_view_fields_<?php echo $this->number ?> .search-msg-btn').on('click',function(){

            // Hide all notice message and set the cookie for it
            $('.search-msg-wp').fadeOut(function(){
                $(this).hide();
                Realtyna.setCookie('wpl-search-widget-notice', '1');
            });

        });

        // + Initialize elements

        // - Append active items Active block
        $('#wpl_view_fields_<?php echo $this->number ?> .search-field-wp').filter('[data-status="enable"]').each(function () {
            $(this).appendTo($(this).closest('.search-body').find('.active-block'));
        });

        // - Append inactive items Inactive block
        $('#wpl_view_fields_<?php echo $this->number ?> .search-field-wp').filter('[data-status!="enable"]').each(function () {
            var __self = $(this);
            if(!__self.hasClass("disable"))
                __self.addClass("disable").attr("data-status","disable");

            __self.appendTo($(this).closest('.search-body').find('.inactive-block'));
        });

        // - Add icons
        $('#wpl_view_fields_<?php echo $this->number ?> .search-field-wp[data-status="disable"] h4').append(searchConfig.templates.move);
        $('#wpl_view_fields_<?php echo $this->number ?> .search-field-wp[data-status="enable"] h4').append(searchConfig.templates.delete);

        // Resize inactive block
        W.resizeInactiveBlock();

        // + Order list

        // - Initialize OrderList
        if ($('#wpl_view_fields_<?php echo $this->number ?>' + selActive + ' .search-field-wp[data-status="enable"]').length > 0) {

            $('#wpl_view_fields_<?php echo $this->number ?>' + selActive + ' .search-field-wp[data-status="enable"]').each(function () {
                addToOrderList($(this),true);
            });

            updateOrderList();
        }


        // Bind Remove button event
        function removeButtonHandler(elm, e){
            e.preventDefault();

            var _field = elm.closest('.search-field-wp');

            _field.hide()
                .attr({ 'data-status': 'disable' })
                .removeClass('enable')
                .addClass('disable');

            _field.find('h4 > .field-btn').removeClass('icon-disabled').addClass('icon-move');

            _field.find('input[id^="field_enable_"]').val('disable');

            _field.prependTo(getBlockId('inactive'));

            _field.show().draggable().draggable('enable').draggable('option', searchConfig.draggable);

            // Resize InactiveBlock width
            W.resizeInactiveBlock();

            // Remove element from OrderList
            removeFromOrderList(_field.attr('data-field-id'));

        }

        $('#wpl_view_fields_<?php echo $this->number ?> .icon-disabled').on('click.wpl-events', function(e){ removeButtonHandler($(this), e); });


        // + Tabs

        // - Calc tabs size
        var __currentTab = 0,
            __numberOfTabs = $('#wpl_view_fields_<?php echo $this->number ?> .search-tab').length,
            itemPerRow = 7,
            tabsWidth = 0,
            rows = 0;

        if (Realtyna.getBrowserSizes().browser().width > 1400)
            itemPerRow = 9;

        tabsWidth = 100 / itemPerRow;


        if(__numberOfTabs >= itemPerRow){

            $('#wpl_view_fields_<?php echo $this->number ?> .search-tabs-wp').addClass('multi-row-tab');

            var rows = 0;
            for (var i = 0; i < __numberOfTabs; i++) {
                var classes = 'row-' + rows;

                if(i == __numberOfTabs - 2)
                    classes += ' wpl-i-last-child';

                $('#wpl_view_fields_<?php echo $this->number ?>' + ' .search-tab').eq(i).addClass(classes);

                if(i === itemPerRow - 1)
                    rows++;
            }
        }

        W.tabRows = rows + 1;



        // - Tab event
        $('#wpl_view_fields_<?php echo $this->number ?> .search-tab').on('click.wpl-events',function(e){
            e.preventDefault();

            var __tabID = $(this).attr('href');
            // Set changes on tabs
            $('#wpl_view_fields_<?php echo $this->number ?>' + ' .search-tab').removeClass('active');
            $(this).addClass('active');

            // Show current tab content
            $('#wpl_view_fields_<?php echo $this->number ?>' + ' .search-body').hide();

            // Save current tabs & Show active tab content
            $('#wpl_view_fields_<?php echo $this->number ?>').attr({'data-active-tab': __tabID}).find(__tabID).fadeIn();

            // Resize InactiveBlock width
            W.resizeInactiveBlock();

        });

        // - Set Tab size and open first tab
        $('#wpl_view_fields_<?php echo $this->number ?>').attr({'data-active-tab': $('#wpl_view_fields_<?php echo $this->number ?>').find('.search-tab').eq(__currentTab).attr('href')});
        $('#wpl_view_fields_<?php echo $this->number ?>' + ' .search-tab').css({width: tabsWidth + '%'}).eq(__currentTab).trigger('click');

        //.. Add Overlay div to Active Element
        /*
        $('.overlay-wp').each(function () {
            var __overlay = $(this),
                __activeElement = __overlay.siblings('.active-block');
            __activeElement.prepend(__overlay);
        });
        */
        //.

        // - Apply Sortable on OrderList
        $('#wpl_view_fields_<?php echo $this->number ?> #fields-order ul').sortable({
            handle: 'i',
            opacity: 0.5,
            placeholder: "placeholder-item",
            scroll: false,
            axis: "y",
            update: function (event, ui) {
                updateOrderList(true);
            },
            change: function (event, ui) {
                var p = ui.position.top,
                    h = ui.helper.outerHeight(true),
                    s = ui.placeholder.position().top,
                    elem = $('#wpl_view_fields_<?php echo $this->number ?>' + ' .order-list-body .mCustomScrollBox')[0],
                    elemHeight = $('#wpl_view_fields_<?php echo $this->number ?>' + ' .order-list-body .mCustomScrollBox').height(),
                    pos = findPos(elem),
                    mouseCoordsY = event.pageY - pos[0];

                if (mouseCoordsY < h || mouseCoordsY > elemHeight - h) {
                    $('#wpl_view_fields_<?php echo $this->number ?>' + ' .order-list-body').mCustomScrollbar('scrollTo', p - (elemHeight / 2));
                }
            }
        });


        $('#wpl_view_fields_<?php echo $this->number ?> .order-list-wp ul').disableSelection();


        // - Initialize InactiveBlock for draggable
        $('#wpl_view_fields_<?php echo $this->number ?>' + selInactive + ' .search-field-wp').draggable({
            cursor: 'move',
            revert: true,
            delay: 200,
            refreshPositions: false,
            scroll: false,
            opacity: 0.6,
            start: function () {
                $(getBlockId('active')).find('.overlay-wp').fadeIn(100);
            },
            stop: function () { //show original when hiding clone
               /* $(getBlockId('active')).find('.overlay-wp').fadeOut(100);
                $(this).transition({scale: 1});*/
            }
        });

        // - Initialize ActiveBlock for droppable
        $('#wpl_view_fields_<?php echo $this->number ?>' + selActive).droppable({
            hoverClass: "wpl-search-onhover",
            drop: function (event, ui) {

                // Hide overlay
                $(getBlockId('active')).find('.overlay-wp').fadeOut(400);

                var item = ui.draggable;

                // - Move scroll to bottom
                $(getBlockId('active')).mCustomScrollbar('scrollTo', 'bottom');

                item.draggable("disable");

                item.hide().removeAttr('style');

                // Remove move icon and add Delete icon and bind necessary events
                item.find('.action-btn').removeClass('icon-move').addClass('icon-disabled')
                    .off('click.wpl-events').on('click.wpl-events',function(e){ removeButtonHandler($(this), e); });

                // Add
                //item.find('h4').append(searchConfig.templates.delete);
                //item.find('h4 .action-btn').on('click', function(e){ removeButtonHandler($(this), e); });

                // Change item status
                item.removeClass('disable').addClass('enable');
                item.attr({'data-status': 'enable'}).find('input[id^="field_enable_"]').val('enable');

                // Append it to ActiveBlock
                item.fadeIn().appendTo(getBlockId('active') + ' .mCSB_container');

                //item.transition({scale:1});

                W.resizeInactiveBlock();

                // Add Element to OrderList
                addToOrderList(item);

                updateOrderList(true);

                $(getBlockId('active')).mCustomScrollbar('scrollTo', 'bottom');

            }
        });

        // - Add overlay to ActiveBlock
        $('#wpl_view_fields_<?php echo $this->number ?> .overlay-wp').each(function () {
            var __thisActive = $(this).closest('.active-block');
            __thisActive.prepend($(this));
        });


        //// Set init attr to TRUE
        $('#btn-search-<?php echo $this->number ?>').addClass('fancybox').attr('data-is-init', 'true');
        $('#btn-shortcode-<?php echo $this->number ?>').addClass('fancybox').attr('data-is-init', 'true');
        $('#wpl-js-page-must-reload-<?php echo $this->number ?>').hide();

    });

    return W;
})(jQuery);

// + Search select field
function selectChange(element, type) {
    var __self = element;

    switch (type) {
        case 'property_types':

            if (__self.value == 'predefined' || __self.value == 'select-predefined')
                wplj(__self).parent().siblings('.wpl_extoptions_span').slideDown(400);
            else
                wplj(__self).parent().siblings('.wpl_extoptions_span').slideUp(400);

            if (__self.value == 'predefined')
                wplj(__self).siblings('.wpl_extoptions_span').children('select').removeAttr('multiple');

            if (__self.value == 'select-predefined')
                wplj(__self).parent().siblings('.wpl_extoptions_span').children('select').attr('multiple', 'multiple');

            break;

        case 'listings':

            if (__self.value == 'predefined' || __self.value == 'select-predefined')
                wplj(__self).siblings('.wpl_extoptions_span').slideDown(400);
            else
                wplj(__self).parent().siblings('.wpl_extoptions_span').slideUp(400);

            if (this.value == 'predefined')
                wplj(__self).parent().siblings('.wpl_extoptions_span').children('select').removeAttr('multiple');

            if (this.value == 'select-predefined')
                wplj(__self).parent().siblings('.wpl_extoptions_span').children('select').attr('multiple', 'multiple');

            break;

        case 'number':
            if (__self.value != 'text' && __self.value != 'exacttext')
                wplj(__self).parent().siblings('.wpl_extoptions_span').slideDown(400);
            else
                wplj(__self).parent().siblings('.wpl_extoptions_span').slideUp(400);

            break;

        case 'locations':
            if (__self.value != 'simple')
                wplj(__self).parent().siblings('.wpl_extoptions_span').slideDown(400);
            else
                wplj(__self).parent().siblings('.wpl_extoptions_span').slideUp(400);

            break;

        case 'select':

            if (__self.value == 'predefined')
                wplj(__self).parent().siblings('.wpl_extoptions_span').slideDown(400);
            else
                wplj(__self).parent().siblings('.wpl_extoptions_span').slideUp(400);

            break;
    }

}

(function($){$(function(){isWPL();})})(jQuery);

</script>