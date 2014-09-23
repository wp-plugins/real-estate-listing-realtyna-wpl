<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Tour Service
 * @author Howard <howard@realtyna.com>
 * @date 9/13/2014
 */
class wpl_service_tips
{
    /**
     * Service runner
     * @author Howard <howard@realtyna.com>
     * @return void
     */
	public function run()
	{
        /** return if wpl tour is not called **/
        if(!wpl_request::getVar('wpltour')) return false;
                
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
            <?php foreach($tips as $tip): ?>
            /****************************** Tip(<?php echo $tip['id']; ?>) ******************************/
            var wpltip<?php echo $tip['id']; ?> =
            {
                content: '<?php echo $tip['content']; ?>',
                position:
                {
                    edge: '<?php echo (isset($tip['position']['edge']) ? $tip['position']['edge'] : 'left'); ?>',
                    align: '<?php echo (isset($tip['position']['align']) ? $tip['position']['align'] : 'center'); ?>'
                },
                open: function()
                {
                    <?php if(isset($tip['buttons'][2])): ?>
                    wplj('.wpl-pointer-close').after('<a class="wpl-pointer-primary button-primary"><?php echo $tip['buttons'][2]['label']; ?></a>');
                    wplj('.wpl-pointer-primary').click(function()
                    {
                        wpltip<?php echo $tip['id']; ?>.next();
                    });
                    <?php endif; ?>
                    
                    <?php if(isset($tip['buttons'][3])): ?>
                    wplj('.wpl-pointer-primary').after('<a class="wpl-pointer-prev button-secondary"><?php echo $tip['buttons'][3]['label']; ?></a>');
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
                buttons: function (event, t)
                {
                    var button = wplj('<a class="wpl-pointer-close button-secondary"><?php echo __('Close', WPL_TEXTDOMAIN); ?></a>');

                    button.bind('click.pointer', function () {
                        wpltip<?php echo $tip['id']; ?>.close();
                    });

                    return button;
                },
                prev: function()
                {
                    <?php if(isset($tip['buttons'][3]['code'])) echo $tip['buttons'][3]['code']; ?>
                            
                    wpltip<?php echo $tip['id']; ?>.close();
                    wplpointer<?php echo ($tip['id']-1); ?>.pointer('open');
                },
                next: function()
                {
                    <?php if(isset($tip['buttons'][2]['code'])) echo $tip['buttons'][2]['code']; ?>
                    
                    wpltip<?php echo $tip['id']; ?>.close();
                    wplpointer<?php echo ($tip['id']+1); ?>.pointer('open');
                }
            };

            wplpointer<?php echo $tip['id']; ?> = wplj("<?php echo $tip['selector']; ?>").pointer(wpltip<?php echo $tip['id']; ?>)<?php echo ($tip['id'] == 1 ? '.pointer("open")' : ''); ?>;
            <?php endforeach; ?>
        });
        </script>
        <?php
    }
}