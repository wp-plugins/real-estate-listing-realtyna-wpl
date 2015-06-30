<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * @script Name: *Digg Style Paginator Class
 * @script URI: http://www.mis-algoritmos.com/2007/05/27/digg-style-pagination-class/
 * @description: Class in PHP that allows to use a pagination like a digg or sabrosus style.
 * @script Version: 0.4
 * @author: Victor De la Rocha
 * @author URI: http://www.mis-algoritmos.com
 * @package WPL
 */
class wpl_pagination
{
    /** Default values **/
    public $total_pages = -1; //items
    public $limit = null;
    public $limit_query = '';
    public $target = "";
    public $page = 1;
    public $adjacents = 2;
    public $showCounter = false;
    public $className = "pagination";
    public $parameterName = "page";
    public $urlF = false; //urlFriendly
    public $calculate = false;
    public $js_link = false;
    
    /** Buttons next and previous **/
    public $nextT = "Next";
    public $nextI = "&#187;"; //&#9658;
    public $prevT = "Previous";
    public $prevI = "&#171;"; //&#9668;

    #Total items
    public function items($value)
	{
        $this->total_pages = (int) $value;
    }

    #how many items to show per page
    public function limit($value)
	{
        $this->limit = (int) $value;
    }

    #Page to sent the page value
    public function target($value)
	{
        $this->target = $value;
    }

    #Current page
    public function currentPage($value)
	{
        $this->page = (int) $value;
    }

    #How many adjacent pages should be shown on each side of the current page?
    public function adjacents($value)
	{
        $this->adjacents = (int) $value;
    }

    #show counter?
    public function showCounter($value = "")
	{
        $this->showCounter = ($value === true) ? true : false;
    }

    #to change the class name of the pagination div
    public function changeClass($value = "")
	{
        $this->className = $value;
    }

    public function nextLabel($value)
	{
        $this->nextT = $value;
    }

    public function nextIcon($value)
	{
        $this->nextI = $value;
    }

    public function prevLabel($value)
	{
        $this->prevT = $value;
    }

    public function prevIcon($value)
	{
        $this->prevI = $value;
    }

    #to change the class name of the pagination div
    public function parameterName($value = "")
	{
        $this->parameterName = $value;
    }

    #to change urlFriendly
    public function urlFriendly($value = "%")
	{
        if(eregi('^ *$', $value))
		{
			$this->urlF = false;
			return false;
        }

        $this->urlF = $value;
    }

    var $pagination;

    public function pagination()
	{
    }

    public function show()
	{
        if(!$this->calculate and $this->calculate())
		{
            echo "<ul class=\"$this->className\">$this->pagination</ul>\n";

            if(isset($this->show_total) and $this->show_total) echo '<span class="wpl_total_result">' . $this->total_pages . ' ' . __('Results returned.', WPL_TEXTDOMAIN) . '</span>';
            if(isset($this->show_page_size) and $this->show_page_size)
			{
                $page_sizes = explode(',', trim(wpl_global::get_setting('page_sizes'), ', '));

                echo '<span class="wpl_page_size">';
                echo '<span class="wpl_page_size_title">' . __('Per Page ', WPL_TEXTDOMAIN) . '</span>';
                echo '<select class="wpl_page_size_options" onchange="wpl_pagesize_changed(this.value);">';
				
                foreach ($page_sizes as $page_size) echo '<option value="' . $page_size . '" ' . ($this->limit == $page_size ? 'selected="selected"' : '') . '>' . $page_size . '</option>';
                
				echo '</select>';
                echo '</span>';
            }
        }
    }

    public function getOutput()
	{
        if(!$this->calculate and $this->calculate())
		{
            $string = '';
            $string .= "<ul class=\"$this->className\">$this->pagination</ul>\n";

            if($this->show_total) $string .= '<span class="wpl_total_result">' . $this->total_pages . ' ' . __('Results returned.', WPL_TEXTDOMAIN) . '</span>';
            if($this->show_page_size)
			{
                $page_sizes = explode(',', trim(wpl_global::get_setting('page_sizes'), ', '));

                $string .= '<span class="wpl_page_size">';
                $string .= '<span class="wpl_page_size_title">' . __('Per Page ', WPL_TEXTDOMAIN) . '</span>';
                $string .= '<select class="wpl_page_size_options" onchange="wpl_pagesize_changed(this.value);">';
				
                foreach($page_sizes as $page_size) $string .= '<option value="' . $page_size . '" ' . ($this->limit == $page_size ? 'selected="selected"' : '') . '>' . $page_size . '</option>';
                
				$string .= '</select>';
                $string .= '</span>';
            }

            return $string;
        }
    }

    public function get_pagenum_link($id)
	{
        if($this->js_link)
        {
            return 'javascript:wpl_paginate('.$id.');';
        }
        
        if(strpos($this->target, '?') === false)
		{
            if($this->urlF) return str_replace($this->urlF, $id, $this->target);
            else return wpl_global::add_qs_var($this->parameterName, $id, $this->target);
        }
        else
		{
            return wpl_global::add_qs_var($this->parameterName, $id, $this->target);
        }
    }

    public function calculate()
	{
        $this->pagination = "";
        $this->calculate == true;
        $error = false;

        if($this->urlF and $this->urlF != '%' and strpos($this->target, $this->urlF) === false)
		{
            $error = true;
        }
		elseif($this->urlF and $this->urlF == '%' and strpos($this->target, $this->urlF) === false)
		{
            $error = true;
        }

        if($this->total_pages < 0)
		{
            $error = true;
        }

        if($this->limit == null)
		{
            $error = true;
        }

        if($error) return false;

        $n = $this->nextT; //trim($this->nextT . ' ' . $this->nextI);
        $p = $this->prevT; //trim($this->prevI . ' ' . $this->prevT);

        /* Setup vars for query. */
        if($this->page) $start = ($this->page - 1) * $this->limit; //first item to display on this page
        else $start = 0; //if no page var is given, set start to 0

		/* Setup page vars for display. */
        $prev = $this->page - 1;                            //previous page is page - 1
        $next = $this->page + 1;                            //next page is page + 1
        $lastpage = ceil($this->total_pages / $this->limit);        //lastpage is = total pages / items per page, rounded up.
        $lpm1 = $lastpage - 1;                        //last page minus 1
		
        /*
          Now we apply our rules and draw the pagination object.
          We're actually saving the code to a variable in case we want to draw it more than once.
         */
        if($lastpage > 1)
		{
            if($this->page)
			{
                //anterior button
                if($this->page > 1) $this->pagination .= "<li class=\"prev\"><a href=\"" . $this->get_pagenum_link($prev) . "\">$p</a></li>";
                else $this->pagination .= "<li class=\"prev disabled\"><a href=\"#\">$p</a></li>";
            }

            //pages	
            if($lastpage <= 7 + ($this->adjacents * 2))
			{
                for($counter = 1; $counter <= $lastpage; $counter++)
				{
                    if($counter == $this->page) $this->pagination .= "<li class=\"active\"><a href=\"#\">$counter</a></li>";
                    else $this->pagination .= "<li><a href=\"" . $this->get_pagenum_link($counter) . "\">$counter</a></li>";
                }
            }
            elseif($lastpage > 7 + ($this->adjacents * 2)) //enough pages to hide some
			{
                //close to beginning; only hide later pages
                if($this->page <= 2 + ($this->adjacents * 2))
				{
                    for($counter = 1; $counter < 4 + ($this->adjacents * 2); $counter++)
					{
                        if($counter == $this->page) $this->pagination .= "<li class=\"active\"><a href=\"#\">$counter</a></li>";
                        else $this->pagination .= "<li><a href=\"" . $this->get_pagenum_link($counter) . "\">$counter</a></li>";
                    }

                    $this->pagination .= "<li><span>...</span></li>";
                    $this->pagination .= "<li><a href=\"" . $this->get_pagenum_link($lpm1) . "\">$lpm1</a></li>";
                    $this->pagination .= "<li><a href=\"" . $this->get_pagenum_link($lastpage) . "\">$lastpage</a></li>";
                }
                //in middle; hide some front and some back
                elseif($lastpage - (2 + $this->adjacents * 2) >= $this->page && $this->page > ($this->adjacents * 2))
				{
                    $this->pagination .= "<li><a href=\"" . $this->get_pagenum_link(1) . "\">1</a></li>";
                    $this->pagination .= "<li><a href=\"" . $this->get_pagenum_link(2) . "\">2</a></li>";
                    $this->pagination .= "<li><span>...</span></li>";

                    for($counter = $this->page - $this->adjacents; $counter <= $this->page + $this->adjacents; $counter++)
					{
                        if($counter == $this->page) $this->pagination .= "<li class=\"active\"><a href=\"#\">$counter</a></li>";
                        else $this->pagination .= "<li><a href=\"" . $this->get_pagenum_link($counter) . "\">$counter</a></li>";
                    }

                    $this->pagination .= "<li><span>...</span></li>";
                    $this->pagination .= "<li><a href=\"" . $this->get_pagenum_link($lpm1) . "\">$lpm1</a></li>";
                    $this->pagination .= "<li><a href=\"" . $this->get_pagenum_link($lastpage) . "\">$lastpage</a></li>";
                }
                //close to end; only hide early pages
                else
				{
                    $this->pagination .= "<li><a href=\"" . $this->get_pagenum_link(1) . "\">1</a></li>";
                    $this->pagination .= "<li><a href=\"" . $this->get_pagenum_link(2) . "\">2</a></li>";
                    $this->pagination .= "<li><span>...</span></li>";

                    for($counter = $lastpage - (2 + ($this->adjacents * 2)); $counter <= $lastpage; $counter++)
					{
                        if($counter == $this->page) $this->pagination .= "<li class=\"active\"><a href=\"#\">$counter</a></li>";
                        else $this->pagination .= "<li><a href=\"" . $this->get_pagenum_link($counter) . "\">$counter</a></li>";
                    }
                }
            }

            if($this->page)
			{
                //siguiente button
                if($this->page < $counter - 1) $this->pagination .= "<li class=\"next\"><a href=\"" . $this->get_pagenum_link($next) . "\">$n</a>";
                else $this->pagination .= "<li class=\"next disabled\"><a href=\"#\">$n</a></span>";

                if($this->showCounter) $this->pagination .= "<div class=\"pagination_data\">($this->total_pages Pages)</div>";
            }
        }

        return true;
    }

    public static function get_pagination($num_result, $page_size = '', $show_options = false, $js_link = 0)
	{
        if(!$page_size) $page_size = 20;

        $p = new wpl_pagination;
        
        /** return js function **/
        $p->js_link = $js_link;
        
        $p->items($num_result);
        $p->limit($page_size); // Limit entries per page
        $p->target(wpl_global::get_full_url());
        $p->currentPage(wpl_request::getVar('wplpage')); // Gets and validates the current page
        $p->calculate(); // Calculates what to show
        $p->parameterName('wplpage');
        $p->adjacents(1); //No. of page away from the current page
        //making next and previous keyword to be translated
        $p->nextLabel(__("Next", WPL_TEXTDOMAIN));
        $p->prevLabel(__("Previous", WPL_TEXTDOMAIN));

        /** validation for page **/
        if(!wpl_request::getVar('wplpage')) $p->page = 1;
        else $p->page = wpl_request::getVar('wplpage');

        $p->max_page = ceil($num_result / $page_size);
        if($p->page <= 0 or ($p->page > $p->max_page)) $p->page = 1;

        //Query for limit paging
        $p->limit_query = "LIMIT " . ($p->page - 1) * $p->limit . ", " . $p->limit;

        if($show_options)
		{
            $p->show_total = true;
            $p->show_page_size = true;
        }

        return $p;
    }
}