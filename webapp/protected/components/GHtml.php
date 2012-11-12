<?php

class GHtml extends CHtml {
    

    /**
     * 
     */
    public static function enumItem($model,$attribute)
    {
        self::resolveName($model,$attribute);
        preg_match('/\((.*)\)/',$model->tableSchema->columns[$attribute]->dbType,$matches);
        foreach(explode(',', $matches[1]) as $value)
        {
                $value=str_replace("'",NULL,$value);
                $values[$value] = Yii::t('enumItem',$value);
        }
       
        return $values;
    }    
    
    /**
     * 
     */
    public static function enumDropDownList($model, $attribute, $htmlOptions = array())
    {
        return CHtml::activeDropDownList( $model, $attribute, self::enumItem($model,  $attribute), $htmlOptions);
    }
    
}
