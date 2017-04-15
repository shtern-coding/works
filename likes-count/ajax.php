<?php
session_start();

require_once 'classes/VkApi.php';
require_once 'classes/LikesSorting.php';
require_once 'classes/HTMLMaker.php';
require_once 'classes/RequestWatch.php';

$token = '88d1ef5cc71bb96d2a59b3d6c3792c668a0ec4fdebedbda7f0c23e840b914589c9a9a095c6d06b8fc33fd';
$vk = new VkApi($token);

// Отфильтровать по времени, дню недели или дате
if (isset($_POST['filter'])) {
    $filters['date1'] = (strlen($_POST['date1'])) ? strtotime($_POST['date1']) : null;
    $filters['date2'] = (strlen($_POST['date2'])) ? strtotime($_POST['date2']) : null;
    $filters['weekdays'] = (isset($_POST['weekdays'])) ? $_POST['weekdays'] : null;
    $filters['hours'] = (isset($_POST['hours'])) ? $_POST['hours'] : null;
    $HTMLMaker = new HTMLMaker();
    $list = LikesSorting::filterList($_SESSION['list'], $filters);
    $_SESSION['filtered'] = $list['list'];
    $list['list'] = $HTMLMaker->htmlList($list['list'], $list['total-posts']);
    echo json_encode($list);
}

// Вернуть список в обратном порядке
if (isset($_POST['upend'])) {
    if ($_POST['upend'] == 'notfiltered') {
        $HTMLMaker = new HTMLMaker();
        $_SESSION['list'] = array_reverse($_SESSION['list']);
        echo $HTMLMaker->htmlList($_SESSION['list'], $_SESSION['total-posts']);
    } else {
        $HTMLMaker = new HTMLMaker();
        $_SESSION['filtered'] = array_reverse($_SESSION['filtered']);
        echo $HTMLMaker->htmlList($_SESSION['filtered'], count($_SESSION['filtered']));
    }
}

// Показать ещё 250 записей
if (isset($_POST['more_posts'])) {
    if ($_POST['more_posts'] == 'notfiltered') {
        $HTMLMaker = new HTMLMaker();
        $lastPost = $_POST['shown'];
        echo $HTMLMaker->htmlList($_SESSION['list'], $_SESSION['total-posts'], $lastPost, $lastPost+250);
    } else {
        $HTMLMaker = new HTMLMaker();
        $lastPost = $_POST['shown'];
        echo $HTMLMaker->htmlList($_SESSION['filtered'], count($_SESSION['filtered']), $lastPost, $lastPost+250);
    }
}

// Проверяет существование паблика. Если существует, возвращает массив с его id и колличеством постов
if (isset($_POST['exist'])) {
    $info['id'] = $vk->idByName($_POST['exist']);
    if ($info['id'] == 0) {
        echo 'none';
    } elseif ($vk->isClosed($info['id']) == 1) {
        echo 'closed';
    } else {
        $info['posts-count'] = $vk->postsCount($info['id']);
        echo json_encode($info);
    }
}

// Получить отсортированными все записи
if (isset($_POST['public_id'])) {
    $request = new RequestWatch($_SERVER['REMOTE_ADDR']);
    $sorter = new LikesSorting($_POST['public_id'], $vk, $request);
    $HTMLMaker = new HTMLMaker();
    $request->createStatus();
    $list = $_SESSION['list'] = $sorter->makeList();
    $result['list'] = $HTMLMaker->htmlList($list, $sorter->postsCount);
    $result['name'] = $sorter->groupName;
    $result['photo'] = $sorter->groupPhoto;
    $result['total-likes'] = $sorter->totalLikes;
    $result['total-posts'] = $_SESSION['total-posts'] = $sorter->postsCount;
    echo json_encode($result);
}
