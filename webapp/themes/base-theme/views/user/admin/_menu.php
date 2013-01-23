<?php if(isset($list)): $this->widget('bootstrap.widgets.TbMenu', array(
  'type'=>'tabs', // '', 'tabs', 'pills' (or 'list')
  'stacked'=>false, // whether this is a stacked menu
  'items'=>$list,
)); endif; ?>


