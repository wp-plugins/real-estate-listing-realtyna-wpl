<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">

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

function showLiBo<?php echo $this->number ?>() {
    _j.LiBo.open('#wpl_view_fields_<?php echo $this->number ?>', {
        horizontal_padding: rta.config.liBo.horizontal_padding,
        default_width: rta.config.liBo.default_width,
        default_height: rta.config.liBo.default_height,
        social_tools: '',
        markup: rta.config.liBo.tmpl.wrap.replaceAll('${sample}', rta.config.liBo.tmpl.sample),
        inline_markup: rta.config.liBo.tmpl.inline,
        inline_sample_markup: rta.config.liBo.tmpl.inlineSample,
        ajaxcallback: function () {
            var __callerID = _j('#wpl_view_fields_<?php echo $this->number ?>'),
                __specConfig = (rta.config.fancySpecificOptions.hasOwnProperty(__callerID)) ? rta.config.fancySpecificOptions[__callerID] : null;

            if (__specConfig !== null && typeof (__specConfig.afterShowMore) !== undefined) {
                for (var func in __specConfig.afterShowMore) {
                    if (_j.isFunction(__specConfig.afterShowMore[func])) {
                        __specConfig.afterShowMore[func].call();
                        rta.util.log(func + ' fucntion has been call after show fancy.');
                    }
                }
            }
        }
    });
    return false;
}

function showShortCodeInfo<?php echo $this->number ?>() {
    _j.LiBo.open('#wpl_view_shortcode_<?php echo $this->number ?>', {
        horizontal_padding: rta.config.liBo.horizontal_padding,
        default_width: rta.config.liBo.default_width,
        default_height: rta.config.liBo.default_height,
        social_tools: '',
        markup: rta.config.liBo.tmpl.wrap.replaceAll('${sample}', rta.config.liBo.tmpl.sample),
        inline_markup: rta.config.liBo.tmpl.inline,
        inline_sample_markup: rta.config.liBo.tmpl.inlineSample,
        ajaxcallback: function () {
            var __callerID = _j('#wpl_view_shortcode_<?php echo $this->number ?>'),
                __specConfig = (rta.config.fancySpecificOptions.hasOwnProperty(__callerID)) ? rta.config.fancySpecificOptions[__callerID] : null;

            if (__specConfig !== null && typeof (__specConfig.afterShowMore) !== undefined) {
                for (var func in __specConfig.afterShowMore) {
                    if (_j.isFunction(__specConfig.afterShowMore[func])) {
                        __specConfig.afterShowMore[func].call();
                        rta.util.log(func + ' fucntion has been call after show fancy.');
                    }
                }
            }
        }
    });
    return false;
}

var wplSearchWidget<?php echo $this->number ?> = (function (codeId) {


    var dataFancyId = 'wpl_view_fields_<?php echo $this->number ?>',
        currentBlock = '#wpl_view_fields_<?php echo $this->number ?>',
        selActive = ' .active-block',
        selInactive = ' .inactive-block',
        globalBlockID = '<?php echo $this->number ?>',
        orderList = [];


    /**
     * Fields set modules initialize
     */
    var searchConfig = {
        isChanged: false,
        lastOrder: 0,
        inActiveWidth: 829.5,
        draggable: {
            cursor: 'move',
            //helper: 'clone',
            revert: 'invalid',
            delay: 200,
            zIndex: 1000000,
            refreshPositions: false,
            scroll: false,
            //snap: true,
//                snapTolerance: 100,
//                scrollSpeed: 1000,
            opacity: 0.6,
            //cursorAt: { left: 5 },
            start: function () { //hide original when showing clone
                //_j(this).transition({scale: 0});
                _j(getBlockId('active')).addClass('js-blur').find('.overlay-wp').show();
                _j(getBlockId('active')).removeClass('js-blur').find('.overlay-wp').show();
            },
            stop: function () { //show original when hiding clone
                _j(this).transition({scale: 1});
                _j(getBlockId('active')).find('.overlay-wp').hide();

            }
        },
        templates: {
            move: '<div class="field-btn action-btn icon-move"></div>',
            delete: '<div class="field-btn action-btn icon-disabled"></div>'
        }
    };


    /**
     * Fancybox Specific Configurations
     */

    rta.config.fancySpecificOptions['wpl_view_fields_<?php echo $this->number ?>'] = {
        afterShowMore: {
            'initCheck': function () {
                if (_j('#wpl_view_fields_<?php echo $this->number ?>').attr('data-is-init') == false)
                    location.reload();
            },
            'sameHeight': function () {
                updateElements();
                updateSizeOfInactive();
                _j('#wpl_view_fields_<?php echo $this->number ?>' + ' .fancy-search-body > div').equalHeight();
            },
            'moveScrollbar': function () {
                _j(getBlockId('inactive')).mCustomScrollbar('scrollTo', 'left');
                _j(getBlockId('active')).mCustomScrollbar('scrollTo', 'top');
            }
        }
    };

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

    function rgb2hex(rgb) {
        rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
        function hex(x) {
            return ("0" + parseInt(x).toString(16)).slice(-2);
        }

        return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
    }

// Local Functions

    function updateSizeOfInactive(justCurrent) {
        var __justCurrent = justCurrent || false,
            __block = (__justCurrent) ? getBlockId('inactive') : '#wpl_view_fields_<?php echo $this->number ?>' + selInactive,
            __inActiveBlocks = _j(__block),
            __minWidth = searchConfig.inActiveWidth;

        __inActiveBlocks.each(function () {
            var __elements = _j(this).find('.search-field-wp'),
                __blockWidth = 10 * __elements.length;

            __elements.each(function () {
                __blockWidth += _j(this).outerWidth(true);
            });

            __blockWidth = Math.max(__blockWidth, __minWidth);

            _j(this).find('.mCSB_container').css({width: __blockWidth + 'px'});
        });
    }

    function reOrderActiveElements() {
        _j('#wpl_view_fields_<?php echo $this->number ?>' + selActive).each(function () {

            var __elements = _j(this).find('.search-field-wp'),
                __wrapper = __elements.eq(0).parent();

            __elements.hide().sort(function (a, b) {
                return (+_j(a).data('field-order') > +_j(b).data('field-order')) ? 1 : 0;
            }).appendTo(__wrapper);

        });
    }

    function updateElements(justCurrent) {
        var __justCurrent = justCurrent || false;
        // Update size of Inactive elements
        updateSizeOfInactive(__justCurrent);
    }

    function getBlockId(type) {
        var tabData = _j('#wpl_view_fields_<?php echo $this->number ?>').attr('data-active-tab'),
            currentTab = (tabData) ? tabData : ':eq(0)';

        if (type === 'active') {
            return '#wpl_view_fields_<?php echo $this->number ?>' + ' ' + currentTab + selActive;
        } else if (type === 'inactive') {
            return '#wpl_view_fields_<?php echo $this->number ?>' + ' ' + currentTab + selInactive;
        }
    }

    function updateOrder(isInit) {
        var __isInit = isInit || false,
            orderList = [];


        // Update Order Panel
        _j('#wpl_view_fields_<?php echo $this->number ?> .order-list-body li').each(function (index) {
            var __self = _j(this),
                __order = (isInit) ? __self.data('field-order') || index + 1 : index + 1,
                __fieldID = __self.data('field-id'),
                __tempObj = {};

            __tempObj[__fieldID] = __order;

            orderList.push(__tempObj);

            __self.attr('data-field-order', __order).find('>i').text(__order);

        });

        if (!__isInit) {
            // Apply New Orders to Item Attributes
            for (var itemId in orderList) {
                var __id, __order;

                for (var i in orderList[itemId]) {
                    __id = i;
                    __order = orderList[itemId][i];
                }

                var __item = _j('#wpl_view_fields_<?php echo $this->number ?> .search-field-wp[data-field-id="' + __id + '"]');

                __item.attr('data-field-order', __order).find('#field_sort_' + __id).val(__order);
            }
        } else {
            _j('#wpl_view_fields_<?php echo $this->number ?> .order-list-body li').sortElements(function (a, b) {
                return _j(a).data('field-order') > _j(b).data('field-order') ? 1 : -1;
            });
        }

        reOrderActiveElements();
    }

    function updateStatus(object, status) {
        if (typeof object === undefined || typeof status === undefined)
            return false;

        object.attr({'data-status': status}).find('input[id^="field_enable_"]').val(status);

        return true;
    }

    function addOrderElement(element, reorder, orderInit) {
        var __itemId = element.attr('data-field-id'),
            __itemName = element.attr('data-field-name'),
            __itemOrder = element.attr('data-field-order') || _j('#wpl_view_fields_<?php echo $this->number ?>' + ' .order-list-body li:last').data('field-order') + 1,
            newElement = _j('<li data-field-id="' + __itemId + '" data-field-order="' + __itemOrder + '"><i>' + __itemOrder + '</i><span>' + __itemName + '</span></li>'),
            __reorder = reorder || false,
            __orderInit = orderInit || false;
        ;

        if (_j('#wpl_view_fields_<?php echo $this->number ?>' + ' #fields-order li[data-field-id="' + __itemId + '"]').length === 0) {
            newElement.hide().appendTo('#wpl_view_fields_<?php echo $this->number ?>' + ' #fields-order ul');
            newElement.fadeIn();

            // Update order list
            if (__reorder)
                updateOrder(__orderInit);
        }
    }

    function deleteOrderElement(elementID, reorder, orderInit) {
        var __reorder = reorder || false,
            __orderInit = orderInit || false;

        _j('#wpl_view_fields_<?php echo $this->number ?>' + ' #fields-order ul').find('li[data-field-id="' + elementID + '"]').fadeOut(function () {
            _j(this).remove();

            // Update order list
            if (__reorder)
                updateOrder(__orderInit);
        });
    }

    function elementChanged(value) {
        rta.config.searchModule.isChanged = value;
    }

    _j(function () {

        _j('div.widgets-sortables').bind('sortstop', function (event, ui) {
            rta.util.log('New widget added to "Main Widget Area".');
        });

        /**
         * Elements Moving Initialization
         */

            // Append the move icon to disable element

        // Append the move icon to disable element
        _j('#wpl_view_fields_<?php echo $this->number ?> .search-field-wp[data-status="disable"] h4').append(searchConfig.templates.move);
        _j('#wpl_view_fields_<?php echo $this->number ?> .search-field-wp[data-status="enable"] h4').append(searchConfig.templates.delete);

        // Move enable elements to active block
        _j('#wpl_view_fields_<?php echo $this->number ?> .search-field-wp').filter('[data-status="enable"]').each(function () {
            _j(this).appendTo(_j(this).closest('.search-body').find('.active-block'));
        });

        // Move disable elements to inactive block
        _j('#wpl_view_fields_<?php echo $this->number ?> .search-field-wp').filter('[data-status!="enable"]').each(function () {
            var __self = _j(this);
            if(!__self.hasClass("disable"))
                __self.addClass("disable").attr("data-status","disable");

            __self.appendTo(_j(this).closest('.search-body').find('.inactive-block'));
        });

        // Initialize OrderList
        if (_j('#wpl_view_fields_<?php echo $this->number ?>' + selActive + ' .search-field-wp[data-status="enable"]').length > 0) {

            _j('#wpl_view_fields_<?php echo $this->number ?>' + selActive + ' .search-field-wp[data-status="enable"]').each(function () {
                addOrderElement(_j(this));
            });
            updateOrder(true);
        }

        // Reorder Button Action
        _j(document).on('click', '#wpl_view_fields_<?php echo $this->number ?> #reorder-list', function (e) {
            e.preventDefault();
            updateOrder();
        });

        // Remove Drag Message
        _j(document).on('click', '#wpl_view_fields_<?php echo $this->number ?> .search-msg-btn', function () {
            _j('.search-msg-btn').parent().transition({opacity: 0}, function () {
                _j(this).remove();
                _j('.fancy-search-body > div').attr('style', '').equalHeight();
            });
        });

        /**
         * Disable field trigger
         */
        _j(document).on('click', '#wpl_view_fields_<?php echo $this->number ?> .icon-disabled', function (e) {
            e.preventDefault();

            var _field = _j(this).closest('.search-field-wp'),
                _fieldID = _field.attr('data-field-id'),
                inActiveBlock = getBlockId('inactive') + ' .mCSB_container';

            _field.transition({scale: 0}, function () {


                _field.find('h4 > .field-btn').removeClass('icon-disabled').addClass('icon-move');

                _field.attr({
                    'data-status': 'disable'
                }).removeClass('enable').addClass('disable');


                // Update Field Status
                updateStatus(_field, 'disable');

                _field.prependTo(inActiveBlock).show().transition({scale: 1}, 200, function () {
                    _j(getBlockId('inactive')).mCustomScrollbar('scrollTo', 'first');
                });

                // Renable Draggable Functionallity on Element
                var _fieldInactive = _j(inActiveBlock).find('div[data-field-id="' + _fieldID + '"]');
                _fieldInactive.draggable().draggable('enable').draggable('option', searchConfig.draggable);

                //// Delete order element
                deleteOrderElement(_fieldID, true);

                //// Delete order element
                updateElements(true);
            });
        });

        /**
         * Change Tab Trigger
         */
        _j(document).on('click', '#wpl_view_fields_<?php echo $this->number ?> .search-tab', function (e) {
            e.preventDefault();

            var __tabID = _j(this).attr('href');
            // Set changes on tabs
            _j('#wpl_view_fields_<?php echo $this->number ?>' + ' .search-tab').removeClass('active');
            _j(this).addClass('active');

            // Show current tab content
            _j('#wpl_view_fields_<?php echo $this->number ?>' + ' .search-body').hide();

            // Save current tabs & Show active tab content
            _j('#wpl_view_fields_<?php echo $this->number ?>').attr({'data-active-tab': __tabID}).find(__tabID).fadeIn();
        });

        // Check to Run Once
        if (_j.isNumeric('<?php echo $this->number ?>')) {

            /**
             * Once Initialize Functions
             */

            var __currentTab = 0,
                __numberOfTabs = _j('#wpl_view_fields_<?php echo $this->number ?> .search-tab').length,
                __tabs_width = 100 / __numberOfTabs;
            if (__numberOfTabs > 7) {
                __tabs_width = 100 / 7;
                _j('#wpl_view_fields_<?php echo $this->number ?> .search-tabs-wp').addClass('multi-row-tab');
                for (var i = 0; i < __numberOfTabs; i++) {
                    var __row = parseInt((i + 1) / 8);
                    _j('#wpl_view_fields_<?php echo $this->number ?>' + ' .search-tab').eq(i).addClass('row-' + __row);
                }
            }


            /**
             * Set Tab Sizes
             */
            _j('#wpl_view_fields_<?php echo $this->number ?>').attr({'data-active-tab': _j('#wpl_view_fields_<?php echo $this->number ?>').find('.search-tab').eq(__currentTab).attr('href')});
            _j('#wpl_view_fields_<?php echo $this->number ?>' + ' .search-tab').css({width: __tabs_width + '%'}).eq(__currentTab).trigger('click');

            /**
             * mCustomScrollbar Initialization
             */
            _j('#wpl_view_fields_<?php echo $this->number ?> .order-list-body').mCustomScrollbar({
                mouseWheel: true,
                scrollButtons: {
                    enable: true
                },
                advanced: {
                    updateOnContentResize: true
                },
                theme: "dark-thin"
            });


            _j('#wpl_view_fields_<?php echo $this->number ?>' + selActive).mCustomScrollbar({
                mouseWheel: true,
                mouseWheelPixels: 200,
                scrollInertia: 300,
                scrollButtons: {
                    //enable: true
                },
                advanced: {
                    updateOnContentResize: true,
                },
                theme: "dark-thin"
            });


            _j('#wpl_view_fields_<?php echo $this->number ?>' + selInactive).mCustomScrollbar({
                mouseWheel: true,
                mouseWheelPixels: 200,
                scrollInertia: 300,
                scrollButtons: {
                    enable: false
                },
                advanced: {
                    updateOnContentResize: true
                    //autoExpandHorizontalScroll: true
                },
                horizontalScroll: true,
                theme: "dark-thin"
                //set_width: true
            });

            //.. Add Overlay div to Active Element
            _j('.overlay-wp').each(function () {
                var __overlay = _j(this),
                    __activeElement = __overlay.siblings('.active-block');
                __activeElement.prepend(__overlay);
            });
            //.

            /**
             * Initialize Sortable List
             */
            _j('#wpl_view_fields_<?php echo $this->number ?> #fields-order ul').sortable({
                handle: 'i',
                opacity: 0.5,
                placeholder: "placeholder-item",
                //revert:    true,
                scroll: false,
                axis: "y",
                update: function (event, ui) {
                    updateOrder();
                    //_j(currentBlock + selActive).isotope('reloadItems');
                },
                change: function (event, ui) {
                    var p = ui.position.top,
                        h = ui.helper.outerHeight(true),
                        s = ui.placeholder.position().top,
                        elem = _j('#wpl_view_fields_<?php echo $this->number ?>' + ' .order-list-body .mCustomScrollBox')[0],
                        elemHeight = _j('#wpl_view_fields_<?php echo $this->number ?>' + ' .order-list-body .mCustomScrollBox').height();
                    pos = findPos(elem),
                        mouseCoordsY = event.pageY - pos[0];
                    if (mouseCoordsY < h || mouseCoordsY > elemHeight - h) {
                        _j('#wpl_view_fields_<?php echo $this->number ?>' + ' .order-list-body').mCustomScrollbar('scrollTo', p - (elemHeight / 2));
                    }
                }
            });


            _j('#wpl_view_fields_<?php echo $this->number ?> #fields-order ul').disableSelection();


            /**
             * Init draggable elements
             */
            _j('#wpl_view_fields_<?php echo $this->number ?>' + selInactive + ' .search-field-wp').draggable(searchConfig.draggable);

            /**
             * Init droppable elements
             * + ' .mCSB_container'
             */
            _j('#wpl_view_fields_<?php echo $this->number ?>' + selActive).droppable({
                hoverClass: "state-hover",
                drop: function (event, ui) {
                    _j(ui.draggable).draggable("disable");
                    ui.draggable.find('.field-btn.action-btn.icon-move').remove();
                    ui.draggable.find('h4').append(searchConfig.templates.delete);
                    ui.draggable.removeClass('disable').addClass('enable');

                    // Update Field Status
                    updateStatus(ui.draggable, 'enable');

                    _j(getBlockId('active')).mCustomScrollbar('scrollTo', 'top');

                    // currentBlock + selActive + ' .mCSB_container'
                    ui.draggable.removeAttr('style').transition({scale: 0}, 0).prependTo(_j(this).find('.mCSB_container'));

                    // Add Element to OrderList
                    addOrderElement(ui.draggable, true);

                    updateElements(true);

                    //..Move scroll to bottom
                    _j(getBlockId('active')).mCustomScrollbar('scrollTo', 'bottom');

                }
            });


            _j('#wpl_view_fields_<?php echo $this->number ?> .overlay-wp').each(function () {
                var __thisActive = _j(this).closest('.active-block');
                __thisActive.prepend(_j(this));
            });


            //// Set init attr to TRUE
            _j('#btn-search-<?php echo $this->number ?>').addClass('fancybox').attr('data-is-init', 'true');
            _j('#btn-shortcode-<?php echo $this->number ?>').addClass('fancybox').attr('data-is-init', 'true');
            _j('#btn-search-<?php echo $this->number ?>').next('.page-must-reload').remove();
            _j('#btn-shortcode-<?php echo $this->number ?>').next('.page-must-reload').remove();

        }
    });
})('<?php echo $this->number ?>');

(function($){$(function(){isWPL();})})(jQuery);
</script>