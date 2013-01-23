<?php $this->breadcrumbs = array(
	'<h3>Rights</h3>'=>Rights::getBaseUrl(),
	Rights::t('core', 'Operations'),
); ?>

<div id="operations">

	<h2><?php echo Rights::t('core', 'Operations'); ?></h2>

	<p>
		<?php echo Rights::t('core', 'An operation defines a group of fields a user may access and actions they may perform.'); ?><br />
		<?php echo Rights::t('core', 'Operations can only inherit from other operations.'); ?>
	</p>

	<p><?php echo CHtml::link(Rights::t('core', 'Create a new operation'), array('authItem/create', 'type'=>CAuthItem::TYPE_OPERATION), array(
		'class'=>'btn btn-primary',
	)); ?></p>

	<?php $this->widget('bootstrap.widgets.TbGridView', array(
	    'dataProvider'=>$dataProvider,
	    'type' => 'striped',
	    'template'=>'{items}',
	    'emptyText'=>Rights::t('core', 'No operations found.'),
	    'htmlOptions'=>array('class'=>'grid-view operation-table sortable-table'),
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
    			'value'=>'$data->getDeleteOperationLink()',
    		),
	    )
	)); ?>

	<p class="info"><?php echo Rights::t('core', 'Values within square brackets tell how many children each item has.'); ?></p>

</div>
