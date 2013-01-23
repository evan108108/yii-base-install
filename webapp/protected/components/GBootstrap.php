<?php
/**
 * Bootstrap class file.
 * @author Christoffer Niska <ChristofferNiska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2011-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version 0.9.11
 */
 
/**
 * @author Goliatone <burgosemi@gmail.com>
 * REVISION Extend Bootstrap.php, add methods, then in main.php: 'class'=>'ext.gbootstrap.components.GBootstrap'.
 * 
 *
 * Modified:
 * -Added wysihtml5.
 * -Added theming options.
 * 
 * DONE Make public $theme, setter/getter. Check for dot, on call register CSS use $this->theme, if parameter, setTheme. 
 * TODO Make widget for wysihtml5. Figure out a way to make template for custom commands and pannels..
 * 
 * Next:
 * - Sticky:     http://s.mechanism.name/bootstrap-addons/#sticky
 * - Timepicker: https://github.com/jdewit/bootstrap-timepicker
 * - UplaodKit:  https://github.com/entropillc/UploadKit
 * 
 * See:
 * - Wysihtml5:  https://github.com/jhollingworth/bootstrap-wysihtml5/
 * - Bootswatch: https://github.com/thomaspark/bootswatch
 *
 */
 
/**
 * Bootstrap application component.
 * Used for registering Bootstrap core functionality.
 */
Yii::import('ext.bootstrap.components.Bootstrap');

class GBootstrap extends Bootstrap
{
    // Bootstrap plugins.
    
    
    //Added plugins
    const PLUGIN_WYSIHTML5  = 'wysihtml5';
    const PLUGIN_DATEPICKER = 'datepicker';
    
    /**
     * @var string default popover CSS selector.
     * @since 0.10.0
     */
    public $popoverSelector = '[rel="popover"]';
    
    /**
     * We override this so that any element can 
     * use a tooltip :)
     * 
     * @var string default tooltip CSS selector.
     */
    public $tooltipSelector = '[rel="tooltip"]';
    /**
     * @var string Represents the bootstrap theme.
     */
    // public $theme = 'light-grey';
    public $theme = '';
    
    public $theme_base_name = 'bootstrap.min.css';
    
    public $theme_path = FALSE;
    
    public function init(){
        //TODO: Maybe here we want to disable this in case we are
        //doing an ajax request?
        
         
        
        parent::init();
    }
    
    /**
     * @param string $theme Bootstrap theme name, with dot! 
     * @see http://bootswatch.com/
     * 
     * Registers the Bootstrap CSS.
     */
    public function registerCoreCss()
    {
        /*
         * TODO: Check to see if we have theme_base_path, if not
         * we use assets url.
         * We HAVE to do this here, does not work in init!!
         */ 
        if(!$this->theme_path)
            $this->theme_path = $this->getAssetsUrl().DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR;
        
        $this->_registerCss($this->theme);
    }
    
    protected function _registerCss($theme = FALSE)
    {
        $theme = $this->makeTheme($theme);
        Yii::app()->clientScript->registerCssFile($theme);
        // Yii::app()->clientScript->registerCssFile($this->getAssetsUrl()."/css/{$theme}bootstrap.min.css");
    }
    
    public function makeTheme($theme)
    {
        $root = $this->theme_base_name;
        
        if($theme)
        {
            //Make sure that dot present
            $theme = rtrim($theme, '.') . '.';
            
            $assetsPath = Yii::getPathOfAlias('bootstrap.assets.css').DIRECTORY_SEPARATOR;
            //is_file() valid theme?
            
            /* 
             * We do all this so that we can have style themes outside of the 
             * extension folder...
             * 
             * Here the issue is that, possible we might have the path as a
             * relative url element, but we check for an actual file path.
             * TODO: Integrate paths with asset manager, to register and cache.
             */ 
            if(is_file($this->theme_path.$theme.$root) ||
               is_file($assetsPath.$theme.$root)       ||
               //TODO: TIU!! WTF, fixme please. 
               is_file(Yii::getPathOfAlias('webroot').'/../..'.$this->theme_path.$theme.$root))
                $this->theme = $theme;    
            else exit(Yii::getPathOfAlias('webroot').'/../..'.$this->theme_path.$theme.$root.' adfadf adf adsf asdf');          
            // else Yii::log("Bootstrap theme {$theme} was not found. Make sure that a file {$theme}{$root} exists in your CSS dir.", 'error', 'bootstrap');
        }
        
        return $this->theme_path.$this->theme.$root;
    }
    
    public function setTheme($theme)
    {
        $this->makeTheme($theme);
    }
    
    
    /**
     * Registers the Bootstrap wysihtml5 extended plugin.
     * @param string $selector the CSS selector
     * @param array $options the plugin options
     * @see https://github.com/jhollingworth/bootstrap-wysihtml5/
     * @since 0.9.8
     */
    public function registerWysihtml5($selector = NULL, $options = array())
    {
        /** @var CClientScript $cs */
        $cs = Yii::app()->getClientScript();
        $cs->registerCssFile($this->getAssetsUrl().'/css/bootstrap-wysihtml5.css');
        $cs->registerCssFile($this->getAssetsUrl().'/css/bootstrap-wysihtml5.content_style.css');
        $position = CClientScript::POS_END;
        $cs->registerScriptFile($this->getAssetsUrl().'/js/bootstrap-wysihtml5.parser_rules.js', $position);   
        $cs->registerScriptFile($this->getAssetsUrl().'/js/bootstrap-wysihtml5.js', $position);   
        
        $this->registerModal(); // Popover requires the modal plugin
        
        $this->registerPlugin(self::PLUGIN_WYSIHTML5, $selector, $options,'.wysihtml5');
    }

    public function registerCkeditor($selector = NULL, $options = array())
    {
        /** @var CClientScript $cs */
        $cs = Yii::app()->getClientScript();
        $cs->registerCssFile($this->getAssetsUrl().'/css/bootstrap-ckeditor.css');
        $position = CClientScript::POS_END;
        $cs->registerScriptFile($this->getAssetsUrl().'/js/bootstrap-ckeditor.js', $position);   
        $cs->registerScriptFile($this->getAssetsUrl().'/js/bootstrap-ckeditor-jquery.js', $position);   
        
        $this->registerModal(); // Popover requires the modal plugin
        
        $this->registerPlugin(self::PLUGIN_WYSIHTML5, $selector, $options,'.wysihtml5');
    }
    
    public function registerDatepicker($selector = NULL, $options = array())
    {
         /** @var CClientScript $cs */
        $cs = Yii::app()->getClientScript();
        $cs->registerCssFile($this->getAssetsUrl().'/css/datepicker2.bootstrap.css');
        $position = CClientScript::POS_END;
        $cs->registerScriptFile($this->getAssetsUrl().'/js/bootstrap-datepicker2.js', $position);        
        
        //$options = CMap::mergeArray($options,array('setStartDate'=>date('Y-m-d')));
        //die(CJavaScript::encode($options));
        $this->registerPlugin(self::PLUGIN_DATEPICKER, $selector, $options,'.datepicker');
    }
    
    
    /**
    * Returns the URL to the published assets folder.
    * @return string the URL
    */
    public function getAssetsUrl()
    {
        if ($this->_assetsUrl !== null)
            return $this->_assetsUrl;
        else
        {
            $assetsPath = Yii::getPathOfAlias('bootstrap.assets');

            if (YII_DEBUG)
                $assetsUrl = Yii::app()->assetManager->publish($assetsPath, false, -1, true);
            else
                $assetsUrl = Yii::app()->assetManager->publish($assetsPath);

            return $this->_assetsUrl = $assetsUrl;
        }
    }
}