<?php $this->breadcrumbs = array(
	'<h3>Rights</h3>'=>Rights::getBaseUrl(),
	Rights::t('core', 'Businesses'),
); ?>

<div id="tasks">

	<h2><?php echo Rights::t('core', 'Businesses'); ?></h2>

	<p>
		<?php echo Rights::t('core', 'A business is an independent set of contacts and inventory.'); ?><br />
		<?php echo Rights::t('core', 'Businesses can inherit from other businesses.'); ?>
	</p>

	<p><?php echo CHtml::link(Rights::t('core', 'Create a new Business'), array('authItem/create', 'type'=>CAuthItem::TYPE_TASK), array(
		'class'=>'btn btn-primary',
	)); ?></p>
   
	<?php $this->widget('bootstrap.widgets.TbGridView', array(
	    'dataProvider'=>$dataProvider,
	    'type' => 'striped',
	    'template'=>'{items}',
	    'emptyText'=>Rights::t('core', 'No tasks found.'),
	    'htmlOptions'=>array('class'=>'grid-view task-table'),
	    'columns'=>array(
    		array(
    			'name'=>'name',
    			'header'=>Rights::t('core', 'Name'),
    			'type'=>'raw',
    			'htmlOptions'=>array('class'=>'name-column'),
    			'value'=>'$data->getGridNameLink()',
    		),
    		array(
    			'name'=>'description',
    			'header'=>Rights::t('core', 'Description'),
    			'type'=>'raw',
    			'htmlOptions'=>array('class'=>'description-column'),
    		),
    		array(
    			'name'=>'bizRule',
    			'header'=>Rights::t('core', 'Business rule'),
    			'type'=>'raw',
    			'htmlOptions'=>array('class'=>'bizrule-column'),
    			'visible'=>Rights::module()->enableBizRule===true,
    		),
    		array(
    			'name'=>'data',
    			'header'=>Rights::t('core', 'Data'),
    			'type'=>'raw',
    			'htmlOptions'=>array('class'=>'data-column'),
    			'visible'=>Rights::module()->enableBizRuleData===true,
    		),
    		array(
    			'header'=>'&nbsp;',
    			'type'=>'raw',
    			'htmlOptions'=>array('class'=>'actions-column'),
    			'value'=>'$data->getDeleteTaskLink()',
    		),
	    )
	)); ?>

	<p class="info"><?php echo Rights::t('core', 'Values within square brackets tell how many children each item has.'); ?></p>

</div>
