<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\dialogues;
use app\models\dialoguesSearch;
use app\models\links;
use app\models\actors;
use yii\helpers\Json;

class SiteController extends Controller
{
    public $enableCsrfValidation = false;
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        $model = new dialoguesSearch();

        return $this->render('index', ['model' => $model]);
    }

    public function actionSearch()
    {   
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = Json::decode(file_get_contents("php://input"));
        
        if ($request) {
            $model = new dialoguesSearch();
            $model->text = $request['dialogue'];
            $model->actorId = $request['actorId'];
            $model->softSearch = $request['softSearch'];
            return $this->asJson($model->search());
        }

        return null;
    }

    public function actionBuild()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = Json::decode(file_get_contents("php://input"));
        
        $nodesArray = [];
        $edgesArray = [];
        $result = [];
        if ($request) {
            $model = new dialoguesSearch();
            $model->conversation = $request['conversationId'];
            $nodes = $model->searchConversation();

            // $edges = [];
            foreach ($nodes as $id => $node) {
                $result[] = [
                    'data' => [
                        'id' => 'node'.$node->dialogueId,
                        'label' => $node->dialogueId,
                        'title' => $node->title,
                        'text' => $node->text,
                        'voiceLine' => $node->audio,
                        'articyId' => $node->articyId,
                        'type' => $node->entryType
                    ],
                    'classes' => $node->class
                ];

                $edges = $node->links;

                foreach ($edges as $edgeId => $edge) {
                    $result[] = ['data' => [
                        'id' => 'edge'.$edge->index,
                        'source' => 'node'.$edge->originDialogue,
                        'target' => 'node'.$edge->destinationDialogue
                    ]];
                }

            }

            return $result;
        }

        return null;
    }

    public function actionNodeTranslation()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = Json::decode(file_get_contents("php://input"));

        $node = dialogues::find()->where(['articyId' => $request['articyId']])->one();

        if($node) {
            return $node->translate($request['language']);
        }

        return null;
    }

    public function actionActors() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $actors = actors::find()->where(['IsNPC' => 'true'])->asArray()->all();

        return $actors;
    }
}
