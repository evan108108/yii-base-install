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
 * TODO Make public $theme, setter/getter. Check for dot, on call register CSS use $this->theme, if parameter, setTheme.
 * TODO Make widget for wysihtml5. Figure out a way to make template for custom commands and pannels..
 * 
 * Next:
 * - Sticky: 	 http://s.mechanism.name/bootstrap-addons/#sticky
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
class Bootstrap extends CApplicationComponent
{
	// Bootstrap plugins.
	const PLUGIN_ALERT = 'alert';
	const PLUGIN_BUTTON = 'button';
	const PLUGIN_CAROUSEL = 'carousel';
	const PLUGIN_COLLAPSE = 'collapse';
	const PLUGIN_DROPDOWN = 'dropdown';
	const PLUGIN_MODAL = 'modal';
	const PLUGIN_POPOVER = 'popover';
	const PLUGIN_SCROLLSPY = 'scrollspy';
	const PLUGIN_TAB = 'tab';
	const PLUGIN_TOOLTIP = 'tooltip';
	const PLUGIN_TRANSITION = 'transition';
	const PLUGIN_TYPEAHEAD = 'typeahead';
	
	//Added plugins
	const PLUGIN_WYSIHTML5 = 'wysihtml5';

	/**
	 * @var boolean whether to register the Bootstrap core CSS (bootstrap.min.css).
	 * Defaults to true.
	 */
	public $coreCss = true;
	/**
	 * @var boolean whether to register the Bootstrap responsive CSS (bootstrap-responsive.min.css).
	 * Defaults to false.
	 */
	public $responsiveCss = false;
	/**
	 * @var boolean whether to register jQuery and the Bootstrap JavaScript.
	 * @since 0.9.10
	 */
	public $enableJS = true;
	/**
	 * @var array the plugin options (name=>options).
	 * @since 0.9.8
	 */
	public $plugins = array();

	protected $_assetsUrl;
	protected $_rp = array();

	/**
	 * Initializes the component.
	 */
	public function init()
	{
		// Register the bootstrap path alias.
		if (!Yii::getPathOfAlias('bootstrap'))
			Yii::setPathOfAlias('bootstrap', realpath(dirname(__FILE__).'/..'));

		// Prevents the extension from registering scripts
		// and publishing assets when ran from the command line.
		if (php_sapi_name() === 'cli')
			return;

		if ($this->coreCss)
			$this->registerCss();

		if ($this->responsiveCss)
			$this->registerResponsiveCss();

		$this->registerYiiCss();

		if ($this->enableJS)
			$this->registerCorePlugins();
	}

	/**
	 * @param string $theme Bootstrap theme name, with dot! 
	 * @see http://bootswatch.com/
	 * 
	 * Registers the Bootstrap CSS.
	 */
	public function registerCss($theme = '')
	{
		//TODO We should not assume dot is there ;) 
		Yii::app()->clientScript->registerCssFile($this->getAssetsUrl()."/css/{$theme}bootstrap.min.css");
	}

	/**
	 * Registers the Bootstrap responsive CSS.
	 * @since 0.9.8
	 */
	public function registerResponsiveCss()
	{
		/** @var CClientScript $cs */
		$cs = Yii::app()->getClientScript();
		$cs->registerMetaTag('width=device-width, initial-scale=1.0', 'viewport');
		$cs->registerCssFile($this->getAssetsUrl().'/css/bootstrap-responsive.min.css');
	}

	/**
	 * Registers the Yii-specific CSS missing from Bootstrap.
	 * @since 0.9.11
	 */
	public function registerYiiCss()
	{
		Yii::app()->clientScript->registerCssFile($this->getAssetsUrl().'/css/bootstrap-yii.css');
	}

	/**
	 * Registers the core JavaScript plugins.
	 * @since 0.9.8
	 */
	public function registerCorePlugins()
	{
		Yii::app()->clientScript->registerCoreScript('jquery');

		if (!$this->isPluginDisabled(self::PLUGIN_TRANSITION))
			$this->enableTransitions();

		if (!$this->isPluginDisabled(self::PLUGIN_TOOLTIP))
			$this->registerTooltip();

		if (!$this->isPluginDisabled(self::PLUGIN_POPOVER))
			$this->registerPopover();
		
		/*
		 * We register the Wysihtml5 pluging.
		 * TODO make widget.
		 */ 
		 /*if (!$this->isPluginDisabled(self::PLUGIN_WYSIHTML5))
			$this->registerWysihtml5();*/
	}

	/**
	 * Enables the Bootstrap transitions plugin.
	 * @since 0.9.8
	 */
	public function enableTransitions()
	{
		$this->registerPlugin(self::PLUGIN_TRANSITION);
	}

	/**
	 * Registers the Bootstrap alert plugin.
	 * @param string $selector the CSS selector
	 * @param array $options the plugin options
	 * @see http://twitter.github.com/bootstrap/javascript.html#alerts
	 * @since 0.9.8
	 */
	public function registerAlert($selector = null, $options = array())
	{
		$this->registerPlugin(self::PLUGIN_ALERT, $selector, $options);
	}

	/**
	 * Registers the Bootstrap buttons plugin.
	 * @param string $selector the CSS selector
	 * @param array $options the plugin options
	 * @see http://twitter.github.com/bootstrap/javascript.html#buttons
	 * @since 0.9.8
	 */
	public function registerButton($selector = null, $options = array())
	{
		$this->registerPlugin(self::PLUGIN_BUTTON, $selector, $options);
	}

	/**
	 * Registers the Bootstrap carousel plugin.
	 * @param string $selector the CSS selector
	 * @param array $options the plugin options
	 * @see http://twitter.github.com/bootstrap/javascript.html#carousel
	 * @since 0.9.8
	 */
	public function registerCarousel($selector = null, $options = array())
	{
		$this->registerPlugin(self::PLUGIN_CAROUSEL, $selector, $options);
	}

	/**
	 * Registers the Bootstrap collapse plugin.
	 * @param string $selector the CSS selector
	 * @param array $options the plugin options
	 * @see http://twitter.github.com/bootstrap/javascript.html#collapse
	 * @since 0.9.8
	 */
	public function registerCollapse($selector = null, $options = array())
	{
		$this->registerPlugin(self::PLUGIN_COLLAPSE, $selector, $options, '.collapse');
	}

	/**
	 * Registers the Bootstrap dropdowns plugin.
	 * @param string $selector the CSS selector
	 * @param array $options the plugin options
	 * @see http://twitter.github.com/bootstrap/javascript.html#dropdowns
	 * @since 0.9.8
	 */
	public function registerDropdown($selector = null, $options = array())
	{
		$this->registerPlugin(self::PLUGIN_DROPDOWN, $selector, $options, '.dropdown-toggle[data-dropdown="dropdown"]');
	}

	/**
	 * Registers the Bootstrap modal plugin.
	 * @param string $selector the CSS selector
	 * @param array $options the plugin options
	 * @see http://twitter.github.com/bootstrap/javascript.html#modal
	 * @since 0.9.8
	 */
	public function registerModal($selector = null, $options = array())
	{
		$this->registerPlugin(self::PLUGIN_MODAL, $selector, $options);
	}

	/**
	 * Registers the Bootstrap scrollspy plugin.
	 * @param string $selector the CSS selector
	 * @param array $options the plugin options
	 * @see http://twitter.github.com/bootstrap/javascript.html#scrollspy
	 * @since 0.9.8
	 */
	public function registerScrollSpy($selector = null, $options = array())
	{
		$this->registerPlugin(self::PLUGIN_SCROLLSPY, $selector, $options);
	}

	/**
	 * Registers the Bootstrap popover plugin.
	 * @param string $selector the CSS selector
	 * @param array $options the plugin options
	 * @see http://twitter.github.com/bootstrap/javascript.html#popover
	 * @since 0.9.8
	 */
	public function registerPopover($selector = null, $options = array())
	{
		$this->registerTooltip(); // Popover requires the tooltip plugin
		$this->registerPlugin(self::PLUGIN_POPOVER, $selector, $options, 'a[rel="popover"]');
	}

	/**
	 * Registers the Bootstrap tabs plugin.
	 * @param string $selector the CSS selector
	 * @param array $options the plugin options
	 * @see http://twitter.github.com/bootstrap/javascript.html#tabs
	 * @since 0.9.8
	 */
	public function registerTabs($selector = null, $options = array())
	{
		$this->registerPlugin(self::PLUGIN_TAB, $selector, $options);
	}

	/**
	 * Registers the Bootstrap tooltip plugin.
	 * @param string $selector the CSS selector
	 * @param array $options the plugin options
	 * @see http://twitter.github.com/bootstrap/javascript.html#tooltip
	 * @since 0.9.8
	 */
	public function registerTooltip($selector = null, $options = array())
	{
		$this->registerPlugin(self::PLUGIN_TOOLTIP, $selector, $options, 'a[rel="tooltip"]');
	}

	/**
	 * Registers the Bootstrap typeahead plugin.
	 * @param string $selector the CSS selector
	 * @param array $options the plugin options
	 * @see http://twitter.github.com/bootstrap/javascript.html#typeahead
	 * @since 0.9.8
	 */
	public function registerTypeahead($selector = null, $options = array())
	{
		$this->registerPlugin(self::PLUGIN_TYPEAHEAD, $selector, $options);
	}
	
	/**
	 * Registers the Bootstrap wysihtml5 extended plugin.
	 * @param string $selector the CSS selector
	 * @param array $options the plugin options
	 * @see https://github.com/jhollingworth/bootstrap-wysihtml5/
	 * @since 0.9.8
	 */
	public function registerWysihtml5($selector = null, $options = array())
	{
		/** @var CClientScript $cs */
		$cs = Yii::app()->getClientScript();
		$cs->registerCssFile($this->getAssetsUrl().'/css/bootstrap-wysihtml5.css');
		
		$this->registerModal(); // Popover requires the modal plugin
		
		$this->registerPlugin(self::PLUGIN_WYSIHTML5, $selector, $options,'.wysihtml5');
	}

	/**
	 * Sets the target element for the scrollspy.
	 * @param string $selector the CSS selector
	 * @param string $target the target CSS selector
	 * @param string $offset the offset
	 */
	public function spyOn($selector, $target = null, $offset = null)
	{
		$script = "jQuery('{$selector}').attr('data-spy', 'scroll');";

		if (isset($target))
			$script .= "jQuery('{$selector}').attr('data-target', '{$target}');";

		if (isset($offset))
			$script .= "jQuery('{$selector}').attr('data-offset', '{$offset}');";

		Yii::app()->clientScript->registerScript(__CLASS__.'.spyOn.'.$selector, $script, CClientScript::POS_BEGIN);
	}

	/**
	 * Returns whether a plugin is registered.
	 * @param string $name the name of the plugin
	 * @return boolean the result
	 */
	public function isPluginRegistered($name)
	{
		return isset($this->_rp[$name]);
	}

	/**
	 * Returns whether a plugin is disabled in the plugin configuration.
	 * @param string $name the name of the plugin
	 * @return boolean the result
	 * @since 0.9.8
	 */
	protected function isPluginDisabled($name)
	{
		return isset($this->plugins[$name]) && $this->plugins[$name] === false;
	}

	/**
	 * Registers a Bootstrap JavaScript plugin.
	 * @param string $name the name of the plugin
	 * @param string $selector the CSS selector
	 * @param array $options the plugin options
	 * @param string $defaultSelector the default CSS selector
	 * @since 0.9.8
	 */
	protected function registerPlugin($name, $selector = null, $options = array(), $defaultSelector = null)
	{
		if (!$this->isPluginRegistered($name))
		{
			$this->registerScriptFile("bootstrap-{$name}.js");
			$this->_rp[$name] = true;
		}

		if (!isset($selector) && empty($options))
		{
			// Initialization from extension configuration.
			$config = isset($this->plugins[$name]) ? $this->plugins[$name] : array();

			if (isset($config['selector']))
				$selector = $config['selector'];

			if (isset($config['options']))
				$options = $config['options'];

			if (!isset($selector))
				$selector = $defaultSelector;
		}

		if (isset($selector))
		{
			$key = __CLASS__.'.'.md5($name.$selector.serialize($options).$defaultSelector);
			$options = !empty($options) ? CJavaScript::encode($options) : '';
			Yii::app()->clientScript->registerScript($key, "jQuery('{$selector}').{$name}({$options});");
		}
	}

	/**
	 * Registers a JavaScript file in the assets folder.
	 * @param string $fileName the file name.
     * @param integer $position the position of the JavaScript file.
	 */
	protected function registerScriptFile($fileName, $position=CClientScript::POS_END)
	{
		Yii::app()->clientScript->registerScriptFile($this->getAssetsUrl().'/js/'.$fileName, $position);
	}

	/**
	* Returns the URL to the published assets folder.
	* @return string the URL
	*/
	protected function getAssetsUrl()
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