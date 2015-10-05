<script type="text/javascript">

    var _wplSearchCallbacks<?php echo $widget_id; ?> = [];

    wplj(document).ready(function () {
        wplj('.wpl_search_from_box #more_search_option<?php echo $widget_id; ?>').on('click', function () {
            var widget_id = wplj(this).attr('data-widget-id');

            if (wplj(this).hasClass('active')) {
                wplj(this).removeClass('active');
                wplj('.wpl_search_from_box #wpl_search_from_box_bot' + widget_id).slideUp("fast");
                wplj('.wpl_search_from_box #wpl_search_from_box_bot' + widget_id + ' .wpl_search_field_container').animate({
                    marginLeft: 100 + 'px',
                    opacity: 1
                });
                wplj(this).text("<?php echo __('More options', WPL_TEXTDOMAIN); ?>");
            }
            else {
                wplj(this).addClass('active');
                wplj('.wpl_search_from_box #wpl_search_from_box_bot' + widget_id).fadeIn();
                wplj('.wpl_search_from_box #wpl_search_from_box_bot' + widget_id + ' .wpl_search_field_container').animate({
                    marginLeft: 0 + 'px',
                    opacity: 1
                });
                wplj(this).text("<?php echo __('Fewer options', WPL_TEXTDOMAIN); ?>");
            }
        })

        wplj(".MD_SEP > .wpl_search_field_container:first-child").on('click', function () {
            wplj(this).siblings(".wpl_search_field_container").slideToggle(400);
        })

        <?php if($bott_div_open): ?>
        wplj(".wpl_search_from_box #more_search_option<?php echo $widget_id; ?>").trigger('click');
        <?php endif; ?>

        <?php if($this->ajax == 2): ?>
        wplj("#wpl_search_form_<?php echo $widget_id; ?> input, #wpl_search_form_<?php echo $widget_id; ?> select, #wpl_search_form_<?php echo $widget_id; ?> textarea").on('change', function () {
            setTimeout("wpl_do_search_<?php echo $widget_id; ?>()", 300);
        });
        <?php endif; ?>
    });

    /** main search function **/
    function wpl_do_search_<?php echo $widget_id; ?>() {
        request_str = '';
        wplj("#wpl_searchwidget_<?php echo $widget_id; ?> input:checkbox").each(function (index, element) {
            id = element.id;
            name = element.name;
            if (name.substring(0, 2) == 'sf') {
                if (wplj("#wpl_searchwidget_<?php echo $widget_id; ?> #" + id).closest('li').css('display') != 'none') {
                    if (element.checked) value = element.value; else value = "-1";
                    request_str += "&" + element.name.replace('sf<?php echo $widget_id; ?>_', 'sf_') + "=" + value;
                }
            }
        });

        wplj("#wpl_searchwidget_<?php echo $widget_id; ?> input:text").each(function (index, element) {
            id = element.id;
            name = element.name;
            if (name.substring(0, 2) == 'sf') {
                if (wplj("#wpl_searchwidget_<?php echo $widget_id; ?> #" + id).closest('li').css('display') != 'none') {
                    value = element.value;
                    request_str += "&" + element.name.replace('sf<?php echo $widget_id; ?>_', 'sf_') + "=" + value;
                }
            }
        });

        wplj("#wpl_searchwidget_<?php echo $widget_id;?> input[type=hidden]").each(function (index, element) {
            id = element.id;
            name = element.name;
            if (name.substring(0, 2) == 'sf') {
                if (wplj("#wpl_searchwidget_<?php echo $widget_id; ?> #" + id).closest('li').css('display') != 'none') {
                    value = element.value;
                    request_str += "&" + element.name.replace('sf<?php echo $widget_id; ?>_', 'sf_') + "=" + value;
                }
            }
        });

        wplj("#wpl_searchwidget_<?php echo $widget_id; ?> select, #wpl_searchwidget_<?php echo $widget_id; ?> textarea").each(function (index, element) {
            id = element.id;
            name = element.name;
            if (name.substring(0, 2) == 'sf') {
                if (wplj(element).closest('li').css('display') != 'none') {
                    value = wplj(element).val();
                    
                    var multiple = wplj(element).attr('multiple');
                    if(typeof multiple != 'undefined' && value == null) value = '';
                    
                    if (value != null) request_str += "&" + element.name.replace('sf<?php echo $widget_id; ?>_', 'sf_') + "=" + value;
                }
            }
        });

        /** Adding widget id **/
        request_str = 'widget_id=<?php echo $widget_id; ?>' + request_str;

        /** Create full url of search **/
        search_page = '<?php echo $this->get_target_page($target_id); ?>';

        if (search_page.indexOf('?') >= 0) search_str = search_page + '&' + request_str
        else search_str = search_page + '?' + request_str

        <?php if(!$this->ajax): ?>
        wpl_do_search_no_ajax<?php echo $widget_id; ?>(search_str);
        <?php elseif($this->ajax): ?>
        if (!wplj('#wpl_property_listing_container').length) wpl_do_search_no_ajax<?php echo $widget_id; ?>(search_str);
        else wpl_do_search_ajax<?php echo $widget_id; ?>(request_str, search_str);
        <?php endif; ?>

        return false;
    }

    function wpl_do_search_no_ajax<?php echo $widget_id; ?>(search_str) {
        window.location = search_str;
    }

    function wpl_add_callback_search<?php echo $widget_id; ?>(func){

        if(typeof func != 'undefined'){

            if(wplj.isFunction(func)){
                _wplSearchCallbacks<?php echo $widget_id; ?>.push(func);
                return true;
            }

        }

        return false;
    }

    function wpl_get_callback_search<?php echo $widget_id; ?>(){
        return _wplSearchCallbacks<?php echo $widget_id; ?>;
    }

    function wpl_clear_callback_search<?php echo $widget_id; ?>(){
        _wplSearchCallbacks<?php echo $widget_id; ?> = [];
        return true;
    }

    function wpl_do_search_ajax<?php echo $widget_id; ?>(request_str, search_str) {
        /** Move to First Page **/
        request_str = wpl_update_qs('wplpage', '1', request_str);

        if (typeof wpl_listing_request_str != 'undefined') {
            wpl_listing_request_str = wpl_qs_apply(wpl_listing_request_str, request_str);
            request_str = wpl_qs_apply(request_str, wpl_listing_request_str);

            search_str = wpl_qs_apply(search_str, request_str);
        }

        /** Load Markers **/
        if (typeof wpl_load_map_markers == 'function') wpl_load_map_markers(request_str, true);

        wplj(".wpl_property_listing_list_view_container").fadeTo(300, 0.5);

        try {
            history.pushState({search: 'WPL'}, "<?php echo addslashes(__('Search Results', WPL_TEXTDOMAIN)); ?>", search_str);
        }
        catch (err) {
        }

        wplj.ajax(
            {
                url: '<?php echo wpl_global::get_full_url(); ?>',
                data: 'wpl_format=f:property_listing:list&' + request_str,
                dataType: 'json',
                type: 'GET',
                async: true,
                cache: false,
                timeout: 30000,
                success: function (data) {
                    wpl_listing_total_pages = data.total_pages;
                    wpl_listing_current_page = data.current_page;

                    wplj(".wpl_property_listing_list_view_container").html(data.html);
                    wplj(".wpl_property_listing_list_view_container").fadeTo(300, 1);
                    
                    if(typeof wpl_fix_no_image_size == 'function') setTimeout(function(){wpl_fix_no_image_size();}, 50);
                    if(typeof wpl_scroll_pagination == 'function' && wpl_current_property_css_class == 'map_box')
                    {
                        setTimeout(function()
                        {
                            /** Remove previous scroll listener **/
                            wplj(wpl_sp_selector_div).off('scroll', wpl_scroll_pagination_listener);

                            wpl_sp_selector_div = '.wpl_property_listing_listings_container';
                            wpl_sp_append_div = '.wpl_property_listing_listings_container';

                            /** Add new scroll listener **/
                            var wpl_scroll_pagination_listener = wplj(wpl_sp_selector_div).on('scroll', function()
                            {
                                wpl_scroll_pagination();
                            });
                        }, 50);
                    }
                    
                    wpl_listing_last_search_time = new Date().getTime();

                    var callbacks = wpl_get_callback_search<?php echo $widget_id; ?>();
                    for (var func in callbacks) {
                        if (wplj.isFunction(callbacks[func])) {
                            callbacks[func].call();
                        }
                    }
                }
            });
    }

    function wpl_sef_request<?php echo $widget_id; ?>(request_str) {
        request_str = request_str.slice(1);
        splited = request_str.split("&");
        sef_str = '';
        unsef_str = '';
        var first_param = true;

        for (var i = 0; i < splited.length; i++) {
            splited2 = splited[i].split("=");
            key = splited2[0];
            value = splited2[1];

            if (key.substring(0, 9) == 'sf_select') {
                table_field = splited2[0].replace('sf_select_', '');
                key = wpl_ucfirst(table_field.replace('_', ' '));
                value = splited2[1];

                /** for setting text instead of value **/
                if (value != -1 && value != '' && (table_field == 'listing' || table_field == 'property_type')) {
                    field_type = wplj("#sf<?php echo $widget_id; ?>_select_" + table_field).prop('tagName');
                    if (field_type.toLowerCase() == 'select') value = wplj("#sf<?php echo $widget_id; ?>_select_" + table_field + " option:selected").text();
                }

                /** set to the SEF url **/
                if (value != -1 && value != '') sef_str += '/' + key + ':' + value;
            }
            else {
                if (first_param && value != -1 && value != '') {
                    unsef_str += '?' + key + '=' + value;
                    first_param = false;
                }
                else if (value != -1 && value != '') {
                    unsef_str += '&' + key + '=' + value;
                }
            }
        }

        final_str = sef_str + "/" + unsef_str;
        return final_str.slice(1);
    }

    function wpl_add_to_multiple<?php echo $widget_id; ?>(value, checked, table_column) {
        setTimeout("wpl_add_to_multiple<?php echo $widget_id; ?>_do('" + value + "', " + checked + ", '" + table_column + "');", 30);
    }

    function wpl_add_to_multiple<?php echo $widget_id; ?>_do(value, checked, table_column) {
        var values = wplj('#sf<?php echo $widget_id; ?>_multiple_' + table_column).val();
        values = values.replace(value + ',', '');

        if (checked) values += value + ',';
        wplj('#sf<?php echo $widget_id; ?>_multiple_' + table_column).val(values);
    }

    function wpl_select_radio<?php echo $widget_id; ?>(value, checked, table_column) {
        console.log(value + ":" + checked + ":" + table_column);
        if (checked) wplj('#sf<?php echo $widget_id;?>_select_' + table_column).val(value);
    }

    function wpl_do_reset<?php echo $widget_id; ?>(exclude, do_search) {
        if (!exclude) exclude = new Array();
        if (!do_search) do_search = false;

        wplj("#wpl_searchwidget_<?php echo $widget_id; ?>").find(':input').each(function () {
            if (exclude.indexOf(this.id) != -1) return;

            switch (this.type) {
                case 'text':

                    elmid = this.id;
                    idmin = elmid.indexOf("min");
                    idmax = elmid.indexOf("max");
                    iddate = elmid.indexOf("date");

                    if (idmin != '-1' && iddate == '-1') wplj(this).val('0');
                    else if (idmax != '-1' && iddate == '-1') wplj(this).val('1000000');
                    else wplj(this).val('');

                    break;
                case 'select-multiple':

                    wplj(this).multiselect("uncheckAll");
                    break;

                case 'select-one':

                    wplj(this).val(wplj(this).find('option:first').val());
                    wplj(this).trigger("chosen:updated");
                    break;

                case 'password':
                case 'textarea':

                    wplj(this).val('');
                    break;

                case 'checkbox':
                case 'radio':

                    this.checked = false;
                    break;

                case 'hidden':

                    elmid = this.id;
                    idmin = elmid.indexOf("min");
                    idmax = elmid.indexOf("max");
                    idtmin = elmid.indexOf("tmin");
                    idtmax = elmid.indexOf("tmax");

                    if (idtmin != '-1') {
                        var table_column = elmid.split("_tmin_");
                        table_column = table_column[1];
                        var widget_id = elmid.split("_");
                        widget_id = parseInt(widget_id[0].replace("sf", ""));
                    }
                    else if (idtmax != '-1') {
                        var table_column = elmid.split("_tmax_");
                        table_column = table_column[1];
                        var widget_id = elmid.split("_");
                        widget_id = parseInt(widget_id[0].replace("sf", ""));
                    }
                    else if (idmin != '-1') {
                        var table_column = elmid.split("_min_");
                        table_column = table_column[1];
                        var widget_id = elmid.split("_");
                        widget_id = parseInt(widget_id[0].replace("sf", ""));
                    }
                    else if (idmax != '-1') {
                        var table_column = elmid.split("_max_");
                        table_column = table_column[1];
                        var widget_id = elmid.split("_");
                        widget_id = parseInt(widget_id[0].replace("sf", ""));
                    }

                    try {
                        var min_slider_value = wplj("#slider" + widget_id + "_range_" + table_column).slider("option", "min");
                        var max_slider_value = wplj("#slider" + widget_id + "_range_" + table_column).slider("option", "max");

                        wplj("#sf" + widget_id + "_tmin_" + table_column).val(min_slider_value);
                        wplj("#sf" + widget_id + "_tmax_" + table_column).val(max_slider_value);
                        wplj("#sf" + widget_id + "_min_" + table_column).val(min_slider_value);
                        wplj("#sf" + widget_id + "_max_" + table_column).val(max_slider_value);

                        wplj("#slider" + widget_id + "_range_" + table_column).slider("values", 0, min_slider_value);
                        wplj("#slider" + widget_id + "_range_" + table_column).slider("values", 1, max_slider_value);

                        wplj("#slider" + widget_id + "_showvalue_" + table_column).html(wpl_th_sep<?php echo $widget_id; ?>(min_slider_value) + " - " + wpl_th_sep<?php echo $widget_id; ?>(max_slider_value));
                    }
                    catch (err) {
                    }
            }
        });

        if (do_search) wpl_do_search_<?php echo $widget_id; ?>();
    }

    function wpl_th_sep<?php echo $widget_id; ?>(num) {
        sep = ",";
        num = num.toString();
        x = num;
        z = "";

        for (i = x.length - 1; i >= 0; i--)
            z += x.charAt(i);

        // add seperators. but undo the trailing one, if there
        z = z.replace(/(\d{3})/g, "$1" + sep);

        if (z.slice(-sep.length) == sep)
            z = z.slice(0, -sep.length);

        x = "";
        // reverse again to get back the number
        for (i = z.length - 1; i >= 0; i--)
            x += z.charAt(i);

        return x;
    }

    <?php
        $this->create_listing_specific_js();
        $this->create_property_type_specific_js();
    ?>

    (function($){

        $(function(){

            try{

                if(typeof $.fn.chosen != 'undefined')
                    $("#wpl_searchwidget_<?php echo $widget_id; ?> select").chosen();
                else
                    throw 'WPL::Dependency Missing->Chosen library is not available.';

                $('#wpl_searchwidget_<?php echo $widget_id; ?> input.yesno[type="checkbox"]').checkbox({
                    cls: 'jquery-safari-checkbox',
                    empty: '<?php echo wpl_global::get_wpl_asset_url('img/empty.png'); ?>'
                });

                $('#wpl_searchwidget_<?php echo $widget_id; ?> input[type="checkbox"]:not(.yesno)').checkbox({empty: '<?php echo wpl_global::get_wpl_asset_url('img/empty.png'); ?>'});

                /** make the form empty if searched by listing id **/
                $("#sf<?php echo $widget_id; ?>_select_mls_id").on("change", function () {
                    wpl_do_reset<?php echo $widget_id; ?>(new Array("sf<?php echo $widget_id; ?>_select_mls_id"), false);
                });

            }catch(e){
                console.log(e);
            }

        });

    })(jQuery);

</script>