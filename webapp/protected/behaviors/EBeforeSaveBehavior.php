<?php
  class EBeforeSaveBehavior extends CActiveRecordBehavior
  {
    public function beforeSave($event)
    {
      if($this->owner->isNewRecord)
      {
        $this->owner->crt_dtm = new CDbExpression('NOW()');
        $this->owner->lud_dtm = new CDbExpression('NOW()');
      }
      else
        $this->owner->lud_dtm = new CDbExpression('NOW()');

      return parent::beforeSave($event);
    }

