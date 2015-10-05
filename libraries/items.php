<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Items Library
 * @author Howard R <howard@realtyna.com>
 * @since WPL1.0.0
 * @date 07/18/2013
 * @package WPL
 */
class wpl_items
{
    /**
     * Gets items
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $parent_id
     * @param string $item_type
     * @param int $parent_kind
     * @param string $category
     * @param int $enabled
     * @param string $condition
     * @return array
     */
	public static function get_items($parent_id, $item_type = '', $parent_kind = 0, $category = '', $enabled = 1, $condition = '')
	{
		/** first validation **/
		if(trim($parent_id) == '') return NULL;
		
		if(trim($condition) == '')
		{
			$condition = "";
			$condition .= " AND `parent_id`='$parent_id' AND `parent_kind`='$parent_kind'";
			
			if(trim($item_type) != '') $condition .= " AND `item_type`='$item_type'";
			if(trim($category) != '') $condition .= " AND `item_cat`='$category'";
			if(trim($enabled) != '') $condition .= " AND `enabled`>='$enabled'";
			
			$condition .= " ORDER BY `index` ASC";
		}
		
		$query = "SELECT * FROM `#__wpl_items` WHERE 1 ".$condition;
		$records = wpl_db::select($query);
		
		if(trim($item_type) != '') return $records;
		
		$items = array();
		foreach($records as $record)
		{
			$items[$record->item_type][] = $record;
		}
		
		return $items;
	}
	
    /**
     * Saves an item. For adding new item and updating existing items
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param array $values
     * @param int $item_id
     * @return int
     */
	public static function save($values = array(), $item_id = '')
	{
		/** first validation **/
		if(!is_array($values) or count($values) == 0) return false;
		
		if($item_id) $result = wpl_items::update($item_id, $values);
		else $result = wpl_items::insert($values);
		
		return $result;
	}
	
    /**
     * Updates one item
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $item_id
     * @param array $values
     * @return int affected rows
     */
	public static function update($item_id, $values = array())
	{
		/** first validation **/
		if(!trim($item_id) or count($values) == 0) return false;
		
		$q = '';
		foreach($values as $key=>$value) $q .= "`$key`='$value', ";
		$q = trim($q, ", ");
		
		$query = "UPDATE `#__wpl_items` SET ".$q." WHERE `id`='$item_id'";
		$affected_rows = wpl_db::q($query, 'update');
		
		/** trigger event **/
		wpl_global::event_handler('item_updated', array('item_id'=>$item_id));
		
		return $affected_rows;
	}
    
    /**
     * Inserts a new item
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param array $values
     * @return int new item id
     */
	public static function insert($values = array())
	{
		/** first validation **/
		if(count($values) == 0) return false;
		
		$q1 = '';
		$q2 = '';
		
		foreach($values as $key=>$value)
		{
			$q1 .= "`$key`,";
			$q2 .= "'$value',";
		}
		
		$q1 = trim($q1, ', ');
		$q2 = trim($q2, ', ');
		
		$query = "INSERT INTO `#__wpl_items` (".$q1.") VALUES (".$q2.")";
		$insert_id = wpl_db::q($query, 'insert');
		
		/** trigger event **/
		wpl_global::event_handler('item_added', array('item_id'=>$insert_id));
		
		return $insert_id;
	}
	
    /**
     * Returns one item data
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $item_id
     * @param string $selects
     * @return object
     */
	public static function get($item_id, $selects = '*')
	{
		/** get item **/
		return wpl_db::get($selects, 'wpl_items', 'id', $item_id);
	}
	
    /**
     * Deletes an item record from items table
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $item_id
     * @return boolean
     */
	public static function delete($item_id)
	{
		/** trigger event **/
		wpl_global::event_handler('item_deleted', array('item_id'=>$item_id));
		
		/** delete item **/
		return wpl_db::delete('wpl_items', $item_id);
	}
	
    /**
     * Removes all related items of a property or user
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $parent_id
     * @param int $kind
     * @return boolean
     */
	public static function delete_all_items($parent_id, $kind = 0)
	{
		/** first validation **/
		if(!trim($parent_id) or trim($kind) == '') return false;
		
		/** trigger event **/
		wpl_global::event_handler('all_items_deleted', array('parent_id'=>$parent_id, 'kind'=>$kind));
		
		/** delete items **/
		$query = "DELETE FROM `#__wpl_items` WHERE `parent_kind`='$kind' AND `parent_id`='$parent_id'";
		return wpl_db::q($query);
	}
	
    /**
     * Sorts items
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $parent_id
     * @param string $order
     * @param string $column
     * @return void
     */
	public static function sort_items($parent_id, $order, $column = 'item_name')
	{
		$order_array = explode(',' , $order);
		$counter = 0;
		
		foreach($order_array as $file_name)
		{
			self::update_file($file_name, $parent_id, array('index'=>(++$counter)));
		}
	}
	
    /**
     * Deletes a file
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param string $file_name
     * @param int $parent_id
     * @param int $kind
     * @return boolean
     */
	public static function delete_file($file_name, $parent_id, $kind = 0, $blog_id = NULL)
	{
		if(!trim($file_name) or !trim($parent_id)) return false;
		
		$query = "DELETE FROM `#__wpl_items` WHERE `parent_kind`='$kind' AND `parent_id`='$parent_id' AND `item_name`='$file_name'";
		wpl_db::q($query, 'delete');
		
        if(is_null($blog_id)) $blog_id = wpl_property::get_blog_id($parent_id);
		$folder = wpl_items::get_path($parent_id, $kind, $blog_id);
		
		if(wpl_file::exists($folder . $file_name))
		{
			wpl_file::delete($folder . $file_name);
			if(wpl_file::exists($folder . 'thumbnail' .DS. $file_name)) wpl_file::delete($folder . 'thumbnail' .DS. $file_name);
        }
		
		/** trigger event **/
		wpl_global::event_handler('item_deleted', array('file_name'=>$file_name,'parent_id'=>$parent_id));
        return true;
	}
	
    /**
     * Updates a file
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param string $file_name
     * @param int $parent_id
     * @param array $values
     * @return boolean
     */
	public static function update_file($file_name, $parent_id, $values = array())
	{
		/** first validation **/
		if(!trim($file_name) or !trim($parent_id) or count($values) == 0) return false;
		
		$q = '';
		
		foreach($values as $key=>$value) $q .= "`$key`='$value', ";
		
		$q = trim($q, ", ");
		$file_name = trim($file_name);
		
		$query = "UPDATE `#__wpl_items` SET ".$q." WHERE `parent_id`='$parent_id' AND `item_name`='$file_name'";
		$affected_rows = wpl_db::q($query, 'update');
		
		/** trigger event **/
		wpl_global::event_handler('item_updated', array('file_name'=>$file_name,'parent_id'=>$parent_id));
		return $affected_rows;
	}
	
    /**
     * Returns item categories
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param string $item_type
     * @param int $parent_kind
     * @param string $condition
     * @return mixed
     */
	public static function get_item_categories($item_type, $parent_kind, $condition = '')
	{
		if(trim($condition) == '')
		{
			$condition = "";
			
			if(trim($item_type) != '') $condition .= " AND `item_type`='$item_type'";
			$condition .= " ORDER BY `index` ASC";
		}
		
		$query = "SELECT * FROM `#__wpl_item_categories` WHERE 1 ".$condition;
		$records = wpl_db::select($query);
		
		if(trim($item_type) != '') return $records;
		
		$items = array();
		foreach($records as $record)
		{
			$items[$record->item_type][] = $record;
		}
		
		return $items;
	}
	
    /**
     * Returns item directory URL
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $parent_id
     * @param int $kind
     * @param int $blog_id
     * @return string
     */
	public static function get_folder($parent_id, $kind = 0, $blog_id = NULL)
	{
		if($kind == 2) return wpl_global::get_upload_base_url($blog_id).'users/'.$parent_id.'/';
		else return wpl_global::get_upload_base_url($blog_id).$parent_id.'/';
	}
	
    /**
     * Returns item directory path. If it's not exist it creates the directory 
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $parent_id
     * @param int $kind
     * @param int $blog_id
     * @return string
     */
	public static function get_path($parent_id, $kind = 0, $blog_id = NULL)
	{
		if($kind == 2) $path = wpl_global::get_upload_base_path($blog_id). 'users' .DS. $parent_id .DS;
		else $path = wpl_global::get_upload_base_path($blog_id). $parent_id .DS;
		
		if(!wpl_folder::exists($path)) wpl_folder::create($path);
		
		return $path;
	}
	
    /**
     * Returns maximum index for sorting a new item
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $parent_id
     * @param string $item_type
     * @param int $parent_kind
     * @param mixed $category
     * @param int $enabled
     * @param string $condition
     * @return int
     */
	public static function get_maximum_index($parent_id, $item_type = '', $parent_kind = 0, $category = '', $enabled = '', $condition = '')
	{
		/** first validation **/
		if(trim($parent_id) == '') return NULL;
		
		if(trim($condition) == '')
		{
			$condition = "";
			$condition .= " AND `parent_id`='$parent_id' AND `parent_kind`='$parent_kind'";
			
			if(trim($item_type) != '') $condition .= " AND `item_type`='$item_type'";
			if(trim($category) != '') $condition .= " AND `item_cat`='$category'";
			if(trim($enabled) != '') $condition .= " AND `enabled`>='$enabled'";
		}
		
		$query = "SELECT MAX(`index`) as max FROM `#__wpl_items` WHERE 1 ".$condition;
		
		$index = wpl_db::select($query,'loadObject');
		return $index->max;
	}
	
    /**
     * Returns gallery of a property
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $parent_id
     * @param int $parent_kind
     * @param mixed $category
     * @param int $enabled
     * @param int $blog_id
     * @return array
     */
	public static function get_gallery($parent_id, $parent_kind = 0, $category = '', $enabled = 1, $blog_id = NULL)
	{
		$items = wpl_items::get_items($parent_id, 'gallery', $parent_kind , $category, $enabled);
		
        // Get blog ID of property
        if(is_null($blog_id)) $blog_id = wpl_property::get_blog_id($parent_id);
            
		/** render items **/
		return wpl_items::render_gallery($items, $blog_id);
	}
	
    /**
     * Renders gallery
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param array $images
     * @param int $blog_id
     * @return array
     */
	public static function render_gallery($images = array(), $blog_id = NULL)
	{
		/** force to array **/
		$images = (array) $images;
		$return = array();
		$i = 0;
		
		foreach($images as $image)
		{
			/** force to array **/
			$image = (array) $image;
			
            // Get blog ID of property
            if(is_null($blog_id)) $blog_id = wpl_property::get_blog_id($image['parent_id']);
            
			$image_path = self::get_path($image['parent_id'], $image['parent_kind'], $blog_id) . $image['item_name'];
			$image_url = self::get_folder($image['parent_id'], $image['parent_kind'], $blog_id) . $image['item_name'];
			
            /** external images **/
            if(isset($image['item_cat']) and $image['item_cat'] == 'external')
            {
                $image_path = $image['item_extra3'];
                $image_url = $image['item_extra3'];
            }
            
			/** existance check **/
			if(!wpl_file::exists($image_path) and $image['item_cat'] != 'external') continue;
			
			$pathinfo = @pathinfo($image_path);
			
			$return[$i]['item_id'] = $image['id'];
			$return[$i]['path'] = $image_path;
			$return[$i]['url'] = $image_url;
			$return[$i]['size'] = @filesize($image_path);
			$return[$i]['title'] = (string) $image['item_extra1'];
			$return[$i]['description'] = (string) $image['item_extra2'];
			$return[$i]['category'] = $image['item_cat'];
			$return[$i]['ext'] = isset($pathinfo['extension']) ? $pathinfo['extension'] : NULL;
			$return[$i]['raw'] = $image;
			
			$i++;
		}
		
		return $return;
	}
    
    /**
     * Renders attachments
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param array $attachments
     * @param int $blog_id
     * @return array
     */
	public static function render_attachments($attachments = array(), $blog_id = NULL)
	{
		_wpl_import('libraries.render');
		
		/** force to array **/
		$attachments = (array) $attachments;
		$return = array();
		$i = 0;
		
		foreach($attachments as $attachment)
		{
			/** force to array **/
			$attachment = (array) $attachment;
			
            // Get blog ID of property
            if(is_null($blog_id)) $blog_id = wpl_property::get_blog_id($attachment['parent_id']);
            
			$att_path = self::get_path($attachment['parent_id'], $attachment['parent_kind'], $blog_id) . $attachment['item_name'];
			$att_url = self::get_folder($attachment['parent_id'], $attachment['parent_kind'], $blog_id) . $attachment['item_name'];
			
			/** existance check **/
			if(!wpl_file::exists($att_path)) continue;
			
			$pathinfo = @pathinfo($att_path);
			$filesize = @filesize($att_path);
			
			$return[$i]['item_id'] = $attachment['id'];
			$return[$i]['name'] = (isset($attachment['item_extra1']) and trim($attachment['item_extra1']) != '') ? $attachment['item_extra1'] : $attachment['item_name'];
			$return[$i]['path'] = $att_path;
			$return[$i]['url'] = $att_url;
			$return[$i]['size'] = $filesize;
			$return[$i]['rendered_size'] = wpl_render::render_file_size($filesize);
			$return[$i]['title'] = (string) $attachment['item_extra1'];
			$return[$i]['description'] = (string) $attachment['item_extra2'];
			$return[$i]['category'] = $attachment['item_cat'];
			$return[$i]['ext'] = $pathinfo['extension'];
			$return[$i]['raw'] = $attachment;
			
			/** attachment icon **/
			$icon_url = wpl_global::get_wpl_asset_url('img/extentions/'.$pathinfo['extension'].'.png');
			$icon_path = WPL_ABSPATH. 'assets' .DS. 'img' .DS. 'extentions' .DS. $pathinfo['extension'].'.png';
			
			if(!wpl_file::exists($icon_path))
			{
				$icon_url = wpl_global::get_wpl_asset_url('img/extentions/default.png');
				$icon_path = WPL_ABSPATH .DS. 'assets' .DS. 'img' .DS. 'extentions' .DS. 'default.png';
			}
			
			$return[$i]['icon_path'] = $icon_path;
			$return[$i]['icon_url'] = $icon_url;
			
			$i++;
		}
		
		return $return;
	}
	
    /**
     * Renders videos
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param array $videos
     * @param int $blog_id
     * @return array
     */
	public static function render_videos($videos = array(), $blog_id = NULL)
	{
		/** force to array **/
		$videos = (array) $videos;
		$return = array();
		$i = 0;
		
		foreach($videos as $video)
		{
			/** force to array **/
			$video = (array) $video;
			
			$return[$i]['item_id'] = $video['id'];
			$return[$i]['category'] = $video['item_cat'];
				
			if($video['item_cat'] == 'video')
			{
                // Get blog ID of property
                if(is_null($blog_id)) $blog_id = wpl_property::get_blog_id($video['parent_id']);
            
				$video_path = self::get_path($video['parent_id'], $video['parent_kind'], $blog_id) . $video['item_name'];
				$video_url = self::get_folder($video['parent_id'], $video['parent_kind'], $blog_id) . $video['item_name'];
				
				/** existance check **/
				if(!wpl_file::exists($video_path)) continue;
				
				$pathinfo = @pathinfo($video_path);
				
				$return[$i]['path'] = $video_path;
				$return[$i]['url'] = $video_url;
				$return[$i]['size'] = @filesize($video_path);
				$return[$i]['title'] = (string) $video['item_extra1'];
				$return[$i]['description'] = (string) $video['item_extra2'];
				$return[$i]['ext'] = $pathinfo['extension'];
			}
			elseif($video['item_cat'] == 'video_embed')
			{
				$return[$i]['path'] = '';
				$return[$i]['url'] = (string) $video['item_extra2'];
				$return[$i]['size'] = '';
				$return[$i]['title'] = (string) $video['item_name'];
				$return[$i]['description'] = (string) $video['item_extra1'];
				$return[$i]['ext'] = '';
			}
			
			$return[$i]['raw'] = $video;
			$i++;
		}
		
		return $return;
	}
	
    /**
     * Render gallery of a property based on custom sizes
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $property_id
     * @param array $images
     * @param array $custom_sizes
     * @param int $blog_id
     * @return array
     */
	public static function render_gallery_custom_sizes($property_id, $images = '', $custom_sizes = array(), $blog_id = NULL)
	{
		$kind = wpl_property::get_property_kind($property_id);
		if(!$images) $images = wpl_items::get_items($property_id, 'gallery', $kind);
		
		/** no image gallery **/
		if(!count($images)) return array();
		
        // Get blog ID of property
        if(is_null($blog_id)) $blog_id = wpl_property::get_blog_id($property_id);
            
		$return = array();
		foreach($custom_sizes as $custom_size)
		{
			$custom_size = str_replace('*', '_', $custom_size);
			list($x, $y) = explode('_', $custom_size);
			if(trim($x) == '' or trim($y) == '') continue;
			if(!is_numeric($x) or !is_numeric($y)) continue;
			
			$i = 0;
			foreach($images as $image)
			{
				/** force to array **/
				$image = (array) $image;
				
				$source_path = self::get_path($image['parent_id'], $image['parent_kind'], $blog_id) . $image['item_name'];
				$source_url = self::get_folder($image['parent_id'], $image['parent_kind'], $blog_id) . $image['item_name'];
				
				$params = array('image_name'=>$image['item_name'], 'image_source'=>$source_path, 'image_parentid'=>$image['parent_id'], 'image_parentkind'=>$image['parent_kind']);
                
                /** taking care for external images **/
				if($image['item_cat'] != 'external')
                {
                    $dest_url = wpl_images::create_gallery_image($x, $y, $params, 0, 0);
                    $pathinfo = @pathinfo($dest_url);
                    $dest_path = self::get_path($image['parent_id'], $image['parent_kind'], $blog_id) . $pathinfo['basename'];
                }
                else
                {
                    $dest_url = $source_url;
                    $pathinfo = @pathinfo($dest_url);
                    $dest_path = $source_path;
                }
				
				$return[$custom_size][$i]['item_id'] = $image['id'];
				$return[$custom_size][$i]['custom_size'] = $custom_size;
				$return[$custom_size][$i]['path'] = $dest_path;
				$return[$custom_size][$i]['url'] = $dest_url;
				$return[$custom_size][$i]['size'] = @filesize($dest_path);
				$return[$custom_size][$i]['title'] = (string) $image['item_extra1'];
				$return[$custom_size][$i]['description'] = (string) $image['item_extra2'];
				$return[$custom_size][$i]['category'] = $image['item_cat'];
				$return[$custom_size][$i]['ext'] = $pathinfo['extension'];
				$return[$custom_size][$i]['raw'] = $image;
				
				$i++;
			}
		}
		
		return $return;
	}
}