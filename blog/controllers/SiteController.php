<?php

namespace app\controllers;

use Yii;
use yii\bootstrap\Html;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use app\models\Articles;
use app\models\Tags;
use app\models\Subscribe;
use app\models\SearchForm;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        $model = new SearchForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $request = Html::encode($model->request);
            return $this->redirect(Yii::$app->urlManager->createUrl(['site/search', 'request' => $request]));
        }
        return true;
    }

    /**
     * @inheritdoc
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
        $query = Articles::find()->orderBy(['id' => SORT_DESC]);
        $pages = new Pagination(['totalCount' => $query->count(), 'defaultPageSize' => 3, 'forcePageParam' => false]);
        $tags = Tags::find()->all();
        $search = new SearchForm();

        $articles = $query->offset($pages->offset)
                ->limit($pages->limit)
                ->all();


        return $this->render('index', [
            'articles' => $articles,
            'pages' => $pages,
            'tags' => $tags,
            'currentTag' => false,
            'request' => false,
            'search' => $search,
        ]);
    }
    
    public function actionArticle($url_name, $comment = null)
    {
        $article = Articles::findOne(['url_name' => $url_name]);
        $tags = Tags::find()->all();
        $search = new SearchForm();
        
        return $this->render('article', [
            'article' => $article,
            'comment' => $comment,
            'tags' => $tags,
            'search' => $search,
        ]);
    }

    public function actionSubscribe()
    {
        $this->layout = 'admin';
        $subscribeForm = new Subscribe();

        if (Yii::$app->request->post() && $subscribeForm->validate()) {
            echo "sdfsdf";
        }
        return $this->render('subscribe', [
            'model' => $subscribeForm,
        ]);
    }

    public function actionTags($request)
    {
        $query = Tags::findOne(['tag' => $request]);
        $articles = array_reverse($query->articles);
        $tags = Tags::find()->all();
        $pages = new Pagination(['totalCount' => count($articles), 'defaultPageSize' => 3, 'forcePageParam' => false]);
        $articles = Articles::articlesCount($articles, $pages->offset, $pages->limit);
        $search = new SearchForm();

        return $this->render('index', [
            'articles' => $articles,
            'pages' => $pages,
            'currentTag' => $request,
            'tags' => $tags,
            'search' => $search,
            'request' => false,
        ]);
    }

    public function actionSearch($request) {
        $model = new SearchForm();
        $posts = $model->search($request);
        $tags = Tags::find()->all();
        $pages = new Pagination(['totalCount' => count($posts), 'defaultPageSize' => 3, 'forcePageParam' => false]);
        $posts = Articles::articlesCount($posts, $pages->offset, $pages->limit);
        return $this->render('index', [
            'request' => $request,
            'articles' => $posts,
            'search' => $model,
            'tags' => $tags,
            'currentTag' => false,
            'pages' => $pages,
        ]);
    }
}
