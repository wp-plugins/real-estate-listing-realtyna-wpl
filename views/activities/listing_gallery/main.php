<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
_wpl_import('libraries.images');

/** activity class **/
class wpl_activity_main_listing_gallery extends wpl_activity
{
    public $tpl_path = 'views.activities.listing_gallery.tmpl';
	
	public function start($layout, $params)
	{
		/** include layout **/
		$layout_path = _wpl_import($layout, true, true);
		include $layout_path;
	}
    
    public function tags()
	{
        $kind = $this->current_property['data']['kind'];
        $tags_str = '';
        
        $tags = wpl_flex::get_tag_fields($kind);
        foreach($tags as $tag)
        {
            if(!$this->current_property['raw'][$tag->table_column]) continue;
            
            $options = json_decode($tag->options, true);
            if(!$options['ribbon']) continue;
            
            $tags_str .= '<div class="wpl-listing-tag '.$tag->table_column.'">'.$tag->name.'</div>';
        }
        
        /** Load Tag Styles **/
        $this->tags_styles($tags);
        
        return $tags_str;
	}
    
    public function tags_styles($tags = NULL)
    {
        static $loaded = array();
        
        if(isset($loaded[$this->activity_id])) return;
        if(!isset($loaded[$this->activity_id])) $loaded[$this->activity_id] = true;
        
        if(is_null($tags))
        {
            $kind = $this->current_property['data']['kind'];
            $tags = wpl_flex::get_tag_fields($kind);
        }
        
        /** Initialize WPL color library **/
        $color = new wpl_color();
        
        $styles_str = '';
        foreach($tags as $tag)
        {
            $options = json_decode($tag->options, true);
            if(!$options['ribbon']) continue;
            
            $darken = $color->convert(trim($options['color'], '# '), 130, true);
            $styles_str .= '.wpl-listing-tag.'.$tag->table_column.'{background-color: #'.trim($options['color'], '# ').'; color: #'.trim($options['text_color'], '# ').'} .wpl-listing-tag.'.$tag->table_column.'::after{border-color: #'.$darken.' transparent transparent #'.$darken.';}';
        }
        
        _wpl_import('libraries.html');
        
        $wplhtml = wpl_html::getInstance();
        $wplhtml->set_footer('<style type="text/css">'.$styles_str.'</style>');
    }
}