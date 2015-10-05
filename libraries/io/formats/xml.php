<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

class wpl_io_format_xml extends wpl_io_format_base
{
	public function __construct($cmd, $params)
	{
        $this->init($cmd, $params);
	}
	
	public function render($response)
	{
		$main_node = array_keys($response);
		return $this->toXml($response[$main_node[0]], $main_node[0], NULL, $main_node[0]);
	}
	
	public function toXml($data, $rootNodeName = 'data', $xml = NULL, $parent_node = '')
	{
		// Turn off compatibility mode as simple xml throws a wobbly if you don't.
		if(ini_get('zend.ze1_compatibility_mode') == 1) ini_set('zend.ze1_compatibility_mode', 0);

		// This code, strips attributes then add them to the root node
		$attributes = '';
		$val = explode(':', $rootNodeName);
        
		if(count($val) > 1)
		{
			$rootNodeName = $val[0];
			$attributes = explode('&', $val[1]);
		}
		
		$atts = '';
        if(is_array($attributes))
        {
            foreach($attributes as $att)
            {
                $att = explode('=', $att);
                $atts .= $att[0].'="'.$att[1].'" ';
            }
        }
		
		$val = explode(':', $parent_node);
		if(count($val) > 1)	$parent_node = $val[0];
		
		if($xml == NULL) $xml = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><$rootNodeName $atts/>", 'wpl_SimpleXMLExtended');
		
        print_r($data);
        exit;
		// loop through the data passed in.
		foreach($data as $key=>$value)
		{
			$attributes = '';
			$val = explode(':', $key);
            
			if(count($val) > 1)
			{
				$key = $val[0];
				$attributes = explode('&', $val[1]);
			}
			
			// no numeric keys in our xml please!
			if(is_numeric($key))
			{
				// Inteligent node name generator by Howard
				if(substr($parent_node, strlen($parent_node)-3, 3) == 'ies')
				{
					// case like properties -> property
					$key = substr($parent_node ,0,strlen($parent_node)-3).'y';
				}
				// Inteligent node name generator by MAX
				elseif(substr($parent_node, strlen($parent_node)-1, 1) == 's')
				{
					// case like images -> image
					$key = substr($parent_node, 0, strlen($parent_node)-1);
				}
				else
				{
					$key = $parent_node;
				}
			}
			
			if(strpos($key, '^'))
			{
				$val = explode('^', $key);
				$key = $val[0];
			}
			
			// if there is another array found recrusively call this function
			if(is_array($value))
			{
				$node = $xml->addChild($key);
                
				// add attributes
				$this->add_attributes($node, $attributes);
                
				// recrusive call.
				$this->toXml($value, $rootNodeName, $node, $key);
			}
			else
			{
				// add single node.
				$value = $this->xml_entities($value);
				
				// Developed by Steve, check if there is CDATA included, then add it as true CDATA
				if(stristr($value, '&lt;![CDATA['))
				{
					$node = $xml->addChild($key);
					$value = str_replace('&lt;![CDATA[', '', $value);
					$value = str_replace(']]&gt;', '', $value);
					$node->addCData($value);
				}
				else
				{
					$node = $xml->addChild($key, $value);
				}
				
				// add attributes
				$this->add_attributes($node, $attributes);
			}
		}
        
		$dom = new DOMDocument('1.0');
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		$dom->loadXML($xml->asXML());
        
		return $dom->saveXML();
	}
	
	public function xml_entities($text, $charset = 'Windows-1252')
    {
		// First we encode html characters that are also invalid in xml
		$text = htmlentities($text, ENT_COMPAT, $charset, false);
        
		// XML character entity array from Wiki
		// Note: &apos; is useless in UTF-8 or in UTF-16
		$arr_xml_special_char = array("&quot;","&amp;","&apos;","&lt;","&gt;");
        
		// Building the regex string to exclude all strings with xml special char
		$arr_xml_special_char_regex = "(?";
		foreach($arr_xml_special_char as $key=>$value) $arr_xml_special_char_regex .= "(?!$value)";
		$arr_xml_special_char_regex .= ")";
        
		// Scan the array for &something_not_xml; syntax
		$pattern = "/$arr_xml_special_char_regex&([a-zA-Z0-9]+;)/";
        
		// Replace the &something_not_xml; with &amp;something_not_xml;
		$replacement = '&amp;${1}';
		return preg_replace($pattern, $replacement, $text);
	}

	public function xml_entity_decode($text, $charset = 'Windows-1252')
    {
		// Double decode, so if the value was &amp;trade; it will become Trademark
		$text = html_entity_decode($text, ENT_COMPAT, $charset);
		return html_entity_decode($text, ENT_COMPAT, $charset);
	}
	
	// Developed by Steve
	// Add XML attributes to the output
	public function add_attributes(&$node, $attributes)
	{
		if(empty($attributes)) return;
		
		foreach($attributes as $att)
		{
			$att = explode('=', $att);
			$node->addAttribute($att[0], $att[1]);
		}
	}
}

// Developed by Steve, for CDATA section
class wpl_SimpleXMLExtended extends SimpleXMLElement
{
	public function addCData($cdata_text)
	{
		$node= dom_import_simplexml($this);
		$no = $node->ownerDocument;
		$node->appendChild($no->createCDATASection($cdata_text));
	}
}