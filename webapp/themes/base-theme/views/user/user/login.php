<?php
$this->pageTitle=Yii::app()->name . ' - '.UserModule::t("Login");
?>

<?php $this->breadcrumbs = array(UserModule::t("Login")); ?>

<?php /** @var BootActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'verticalForm',
    'htmlOptions'=>array('class'=>'well'),
)); ?>
 
<?php echo $form->textFieldRow($model, 'username', array('class'=>'span3')); ?>
<?php echo $form->passwordFieldRow($model, 'password', array('class'=>'span3')); ?>
<?php echo $form->checkboxRow($model, 'rememberMe'); ?>
<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'icon'=>'ok', 'label'=>'Submit')); ?>
 
<?php $this->endWidget(); ?>
