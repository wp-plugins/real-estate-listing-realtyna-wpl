// Declare custom jQuery handler
var _j = wplj = jQuery.noConflict();

/***************************** Old JS *****************************************/
var wplj;
var wpl_show_messages_cur_class;
var wpl_show_messages_html_element;
var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('#');

wplj(document).ready(function()
{
    wplj.fn.wpl_help = function() {
        wplj('.wpl_help').hover(
                function()
                {
                    wplj(this).children(".wpl_help_description").show();
                }
        ,
                function()
                {
                    wplj(this).children(".wpl_help_description").hide();
                }
        )
    };
    wplj('.wpl_help').wpl_help();

});
/** after show default function (don't remove it) **/
function wpl_fancybox_afterShow_callback()
{
}

function wpl_ajax_save(table, key, element, id, url)
{
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

function wpl_show_messages(message, html_element, msg_class)
{
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

function wpl_remove_message(html_element)
{
    if (!html_element)
        html_element = wpl_show_messages_html_element;
    if (!wpl_show_messages_cur_class)
        return;
    wplj(html_element).removeClass(wpl_show_messages_cur_class);
    wplj(html_element).html('');
    wpl_show_messages_cur_class = '';
}

function wpl_run_ajax_query(url, request_str, ajax_loader, data_type, ajax_type)
{
    if (!data_type)
        data_type = "JSON";
    if (!ajax_type)
        ajax_type = "POST";
    ajax_result = wplj.ajax(
            {
                type: ajax_type,
                dataType: data_type,
                url: url,
                data: request_str,
                success: function(data)
                {
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    if (ajax_loader)
                        wplj(ajax_loader).html('');
                }
            });
    return ajax_result;
}

/** update query string **/
function wpl_update_qs(key, value, url)
{
    if (!url)
        url = window.location.href;
    var re = new RegExp("([?|&])" + key + "=.*?(&|#|$)(.*)", "gi");
    if (re.test(url))
    {
        if (value)
            return url.replace(re, '$1' + key + "=" + value + '$2$3');
        else
            return url.replace(re, '$1$3').replace(/(&|\?)$/, '');
    }
    else
    {
        if (value)
        {
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

function wpl_thousand_sep(field_id)
{
    var sep = ",";
    var num = wplj("#" + field_id).val();
    num = num.toString();
    var dotpos = num.indexOf(".");
    var endString = '';
    if (dotpos != -1)
    {
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

function wpl_de_thousand_sep(val)
{
    return val.replace(/,/g, "");
}

function wpl_alert(string)
{
    alert(string);
}

function wpl_ucfirst(string)
{
    return string.charAt(0).toUpperCase() + string.slice(1);
}
