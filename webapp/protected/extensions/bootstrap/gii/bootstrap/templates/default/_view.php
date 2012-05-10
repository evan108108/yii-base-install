<?php
/**
 * The following variables are available in this template:
 * - $this: the BootCrudCode object
 */
?>
<div class="well">
<dl class="dl-horizontal">

<?php
echo '<span class="pull-right">';
echo "\t<b><?php echo CHtml::encode(\$data->getAttributeLabel('{$this->tableSchema->primaryKey}')); ?>:</b>\n";
echo "\t<?php echo CHtml::link(CHtml::encode(\$data->{$this->tableSchema->primaryKey}),array('view','id'=>\$data->{$this->tableSchema->primaryKey})); ?>\n";
echo "</span>\n\n\t<br />";

$count=0;
foreach($this->tableSchema->columns as $column)
{
	if($column->isPrimaryKey)
		continue;
	if(++$count==7)
		echo "\t<?php /*\n";
	echo "\t<dt><?php echo CHtml::encode(\$data->getAttributeLabel('{$column->name}')); ?>:</dt>\n";
	echo "\t<dd><?php echo CHtml::encode(\$data->{$column->name}); ?>\n\t</dd>\n\n";
}
if($count>=7)
	echo "\t*/ ?>\n";
?>

</dl>
</div>