/**
 * @preserve RTA Framework v.0.1.0
 * @Copyright Realtyna Inc. Co 2013
 * @Author Steve.M
 */

// Declare custom jQuery handler
var _j = wplj = jQuery.noConflict();

// Global variables
var _rta_app_dirs = {js: 'js/', libs: 'libs/'},
    _rta_baseUrl = wpl_baseUrl,
    _rta_urlAssets = 'wp-content/plugins/'+wpl_baseName+'/assets/',
    _rta_urlJs = _rta_baseUrl + _rta_urlAssets + _rta_app_dirs.js,
    _rta_urlJsLibs = _rta_baseUrl + _rta_urlAssets + _rta_app_dirs.js + ((_rta_app_dirs.js == _rta_app_dirs.libs) ? '' : _rta_app_dirs.libs),
    _rta_frontViews = {},
    _rta_backViews = {};

/**
 * Add some functions to String
 * @returns {String.prototype@call;replace}
 */


/**
 * Steve.M
 * Escape unwanted characters
 * @param {string} str
 * @returns {string}
 */
function escapeRegExp(str) {
    return str.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
}
;
// Trim String
String.prototype.trim = function () {
    return this.replace(/^\s+|\s+$/g, "");
};
// To Camel Case
String.prototype.toCamel = function () {
    return this.replace(/(\-[a-z])/g, function ($1) {
        return $1.toUpperCase().replace('-', '');
    });
};
// To Dashed from Camel Case
String.prototype.toDash = function () {
    return this.replace(/([A-Z])/g, function ($1) {
        return "-" + $1.toLowerCase();
    });
};
// To Underscore from Camel Case
String.prototype.toUnderscore = function () {
    return this.replace(/([A-Z])/g, function ($1) {
        return "_" + $1.toLowerCase();
    });
};

// Replace all string that match
String.prototype.replaceAll = function (find, replace) {
    return this.replace(new RegExp(escapeRegExp(find), 'g'), replace);
};

/**
 * Steve.M
 * Add some functions to Date
 * @returns {String}
 */
//For todays date;
Date.prototype.today = function () {
    return ((this.getDate() < 10) ? "0" : "") + this.getDate() + "/" + (((this.getMonth() + 1) < 10) ? "0" : "") + (this.getMonth() + 1) + "/" + this.getFullYear()
};
//For the time now
Date.prototype.timeNow = function () {
    return ((this.getHours() < 10) ? "0" : "") + this.getHours() + ":" + ((this.getMinutes() < 10) ? "0" : "") + this.getMinutes() + ":" + ((this.getSeconds() < 10) ? "0" : "") + this.getSeconds();
};

/**
 * Steve.M
 * Add some function to Array
 * @returns {Array.prototype.unique.a|Array.prototype.unique@call;concat}
 */
// Add unique method to concat array
Array.prototype.unique = function () {
    var a = this.concat();
    for (var i = 0; i < a.length; ++i) {
        for (var j = i + 1; j < a.length; ++j) {
            if (a[i] === a[j])
                a.splice(j--, 1);
        }
    }

    return a;
};

function isWPL(){
    _j('html').attr('data-wpl-plugin','');
}

/**
 * RTA Framework
 */
(function (window, document, $, undefined) {

    /* Unconditions functions - Start */
    function wpl_fancybox_afterShow_callback() {
    }

    /* END */


    /* RTA Plugins
     * ************************************************************************/

    /**
     * Steve.M
     * Get inline style value
     * Usage:
     $("#someElem").inlineStyle("width");
     * @param {string} prop
     * @returns {string}
     */
    $.fn.inlineStyle = function (prop) {
        var styles = this.attr("style"),
            value;
        styles && styles.split(";").forEach(function (e) {
            var style = e.split(":");
            if ($.trim(style[0]) === prop) {
                value = style[1];
            }
        });
        return value;
    };


    /**
     * jQuery.fn.sortElements
     * --------------
     * @param Function comparator:
     *   Exactly the same behaviour as [1,2,3].sort(comparator)
     *
     * @param Function getSortable
     *   A function that should return the element that is
     *   to be sorted. The comparator will run on the
     *   current collection, but you may want the actual
     *   resulting sort to occur on a parent or another
     *   associated element.
     *
     *   E.g. $('td').sortElements(comparator, function(){
     *      return this.parentNode;
     *   })
     *
     *   The <td>'s parent (<tr>) will be sorted instead
     *   of the <td> itself.
     */
    $.fn.sortElements = (function () {

        var sort = [].sort;

        return function (comparator, getSortable) {

            getSortable = getSortable || function () {
                return this;
            };

            var placements = this.map(function () {

                var sortElement = getSortable.call(this),
                    parentNode = sortElement.parentNode,
                // Since the element itself will change position, we have
                // to have some way of storing its original position in
                // the DOM. The easiest way is to have a 'flag' node:
                    nextSibling = parentNode.insertBefore(
                        document.createTextNode(''),
                        sortElement.nextSibling
                    );

                return function () {

                    if (parentNode === this) {
                        throw new Error(
                            "You can't sort elements if any one is a descendant of another."
                        );
                    }

                    // Insert before flag:
                    parentNode.insertBefore(this, nextSibling);
                    // Remove flag:
                    parentNode.removeChild(nextSibling);

                };

            });

            return sort.call(this, comparator).each(function (i) {
                placements[i].call(getSortable.call(this));
            });

        };

    })();

    /**
     * Steve.M
     * Remove all "Space" and "Break" character from string
     * @returns {jQuery Object}
     */
    $.fn.cleanWhitespace = function () {
        textNodes = this.contents().filter(
            function () {
                return (this.nodeType == 3 && !/\S/.test(this.nodeValue));
            })
            .remove();
        return this;
    };

    /**
     * Steve.M
     * Get full height - cross-browser
     * @returns {Number}
     */
    $.fn.getDocHeight = function () {
        var D = document;
        return Math.max(
            D.body.scrollHeight, D.documentElement.scrollHeight,
            D.body.offsetHeight, D.documentElement.offsetHeight,
            D.body.clientHeight, D.documentElement.clientHeight
        );
    }
    /**
     * Steve.M
     * Is element between specific elements
     * Usage:
     if($("#element").isBetween("#prev","#next"));
     $("#element").remove();
     * @param {string} prev
     * @param {string} next
     * @returns {Boolean}
     */
    $.fn.isBetween = function (prev, next) {
        if (this.prevAll(prev).length === 0)
            return false;
        if (this.nextAll(next).length === 0)
            return false;
        return true;
    }

    /**
     * Steve.M
     * Make all selected elements equal size
     * Usage:
     $('div.bests').equalHeight();
     */
    $.fn.equalHeight = function (callBack) {
        var currentTallest = 0,
            currentRowStart = 0,
            rowDivs = new Array(),
            $el,
            topPosition = 0,
            elCount = $(this).length,
            elIndex = 0;

        $(this).each(function () {


            $el = $(this);
            topPostion = $el.position().top;

            if (currentRowStart != topPostion) {

                // we just came to a new row.  Set all the heights on the completed row
                for (currentDiv = 0; currentDiv < rowDivs.length; currentDiv++) {
                    rowDivs[currentDiv].height(currentTallest);
                }

                // set the variables for the new row
                rowDivs.length = 0; // empty the array
                currentRowStart = topPostion;
                currentTallest = $el.height();
                rowDivs.push($el);

            } else {
                // another div on the current row.  Add it to the list and check if it's taller
                rowDivs.push($el);
                currentTallest = Math.max(currentTallest, $el.height());
            }
            // do the last row
            for (currentDiv = 0; currentDiv < rowDivs.length; currentDiv++) {
                rowDivs[currentDiv].height(currentTallest);
            }

            elIndex++;
            if(elIndex === elCount){
                if(typeof(callBack) !== undefined && $.isFunction(callBack)){
                    callBack.call();
                }
            }
        });
    };

    /**
     * Steve.M
     * Apply jQuery sortable plugin
     * @param {object} options
     * @param {function} update
     * @returns {undefined}
     */
    $.fn.wplSortable = function (options, dataString, postUrl, messages, update) {
        var _options = options || {},
            _dataString = dataString || '',
            _updateFunc = $.noop();
        if (!$.isFunction(update))
            _updateFunc = function (e, ui) {
                var stringDiv = "";
                wplj(this).children("tr").each(function (i) {

                    var tr = wplj(this);
                    var tr_id = tr.attr("id").split("_");
                    if (i != 0) {
                        stringDiv += ",";
                    }
                    stringDiv += tr_id[2];
                });

                wplj.ajax(
                    {
                        type: "POST",
                        url: postUrl,
                        data: _dataString + stringDiv,
                        success: function () {
                        },
                        error: function () {
                            wpl_show_messages(messages.error, '.wpl_data_structure_list .wpl_show_message', 'wpl_red_msg');
                        }
                    });
            };
        _options.update = _updateFunc;
        _options = $.extend(_options, rta.config.sortable);
        $(this).sortable(_options);
    };

    /**
     * Custome Plugins for handle fancybox missing functionality
     */
    if(!$.fancybox)
        $.fancybox = {};

    $.fn.openLiBo = function (options, callBack, cbOptions) {
        var __opts = {},
            __liBo = null;

        if ($.prettyPhoto){
            if (options && $.isPlainObject(options))
                __opts = $.extend({}, rta.config.LB, options);

            __liBo = $(this).prettyPhoto(__opts);

        }else if ($.fancybox){
            if (options && $.isPlainObject(options))
                __opts = $.extend({}, rta.config.fancybox, options);

            __liBo = $(this).fancybox(__opts);
        }


        if (typeof callBack != undefined && $.isFunction(callBack)) {
            if (typeof cbOptions != undefined)
                callBack.apply(this, cbOptions);
            else
                callBack.call();
        }

        return __liBo;
    }

    $.LiBo = {
        open: function(selector,options){
            if(!selector)
                return false;

            var __opts = options || {};
            __opts = $.extend({},rta.config.liBo,__opts);

            __optsObj = {
                horizontal_padding: __opts.horizontal_padding,
                default_width: __opts.default_width,
                default_height: __opts.default_height,
                social_tools: '',
                markup: __opts.tmpl.wrap.replaceAll('${sample}',rta.config.liBo.tmpl.sample),
                inline_markup: __opts.tmpl.inline
            };

            $.prettyPhoto.open(selector,__optsObj);
        }
    }

    $.fn.closeLiBo = $.fancybox.close = function (callBack,cbOptions) {

        if ($.prettyPhoto){
            $.prettyPhoto.close();

        }else if ($.fancybox){
            $.fancybox.close();
        }

        if (typeof callBack != undefined && $.isFunction(callBack)) {
            if (typeof cbOptions != undefined)
                callBack.apply(this, cbOptions);
            else
                callBack.call();
        }

        if(rta.config.fancybox.reloadAfterClose)
            location.reload();
    }

    $.prettyPhoto.close = function(){
        $.prettyPhoto.prototype.close.call(this);
        if(rta.config.fancybox.reloadAfterClose)
            location.reload();
    }
    /**
     * End
     */

        // RTA declaration Declarations
    rta = {
        version: '0.3.5',
        name: 'RTA',
        internal: {},
        registers: {},
        config: {},
        util: {},
        views: {},
        models: {},
        runTime: {},
        template: {}
    };

    // Config
    rta.config = {
        debug: false,
        backend: {
            pageLeftTabs: '.side-tabs-wp',
            pageLeftTabsTrigger: 'click'
        },
        JSes: {
            //// Please insure that your file name is widthout .js extention
            chosen: 'chosen/public/chosen.jquery.min',
            jqueryBridget: 'jquery-bridget/jquery.bridget',
            mCustomScrollbar: 'malihu-custom-scrollbar-plugin-bower/jquery.mCustomScrollbar.concat.min',
            transit: 'transit/jquery.transit.min',
            hoverintent: 'hoverintent/jquery.hoverIntent',
            fileUpload: 'blueimp-file-upload/js/jquery.fileupload',
            fileUploadProc: 'blueimp-file-upload/js/jquery.fileupload-process',
            fileUploadValid: 'blueimp-file-upload/js/jquery.fileupload-validate',
            ajaxFileUpload: 'ajaxfileupload.min'
        },
        defaultSelectors: {
            checkboxWrap: '.access-checkbox-wp',
            slideContainerPrefix: '#wpl_slide_container_id',
            slideLabelPrefix: '#wpl_slide_label_id',
            // Fancy selectors
            fancyWrapper: '.fancybox-wrap',
            fancyInner: '.fancybox-inner',
            fancyContent: '.fanc-content'

        },
        templates: {
            delayStart: false,
            delayTime: 500,
            leftHolder: '${',
            rightHolder: '}',
            tag: 'div',
            idAttr: 'data-id',
            fileName: 'inline.tmpl'
        },
        require: {
            //By default load any module IDs from js/lib
            baseUrl: _rta_urlJs + 'libs/bower_components/'
            /*,
             map: {
             // '*' means all modules will get 'jquery-private'
             // for their 'jquery' dependency.
             '*': {'jquery': 'jquery-private', '$': 'jquery-private'},
             // 'jquery-private' wants the real jQuery module
             // though. If this line was not here, there would
             // be an unresolvable cyclic dependency.
             'jquery-private': {'jquery': 'jquery'}
             }*/
        },
        chosen: {
            disable_search_threshold: 10
        },
        sortable: {
            handle: '.move-element',
            cursor: "move"
        },
        fancySpecificOptions: {},
        fancybox: {
            padding: 0,
            margin: 0,
            width: 800,
            height: 600,
            minWidth: 200,
            minHeight: 100,
            maxWidth: 9999,
            maxHeight: 9999,
            pixelRatio: 1, // Set to 2 for retina display support

            autoSize: false,
            autoHeight: false,
            autoWidth: false,
            autoResize: false,
            alwaysTop: false,
            fitToView: true,
            aspectRatio: false,
            topRatio: 0.5,
            leftRatio: 0.5,
            scrolling: 'no', // 'auto', 'yes' or 'no'
            wrapCSS: '',
            arrows: true,
            closeBtn: true,
            closeClick: false,
            nextClick: false,
            mouseWheel: true,
            autoPlay: false,
            playSpeed: 3000,
            preload: 3,
            modal: false,
            loop: true,
            ajax: {
                dataType: 'html',
                headers: {'X-fancyBox': true}
            },
            iframe: {
                scrolling: 'auto',
                preload: true
            },
            swf: {
                wmode: 'transparent',
                allowfullscreen: 'true',
                allowscriptaccess: 'always'
            },
            keys: {
                next: {
                    13: 'left', // enter
                    34: 'up', // page down
                    39: 'left', // right arrow
                    40: 'up'    // down arrow
                },
                prev: {
                    8: 'right', // backspace
                    33: 'down', // page up
                    37: 'right', // left arrow
                    38: 'down'    // up arrow
                },
                close: [27], // escape key
                play: [32], // space - start/stop slideshow
                toggle: [70]  // letter "f" - toggle fullscreen
            },
            direction: {
                next: 'left',
                prev: 'right'
            },
            scrollOutside: true,
            // Override some properties
            index: 0,
            type: null,
            href: null,
            content: null,
            title: null,
            // HTML templates
            tpl: {
                wrap: '<div class="fancybox-wrap" tabIndex="-1"><div class="fancybox-skin"><div class="fancybox-outer"><div class="fancybox-inner"></div></div></div></div>',
                image: '<img class="fancybox-image" src="{href}" alt="" />',
                error: '<p class="fancybox-error">The requested content cannot be loaded.<br/>Please try again later.</p>',
                closeBtn: '<a title="Close" class="fancybox-item fancybox-close" href="javascript:;"></a>',
                next: '<a title="Next" class="fancybox-nav fancybox-next" href="javascript:;"><span></span></a>',
                prev: '<a title="Previous" class="fancybox-nav fancybox-prev" href="javascript:;"><span></span></a>'
            },
            // Properties for each animation type
            // Opening fancyBox
            openEffect: 'none', // 'elastic', 'fade' or 'none'
            openSpeed: 500,
            openEasing: 'swing',
            openOpacity: false,
            openMethod: 'zoomIn',
            // Closing fancyBox
            closeEffect: 'elastic', // 'elastic', 'fade' or 'none'
            closeSpeed: 250,
            closeEasing: 'swing',
            closeOpacity: true,
            closeMethod: 'zoomOut',
            // Changing next gallery item
            nextEffect: 'elastic', // 'elastic', 'fade' or 'none'
            nextSpeed: 250,
            nextEasing: 'swing',
            nextMethod: 'changeIn',
            // Changing previous gallery item
            prevEffect: 'elastic', // 'elastic', 'fade' or 'none'
            prevSpeed: 250,
            prevEasing: 'swing',
            prevMethod: 'changeOut',
            // Enable default helpers
            helpers: {
                overlay: true,
                title: null
            },
            afterShowMore: {},
            manualResize: function (callBack) {

                var __callback = callBack || $.noop();

                $(rta.config.defaultSelectors.fancyWrapper).css({display: 'block', opacity: 0});

                setTimeout(function () {

                    var __currentHeight = $(rta.config.defaultSelectors.fancyInner).inlineStyle('height'),
                        __currentWidth = $(rta.config.defaultSelectors.fancyInner).inlineStyle('width');

                    // If box using automatic resize and not set yet do it manually
                    if (__currentHeight !== 'auto' || __currentWidth === rta.config.fancybox.minWidth + 'px') {

                        var __fancyContent = ($(rta.config.defaultSelectors.fancyContent).length > 1) ?
                                $(rta.config.defaultSelectors.fancyContent).eq($(rta.config.defaultSelectors.fancyContent).length - 1) : // If content load static get last copy as main content
                                $(rta.config.defaultSelectors.fancyContent),
                            __contentWidth = __fancyContent.outerWidth(),
                            __contentHeight = __fancyContent.outerHeight();

                        // Calculate position on screen
                        var __contentPos = {
                            left: (rta.config.defaultSize.browser.width / 2) - (__contentWidth / 2),
                            top: (rta.config.defaultSize.browser.height / 2) - (__contentHeight / 2)
                        };

                        // If __contentHeight is bigger than of (rta.config.defaultSize.browser.height / 2)
                        // set top to zero for preventing from outbound top
                        if (__contentPos.top < 0)
                            __contentPos.top = '25px';

                        if (rta.config.fancybox.alwaysTop)
                            __contentPos.top = '25px';

                        // Set fancybox size
                        $(rta.config.defaultSelectors.fancyWrapper + ',' + rta.config.defaultSelectors.fancyInner).width(__contentWidth).height('auto');

                        // Set position of fancybox
                        $(rta.config.defaultSelectors.fancyWrapper).css({
                            left: __contentPos.left,
                            top: __contentPos.top
                        });

                        // Show box
                        $(rta.config.defaultSelectors.fancyWrapper).animate({'opacity': 1});

                        rta.util.log('Fancybox size set manually by RTA.');

                        //// Call Callback function
                        __callback.call();
                    }
                }, 500);
            },
            // Callbacks
            onCancel: $.noop, // If canceling
            beforeLoad: $.noop(),
            afterLoad: $.noop(), // After loading
            beforeShow: function () {
                // Hide fancybox for apply some change to it.
                $(rta.config.defaultSelectors.fancyWrapper).hide();
            }, // Before changing in current item
            afterShow: function (e) {
                rta.config.fancybox.manualResize(function () {
                    var __callerID = $(rta.config.defaultSelectors.fancyWrapper).find('.fanc-box-wp').attr('id'),
                        __specConfig = (rta.config.fancySpecificOptions.hasOwnProperty(__callerID)) ? rta.config.fancySpecificOptions[__callerID] : null;

                    if (__specConfig !== null && typeof (__specConfig.afterShowMore) !== undefined) {
                        for (var func in __specConfig.afterShowMore) {
                            if ($.isFunction(__specConfig.afterShowMore[func])) {
                                __specConfig.afterShowMore[func].call();
                                rta.util.log(func + ' fucntion has been call after show fancy.');
                            }
                        }
                    }
                });
            }, // After opening
            beforeChange: $.noop, // Before changing gallery item
            beforeClose: $.noop, // Before closing
            reloadAfterClose: false,

            afterClose: function () { // After closing
                if (this.reloadAfterClose)
                    window.location.reload();
            }
        },
        liBo: {
            horizontal_padding: 20,
            default_width: 800,
            default_height: 120,
            tmpl:{
                sample: '<div class="pp_inline pp_details fancybox-inner">' +
                    '<div class="fanc-content size-width-1">' +
                    '<div class="fanc-body">' +
                    '<div class="fanc-row pp-sample">' +
                    '<div class="pp-ajax-loading"></div>' +
                    '<span>Please wait to load content ...</span>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>',

                inline: '<a title="Close" class="fancybox-item fancybox-close pp_close"></a>' +
                    '<div class="pp_inline pp_details fancybox-inner" id="{cID}"></div>',

                inlineSample : '<div class="fanc-content size-width-1">' +
                    '<div class="fanc-body">' +
                    '<div class="fanc-row pp-sample">' +
                    '<div class="pp-ajax-loading"></div>' +
                    '<span>Please wait to load content ...</span>' +
                    '</div>' +
                    '</div>' +
                    '</div>',

                wrap: '<div class="pp_pic_holder">' +
                    '<div class="fancybox-wrap">' +
                    '<div class="fancybox-skin">' +
                    '<div class="fancybox-outer">' +
                    '<div id="pp_full_res">${sample}</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '<div class="pp_overlay"></div>'
            }
        }
    };

    // Global Register
    rta.registers = (function () {
        var _registers = [];

        return {
            get: function (name, isLocal) {
                var isLocal = isLocal || false;

                if (rta.util.getCookie(name) && !isLocal)
                    return rta.util.getCookie(name);

                if (_registers.hasOwnProperty(name))
                    return _registers[name];

                return false;
            },
            set: function (name, value, permanent) {
                var _value = value || '',
                    _permanent = permanent || false;
                if (!name)
                    return false;
                _registers[name] = _value;

                if (_permanent)
                    rta.util.setCookie(name, _value);

                return _value;
            }
        };
    })();

    // Tools
    rta.util = (function () {

        var _pageHashes = [],
            _queryStrings = [];

        return {
            has_fancy_box: null,
            messageType: {
                error: 'error',
                warning: 'warning',
                info: 'info'
            },
            showMultiFancy: function () {
                $(document).on('click', '.multi-fancybox', function (e) {
                    e.preventDefault();
                    var __self = $(this),
                        __currentFancyID = __self.attr('data-fancy-id');
                    __currentOption = rta.config.fancybox;
                    if (rta.config.fancySpecificOptions.hasOwnProperty(__currentFancyID))
                        __currentOption.afterShowMore = rta.config.fancySpecificOptions[__currentFancyID];
                    $.fancybox.open(__self, __currentOption);
                });
            },
            showMessage: function (message, type, title, hasClose) {
                var __type = type || this.messageType.error,
                    __hasClose = hasClose || true,
                    __title = title || __type.toCamel();
                if (!message)
                    return false;

                var message = rta.template.bind({
                    type: __type,
                    title: __title,
                    message: message
                }, 'notificationTemplate');

                //$.LiBo.open();

                //$.fancybox($(message), $.extend(true, rta.config.fancybox, { wrapCSS: 'fanc-' + __type }));
            },
            /**
             * Steve.M
             * Get browser size
             * @returns {object}
             */
            getBrowserSize: function () {
                var __size = {};

                if (window.innerHeight) //if browser supports window.innerWidth
                {
                    __size.height = window.innerHeight;
                    __size.width = window.innerWidth;
                }
                else if (document.all) //else if browser supports document.all (IE 4+)
                {
                    __size.height = document.body.clientHeight;
                    __size.width = document.body.clientWidth;
                }

                return __size;
            },
            /**
             * Steve.M
             * Show log message if log function is availble
             * @param {string[]} message
             * @returns {bool}
             */
            log: function (msgs) {
                var _messages = arguments;
                if (rta.config.debug) {
                    try {
                        var _date = new Date();
                        for (var _i = 0; _i < _messages.length; ++_i) {
                            var _str_message = rta.name + ' - v.' + rta.version + ' [' + _date.today() + " - " + _date.timeNow() + '] >> ' + _messages[_i];
                            console.log(_str_message);
                        }
                        return true;
                    } catch (e) {
                        return false;
                    }
                }
                return true;
            },
            /**
             * Steve.M
             * Get cookie by name
             * @param {string} name
             * @returns {string}
             */
            getCookie: function (name) {
                var start = document.cookie.indexOf(name + "=");
                var len = start + name.length + 1;
                if ((!start) && (name != document.cookie.substring(0, name.length))) {
                    return null;
                }
                if (start == -1)
                    return null;
                var end = document.cookie.indexOf(";", len);
                if (end == -1)
                    end = document.cookie.length;
                return unescape(document.cookie.substring(len, end));
            },
            /**
             * Steve.M
             * Set a cookie
             * @param {string} name
             * @param {string} value
             * @param {date} expires
             * @param {string} path
             * @param {string} domain
             * @param {bool} secure
             * @returns {null}
             */
            setCookie: function (name, value, expires, path, domain, secure) {
                var today = new Date();
                today.setTime(today.getTime());
                if (expires) {
                    expires = expires * 1000 * 60 * 60 * 24;
                }
                var expires_date = new Date(today.getTime() + (expires));
                document.cookie = name + "=" + escape(value) +
                    ((expires) ? ";expires=" + expires_date.toGMTString() : "") +
                    ((path) ? ";path=" + path : "") +
                    ((domain) ? ";domain=" + domain : "") +
                    ((secure) ? ";secure" : "");
            },
            /**
             * Steve.M
             * Delete a cookie
             * @param {string} name
             * @param {string} path
             * @param {string} domain
             * @returns {null}
             */
            deleteCookie: function (name, path, domain) {
                if (getCookie(name))
                    document.cookie = name + "=" +
                        ((path) ? ";path=" + path : "") +
                        ((domain) ? ";domain=" + domain : "") +
                        ";expires=Thu, 01-Jan-1970 00:00:01 GMT";
            },
            /**
             * Steve.M
             * @param {string} tagType
             * @param {object} attrs
             * @returns {bool}
             */
            createElement: function (tagType, attrs) {
                //local attributes variables
                var _attrs = attrs || {};
                var _tagType = tagType || 'script'

                if (_tagType == 'script') {
                    try {

                        var _scriptEl = document.createElement('script');
                        for (atr in _attrs) {
                            _scriptEl.setAttribute(atr.toDash(), _attrs[atr]);
                        }

                        // Add element to head
                        document.head.appendChild(_scriptEl);
                        return true;
                    } catch (exception) {
                        return false;
                    }

                }
            },
            /**
             * Steve.M
             * Load script file(s) dynamiclly
             * @param {type} url
             * @param {type} callback
             * @returns {undefined}
             */
            loadScript: function (url, callback) {
                var _callback = callback || $.noop();
                if (!url)
                    return false;
                $.ajax({
                    url: url,
                    dataType: 'script',
                    success: _callback,
                    error: function (e) {
                        rta.util.log(e);
                    },
                    async: false
                });
            },
            /**
             * Howard.Rf, Steve.M
             * Populate url hashes
             * @returns {null}
             */
            populateHashesQueryStrings: function () {
                _pageHashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('#');

                // Extract querystirng variables
                var __qstringsTemp = _pageHashes[0].split('&');
                for (var iv = 0; iv < __qstringsTemp.length; ++iv) {
                    var __varPair = __qstringsTemp[iv].split('=');
                    _queryStrings[__varPair[0]] = __varPair[1];
                }
                rta.util.log('Hashes successfully populated.');
            },
            /**
             * Steve.M
             * get
             * @param {int=1} index
             * @returns {string}
             */
            getHash: function (index) {
                var _index = index || 1;
                return _pageHashes[_index];
            },
            /**
             * Steve.M
             * Currency class
             */
            currency: {
                /**
                 * Howard Rf
                 * Seperate currency value 3 digit
                 * @param {string} field_id
                 * @returns {null}
                 */
                digit_sep: function (field_id) {
                    var sep = ",";
                    var num = $("#" + field_id).val();
                    num = num.toString();
                    var dotpos = num.indexOf(".");
                    var endString = '';
                    if (dotpos != -1) {
                        endString = num.substring(dotpos);
                        num = num.substring(0, dotpos);
                    }

                    var num2 = num.replace(/,/g, "");
                    x = num2;
                    z = "";
                    for (i = x.length - 1; i >= 0; i--)
                        z += x.charAt(i);
                    // add seperators. but undo the trailing one, if there
                    z = z.replace(/(\d{3})/g, "$1" + sep);
                    if (z.slice(-sep.length) == sep)
                        z = z.slice(0, -sep.length);
                    //z.concat(endString);
                    x = "";
                    // reverse again to get back the number
                    for (i = z.length - 1; i >= 0; i--)
                        x += z.charAt(i);
                    x += endString;
                    $("#" + field_id).val(x);
                },
                getNumber: function (value) {
                    return val.replace(/,/g, '');
                }
            },
            /**
             * Steve.M
             * Checkboxes utilities
             */
            checkboxes: {
                /**
                 * Steve.M, Howard.Rf
                 * Toggle selections of all checkbox in selector
                 * @param {string} selector
                 * @returns {null}
                 */
                toggle: function (selector, checkboxSelector) {
                    var _sel = selector || rta.config.defaultSelectors.checkboxWrap,
                        _checkboxSel = checkboxSelector || 'input:checkbox';
                    $(_sel).find(_checkboxSel).each(function (ind, elm) {
                        if (elm.checked)
                            elm.checked = false;
                        else
                            elm.checked = true;
                    });
                },
                /**
                 * Steve.M, Howard.Rf
                 * Select selections of all checkbox in selector
                 * @param {string} selector
                 * @returns {null}
                 */
                selectAll: function (selector, checkboxSelector) {
                    var _sel = selector || rta.config.defaultSelectors.checkboxWrap,
                        _checkboxSel = checkboxSelector || 'input:checkbox';
                    $(_sel).find(_checkboxSel).each(function (ind, elm) {
                        elm.checked = true;
                    });
                },
                /**
                 * Steve.M, Howard.Rf
                 * Deselect selections of all checkbox in selector
                 * @param {string} selector
                 * @returns {null}
                 */
                deSelectAll: function (selector, checkboxSelector) {
                    var _sel = selector || rta.config.defaultSelectors.checkboxWrap,
                        _checkboxSel = checkboxSelector || 'input:checkbox';
                    $(_sel).find(_checkboxSel).each(function (ind, elm) {
                        elm.checked = false;
                    });
                }
            }

        };
    })();

    // Application Functions
    rta.internal = (function () {
        return {
            slides: {
                /**
                 * Steve.M
                 * select right slide base on selectors
                 * @param {string} slideId : ID of selected item
                 * @param {string} labelClass : Class of tab container
                 * @param {string} containerClass : Class of every content container
                 * @param {string} registerID : ID of register
                 * @param {string} labelPrefixID : Prefix id of labels
                 * @param {string} containerPrefixID : Prefix id of container
                 * @returns {Boolean}
                 */
                open: function (slideId, labelClass, containerClass, registerID, labelPrefixID, containerPrefixID) {

                    var _slideLabelID = labelPrefixID || rta.config.defaultSelectors.slideLabelPrefix,
                        _slideContainerID = containerPrefixID || rta.config.defaultSelectors.slideContainerPrefix,
                        _registerID = registerID || 'currentSlide',
                        _currentSlideID = rta.registers.get(_registerID);

                    if (!labelClass || !containerClass)
                        return false;
                    else if (_currentSlideID === slideId) {

                        // Check is current slide is active
                        if ($(_slideLabelID + slideId).parent().hasClass('active'))
                            return false;
                        _currentSlideID = rta.registers.set(_registerID, $(labelClass).find('li').eq(0).find('a').attr('id').slice(_slideLabelID.length - 1));
                    }

                    // Hide all containers
                    $(containerClass).hide();

                    // Remove active class to label li
                    if (!_currentSlideID)
                        $(labelClass).find('li').eq(0).removeClass("active");
                    else
                        $(_slideLabelID + _currentSlideID).parent().removeClass("active");

                    // Show new slide content and add active class to its label
                    $(_slideContainerID + slideId).fadeIn(700);
                    $(_slideLabelID + slideId).parent().addClass("active");


                    // Set currentSlide to currect id
                    rta.registers.set(_registerID, slideId);
                    return true;
                }
            },
            initChosen: function () {
                $("select[data-has-chosen],.prow select, .panel-body > select, .fanc-row > select").chosen(rta.config.chosen);
            }
        }
    })();

    // Internal Page Function manager
    rta.runTime = (function () {
        var _functionList = {},
            _isRuned = {},
            _isRunOnce = {};
        return {
            /**
             *
             * @returns {func}
             */
            getAll: function () {
                return _functionList;
            },
            get: function (id) {
                if (!id)
                    return false;
                if (_functionList.hasOwnProperty(id))
                    return _functionList[id];
            },
            /**
             * Steve.M
             * Add new function to function list and run it with delay
             * @param {function} func
             * @param {string} id
             * @param {integer} delay
             * @returns {Boolean}
             */
            add: function (func, id, runOnce, delay) {
                var __id = id || _functionList.length;
                var __runOnce = runOnce || true;
                var __delay = (typeof delay === undefined) ? -1 : delay;

                if (!func || !$.isFunction(func))
                    return false;

                if (_functionList.hasOwnProperty(__id))
                    return false;

                _functionList[__id] = func;
                _isRunOnce[__id] = __runOnce;
                _isRuned[__id] = false;

                if (__delay >= 0)
                    this.run(__id, __delay);

                return true;

            },
            /**
             *
             * @param {type} id
             * @returns {Boolean}
             */
            run: function (id, delay, params) {
                var __delay = (typeof delay === undefined) ? -1 : delay;
                if (!id)
                    return false;
                if (_functionList.hasOwnProperty(id)) {
                    if (_isRunOnce[id] && _isRuned[id])
                        return;

                    var __runTimeOut = setTimeout(function () {
                        _functionList[id].call(params);
                        _isRuned[id] = true;
                        clearTimeout(__runTimeOut);
                    }, __delay);
                }
                return true;
            },
            /**
             *
             * @returns {undefined}
             */
            runAll: function () {
                if (_functionList.length > 0)
                    for (_ifunc in _functionList) {
                        _functionList[_ifunc].call();
                        _isRuned[_ifunc] = true;
                    }
            }
        }
    })();

    rta.template = (function () {
        var _templates = {},
            _tag = rta.config.templates.tag,
            _leftH = rta.config.templates.leftHolder,
            _rightH = rta.config.templates.rightHolder,
            _idAttr = rta.config.templates.idAttr;

        return{
            bind: function (object, templateName) {
                if (!object)
                    return false;

                if (!$.isPlainObject(object) || $.isEmptyObject(object) || $.isEmptyObject(_templates))
                    return false;

                var _temName = templateName.toCamel() || 0,
                    _temTemplate;

                if (!$.isNumeric(_temName))
                    if (!_templates.hasOwnProperty(_temName))
                        return false;

                _temTemplate = _templates[_temName];

                for (var _field in object) {
                    var _strRep = _leftH + _field + _rightH;
                    _temTemplate = _temTemplate.replaceAll(_strRep, object[_field]);
                }

                // data-src attr to src
                _temTemplate = _temTemplate.replaceAll('data-src', 'src');

                rta.util.log('A template data bind.');

                return _temTemplate;

            },
            initPage: function () {
                $.get(_rta_urlJs +  rta.config.templates.fileName).done(function (data) {
                    $(data).filter(_tag).each(function () {
                        var __id = $(this).attr(_idAttr);

                        if (__id === 'undefined' || __id === false)
                            return;

                        __id = __id.toCamel();

                        _templates[__id] = $(this).html();
                        $(this).remove();
                    });
                    rta.util.log('All dynamic templates initilized.');
                    return true;
                });
            },
            init: function () {
                var __self = this;
                if (rta.config.templates.delayStart) {
                    var __timer = setTimeout(function () {
                        __self.initPage();
                        clearTimeout(__timer);
                    }, rta.config.templates.delayTime);
                }
                else {
                    __self.initPage();
                }

            }
        };
    })();

    /**
     * Steve.M
     * Load all framework dependencies
     * @returns {bool}
     */
    rta.fwLoader = function () {
        /*requirejs.config(rta.config.require);*/

        rta.util.log('Framework completely loaded.');
        return true;
    };

    /**
     * Steve.M
     * @returns {undefined}
     */
    rta.pageElementsStartupTriggers = function () {

        // FancyBox event trigger
        // rta.util.has_fancy_box = $('.fancybox').fancybox(rta.config.fancybox);

        $('.fancybox').openLiBo({
            horizontal_padding: rta.config.liBo.horizontal_padding,
            default_width: rta.config.liBo.default_width,
            default_height: rta.config.liBo.default_height,
            social_tools: '',
            markup: rta.config.liBo.tmpl.wrap.replaceAll('${sample}',rta.config.liBo.tmpl.sample),
            inline_markup: rta.config.liBo.tmpl.inline,
            inline_sample_markup: rta.config.liBo.tmpl.inlineSample,
            keyboard_shortcuts: false,
            ajaxcallback: function(){
                var __callerID = $('.fancybox-inner').attr('id'),
                    __specConfig = (rta.config.fancySpecificOptions.hasOwnProperty(__callerID)) ? rta.config.fancySpecificOptions[__callerID] : null;

                if (__specConfig !== null && typeof (__specConfig.afterShowMore) !== undefined) {
                    for (var func in __specConfig.afterShowMore) {
                        if ($.isFunction(__specConfig.afterShowMore[func])) {
                            __specConfig.afterShowMore[func].call();
                            rta.util.log(func + ' fucntion has been call after show fancy.');
                        }
                    }
                }
            }
        });

        /*$( document ).ajaxComplete(function( event,request, settings ) {
         console.log(settings);
         });*/
        //rta.util.showMultiFancy();

        // Backend left tab system
        if (rta.util.getHash())
            $(rta.config.backend.pageLeftTabs).find("a[href='#" + rta.util.getHash() + "']").trigger(rta.config.backend.pageLeftTabsTrigger);
        else {
            var __selectIndex = 0;
            if ($(rta.config.backend.pageLeftTabs).find("a:eq(0)").hasClass('tab-finalize'))
                __selectIndex = 1;
            $(rta.config.backend.pageLeftTabs).find('li:eq(' + __selectIndex + ') a').trigger(rta.config.backend.pageLeftTabsTrigger);
        }


        rta.config.defaultSize = {
            window: {
                height: $(window).height(),
                width: $(window).width()
            },
            document: {
                height: $(document).height(),
                width: $(document).getDocHeight()
            },
            browser: rta.util.getBrowserSize()
        };

        // Functional Classes
        $(".js-clear").each(function () {
            $(this).removeClass('js-clear').after('<div class="clear"></div>');
        });

        // Initialize all template in the page
        rta.template.init();


        $('.rt-same-height .panel-wp').equalHeight(function(){
            $('.rt-same-height .js-full-height').each(function(){
                var __height = $(this).find('.panel-wp').height();
                if($(this).attr('data-minuse-size'))
                    __height -= parseInt($(this).attr('data-minuse-size'));
                $(this).find('.panel-body').css('max-height',__height);
            });
        });

        $(".side-changes .panel-body,.side-announce .panel-body").mCustomScrollbar({
            mouseWheel: true,
            mouseWheelPixels: 200,
            scrollInertia: 300,
            scrollButtons: {
                //enable: true
            },
            advanced: {
                updateOnContentResize: true
            },
            theme: "dark-thin"
        });

        rta.internal.initChosen();
    };

    rta.init = function () {

        rta.util.log('RTA framework started ...');
        // Load Framework Prerequests
        rta.fwLoader();

        // Populate page hashes in  w
        rta.util.populateHashesQueryStrings();

        // Run all startup triggers on elements
        rta.pageElementsStartupTriggers();
    };

    $(function () {
        // Initialized
        rta.init();
    });

    $(window).ready(function(){

    });

})(window, document, jQuery);


/***************************** Old JS *****************************************/
var wplj;
var wpl_show_messages_cur_class;
var wpl_show_messages_html_element;
var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('#');

wplj(document).ready(function () {
    wplj.fn.wpl_help = function () {
        wplj('.wpl_help').hover(
            function () {
                wplj(this).children(".wpl_help_description").show();
            }
            ,
            function () {
                wplj(this).children(".wpl_help_description").hide();
            }
        )
    };
    wplj('.wpl_help').wpl_help();

});
/** after show default function (don't remove it) **/
function wpl_fancybox_afterShow_callback() {
}

function wpl_ajax_save(table, key, element, id, url) {
    if (!table || !key || !id || !element || !url)
        return false;
    value = element.value;
    if (!value)
        value = '';
    request_str = 'wpl_format=c:functions:ajax&wpl_function=ajax_save&table=' + table + '&key=' + key + '&value=' + value + '&id=' + id;
    /** run ajax query **/
    ajax = wpl_run_ajax_query(url, request_str);
    return ajax;
}

function wpl_show_messages(message, html_element, msg_class) {
    if (!msg_class)
        msg_class = 'wpl_gold_msg';
    if (!html_element)
        html_element = '.wpl_show_message';
    if (!message)
        return;
    wpl_show_messages_html_element = html_element;
    wplj(html_element).html(message);
    wplj(html_element).show();
    wplj(html_element).addClass(msg_class);
    if (wpl_show_messages_cur_class && wpl_show_messages_cur_class != msg_class)
        wplj(html_element).removeClass(wpl_show_messages_cur_class);
    wpl_show_messages_cur_class = msg_class;
}

function wpl_remove_message(html_element) {
    if (!html_element)
        html_element = wpl_show_messages_html_element;
    if (!wpl_show_messages_cur_class)
        return;
    wplj(html_element).removeClass(wpl_show_messages_cur_class);
    wplj(html_element).html('');
    wplj(html_element).hide();
    wpl_show_messages_cur_class = '';
}

function wpl_run_ajax_query(url, request_str, ajax_loader, data_type, ajax_type) {
    if (!data_type) data_type = "JSON";
    if (!ajax_type) ajax_type = "POST";

    ajax_result = wplj.ajax(
        {
            type: ajax_type,
            dataType: data_type,
            url: url,
            data: request_str,
            success: function (data) {
            },
            error: function (jqXHR, textStatus, errorThrown) {
                if (ajax_loader)
                    wplj(ajax_loader).html('');
            }
        });
    return ajax_result;
}

/** update query string **/
function wpl_update_qs(key, value, url) {
    if (!url)
        url = window.location.href;
    var re = new RegExp("([?|&])" + key + "=.*?(&|#|$)(.*)", "gi");
    if (re.test(url)) {
        if (value)
            return url.replace(re, '$1' + key + "=" + value + '$2$3');
        else
            return url.replace(re, '$1$3').replace(/(&|\?)$/, '');
    }
    else {
        if (value) {
            var separator = url.indexOf('?') !== -1 ? '&' : '?';
            var hash = url.split('#');
            url = hash[0] + separator + key + '=' + value;
            if (hash[1])
                url += '#' + hash[1];
            return url;
        }
        else
            return url;
    }
}

function wpl_thousand_sep(field_id) {
    var sep = ",";
    var num = wplj("#" + field_id).val();
    num = num.toString();
    var dotpos = num.indexOf(".");
    var endString = '';
    if (dotpos != -1) {
        endString = num.substring(dotpos);
        num = num.substring(0, dotpos);
    }

    var num2 = num.replace(/,/g, "");
    x = num2;
    z = "";
    for (i = x.length - 1; i >= 0; i--)
        z += x.charAt(i);
    // add seperators. but undo the trailing one, if there
    z = z.replace(/(\d{3})/g, "$1" + sep);
    if (z.slice(-sep.length) == sep)
        z = z.slice(0, -sep.length);
    //z.concat(endString);
    x = "";
    // reverse again to get back the number
    for (i = z.length - 1; i >= 0; i--)
        x += z.charAt(i);
    x += endString;
    wplj("#" + field_id).val(x);
}

function wpl_de_thousand_sep(val) {
    return val.replace(/,/g, "");
}

function wpl_alert(string) {
    alert(string);
}

function wpl_ucfirst(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}
