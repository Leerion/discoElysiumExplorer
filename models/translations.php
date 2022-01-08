<?php
namespace app\models;

use yii\db\ActiveRecord;

class translations extends ActiveRecord
{
    public static function tableName()
    {
        return '{{translations}}';
    }
}
