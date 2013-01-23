<?php
$this->breadcrumbs=array(
	"<h3>".UserModule::t('Users')."</h3>" => array('admin'),
	UserModule::t('Create'),
);
?>
<h1><?php echo UserModule::t("Create User"); ?></h1>
<br/>
<?php echo $this->renderPartial('_menu', array(
		'list'=> array(
			//CHtml::linkButton(UserModule::t('Delete User'),array('submit'=>array('delete','id'=>$model->id),'confirm'=>UserModule::t('Are you sure to delete this item?'))),
      array('label'=>'Manage User', 'url'=>Yii::app()->baseUrl.'/user/admin'),
    ),
  )); 
	echo $this->renderPartial('_menu',array(
		'list'=> null,
	));
	echo $this->renderPartial('_form', array('model'=>$model,'profile'=>$profile));
?>
