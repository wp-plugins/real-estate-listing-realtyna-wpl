<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<style type="text/css">
.wpl-widget-search-wp{margin: 10px 0;}
.wpl_field_container{border: 0px; width: 100%; border-bottom: 1px solid #CCC;}
.wpl_field_container select{min-width: 215px;}
.wpl_field_container td, wpl_field_container tr{border: 0px; text-align: left;}
tr[wpl_situation="disable"] > td{display: none;}
tr[wpl_situation="enable"] > td{display: table-cell;}
.fields_category_box > .fields_category_inputs{display: none;}
.fields_category_box{}
.wpl_listing_field_add{cursor: pointer; width: 20px; text-align: center; height: 15px;}
.wpl_listing_field_add.disable{background: #F93; color: #333;}
.wpl_listing_field_add.enable{background: #393; color: #030;}
.wpl_listing_field_add.enable:before{content: "-"; font-size: 12px; text-align: center;	padding: 0px 5px 0px 7px;}
.wpl_listing_field_add.disable:before{content: "+";	font-size: 12px; text-align: center; padding: 0px 5px 0px 7px;}
.fields_category_box:hover{background: #EEE;}
.fields_category_box:hover > .fields_category_title{background: #CCC; cursor: pointer;}
.fields_category_title{border-bottom: 1px solid #CCC; padding: 0px 10px; margin-bottom: 5px; border-left: 3px solid #999; line-height: 25px; font-weight: bold;}
.fields_category_title:hover{background: #CCC;}
.fields_category_box > table:hover{border-bottom: 1px solid #000;}
input[name$="[sort]"]{width: 100%; margin: 0px; font-size: 11px; padding: 0px; height: 20px;}
.wpl_extoptions_span{display: none;}
.wpl_extoptions_span.show,
.wpl_extoptions_span.predefined,
.wpl_extoptions_span.select-predefined,
.wpl_extoptions_span.minmax,
.wpl_extoptions_span.minmax_slider,
.wpl_extoptions_span.minmax_selectbox,
.wpl_extoptions_span.minmax_selectbox_plus,
.wpl_extoptions_span.locationtextsearch,
.wpl_extoptions_span.radiussearch,
.wpl_extoptions_span.minmax_selectbox_any{display: inline;}
.wpl_widget_shortcode_preview{text-align: center; padding: 15px 3px 5px;}
</style>