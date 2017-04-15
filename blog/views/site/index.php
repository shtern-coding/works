<?php
use app\components\MiniPagin;
use app\components\TagRender;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

$this->title = 'Блог Алексея Рябова';
?>

<div class="menu post">
    <a href="<?= Yii::$app->urlManager->createUrl(['site/index']) ?>"><img id="top-img" src='/imgs/logo2.jpg' class="img-responsive"></a>
    <div id="custom-search-input">
        <div class="input-group">
            <?php $form = ActiveForm::begin(['layout' => 'inline']); ?>
            <?= $form->field($search, 'request')->input('text', ['class' => 'form-control input-lg', 'placeholder' => 'Поиск']); ?>
            <span class="input-group-btn">
                <?= Html::submitButton('<i class="glyphicon glyphicon-search"></i>', ['class' => 'btn btn-info btn-lg']) ?>
            </span>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <div class="tags">
        <h5><?= TagRender::widget(['request' => $tags]); ?></h5>
    </div>
    <a class="mail" href="mailto:shtern.coding@gmail.com">shtern.coding@gmail.com</a>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="hat"></div>
            <?php if ($currentTag): ?>
                <h5 class="tag-request">Тег: <span><a href="<?= Yii::$app->urlManager->createUrl(['site/tags', 'request' => $currentTag]) ?>"><?= $currentTag ?></a></span></h5>
            <?php endif ?>
            <?php if ($request): ?>
                <h5 class="search-request">Поиск: <span class="underlined"><?= $request ?></span></h5>
            <?php endif ?>
            <?php  if (count($articles) == 0): ?>
                <div class='post'>
                    <h2 class="none">Нет статей по такому запросу...</h2>
                </div>
            <?php endif ?>
            <?php foreach($articles as $article): ?>
                <div class='post'>
                    <h1><a href='<?= Yii::$app->urlManager->createUrl(['site/article', 'url_name' => $article->url_name]) ?>'><?= $article->name ?></a></h1>
                    <h4 class="tags"><span>Теги: </span><?= TagRender::widget(['request' => $article->tags]); ?></h4>
                    <h3><?= $article->dateFormated ?></h3>
                    <h5><?= $article->article ?></h5>
                    <h4 class="comment"><a href='<?= Yii::$app->urlManager->createUrl(['site/article', 'url_name' => $article->url_name, '#' => 'comment']) ?>'>Комментировать</a></h4>
                </div>
            <?php endforeach ?>
            <?= MiniPagin::widget(['pagination' => $pages]); ?>
        </div>
    </div>
</div>



