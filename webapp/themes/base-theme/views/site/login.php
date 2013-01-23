<?php 
#TODO:Move this into component initialization config option in main.
Yii::app()->bootstrap->registerButton();
Yii::app()->bootstrap->registerModal();		
?>

<?php
#TODO Ugly, move this into app.js, does not belong here! 
#Problem is, if we change the login form id, we are screwed.
Yii::app()->clientScript->registerScript('show-login-screen', "
$('.login-submit').click(function() {
	$(this).button('loading');
	jQuery.ajax({
		'cache':'false',
		'type':'POST',
		'dataType':'json',
		'data':$('#login-form').serialize(),		
		'beforeSend':function(jqXHR, settings) {
		},
		/*'complete':function(){
			//TODO Just to bypass screen in demo1.
			if(window._isAdmin) return;
			 
			$('#login-dashboard').modal({show:true,keyboard:false,backdrop:'static'}).css({
		        width: 'auto',
		        'margin-left': function () {
		        	//We should get each thumbnails child width, and add.
		            return -Math.round($('.thumbnails').width() / 2);
		        }
		    }).on('hidden',function(){if(window._redirect)window.location.replace(window._redirect);});//This should not fire, but...
		},*/
		'url':location.href,
		'success':function(result){
			console.log(result);
			
			if(typeof resutl == 'string') result = jQuery.parseJSON(result);
			else if(result.success)
			{
				//TODO Just to bypass screen in demo1.
				if(result.redirect && result.isAdmin) 
				{
					window._isAdmin = true;
					window.location = result.redirect;
					return;
				}
				
				if(result.redirect) window._redirect = result.redirect;
				//if(result.redirect) window.location = result.redirect;
				//else jQuery('#login-result').html(result.html); 
				jQuery('#login-result').html(result.html);
				
				$('#login-dashboard').modal({show:true,keyboard:false,backdrop:'static'}).css({
			        width: 'auto',
			        'margin-left': function () {
			        	//We should get each thumbnails child width, and add.
			            return -Math.round($('.thumbnails').width() / 2);
			        }
			    }).on('hidden',function(){if(window._redirect)window.location.replace(window._redirect);});//This should not fire, but...
				
			} else if(! result.success)
			{
				console.log(result);
				var obj = jQuery.parseJSON(result.result);
				var message = '';
				for(var m in obj) message += obj[m];
				var alert =  $('<div class=\"alert alert-error fade in\" data-alert><a class=\"close\" data-dismiss=\"alert\" href=\"#\">&times;</a><p>  '+ message +' </p></div>');
				$('#login-form').prepend(alert);
				$('.login-submit').button('reset');
			}
			
		}
	});
	return false;
}); 
");
?>
<div class='span4 offset4'>
<?php
$this->layout = '//layouts/login';
?>

<?php
if(isset($this->breadcrumbs)) unset($this->breadcrumbs); 
?>

<?php /** @var BootActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'login-form',
    'htmlOptions'=> array(
    	'class'=>'well login-form', 
    	'style'=>'margin-top:40%', //TODO Ugly, not here, move to app.css
    	),
)); ?>

<legend><?php echo $this->pageTitle;?></legend>
<?php echo $form->textFieldRow($model, 'username', array('class'=>'login-input')); ?>
<?php echo $form->passwordFieldRow($model, 'password', array('class'=>'login-input')); ?>
<?php echo $form->checkboxRow($model, 'rememberMe'); ?>
<?php echo CHtml::hiddenField('ajax', 'login-form'); ?>
<br/>

<?php $this->widget('bootstrap.widgets.TbButton',array(				 
				'buttonType'=> 'submit', 
				'icon'=>'ok', 
				'label'=>'Submit',				
			    'htmlOptions' => array('class' => 'login-submit', 'data-loading-text' => '...loading')			
			)); 
?>
<?php $this->endWidget(); ?>
</div>

<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'login-dashboard')); ?>
<div class="modal-header">
    <!--a class="close" data-dismiss="modal">&times;</a-->
    <h3>Organizations</h3>
</div-->
 
<div class="modal-body" id="login-result">
    <p>One fine body…</p>
</div>
 
<!--div class="modal-footer">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'type'=>'primary',
        'label'=>'Save changes',
        'url'=>'#',
        'htmlOptions'=>array('data-dismiss'=>'modal'),
    )); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'label'=>'Close',
        'url'=>'#',
        'htmlOptions'=>array('data-dismiss'=>'modal'),
    )); ?>
</div-->
 
<?php $this->endWidget(); ?>