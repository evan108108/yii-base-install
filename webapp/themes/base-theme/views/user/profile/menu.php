<?php 
if(UserModule::isAdmin()) {
  $userList = array('label'=>'Manage User', 'url'=>Yii::app()->createUrl('user/admin'));
}


$this->widget('bootstrap.widgets.TbMenu', array(
  'type'=>'tabs', // '', 'tabs', 'pills' (or 'list')
  'stacked'=>false, // whether this is a stacked menu

  'items'=>array(
      array('label'=>'My Profile', 'url'=>UserModule::t('Profile'), 'active'=>($this->activePage=='profile')?true:false),
      array('label'=>'Edit', 'url'=>Yii::app()->createUrl('user/profile/edit'), 'active'=>($this->activePage=='edit')?true:false),
      array('label'=>'Change Password', 'url'=>Yii::app()->createUrl('user/profile/changepassword'), 'active'=>($this->activePage=='changepassword')?true:false),
      array('label'=>'logout', 'url'=>Yii::app()->createUrl('user/logout')),
  ),
)); ?>

