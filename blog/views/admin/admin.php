<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;

$this->title = "Администрирование";
?>

<h5><a class="admin" href="<?=Yii::$app->UrlManager->createUrl(['site/index'])?>">На главную</a></h5>
<h5><a class="admin" href="<?=Yii::$app->UrlManager->createUrl(['admin/logout'])?>">Выйти</a></h5>
<div class="blog">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="articles-tab active"><a href="#tab-1" aria-controls="tab-1" role="tab" data-toggle="tab">Статьи</a></li>
                    <li role="presentation"><a href="#tab-2" aria-controls="tab-2" role="tab" data-toggle="tab">Теги</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                        <div id="tab-1" role="tabpanel" class="panel panel-default adding tab-pane active">
                            <h1 class="add" id="add-edit"><?= $h1Name ?></h1>
                            <?php $form = ActiveForm::begin(['action' => Yii::$app->urlManager->createUrl([$adress])]); ?>
                            <?= Html::activeHiddenInput($formModel, 'id')?>
                            <?= $form->field($formModel, 'name'); ?>
                            <?= $form->field($formModel, 'url_name', ['enableAjaxValidation' => true]); ?>
                            <?= $form->field($formModel, 'date')->textInput(['value' => date("Y-m-d")]); ?>
                            <?= $form->field($formModel, 'tags')->dropDownList($formModel->tagsArray, ['options' => $formModel->selectedTagsArray, 'class' => 'form-control tags-list', 'multiple' => true]); ?>
                            <?= $form->field($formModel, 'article')->widget(CKEditor::className(), [
                                'editorOptions' => ElFinder::ckeditorOptions('elfinder', [
                                    'preset' => 'custom',
                                    'customConfig' => '/ckeditor/config.js'
                                ]),
                            ]);
                            ?>
                            <?php if (!$update): ?>
                                <?= Html::submitButton('Добавить', ['class' => 'btn btn-default']) ?>
                            <?php else: ?>
                                <?= Html::submitButton('Отредактировать', ['class' => 'btn btn-default']) ?>
                                <?= Html::Button('Отменить', ['class' => 'btn btn-default', 'onclick' => 'location.href="/admin"']) ?>
                            <?php endif; ?>
                            <?php ActiveForm::end(); ?>
                        </div>
                    <div id="tab-2" role="tabpanel" class="tab-pane panel">
                        <h1 class="add">Добавить тег</h1>
                        <!-- Добавить тег -->
                        <?php $form = ActiveForm::begin(['action' => Yii::$app->urlManager->createUrl(['admin/addtag'])]); ?>
                            <?= $form->field($tagsModel, 'tag'); ?>
                            <?= Html::submitButton('Добавить', ['class' => 'btn btn-default']) ?>
                        <?php ActiveForm::end(); ?>
                        <!-- Удалить тег -->
                        <h1 class="add del">Удалить тег</h1>
                        <?php $form = ActiveForm::begin(['action' => Yii::$app->urlManager->createUrl(['admin/deletetag'])]); ?>
                        <?= Html::dropDownList('tags', [], $formModel->tagsArray, ['class' => 'form-control tags-list', 'multiple' => true, 'label' => 'sddfsd']); ?>
                        <div class="form-group">
                            <?= Html::submitButton('Удалить', ['class' => 'btn btn-default delete-btn']) ?>
                        </div>
                        <?php ActiveForm::end(); ?>

                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default bottom">
                    <table class="table">
                        <tr>
                            <th>id</th>
                            <th>Название</th>
                            <th>Дата</th>
                            <th>Редактировать</th>
                            <th>Удалить</th>
                        <?php foreach ($articles as $article): ?>
                            <tr>
                                <td><?= $article->id ?></td>
                                <td><?= $article->name ?></td>
                                <td><?= $article->dateFormated ?></td>
                                <td><a href='<?= Yii::$app->urlManager->createUrl(['admin/update', 'id' => $article->id]) ?>'>Редактировать</a></td>
                                <td><a href='<?= Yii::$app->urlManager->createUrl(['admin/delete', 'id' => $article->id]) ?>'>Удалить</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
