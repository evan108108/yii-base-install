<?php
$this->breadcrumbs=array(
	(UserModule::t('Users'))=>array('admin'),
  $model->username=>array('view','id'=>$model->id),
  (UserModule::t('Update')),
);
?>

<h1><?php echo  UserModule::t('Update User')." '".$model->username . "'"; ?></h1>
<br/>
<?php echo $this->renderPartial('_menu', array(
  'list'=> array(
      array('label'=>'Manage User', 'url'=>Yii::app()->baseUrl.'/user/admin'),
      array('label'=>'Create User', 'url'=>Yii::app()->baseUrl.'/user/admin/create'),
      array('label'=>'View User', 'url'=>Yii::app()->baseUrl.'/user/admin/view/id/' . $model->id)
		),
	)); 
  
	echo $this->renderPartial('_form', array('model'=>$model,'profile'=>$profile)); ?>
