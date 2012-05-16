<?php
/**
 * The following variables are available in this template:
 * - $this: the BootCrudCode object
 */
?>
<?php
echo "<?php\n";
$nameColumn=$this->guessNameColumn($this->tableSchema->columns);
$label=$this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	'$label'=>array('index'),
	\$model->{$nameColumn}=>array('view','id'=>\$model->{$this->tableSchema->primaryKey}),
	'Update',
);\n";
?>

$this->menu=array(
	array('label'=>'Actions'),
	array('label'=>'List <?php echo $this->modelClass; ?>','url'=>array('index'), 'icon' => 'list-alt'),
	array('label'=>'Create <?php echo $this->modelClass; ?>','url'=>array('create'), 'icon' => 'plus-sign'),
	array('label'=>'View <?php echo $this->modelClass; ?>','url'=>array('view','id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>), 'icon' => 'eye-open'),
	array('label'=>'Manage <?php echo $this->modelClass; ?>','url'=>array('admin'), 'icon' => 'edit'),
);
?>

<h1>Update <?php echo $this->modelClass." <?php echo \$model->{$this->tableSchema->primaryKey}; ?>"; ?></h1>

<?php echo "<?php echo \$this->renderPartial('_form',array('model'=>\$model)); ?>"; ?>