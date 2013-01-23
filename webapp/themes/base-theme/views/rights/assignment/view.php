<?php $this->breadcrumbs = array(
	'<h3>Rights</h3>'=>Rights::getBaseUrl(),
	Rights::t('core', 'Assignments'),
); ?>

<div id="assignments">

	<h2><?php echo Rights::t('core', 'Assignments'); ?></h2>

	<p>
		<?php echo Rights::t('core', 'Here you can view which permissions has been assigned to each user.'); ?>
	</p>

	<?php $this->widget('bootstrap.widgets.TbGridView', array(
	    'dataProvider'=>$dataProvider,
	    'type' => 'striped',
	    'columns'=>array(
    		array(
    			'name'=>'name',
    			'header'=>Rights::t('core', 'Name'),
    			'type'=>'raw',
    			'htmlOptions'=>array('class'=>'name-column'),
    			'value'=>'$data->getAssignmentNameLink()',
    		),
    		array(
    			'name'=>'assignments',
    			'header'=>Rights::t('core', 'Roles'),
    			'type'=>'raw',
    			'htmlOptions'=>array('class'=>'role-column'),
    			'value'=>'$data->getAssignmentsText(CAuthItem::TYPE_ROLE)',
    		),
			array(
    			'name'=>'assignments',
    			'header'=>Rights::t('core', 'Businesses'),
    			'type'=>'raw',
    			'htmlOptions'=>array('class'=>'task-column'),
    			'value'=>'$data->getAssignmentsText(CAuthItem::TYPE_TASK)',
    		),
			array(
    			'name'=>'assignments',
    			'header'=>Rights::t('core', 'Operations'),
    			'type'=>'raw',
    			'htmlOptions'=>array('class'=>'operation-column'),
    			'value'=>'$data->getAssignmentsText(CAuthItem::TYPE_OPERATION)',
    		),
	    )
	)); ?>

</div>
