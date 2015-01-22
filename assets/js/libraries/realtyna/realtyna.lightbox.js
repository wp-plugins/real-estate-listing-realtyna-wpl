/*
 @preserve Realtyna Lightbox Plugin
 @Author Steve M. @ Realtyna UI Department
 @Copyright 2015 Realtyna Inc.
 */


(function ($, window, document,undefined) {
    "use strict";

    $._realtyna.lightbox = { version: '1.2.9' };

    $._realtyna.lightbox = $.fn._realtyna.lightbox = function(that,options){


        var defaults = {
            // Layout options
            minWidth  : 100,
            minHeight : 100,
            maxWidth  : 9999,
            maxHeight : 9999,
            width: 800,
            height: 500,
            autoSize: true,

            showLoading: true,
            reloadPage: false,
            clearContent: true,

            loading: {
                color: '#000'
            },

            addTo: 'body',

            ajaxType    : 'POST',
            ajaxDataType: 'html',
            ajaxData    : {},

            // CSS Classes
            cssClasses: {
                lock    : 'realtyna-lightbox-lock',
                overlay : 'realtyna-lightbox-overlay',
                wrap    : 'realtyna-lightbox-wp',
                title   : 'realtyna-lightbox-title',
                content : 'realtyna-lightbox-cnt',
                close   : 'realtyna-lightbox-close-btn',
                error   : 'realtyna-lightbox-error',
                ajax    : 'realtyna-lightbox-ajax',
                placeholder: 'realtyna-lightbox-placeholder'
            },

            effects: {
                fadeIn      : 'wpl-fx-fadeIn',
                fadeOut     : 'wpl-fx-fadeOut',
                showOverlay : 'wpl-fx-fadeIn',
                showBox     : 'wpl-fx-fadeInBottom',
                hideBox     : 'wpl-fx-fadeOutBottom'
            },
            errors:{
                notFound    : "Selected content not found!",
                unexpected  : "An unexpected error happen. Please try again."
            },
            callbacks: {
                beforeOpen: null,
                afterOpen: null,
                afterShow: null,
                afterClose: null
            }

        };

        var R       = Realtyna,
            L       = this,
            self    = that,


            opts    = {},
            classes = null,
            fx      = null,

            placeholder = null,

            isOpen      = false,
            isOpened    = false,
            isLoading   = false,

            overlay = null,
            wrap    = null,
            close   = null,
            content = null,
            loader  = null,
            current = null,
            inner   = null,
            error   = null,
            href    = null,
            watcher = null,
            type    = 'inline',
            IDs     =  {
                overlay : 'realtyna-js-lightbox-overlay',
                wrap    : 'realtyna-js-lightbox-wrapper',
                content : 'realtyna-js-lightbox-content',
                closeBtn: 'realtyna-js-lightbox-close'
            };

        L._beforeOpen = [];
        L._afterOpen  = [];
        L._afterShow  = [];
        L._afterClose  = [];

        // Private Methods

        L.initialize = function (opt,setClick) {

            opts        = $.extend(true, {},defaults, opt);
            classes     = opts.cssClasses;
            fx          = opts.effects;
            placeholder = classes.placeholder;

            if(typeof setClick != 'undefined' && setClick) {

                self.off('click.realtyna-lightbox').on('click.realtyna-lightbox', function (evn) {

                    if (!isLoading) {
                        evn.preventDefault();

                        L.open($(this), evn);
                    }

                });

            }
        };

        L.open = function(obj){
            isLoading = true;

            var that = obj;

            var inlineOpts = L.getInlineOptions(that.attr('data-realtyna-lightbox-opts'));

            opts = inlineOpts ? $.extend({}, opts, inlineOpts) : opts;

            _buildLayout(function (){

                type = _getType(that);

                switch (type){
                    case 'inline':
                        _renderInline(that);
                        break;
                    case 'ajax':
                        _renderURL(that);
                        break;
                    default:
                        return false;
                }

            });
        }

        L.trigger = function (name) {
            var callbacks = opts.callbacks,
                evProp    = L['_' + name];

            // Add option callbacks to the array
            if(callbacks.hasOwnProperty(name) && callbacks[name] !== null){
                evProp.unshift(callbacks[name]);
            }

            for(var i= 0;i < evProp.length; ++i){
                evProp[i].call();
            }

            return true;
        }

        L.getInlineOptions = function(options){
            if(!options)
                return false;

            return R.options2JSON(options);
        }

        L._error = function (err) {
            inner = $('<div/>').text(err).addClass(classes.error);

            inner.invisible().appendTo(content);

            isOpen = true;

            L.trigger('beforeOpen');

            _resetView();

            wrap.unbind('onShow').bind('onShow',function(){

                inner.visible().hide().fadeIn();

                R.ajaxLoader.hide(loader);

                L.trigger('afterShow');

                isLoading = false;
                isOpened  = true;

            });

        }

        // + Initialize plugins

        var _options = options || {};
        L.initialize(_options, true);

        // + API

        // - Open lightbox
        $._realtyna.lightbox.open = function (selector,opt) {
            var _options = opt || {};

            self = R.isQuery(selector)? selector : $(selector);

            if(self.length > 1)
                return false;

            L.initialize(_options);

            //return self.trigger('click.realtyna-lightbox');
            return L.open(self);
        };

        // - Close lightbox
        $._realtyna.lightbox.close = function(){

            if(isOpen){
                wrap.trigger('onReset');

                isOpen    = false;
                isOpened  = false;
                isLoading = false;

                L._beforeOpen = [];
                L._afterOpen  = [];
                L._afterShow  = [];
                L._afterClose  = [];

                $('html').removeClass(classes.lock);

                $(wrap).removeClass(fx.showBox).addClass(fx.hideBox);
                $(overlay).fadeOut(600,function(){

                    _EventsOff();

                    $(this).remove();

                    L.trigger('afterClose');

                    if(opts.reloadPage)
                        location.reload();

                });
                return true;
            }

            return false;

        };

        // - Add event handler
        $._realtyna.lightbox.on = function(evn,func){
            var eventName = '_' + evn;
            if(L.hasOwnProperty(eventName)){
                L[eventName].push(func);
            }
        }


        function _buildLayout(callback){

            // Initialize body
            $('html').addClass(classes.lock);

            // Create Overlay
            overlay = $('<div />').attr('id',IDs.overlay).addClass(classes.overlay);

            // Create Wrapper
            wrap = $('<div />').attr('id',IDs.wrap).addClass(classes.wrap);
            wrap.appendTo(overlay).addClass(fx.showBox);

            // Create close button
            close = $('<div />').attr('id',IDs.closeBtn).addClass(classes.close);
            close.hide().appendTo(wrap);

            // Create content div to wrapper
            content = $('<div />').attr('id',IDs.content).addClass(classes.content);
            content.appendTo(wrap);

            // Create all element to body
            overlay.appendTo(opts.addTo);

            // Set necessary events
            _EventsOn();

            L.trigger('beforeOpen');

            _resetView();

            // Show Loading if necessary
            if(opts.showLoading){
                loader = R.ajaxLoader.show(wrap, 'normal', 'center', false, opts.loading.color);
            }


            callback.call();
        }

        function _resetView(){

            var viewport = R.getBrowserSizes().browser(),
                left,
                top,
                height,
                width;

            if(isOpen){

                var oldPos = inner.css('position');

                inner.css({
                    position: 'absolute'
                });

                if(opts.autoSize){
                    width  = inner.not('script, style').eq(0).outerWidth();
                    height = inner.not('script, style').eq(0).outerHeight();
                }else{
                    width  = opts.width;
                    height = opts.height;
                }

                inner.css({
                    position: (oldPos === 'static')? '' : oldPos
                })

                width  = Math.min(width + 2, opts.maxWidth);
                height = Math.min(height + 2, opts.maxHeight);

                left = viewport.width / 2 - width / 2;
                top  = Math.max(viewport.height / 2 - height / 2, 40);

                if(!isOpened){

                    wrap.animate({
                        left    : left,
                        top     : top,
                        width   : width,
                        height  : height
                    },450);

                    setTimeout(function(){
                        wrap.css({ height: (opts.autoSize)? 'auto' : height });
                        overlay.css({ overflowY: 'auto' });
                        wrap.trigger('onShow');
                        L.trigger('afterOpen');
                    }, 600);


                }else{

                    wrap.css({
                        height  : (opts.autoSize)? 'auto' : height,
                        left    : left,
                        top     : top,
                        width   : width
                    });

                }

            }else{

                wrap.css({
                    width: 100,
                    height: 100,
                    left: viewport.width / 2 - 50,
                    top: viewport.height / 2 - 50
                });

            }

            return true;
        }

        function _getType(elm){
            var _href;

            if(typeof elm.attr('href') != 'undefined'){
                _href = elm.attr('href');
            }else if(typeof elm.attr('data-realtyna-href') != 'undefined'){
                _href = elm.attr('data-realtyna-href');
            }else{
                $._realtyna.lightbox.close();
            }

            if(_href.length > 0){
                if(_href[0] == '#'){
                    return 'inline';
                }else{
                    return 'ajax';
                }
            }
        }

        function _EventsOn(){

            // Close when click on overlay
            $(overlay).on('click.realtyna-lightbox',function(evn){
                var _self = $(evn.target);
                if(!_self.hasClass(classes.wrap) && _self.parents('.' + classes.wrap).length == 0)
                    $._realtyna.lightbox.close();
            });

            $(close).on('click.realtyna-lightbox',function(evn){
                evn.preventDefault();
                $._realtyna.lightbox.close();
            });

            // Reset position on window resize
            $(window).on('resize.realtyna-lightbox',function(){
                window.resizeEvt;
                $(window).on('resize.realtyna-lightbox', function()
                {
                    clearTimeout(window.resizeEvt);
                    window.resizeEvt = setTimeout(function()
                    {
                        _resetView();

                    }, 250);
                });
            });

            $(document).on('keydown.realtyna-lightbox', function(e) {
                if ((e.which || e.keyCode) === 27) {

                    e.preventDefault();

                    $._realtyna.lightbox.close();

                }
            });

        }

        function _EventsOff(){
            $(window).off('resize.realtyna-lightbox');
            $(document).off('keydown.realtyna-lightbox');
        }

        function _renderInline(elm){
            var rxURL = /^#[a-z]+[\w-]*$/gi;

            href = elm.attr('href') || elm.attr('data-realtyna-href');

            // Is element id is valide
            if(!rxURL.test(href))
                return;

            current = $(href);

            // If current element not found
            if(current.length === 0) {
                L._error(opts.errors.notFound);
                return;
            }

            // Put current on right place
            if(!current.data(placeholder))
                current.data(placeholder, $('<div class="' + placeholder + '"></div>').insertAfter( current ).hide());

            current = current.show().invisible().detach();

            isOpen = true;

            $(content).append(current);

            wrap.bind('onReset',function(){

                if($(this).find(current).length){

                    if(opts.autoSize)
                        wrap.height(current.outerHeight());

                    current.hide().replaceAll(current.data(placeholder)).data(placeholder,false);

                    // Clear container div need to clear after show
                    if(opts.clearContent)
                        current.html('');

                }

            });

            wrap.bind('onShow',function(){

                current.visible().hide().fadeIn();

                close.fadeIn();

                R.ajaxLoader.hide(loader);

                isLoading = false;
                isOpened  = true;

            });

            // Proceed when the content actually loaded.
            watcher = setInterval(function(){
                if(current.children().length > 0) {

                    clearInterval(watcher);
                    inner = current.children();
                    _resetView();

                }

            }, 300);

        }

        function _renderURL(elm){

            var data = opts.ajaxData = elm.attr('data-realtyna-lightbox-data') || {};

            href = elm.attr('href') || elm.attr('data-realtyna-href');

            $.ajax({
                type: opts.ajaxType,
                dataType: opts.ajaxDataType,
                url: href,
                data: data,
                error: function (jqXHR, textStatus) {

                    L._error(opts.errors.unexpected);
                },
                success: function (data, textStatus) {
                    if (textStatus === 'success') {
                        current = $('<div/>').addClass(classes.ajax).html(data);
                        inner   = current.children();

                        isOpen = true;

                        L.trigger('beforeOpen');

                        content.append(current.show().invisible());

                        _resetView();
                    }
                }
            });

            wrap.bind('onShow',function(){

                current.visible().hide().fadeIn();

                close.fadeIn();

                R.ajaxLoader.hide(loader);

                isLoading = false;
                isOpened  = true;

            });

        }


    };

})(jQuery,window, document);