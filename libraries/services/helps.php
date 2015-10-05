<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Helps Service
 * @author Howard <howard@realtyna.com>
 * @date 9/13/2014
 * @package WPL
 */
class wpl_service_helps
{
    /**
     * Service runner
     * @author Howard <howard@realtyna.com>
     * @return void
     */
	public function run()
	{
        /** Run WPL Tour **/
        if(wpl_request::getVar('wpltour')) $this->tour();
        
        /** Run WPL Help **/
        if(wpl_request::getVar('wpltour')) $this->help();
	}
    
    public function help()
    {
        add_filter('contextual_help', array($this, 'show_help_tab'), 10, 3);
    }
    
    public function show_help_tab($contextual_help, $screen_id, $screen)
    {
        /** Don't run if it's not WPL Page **/
        if($screen->parent_base != 'WPL_main_menu') return;
        
        $page = wpl_request::getVar('page', '');
        
        /** First Validation **/
        if(!trim($page)) return false;
        
        $tabs = array();
        
        $path = _wpl_import('assets.helps.'.$page, true, true);
        if(wpl_file::exists($path)) $tabs = include_once $path;
        
        /** No Help **/
        if(!is_array($tabs) or (is_array($tabs) and !count($tabs))) return false;
        
        $screen = get_current_screen();
        
        foreach($tabs['tabs'] as $tab)
        {
            /** Add Help Tab **/
            $screen->add_help_tab(array('id'=>$tab['id'], 'title'=>$tab['title'], 'content'=>$tab['content']));
        }
        
        if(!isset($tabs['sidebar'])) $tabs['sidebar'] = array('content'=>'<a class="wpl_contextual_help_tour button" href="'.wpl_global::add_qs_var('wpltour', 1).'">'.__('Introduce Tour', WPL_TEXTDOMAIN).'</a>');
        $screen->set_help_sidebar($tabs['sidebar']['content']);
    }
    
    public function tour()
    {
        add_action('admin_enqueue_scripts', array($this, 'import_styles_scripts'), 0);
        add_action('admin_print_footer_scripts', array($this, 'show_tips'), 0);
    }
    
    public function import_styles_scripts()
    {
        wp_enqueue_style('wp-pointer');
        wp_enqueue_script('wp-pointer');
    }
    
    public function show_tips()
    {
        $page = wpl_request::getVar('page', '');
        
        /** First Validation **/
        if(!trim($page)) return false;
        
        $tips = array();
        
        $path = _wpl_import('assets.tips.'.$page, true, true);
        if(wpl_file::exists($path)) $tips = include_once $path;
        
        /** Generate script **/
        $this->generate_scripts($tips);
    }
    
    public function generate_scripts($tips = array())
    {
        if(!count($tips)) return false;
        ?>
        <script type="text/javascript">
        wplj(document).ready(function()
        {
            <?php foreach($tips as $key=>$tip): ?>
            /****************************** Tip(<?php echo $tip['id']; ?>) ******************************/
            var wpltip<?php echo $tip['id']; ?> =
            {
                content: '<?php echo addslashes($tip['content']); ?>',
                position:
                {
                    edge: '<?php echo (isset($tip['position']['edge']) ? $tip['position']['edge'] : 'left'); ?>',
                    align: '<?php echo (isset($tip['position']['align']) ? $tip['position']['align'] : 'center'); ?>'
                },
                open: function()
                {
                    <?php if(isset($tip['buttons'][2])): ?>
                    wplj('.wp-pointer-buttons').append('<a class="wpl-pointer-primary button-primary wpl-btn-next"><?php echo $tip['buttons'][2]['label']; ?></a>');
                    wplj('.wpl-pointer-primary').click(function()
                    {
                        wpltip<?php echo $tip['id']; ?>.next();
                    });
                    <?php endif; ?>
                    
                    <?php if(isset($tip['buttons'][3])): ?>
                    wplj('.wp-pointer-buttons').append('<a class="wpl-pointer-prev button-secondary wpl-btn-prev"><?php echo $tip['buttons'][3]['label']; ?></a>');
                    wplj('.wpl-pointer-prev').click(function()
                    {
                        wpltip<?php echo $tip['id']; ?>.prev();
                    });
                    <?php endif; ?>
                },
                close: function()
                {
                    wplpointer<?php echo $tip['id']; ?>.pointer('close');
                },
                buttons: function(event, t)
                {
                    var button = wplj('<a class="wpl-pointer-close button-secondary wpl-btn-close"><?php echo __('Close', WPL_TEXTDOMAIN); ?></a>');
                    button.bind('click.pointer', function()
                    {
                        wpltip<?php echo $tip['id']; ?>.close();
                    });
                    
                    return button;
                },
                prev: function()
                {
                    <?php if(isset($tip['buttons'][3]['code'])) echo $tip['buttons'][3]['code']; ?>
                            
                    wpltip<?php echo $tip['id']; ?>.close();
                    wplpointer<?php echo ($tip['id']-1); ?>.pointer('open');
                    <?php if(isset($tips[($key-1)])): ?>Realtyna.scrollTo('<?php echo $tips[($key-1)]['selector']; ?>', -300);<?php endif; ?>
                },
                next: function()
                {
                    <?php if(isset($tip['buttons'][2]['code'])) echo $tip['buttons'][2]['code']; ?>
                    
                    wpltip<?php echo $tip['id']; ?>.close();
                    wplpointer<?php echo ($tip['id']+1); ?>.pointer('open');
                    <?php if(isset($tips[($key+1)])): ?>Realtyna.scrollTo('<?php echo $tips[($key+1)]['selector']; ?>', -300);<?php endif; ?>
                }
            };

            wplpointer<?php echo $tip['id']; ?> = wplj("<?php echo $tip['selector']; ?>").pointer(wpltip<?php echo $tip['id']; ?>)<?php echo ($tip['id'] == 1 ? '.pointer("open")' : ''); ?>;
            <?php endforeach; ?>
        });
        </script>
        <?php
    }
}