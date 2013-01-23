<!doctype html> <!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!-- Consider adding a manifest.appcache: h5bp.com/d/Offline -->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en">
	<!--<![endif]-->
	<head>
		<meta charset="utf-8">

		<!-- Use the .htaccess and remove these lines to avoid edge case issues.
		More info: h5bp.com/i/378 -->
		<!-- <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->
		<title><?php  echo CHtml::encode($this->pageTitle); ?></title>
		<meta name="description" content="">

		<!-- Mobile viewport optimized: h5bp.com/viewport -->
		<meta name="viewport" content="width=device-width">

		<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print">
		<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/app.css">

		<!-- Place favicon.ico and apple-touch-icon.png in the root directory: mathiasbynens.be/notes/touch-icons -->
		<link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/favicon.ico" type="image/x-icon" />
		<!-- More ideas for your <head> here: h5bp.com/d/head-Tips -->

		<!-- All JavaScript at the bottom, except this Modernizr build.
		Modernizr enables HTML5 elements & feature detects for optimal performance.
		Create your own custom Modernizr build: www.modernizr.com/download/ -->
		<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/vendors/modernizr-2.5.3.min.js"></script>

	</head>

	<body>
		<!-- Prompt IE 7 users to install Chrome Frame. Remove this if you support IE 7 :)
		chromium.org/developers/how-tos/chrome-frame-getting-started -->
		<!--[if lt IE 7]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->

		<!-- MAIN MENU -->
		<?php
		$models = Yii::app()->file->set('application.models');
		//print_r($models->getContents(false, '/(?=^((?!Form).)+$)(php)/xs'));

		$models->getContents(false, 'php');
		//add preg_match filter

		$crud_links = array();
		$excluded = array('Form', 'GBase');

		foreach ($models->getContents(false, 'php') as $i => $model) {
			//we need to figure out a way to get correct url segment for modules.

			$label = basename($model, '.php');

			$continue = FALSE;
			foreach ($excluded as $mask) {
				if (is_int(strpos($label, $mask)))
					$continue = TRUE;
			}

			if ($continue)
				continue;

			$url = '/' . strtolower($label);
			array_push($crud_links, array('label' => $label, 'url' => array($url)));
		}
		?>
		<nav id="main-nav" role="navigation">
			<div id="mainmenu" class="navbar-fixed-top"> 
				<?php $this->widget('bootstrap.widgets.TbNavbar',array(
					'fixed'=>false,
					'brand'=>Yii::app()->name,
					'brandUrl'=>'#',
					'collapse'=>true,
					'items'=>array(
						array(
						'class'=>'bootstrap.widgets.TbMenu',
						'items'=>array(
							array('label'=>'Home', 'url'=>array('/site/index')),
							array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
							array('label'=>'Contact', 'url'=>array('/site/contact')),
							array('label'=>'Models', 'url'=>'#', 'items'=>$crud_links),
							/* array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest), */
							/* array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest), */
							/*  array('label'=>'Rights', 'url'=>array('/rights'), 'visible'=>!Yii::app()->user->isGuest),
							array('url'=>Yii::app()->getModule('user')->loginUrl, 'label'=>Yii::app()->getModule('user')->t("Login"), 'visible'=>Yii::app()->user->isGuest),
							array('url'=>Yii::app()->getModule('user')->registrationUrl, 'label'=>Yii::app()->getModule('user')->t("Register"), 'visible'=>Yii::app()->user->isGuest),
							array('url'=>Yii::app()->getModule('user')->profileUrl, 'label'=>Yii::app()->getModule('user')->t("Profile"), 'visible'=>!Yii::app()->user->isGuest),
							array('url'=>Yii::app()->getModule('user')->logoutUrl, 'label'=>Yii::app()->getModule('user')->t("Logout").' ('.Yii::app()->user->name.')', 'visible'=>!Yii::app()->user->isGuest),
							*/),
							),
						),
					));
				?>
			</div>
		</nav>
		<!--@END MAIN MENU -->
		<?php
			#BUG header does not push content down. 
		?>
		<div class="clear">&nbsp;<br/><br/><br/></div>
		
		<!-- CONTENT: Page -->
		<div id="page">

			<?php echo $content; ?>

			<div class="clear"></div>
			<footer id="footer">
				Copyright &copy; <?php echo date('Y'); ?>
				by My Company.
				<br/>
				All Rights Reserved.
				<br/>
				<?php echo Yii::powered(); ?>
			</footer><!-- footer -->

		</div><!--@END CONTENT: Page -->
		<!-- Asynchronous Google Analytics snippet. Change UA-XXXXX-X to be your site's ID.
		mathiasbynens.be/notes/async-analytics-snippet -->
		<!--script>
		var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
		(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
		g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
		s.parentNode.insertBefore(g,s)}(document,'script'));
		</script-->
	</body>
</html>