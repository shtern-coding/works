<?php

namespace app\components;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class MiniPagin extends Widget
{
    public $pagination;
    public $arrCssClass = 'arr';
    public $paginCssClass = 'pagin';
    public $tag = null;
    public $request = null;
    
    public function run()
    {
        return $this->paginView();
    }
    
    public function paginView() 
    {
        $this->request = Yii::$app->getRequest()->getQueryParam('tag');
        $this->request = Yii::$app->getRequest()->getQueryParam('request');
        $route = Yii::$app->controller->route;
        $currentPage = $this->pagination->getPage()+1;
        $pageCount = $this->pagination->getPageCount();
        $buttons = '';
        
        // Обратно
        if ($currentPage !== 1) {
            
            //Если страница 2, то адрес предыдущей — 1-ой — обычная страница
            if ($currentPage == 2) {
                $buttons .= Html::a('<img class="mirror" src="/imgs/arrow.png">', [$route, 'request' => $this->request], ['class' => $this->arrCssClass]);
            }
            else {
                $buttons .= Html::a('<img class="mirror" src="/imgs/arrow.png">', [$route, 'page' => $currentPage-1, 'request' => $this->request], ['class' => $this->arrCssClass]);
            }
        }
        
        // Вперед
        if ($currentPage < $pageCount) {
            $buttons .= Html::a('<img src="/imgs/arrow.png">', [$route, 'page' => $currentPage+1, 'request' => $this->request], ['class' => $this->arrCssClass]);
        }

        return Html::tag('div', $buttons, ['class' => $this->paginCssClass]);
    }
}