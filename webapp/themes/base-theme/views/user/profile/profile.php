<?php $this->pageTitle=Yii::app()->name . ' - '.UserModule::t("Profile");?>

<?php $this->breadcrumbs =  array(UserModule::t("My Profile")); ?>

<h2><?php echo UserModule::t('My profile'); ?></h2>
<br/>
<?php echo $this->renderPartial('menu'); ?>

<?php if(Yii::app()->user->hasFlash('profileMessage')): ?>
<div class="success">
<?php echo Yii::app()->user->getFlash('profileMessage'); ?>
</div>
<?php endif; ?>

<?php 
  $profileData = array();
  $profileData[($model->getAttributeLabel('username'))] = $model->username;
  $profileFields=ProfileField::model()->forOwner()->sort()->findAll();
	if ($profileFields) {
    foreach($profileFields as $field)
      $profileData[($field->title)] = (($field->widgetView($profile))?$field->widgetView($profile):(($field->range)?Profile::range($field->range,$profile->getAttribute($field->varname)):$profile->getAttribute($field->varname)));
  }
  $profileData[($model->getAttributeLabel('email'))] = $model->email;
  $profileData[($model->getAttributeLabel('createtime'))] =date('Y-m-d', $model->createtime);
  $profileData[($model->getAttributeLabel('lastvisit'))] = date('Y-m-d', $model->lastvisit);
  $profileData[(User::itemAlias("UserStatus",$model->status))] = ($model->status)? 'yes':'no';
  
  $profileKeys = array();
  foreach($profileData as $key=>$value)
    $profileKeys[] = array('name'=>$key, 'label'=>$key);

  $this->widget('bootstrap.widgets.TbDetailView', array(
    'data'=>$profileData,
    'attributes'=>$profileKeys,
  ));
?>
