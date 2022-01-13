<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\models\links;
use app\models\actors;
use app\models\translations;

class dialogues extends ActiveRecord
{
    public static function tableName()
    {
        return '{{dialogues}}';
    }

    public function getLinks()
    {
        $links = links::find()->where(['originIndex' => $this->index, 'outputConversationId' => $this->conversationId])->all();

        return $links;
    }

    public function translate($lang)
    {   
        if($lang != -1) {
            $translation = translations::find()->where(['dialogueId' => $this->articyId, 'lang' => $lang])->one();

            if($translation)
                return $translation->text;
        }

        return null;
    }

    public function getActor() 
    {
        return actors::find()->where(['actorId' => $this->actorId])->one();
    }

    public function getClass()
    {
        $class = $this->entryType;

        if($class == 'DialogueFragment' || $class == 'Fork'){
            if($class == 'DialogueFragment') {
                if($this->actor && $this->actor->color > 1){
                    return 'InnerDemons';
                }
            }
            return $class;
        }

        return 'basic';
    }

    public function getAudio()
    {
        if(Yii::$app->params['useAudioClips'])
            return $this->voiceLine;

        return null;
    }
}
