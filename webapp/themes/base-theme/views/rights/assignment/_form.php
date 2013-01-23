<div class="form">

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm'); ?>
	
	<div class="row">
		<?php echo $form->dropDownList($model, 'itemname', $itemnameSelectOptions); ?>
		<?php echo $form->error($model, 'itemname'); ?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton(Rights::t('core', 'Assign'), array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>

</div>
