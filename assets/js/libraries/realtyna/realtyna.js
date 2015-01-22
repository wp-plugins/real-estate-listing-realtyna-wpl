/*
 @preserve Realtyna JavaScript Framework
 @Author Steve M. @ Realtyna UI Department
 @Copyright 2015 Realtyna Inc.
 */

!function ($, window, document) {
    'use strict';

    var verRealtyna = '1.0.0',
        verRPL = '0.0.0',
        verWPL = '2.0.0';

    // + Define realtyna jQuery namespace
    $._realtyna = $.fn._realtyna = {};

    $.fn.realtyna = function (plugin, opts) {
        return $.fn._realtyna[plugin]($(this), opts);
    }

    // + Define Realtyna object
    window.Realtyna = (function () {

        var _pageHashes = [],
            _quaryStrings = [],
            _rxURL = /^(?:(?:https?|ftp):\/\/)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$/i;

        return {
            version: verRealtyna,
            shouldInit: true,

            init: function () {
                Realtyna.initURLVariables();
            },

            // URL parse
            initURLVariables: function () {

                // Init hash values
                _pageHashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('#');

                // Pars quary strings
                var _temp = _pageHashes[0].split('&');
                for (var i = 0; i < _temp.length; ++i) {
                    var _pair = _temp[i].split('=');
                    _quaryStrings[_pair[0]] = _pair[1];
                }

                return true;
            },

            // Get URL hashes
            getHash: function () {
                return (typeof _pageHashes[1] != "undefined") ? _pageHashes[1] : false;
            },

            // Get URL Query Strings
            getQueryString: function (name) {
                if (_quaryStrings.hasOwnProperty(name))
                    return _quaryStrings[name];
            },

            getBrowserSizes: function () {
                var _doc = document;

                return {

                    window: {
                        height: $(window).height(),
                        width: $(window).width()
                    },

                    document: {
                        height: $(_doc).height(),
                        width: function () {

                            return Math.max(
                                _doc.body.scrollHeight, _doc.documentElement.scrollHeight,
                                _doc.body.offsetHeight, _doc.documentElement.offsetHeight,
                                _doc.body.clientHeight, _doc.documentElement.clientHeight
                            );
                        }
                    },

                    browser: function () {
                        var __size = {};

                        if (window.innerHeight) //if browser supports window.innerWidth
                        {
                            __size.height = window.innerHeight;
                            __size.width = window.innerWidth;
                        }
                        else if (_doc.all) //else if browser supports document.all (IE 4+)
                        {
                            __size.height = _doc.body.clientHeight;
                            __size.width = _doc.body.clientWidth;
                        }

                        return __size;
                    }

                }
            },

            isQuery: function (obj) {
                return obj && obj.hasOwnProperty && obj instanceof $;
            },
            isString: function (str) {
                return str && $.type(str) === "string";
            },
            isPercentage: function (str) {
                return isString(str) && str.indexOf('%') > 0;
            },
            isScrollable: function (el) {
                return (el && !(el.style.overflow && el.style.overflow === 'hidden') && ((el.clientWidth && el.scrollWidth > el.clientWidth) || (el.clientHeight && el.scrollHeight > el.clientHeight)));
            },

            // - Inline options to JSON
            options2JSON: function (str) {

                var strArray = str.split('|'),
                    myObject = {},
                    rxItem = /^[a-zA-Z]+:\w+$/i,
                    rxIsBool = /^true|false$/i;

                for (var i = 0; i < strArray.length; ++i) {

                    if (!rxItem.test(strArray[i]))
                        continue;

                    var _sp = strArray[i].split(':');

                    if ($.isNumeric(_sp[1]))
                        myObject[_sp[0]] = parseInt(_sp[1]);

                    else if (rxIsBool.test(_sp[1]))
                        myObject[_sp[0]] = /^true$/i.test(_sp[1]);
                }

                return myObject;
            },

            // - Set cookie
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

            // - Get cookie
            getCookie : function (name) {
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
            }

        };
    })();


    Realtyna.start = function () {

        return Realtyna.initializer(Realtyna);

    }


    // Call init methods
    Realtyna.initializer = function (obj) {
        var _initCalled = 0;

        if (typeof obj == "undefined")
            return false;

        // Self object init
        if (obj.hasOwnProperty('shouldInit') && obj.shouldInit && obj.hasOwnProperty('init')) {
            obj.init();
            _initCalled++;
        }


        // Sub object init
        for (var prop in obj) {

            if (obj[prop].hasOwnProperty('shouldInit') && obj[prop].shouldInit) {

                if (obj[prop].hasOwnProperty('init') && prop != 'prototype') {
                    _initCalled += Realtyna.initializer(obj[prop]);
                }

            }
            ;

        }
        ;

        return _initCalled;

    };

    // + WPL Object ////////////////////////////////////////////////////////////////////////////////////////////////

    Realtyna.wpl = (function () {

        return {
            version: verWPL,
            shouldInit: true,

            init: function () {

            },

            validate: function (obj, props) {
                for (var i = 0; i < props.length; ++i) {
                    if (!obj.hasOwnProperty(props[i]))
                        return false;
                }
                return true;
            }
        };
    })();

    // + Registers /////////////////////////////////////////////////////////////////////////////////////////////////

    // - Add registers prop
    Realtyna.regs = {};


    // + Options ///////////////////////////////////////////////////////////////////////////////////////////////////

    // - Add options prop
    Realtyna.options = {};
    Realtyna.wpl.options = {};

    // - Ajax Loader
    Realtyna.options.ajaxloader = {
        autoHide: 40, // Delay to hide all spinners in seconds

        coverTmpl: '<div class="realtyna-ajaxloader-cover"/>',
        coverStyle: {
            backgroundColor: 'rgba(0,0,0,0.4)',
            position: 'absolute',
            top: 0,
            left: 0,
            width: '100%',
            height: '100%',
            zIndex: 2e8,
            display: 'inline-block'

        }
    };

    // - [WPL] Tab options
    Realtyna.wpl.options.tabs = {
        // Class selectors
        tabSystemClass: '.wpl-js-tab-system',
        tabsClass: '.wpl-gen-tab-wp',
        tabContentsClass: '.wpl-gen-tab-contents-wp',
        tabContentClass: '.wpl-payment-content',

        tabActiveClass: 'wpl-gen-tab-active', // Class Name

        activeChildIndex: 0 // Active tab index
    };

    // - [WPL] Sidebars system
    Realtyna.wpl.options.sidebar = {

        // Selectors
        attrContainer: 'data-wpl-sidebar',
        attrTabs: 'data-wpl-sidebar-tabs',
        attrContents: 'data-wpl-sidebar-contents',

        tabPrefix: 'wpl_slide_label_id',
        contentPrefix: 'wpl_slide_container_id_',

        tabActiveClass: 'wpl-gen-side-tab-active',

        showEffect: 'animated fadeInDownSmall'

    };

    // - [WPL] Functional Class
    Realtyna.wpl.options.css = {
        utilHidden: 'wpl-util-hidden',
        utilShow: 'wpl-util-show',

        actionMove: 'wpl-icon-action-move'
    };


    // + Message Center ////////////////////////////////////////////////////////////////////////////////////////////

    // - Messages body
    Realtyna.wpl._messages = (function () {

        var _isInit = false,
            _template = null,
            _wrapper = null,
            _requireProp = ['msgBody'];

        return {
            shouldInit: true,

            init: function (tmplID) {

                if (typeof Handlebars != "undefined") {

                    var _tmplID = tmplID || '#wpl-tmpl-message',
                        _source = $(_tmplID).html();

                    _template = Handlebars.compile(_source);
                    _wrapper = $('#wpl-messages-wp');

                    _isInit = true;
                }
            },

            isInit: function () {
                return _isInit;
            },

            showMessage: function (msg) {
                if (Realtyna.wpl.validate(msg, _requireProp)) {
                    var _message = $(_template(msg));

                    _wrapper.prepend($(_message).show().addClass('animated fadeIn'));

                    return true;
                }
                return false;
            }
        };
    })();

    // - Sidebar body
    Realtyna.wpl._sidebars = (function () {
        var _sidebarsOpt = Realtyna.wpl.options.sidebar;

        return {
            shouldInit: true,

            init: function () {
                $('[' + _sidebarsOpt.attrContainer + ']').each(function (index) {

                    var _self = $(this),
                        _tabs = _self.find('[' + _sidebarsOpt.attrTabs + ']'),
                        _contents = _self.find('[' + _sidebarsOpt.attrContents + ']');

                    // Bind sidebar click event
                    _tabs.find('a').on('click', function (ev) {

                        // Break if already active
                        if ($(this).parent().hasClass(_sidebarsOpt.tabActiveClass))
                            return false;

                        // Reset layout
                        _tabs.find('a').parent().removeClass(_sidebarsOpt.tabActiveClass);
                        _contents.find('>').hide();

                        $(this).parent().addClass(_sidebarsOpt.tabActiveClass);

                        var _idPostfix = $(this).attr('href').slice(1);
                        _contents.find('#' + _sidebarsOpt.contentPrefix + _idPostfix).show().addClass(_sidebarsOpt.showEffect);

                    });

                    // Open load page
                    var _hasHashTab = false;

                    if (Realtyna.getHash())
                        _hasHashTab = (_contents.find('#' + _sidebarsOpt.contentPrefix + Realtyna.getHash()).length > 0);

                    if (_hasHashTab) {
                        _tabs.find('#' + _sidebarsOpt.tabPrefix + '_' + Realtyna.getHash()).trigger('click');
                    } else {
                        var _firstTab = (_tabs.find('a:eq(0)').attr('id') == _sidebarsOpt.tabPrefix + '10000') ?
                            _tabs.find('a:eq(1)') : _tabs.find('a:eq(0)');
                        _firstTab.trigger('click');
                    }

                });
            }

        };
    })();

    // + Global Methods ////////////////////////////////////////////////////////////////////////////////////////////

    Realtyna.libs = {};


    // + Global Methods ////////////////////////////////////////////////////////////////////////////////////////////

    // - Show Message
    Realtyna.wpl.showMessage = function (msg) {

        if (Realtyna.wpl._messages.isInit()) {
            if (msg.hasOwnProperty('type')) {

                msg.hasButton = false;

                if (msg.type == 'confirm') {

                    msg.hasButton = true;
                    msg.cssClass = ' wpl-get-message-confirm';

                } else if (msg.type == 'info') {
                    msg.cssClass = ' wpl-get-message-info';
                }

            }

            if (msg.hasOwnProperty('defaultBtn')) {
                if (msg.defaultBtn == 'yes')
                    msg.yesClass = ' wpl-gen-message-btn-default';
                else
                    msg.noClass = ' wpl-gen-message-btn-default';
            }

            if (msg.hasOwnProperty('onYes')) {

                if ($.isFunction(msg.onYes)) {

                } else if (typeof msg.onYes === "string") {

                }
            }

            if (msg.hasOwnProperty('onNo')) {

            }

            return Realtyna.wpl._messages.showMessage(msg);
        }

        return false;
    }


    // - Show ajax loader

    Realtyna.ajaxLoader = (function () {

        var _coverTmpl = Realtyna.options.ajaxloader.coverTmpl,
            _coverStyles = Realtyna.options.ajaxloader.coverStyle,
            _hideTimeout;

        return {
            version: '2.0.1',

            _autoHide: function () {
                clearTimeout(_hideTimeout);
                _hideTimeout = setTimeout(function () {
                    Realtyna.ajaxLoader.hide();
                }, Realtyna.options.ajaxloader.autoHide * 1000);
            },

            /**
             * Show Preloader on specific element
             * @param selector
             * @param param2 Size of Preloader or a plain object of all options ['normal']
             * @param param3 Position to show
             * @param param4 With cover [false]
             * @param param5 Color of loader
             * @param param6 Outer Gap size
             */
            show: function (selector, param2, param3, param4, param5, param6) {

                var _spin = null,
                    _self = Realtyna.isQuery(selector) ? selector : $(selector),
                    _size = 'normal',
                    _position = {
                        left: '50%',
                        top: '50%'
                    },
                    _withCover = false,
                    _spinColor = param5 || '#000',
                    _spinSpace = param6 || 3,
                    _tagName = _self.prop('tagName').toLowerCase();


                if (_self.length > 0 && !_self.eq(0).data('wplHasSpin')) {

                    // + Define presents

                    // - Size
                    var _defaultSizes = $.fn.spin.presets = {
                        normal: {
                            spinWidth: 21,
                            lines: 20, // The number of lines to draw
                            length: 5, // The length of each line
                            width: 2, // The line thickness
                            radius: 19, // The radius of the inner circle
                            corners: 0, // Corner roundness (0..1)
                            rotate: 0, // The rotation offset
                            direction: 1, // 1: clockwise, -1: counterclockwise
                            color: '#000', // #rgb or #rrggbb or array of colors
                            speed: 1, // Rounds per second
                            trail: 85, // Afterglow percentage
                            shadow: false, // Whether to render a shadow
                            hwaccel: true, // Whether to use hardware acceleration
                            className: 'realtyna-spin', // The CSS class to assign to the spinner
                            zIndex: 2e9, // The z-index (defaults to 2000000000)
                            top: '50%', // Top position relative to parent
                            left: '50%' // Left position relative to parent
                        },
                        tiny: {
                            spinWidth: 8,
                            lines: 17, // The number of lines to draw
                            length: 0, // The length of each line
                            width: 2, // The line thickness
                            radius: 6, // The radius of the inner circle
                            corners: 1, // Corner roundness (0..1)
                            rotate: 0, // The rotation offset
                            direction: 1, // 1: clockwise, -1: counterclockwise
                            color: '#000', // #rgb or #rrggbb or array of colors
                            speed: 1, // Rounds per second
                            trail: 60, // Afterglow percentage
                            shadow: false, // Whether to render a shadow
                            hwaccel: true, // Whether to use hardware acceleration
                            className: 'realtyna-spin', // The CSS class to assign to the spinner
                            zIndex: 2e9, // The z-index (defaults to 2000000000)
                            top: '50%', // Top position relative to parent
                            left: '50%' // Left position relative to parent
                        },
                        full: {
                            spinWidth: 62,
                            lines: 30, // The number of lines to draw
                            length: 12, // The length of each line
                            width: 2, // The line thickness
                            radius: 60, // The radius of the inner circle
                            corners: 1, // Corner roundness (0..1)
                            rotate: 0, // The rotation offset
                            direction: 1, // 1: clockwise, -1: counterclockwise
                            color: '#000', // #rgb or #rrggbb or array of colors
                            speed: 1, // Rounds per second
                            trail: 36, // Afterglow percentage
                            shadow: false, // Whether to render a shadow
                            hwaccel: true, // Whether to use hardware acceleration
                            className: 'realtyna-spin', // The CSS class to assign to the spinner
                            zIndex: 2e9, // The z-index (defaults to 2000000000)
                            top: '50%', // Top position relative to parent
                            left: '50%' // Left position relative to parent
                        }
                    };

                    // Size or Options
                    if (typeof param2 != "undefined" && !$.isPlainObject(param2)) {

                        // Set size from param2
                        if ($.fn.spin.presets.hasOwnProperty(param2))
                            _size = param2;

                    } else if (typeof param2 != "undefined" && $.isPlainObject(param2)) {

                    }

                    // Check is with cover
                    if (typeof param4 != "undefined") {

                        if (param4) {

                            // Not input
                            if (_tagName != "input" && _tagName != "select") {

                                var _template = $(_coverTmpl);

                                _template.css(_coverStyles);
                                _self.prepend(_template);
                            }
                        }


                    } else {
                        param4 = false;
                    }

                    // Showing position
                    if (typeof param3 != "undefined") {

                        switch (param3) {

                            case 'leftIn':
                                _position.left = _defaultSizes[_size].spinWidth + _spinSpace + 'px';
                                break;
                            case 'leftOut':
                                _position.left = -1 * (_defaultSizes[_size].spinWidth + _spinSpace) + 'px';
                                break;
                            case 'rightOut':
                                _position.left = Math.round(100 + ((_defaultSizes[_size].spinWidth + _spinSpace) * 100 / _self.width())) + '%';
                                break;
                            case 'rightIn':
                                _position.left = Math.round(100 - ((_defaultSizes[_size].spinWidth + _spinSpace) * 100 / _self.width())) + '%';
                                break;
                        }
                    }

                    var opts = $.extend({}, $.fn.spin.presets[_size], _position);
                    opts.color = _spinColor;

                    // Show spin right place in the input
                    if (_tagName == 'input') {

                        var _parent = _self.parent();

                        _parent.data({
                            wplHasSpin: true,
                            wplHasCover: param4,
                            wplCurrentPos: _parent.css('position')
                        });

                        _parent.css('position', 'relative');

                        var _inputPos = _self.position(),
                            _inputSize = {
                                width: _self.outerWidth(),
                                height: _self.height()
                            };

                        if (typeof param3 != "undefined") {

                            opts.top = _inputPos.top + (_inputSize.height / 2) + (_defaultSizes[_size].spinWidth / 2) + 'px';

                            switch (param3) {

                                case 'leftIn':
                                    opts.left = _inputPos.left + _defaultSizes[_size].spinWidth + _spinSpace + 'px';
                                    break;
                                case 'leftOut':
                                    opts.left = _inputPos.left - _defaultSizes[_size].spinWidth - _spinSpace + 'px';
                                    break;
                                case 'rightOut':
                                    opts.left = _inputPos.left + _inputSize.width + _defaultSizes[_size].spinWidth + _spinSpace + 'px';
                                    break;
                                case 'rightIn':
                                    opts.left = _inputPos.left + _inputSize.width - _defaultSizes[_size].spinWidth - _spinSpace + 'px';
                                    break;
                            }
                        }

                        _spin = _parent.spin(opts);

                    } else {
                        // Set necessary information
                        _self.data({
                            wplHasSpin: true,
                            wplHasCover: param4,
                            wplCurrentPos: _self.css('position')
                        });
                        _self.css('position', 'relative');

                        _spin = _self.spin(opts);
                    }


                    // Call autoHide for fix any performance issue
                    Realtyna.ajaxLoader._autoHide();


                    return _spin;
                }

                return false;

            },

            hide: function (elem) {

                var _spinCountHide = 0;

                if (typeof elem != "undefined") {
                    var _self = elem;

                    // Remove cover element
                    if (_self.data('wplHasCover'))
                        _self.find('.realtyna-ajaxloader-cover').remove();

                    // Remove spinner
                    _self.spin(false).removeData('wplHasSpin wplHasCover wplCurrentPos');

                    _spinCountHide++;
                } else {

                    $('.realtyna-spin').each(function (index) {
                        var _self = $(this),
                            _parent = _self.parent();

                        // Remove cover element
                        if (_parent.data('wplHasCover'))
                            _parent.find('.realtyna-ajaxloader-cover').remove();

                        _parent.spin(false).removeData('wplHasSpin wplHasCover wplCurrentPos');

                        _spinCountHide++;
                    });
                }

                clearTimeout(_hideTimeout);

                return _spinCountHide || false;
            }

        };

    })();

    // + Javascript Functions //////////////////////////////////////////////////////////////////////////////////////


    // DOM Ready ///////////////////////////////////////////////////////////////////////////////////////////////////

    $(function () {
        var initCount = Realtyna.start();

        /*Realtyna.wpl.showMessage({
         title: "My New Post2",
         msgBody: "This is my first post!",
         type: 'confirm',
         defaultBtn: 'yes'
         });

         Realtyna.wpl.showMessage({
         title: "My New Post1",
         msgBody: "This is my secodn post!",
         type: 'confirm',
         defaultBtn: 'no'
         });*/

    });

}(jQuery, window, document);