<?php
namespace app\models;

use yii\db\ActiveRecord;

class actors extends ActiveRecord
{
    public static function tableName()
    {
        return '{{actors}}';
    }
}
