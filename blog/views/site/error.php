<?php
use yii\helpers\Html;

$this->title = $name;
?>

<div class="container">
    <div class="error">
        <a href="<?= Yii::$app->urlManager->createUrl(['site/index']) ?>"><img class="top-img" src="/imgs/logo2.jpg" alt="logo"></a>
        <h4>Ошибка!</h4>
        <h5><?= Html::encode($this->title) ?></h5>
    </div>
</div>