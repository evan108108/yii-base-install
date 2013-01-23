<div class="form" style="padding-left:18px;">

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm'); ?>
	
	<div class="row">
		<?php echo $form->dropDownList($model, 'itemname', $itemnameSelectOptions); ?>
		<?php echo $form->error($model, 'itemname'); ?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton(Rights::t('core', 'Add')); ?>
	</div>

<?php $this->endWidget(); ?>

</div>
