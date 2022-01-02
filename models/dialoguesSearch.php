<?php
namespace app\models;

use app\models\dialogues;
use app\models\links;

use yii;

class dialoguesSearch extends \yii\base\Model
{
    public $text;
    public $actorId;
    public $softSearch;
    public $conversation;

    public function search() {
        $searchText = $this->text;

        $searchText = str_replace("\\", "", $searchText);
        $searchText = str_replace('\'', '\\\'', $searchText);
        $searchText = str_replace('\"', '\\\"', $searchText);

        if(empty($searchText))
        {
            return null;
        }

        if($this->softSearch) {
            $sqlCondition = "SELECT *, MATCH(text) AGAINST ('$searchText' IN BOOLEAN MODE) as score FROM dialogues where MATCH(text) AGAINST ('$searchText' IN BOOLEAN MODE) ORDER BY score DESC";

            $result = dialogues::findBySql($sqlCondition);

        } else {
            $whereCondition = "MATCH(text) AGAINST 
                ('\"$searchText\"' IN BOOLEAN MODE)";
            $result = dialogues::find()
            ->where($whereCondition);
        }
        
        if($this->actorId > -1) {
            $result = $result->andWhere(['actorId' => $this->actorId]);
        }

        $result = $result->limit(30)->asArray()->all(); 

        Yii::error(print_r($result, true), 'sqlResult');
        return $result;
        
    }

    public function searchConversation() {
        $convId = $this->conversation;

        if(empty($convId))
        {
            return null;
        }

        $result = dialogues::find()
            ->where(['conversationId' => $convId])->all(); 

        return $result;
    }
}
