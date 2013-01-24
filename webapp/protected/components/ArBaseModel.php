<?php

class ArBaseModel extends CActiveRecord
{
      
    public function behaviors()
    {
        //TODO: WE SHOULD WE ABLE TO OVERRIDE THIS, THINK USE VAR.
        return array(
          'EBeforeSave'=>'application.behaviors.EBeforeSaveBehavior',
      );
    }
      
  }
      