<?php
namespace app\models;

use yii\db\ActiveRecord;

class links extends ActiveRecord
{
    public static function tableName()
    {
        return '{{links}}';
    }
}
