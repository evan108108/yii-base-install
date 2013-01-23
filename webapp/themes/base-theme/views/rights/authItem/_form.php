<div class="form span-12 first">

<?php if( $model->scenario==='update' ): ?>

	<h3><?php echo (Rights::getAuthItemTypeName($model->type) == 'Task')? 'Business' : Rights::getAuthItemTypeName($model->type); ?></h3>

<?php endif; ?>
	
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',
    array(
        'htmlOptions' => array('enctype' => 'multipart/form-data'),
    )); ?>

	<div class="row">
		<?php echo $form->labelEx($model, 'name'); ?>
		<?php echo $form->textField($model, 'name', array('maxlength'=>255, 'class'=>'text-field')); ?>
		<?php echo $form->error($model, 'name'); ?>
		<p class="hint"><?php echo Rights::t('core', 'Do not change the name unless you know what you are doing.'); ?></p>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'description'); ?>
		<?php echo $form->textField($model, 'description', array('maxlength'=>255, 'class'=>'text-field')); ?>
		<?php echo $form->error($model, 'description'); ?>
		<p class="hint"><?php echo Rights::t('core', 'A descriptive name for this item.'); ?></p>
	</div>

	<?php if( Rights::module()->enableBizRule===true ): ?>

		<div class="row">
			<?php echo $form->labelEx($model, 'bizRule'); ?>
			<?php echo $form->textField($model, 'bizRule', array('maxlength'=>255, 'class'=>'text-field')); ?>
			<?php echo $form->error($model, 'bizRule'); ?>
			<p class="hint"><?php echo Rights::t('core', 'Code that will be executed when performing access checking.'); ?></p>
		</div>

	<?php endif; ?>

	<?php if( Rights::module()->enableBizRule===true && Rights::module()->enableBizRuleData ): ?>

		<div class="row">
			<?php echo $form->labelEx($model, 'data'); ?>
			<?php echo $form->textField($model, 'data', array('maxlength'=>255, 'class'=>'text-field')); ?>
			<?php echo $form->error($model, 'data'); ?>
			<p class="hint"><?php echo Rights::t('core', 'Additional data available when executing the business rule.'); ?></p>
		</div>

  <?php endif; ?>

  <?php if(Rights::getAuthItemTypeNamePlural($model->type) == 'Tasks'): ?>
    <?php $model->syncCurrency(); ?>
    <div class="row">
      <?php echo $form->labelEx($model, 'currency'); ?>
      <?php echo $form->dropDownList($model, 'currency', Org::getCurrencyMenu()); ?>
      <?php echo $form->error($model, 'currency'); ?>
		</div>
		<div class="row">
			<?php $model->syncTimezone(); ?>
      <?php echo $form->labelEx($model, 'timezone'); ?>
      <?php echo $form->dropDownList($model, 'timezone', ETimezone::menu()); ?>
      <?php echo $form->error($model, 'timezone'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'doc_header_img'); ?>
        <?php 
            $img_header = Org::getDocImageHeader($model->name);
            if(! empty( $img_header )):?>
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'type'=>'success', // '', 'success', 'warning', 'important', 'info' or 'inverse'
                'label'=>'Image Preview',
                'htmlOptions'=>array('data-title'=>'Image Preview', 'data-content'=>CHtml::image($img_header, 'Image Preview',array('class'=>'image-preview')), 'rel'=>'popover', 'class'=>'span4'),
            )); ?>
            <br />
        <?php else:?>
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'type'=>'warning', // '', 'success', 'warning', 'important', 'info' or 'inverse'
                'label'=>'No Image Preview',
                'htmlOptions'=>array('data-title'=>'No Image Preview', 'class'=>'span4'),
            )); ?>
        <?php endif;?>
      <?php echo $form->fileField($model, 'doc_header_img', array('class'=>'span')); ?>
      <?php echo $form->error($model, 'doc_header_img'); ?>
    </div>
  <?php endif; ?>
  <br/>

	<div class="row buttons">
		<?php echo CHtml::submitButton(Rights::t('core', 'Save')); ?> | <?php echo CHtml::link(Rights::t('core', 'Cancel'), Yii::app()->user->rightsReturnUrl); ?>
	</div>

<?php $this->endWidget(); ?>

</div>
