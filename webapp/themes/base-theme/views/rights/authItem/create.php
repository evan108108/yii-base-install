<?php $this->breadcrumbs = array(
	'<h3>Rights</h3>'=>Rights::getBaseUrl(),
	Rights::t('core', 'Create :type', array(':type'=>(Rights::getAuthItemTypeName($_GET['type']) == 'Task')? 'Business': Rights::getAuthItemTypeName($_GET['type']))),
); ?>

<div class="createAuthItem">

	<h2><?php echo Rights::t('core', 'Create :type', array(
		':type'=>(Rights::getAuthItemTypeName($_GET['type']) == 'Task')? 'Business' : Rights::getAuthItemTypeName($_GET['type']),
	)); ?></h2>

	<?php $this->renderPartial('_form', array('model'=>$formModel)); ?>

</div>
