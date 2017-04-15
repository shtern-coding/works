<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = "Оформить подписку";
?>

<div class="blog">
    <div class="container">

        <div class="row">

            <div class="col-md-3 col-md-offset-2">
                <h1 class="subscribe-h1">Оформить подписку</h1>
                <?php $form = ActiveForm::begin(['action' => Yii::$app->urlManager->createUrl(['site/subscribe']), 'options' => ['class' => 'subscribe-form']]); ?>
                    <?= $form->field($model, 'email'); ?>
                    <?= Html::submitButton('Подписаться', ['class' => 'btn btn-default']) ?>
                <?php ActiveForm::end(); ?>
                <h4>Об авторе</h4>
                <p>Сергей — редактор и коммерческий писатель. Он живёт в информационном коконе.
                    Несколько часов в день он тратит на богатую коллекцию RSS-подписок и рассылок, откуда
                    по крупицам собирает интересные материалы.</p>
            </div>

            <div class="col-md-3 col-md-offset-1">
                <img class="subscribe-img" src="/imgs/subscribe.jpg">
            </div>

        </div>

        <div class="row">
            <div class="footer">
                <div class="col-md-3 col-md-offset-2">
                    <p>По вопросам: <?= Html::mailto('rybov.design@gmail.com', 'rybov.design@gmail.com') ?></p>
                    <p><a>Личный кабинет</a></p>
                    <img class="subscribe-img-payment" src="/imgs/payment-providers.svg">
                </div>
            </div>
        </div>

    </div>
</div>
