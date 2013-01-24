<?php $this->beginContent('//layouts/html5'); ?>
<section class="container">
	<div class='row'>
		<div class="span8">
			<article id="content">
				<?php echo $content; ?>
			</article><!-- content -->
		</div>
		<div class="span4 last">
			<aside id="sidebar1">
			<?php
				$this->widget('bootstrap.widgets.TbMenu', array(
				    'type'=>'pills', // '', 'tabs', 'pills' (or 'list')
				    'stacked'=>TRUE, // whether this is a stacked menu
				    'items'=>array(
				        array('label'=>'Dashboard', 'url'=>$this->createUrl('/deckjs/dashboard'), 'active'=>true),
				        array('label'=>'Presentations', 'url'=>$this->createUrl('presentation/admin')),
				        array('label'=>'Slides', 'url'=> $this->createUrl('slide/admin')),
				        array('label'=>'Slide Content', 'url'=>$this->createUrl('slidecontent/admin')),
				    ),
			    ));
				$this->widget('bootstrap.widgets.TbMenu', array(			 	
					'type' => 'list',
					'items'=>$this->menu,
					'htmlOptions'=>array('class'=>'well operations'),
				));
			?>
			</aside><!-- sidebar -->
		</div>
	</div>
</section>
<?php $this->endContent(); ?>