<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = "Вход";
?>

<h5><a class="admin" href="<?=Yii::$app->UrlManager->createUrl(['site/index'])?>">На главную</a></h5>
<div class="blog">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default login">
                    <h1 class="add" id="add-edit">Авторизируйтесь</h1>
                    <?php $form = ActiveForm::begin(['action' => Yii::$app->urlManager->createUrl(['admin/login'])]); ?>
                        <?= $form->field($model, 'login')->textInput(['autofocus' => true]); ?>
                        <?= $form->field($model, 'password'); ?>
                        <?= $form->field($model, 'checkbox')->checkbox(); ?>
                        <?= Html::submitButton('Войти', ['class' => 'btn btn-default']) ?>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
