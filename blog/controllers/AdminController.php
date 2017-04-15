<?php
namespace app\controllers;

use app\models\CreateTagsForm;
use Yii;
use yii\web\Controller;
use app\models\Articles;
use app\models\Tags;
use app\models\CreateArticleForm;
use yii\widgets\ActiveForm;
use app\models\AdminLogin;
use yii\filters\AccessControl;

class AdminController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'update', 'create', 'delete'],
                'rules' => [
                    [
                    'allow' => true,
                    'roles' => ['@'],
                    ]
                ]
            ]
        ];
    }

    public function beforeAction($action)
    {
        $this->layout = 'admin';
        return parent::beforeAction($action);
    }

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
    
    /**
     * Отображение списка статей в админке и подключение модели 
     * для приема данных из формы создания новой статьи и из формы добавления тегов
     */
    public function actionIndex()
    {
        $articlesTable = new Articles;
        $articles = $articlesTable->find()->orderBy(['id' => SORT_DESC])->all();
        $formModel = new CreateArticleForm(['scenario' => 'create']);
        $formModel->loadTags();
        $tags = Tags::find()->all();
        $tagsModel = new CreateTagsForm();

        return $this->render('admin', [
            'articles' => $articles,
            'formModel' => $formModel,
            'update' => false,
            'adress' => 'admin/create',
            'h1Name' => 'Добавить статью',
            'tags' => $tags,
            'tagsModel' => $tagsModel,
        ]);
    }

    /**
     * @return array|string
     * Создание новой статьи
     */
    public function actionCreate() 
    {
        $articlesTable = new Articles;
        $formModel = new CreateArticleForm(['scenario' => 'create']);
        $tagsModel = new CreateTagsForm();

        if (Yii::$app->request->isAjax && $formModel->load(Yii::$app->request->post())) {
            Yii::$app->response->format = 'json';
            return ActiveForm::validate($formModel);
        }
        
        if ($formModel->load(Yii::$app->request->post()) && $formModel->validate()) {
            $formModel->saveArticle();
            $this->redirect('/admin/');
        } else {
            $this->layout = 'admin';
            $articles = $articlesTable->find()->orderBy(['id' => SORT_DESC])->all();
            return $this->render('admin', [
            'articles' => $articles,
            'formModel' => $formModel,
            'update' => false,
            'adress' => 'admin/create',
            'h1Name' => 'Добавить статью',
            'tagsModel' => $tagsModel,
        ]);
        }
    }
    
    /**
     * Удаление статьи
     */
    public function actionDelete($id)
    {
        $deleteArticle = Articles::findOne($id);
        $deleteArticle->delete();
        $this->redirect('/admin/');
    }

    /**
     * @param null $id
     * @return array|string
     * Редактирование статей
     */
    public function actionUpdate($id = null)
    {
        $articlesTable = new Articles;
        $formModel = new CreateArticleForm(['scenario' => 'update']);
        $tagsModel = new CreateTagsForm();
        $tags = Tags::find()->all();

        if (Yii::$app->request->isAjax && $formModel->load(Yii::$app->request->post())) {
            Yii::$app->response->format = 'json';
            return ActiveForm::validate($formModel);
        }
        
        // Сохранение отредактированных данных
        if ($formModel->load(Yii::$app->request->post()) && $formModel->validate()) {
            $formModel->updateArticle();
            $this->redirect('/admin/');
        }
        else {
            $articles = $articlesTable->find()->orderBy(['id' => SORT_DESC])->all();
            if ($id) {
            $formModel->loadUpdatedArticleInfo($id);
            }
            return $this->render('admin', [
                'articles' => $articles,
                'formModel' => $formModel,
                'update' => true,
                'adress' => 'admin/update',
                'h1Name' => 'Редактировать статью',
                'tags' => $tags,
                'tagsModel' => $tagsModel,
            ]);
        }
    }

    /**
     * @return string
     * Залогинивание в админку
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            $this->goHome();
        }
        $loginModel = new AdminLogin();
        if ($loginModel->load(Yii::$app->request->post()) && $loginModel->validate()) {
            Yii::$app->user->login($loginModel->getUser(), $loginModel->checkbox ? 3600 * 24 * 30 : 0);
            $this->redirect('/admin/');
        } else {
            return $this->render('login', [
                'model' => $loginModel
            ]);
        }
    }

    /**
     * Разлогинивание из админки
     */
    public function actionLogout()
    {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->user->logout();
            $this->redirect('/admin/');
        }
        $this->goHome();
    }

    /**
     * Удаляет тег
     */
    public function actionDeletetag()
    {
        $tags = Yii::$app->request->post('tags');
        CreateTagsForm::deleteTags($tags);
        $this->redirect('/admin/');
    }

    /**
     * Добавляет новый тег
     */
    public function actionAddtag()
    {
        $model = new CreateTagsForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->addTag(Yii::$app->request->post());
            $this->redirect('/admin/');
        }
        $this->redirect('/admin/');
    }

}
