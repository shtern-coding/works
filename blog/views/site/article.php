<?php
use app\components\TagRender;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
?>

<?php
$this->title = $article->name;
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
    <a href="mailto:shtern.coding@gmail.com">shtern.coding@gmail.com</a>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="hat"></div>
            <div class='post'>
                <h1><a href=''><?= $article->name ?></a></h1>
                <h4 class="tags"><span>Теги: </span><?= TagRender::widget(['request' => $article->tags]); ?></h4>
                <h3><?= $article->dateFormated ?></h3>
                <h5><?= $article->article ?></h5>
            </div>

            <?= $this->render('likes'); ?>
        </div>
    </div>
</div>

<script> 
    if (location.hash) {
        $('html, body').animate({
        scrollTop: $('.hc-link').offset().top
        }, 650);
    }
</script>

