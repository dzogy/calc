<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

use app\models\EntryForm;
use yii\helpers\ArrayHelper;

class SiteController extends Controller
{
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

    /*public function actionIndex()
    {
        return $this->render('index');
    }*/

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
        return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }
	
    public function actionIndex()
    {
	$model = new EntryForm();
        $apiplace = 'http://api.boxberry.de/__soap/1c_public.php?wsdl';
        $apiobject = $model->apisoap($apiplace);

    function apiconvert($apiobject){
        if(is_object($apiobject)){
        $apiobject = get_object_vars($apiobject);
        }
        if(is_array($apiobject)){
        return array_map(__FUNCTION__, $apiobject);
        }
            else {
                 return $apiobject;
                }
    }

        $sityarr = apiconvert($apiobject);

        for ($i=0; $i<=300; $i++){
        $keys[$i] = ArrayHelper::getValue($sityarr, "result.$i.Code");
        }

        for ($i=0; $i<=300; $i++){
        $name[$i] = ArrayHelper::getValue($sityarr, "result.$i.Name");
        }

        $sityarray = array_combine($keys, $name);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // данные в $model удачно проверены
            $apiobjectc = $model->apisoapc($apiplace, $model->weight, $model->sity);
            $pricearr = apiconvert($apiobjectc);

            return $this->render('entry-confirm', ['model' => $model, 'sityarray' => $sityarray, 'pricearr' => $pricearr]);
        } else {
            // либо страница отображается первый раз, либо есть ошибка в данных
            return $this->render('entry', ['model' => $model, 'sityarray' => $sityarray]);
        }
    }
    
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
}
