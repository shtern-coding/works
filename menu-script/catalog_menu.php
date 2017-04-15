<?php

// *
// * Подключение к БД
// *
function connect_db() {
    // параметры для подключения к БД - логин, пароль и т.д.
    $db_params = [
        "host" => "localhost",
        "dbname" => "work",
        "user" => "Rybov",
        "password" => "123"
    ];
    // подключение
    $db = new PDO ("mysql:host={$db_params['host']}; dbname={$db_params['dbname']}", $db_params['user'], $db_params['password']);
    $db->exec('set names utf8');
    return $db;
}


// *
// * Функция получает parent_id элемента по его id. Если у элемента нет родительского элемента,
// * то есть он начинает список, тогда возвращается false.
// *
function find_parent_by_id($id) {
    $db = connect_db();
    $result = $db->prepare("SELECT parent_id FROM menu WHERE id = :id");
    $result->bindParam(':id', $id, PDO::PARAM_INT);
    $result->setFetchMode(PDO::FETCH_ASSOC);
    $result->execute();
    $parent_id = $result->fetch();
    if ($parent_id) {
        return $parent_id['parent_id'];
    }
    return false;
}


// *
// * Используя функцию find_parent поочередно находим id всех родителей и возвращем их в виде массива,
// * где первый элемент самый дальний родитель - ачало списка, предпоследний - самый ближний, а последний это сам id.
// * Например если пользователь нажимал на cсылку с id = 11 массив будет [0, 1, 7, 11].
// *
function all_parents_and_child_by_id($id) {
    $parents = [];
    // так как каждый новый элемент массива будет добавляться в начало сдвигая остальные в конец,
    // сначало добавляем сам id элемента, чьи родители ищутся
    array_unshift($parents, $id);
    while(find_parent_by_id($id) !== false) {
        $id = find_parent_by_id($id);
        array_unshift($parents, $id);
    }
    return $parents;
};


// *
// * Получение списка ближайших дочерних элементов по id родительского элемента
// *
function get_children_list_by_parent_id($id) {
    // подключение к БД, отправка запроса на получение списка элементов с нужным parent_id
    $db = connect_db();
    $result = $db->prepare("SELECT * FROM menu WHERE parent_id = :id");
    $result->bindParam(':id', $id, PDO::PARAM_INT);
    $result->setFetchMode(PDO::FETCH_ASSOC);
    $result->execute();

    // сортировка полученной из БД информации в массив
    $list = [];
    $i = 0;
    while ($row = $result->fetch()) {
        $list[$i]['id'] = $row['id'];
        $list[$i]['title'] = $row['title'];
        $i++;
    }
    return $list;
}


// *
// * Функция проверяет является ли элемент с заданным id родителем для каких-нибудь элементов.
// * Это нужно, чтобы элемент в последней вложенности выводился не ссылкой.
// *
function is_parent_by_id($id) {
    $db = connect_db();
    $result = $db->prepare("SELECT id FROM menu WHERE parent_id = :id");
    $result->bindParam(':id', $id, PDO::PARAM_INT);
    $result->setFetchMode(PDO::FETCH_ASSOC);
    $result->execute();
    $parent_id = $result->fetch();
    if ($parent_id) {
        return true;
    }
    return false;
}


// *
// * Рекурсивная функция генерирующая меню и записывающая его в строку. На основе массива с родителеями и id, выбранного элемента.
// * Элементы меню записываются в строковую переменную render, которую функция возвращает в самом конце.
// * Переменные render и menu_level передается вместе с массивом родителей в аргументах,
// * чтобы их можно было обновлять во время рекурсивного процесса.
// *
function render_menu($parents, &$render = '', $menu_level = 0) {
    // menu_level это число которое подставляется в класс каждого элемента на каждо уровне вложенности
    // увеличивается на 1 на каждом уровне рекурсии. Начинается с 1.
    $menu_level++;
    // получаем id первого элемента массива и удаляем его из массива. Удаляем, чтобы когда элементов не останется
    // рекурсия остановилась
    $id = (isset($parents[0])) ? array_shift($parents) : 0;
    // получаем id следующего элемента - его родителя. Чтобы знать когда открыть новую вложенность.
    // Если массив пуст, что произойдет в конце рекурсивного процесса, то значение переменной - false
    $closest_parent = (isset($parents[0])) ? $parents[0] : false;
    // получаем список дочерних элементов для текущего id
    $list = get_children_list_by_parent_id($id);

    // проходимся циклом по эти элементам для каждого элемента генерируя <li> и <a>. Они оборачиваются в <ul>
    $render .= '<ul class="catalog-menu-ul-level-'.$menu_level.'">';
    foreach ($list as $item) {
        $item_id = $item["id"];
        $item_title = $item['title'];
        $render .= '<li class="catalog-menu-li-level-'.$menu_level.'">';
        // если id элемента совпадает с элементом из массива родителей, значит в нем есть вложенность,
        // поэтому после того как тег этой ссылки записывается в render, функция рекурсивно вызывается ещё раз.
        if ($item_id == $closest_parent) {
            // если элемент последний в массиве родителей, то есть его изначально выбирал пользователь,
            // проверяем есть ли у него вообще какая-то вложенность, если нет, то он будет не ссылкой.
            if (count($parents) == 1 && !is_parent_by_id($item_id)) {
                $render .= '<a class="catalog-menu-a-end">'.$item_title.'</a>';
            } else {
                $render .= '<a href="?id='.$item_id.'" class="catalog-menu-active-a-level-'.$menu_level.'">'.$item_title.'</a>';
            }
            render_menu($parents, $render, $menu_level);
        } else {
            $render .= '<a href="?id='.$item_id.'" class="catalog-menu-a-level-'.$menu_level.'">'.$item_title.'</a>';
        }
        $render .= '</li>';
    }
    $render .= '</ul>';

    return $render;
}


// *
// * Выводит меню на экран. В аргументе передается id родителя вложенность которого нужно открыть.
// * По умолчанию это 0 - поэтому загружается только само меню без вложенностей.
// * Если передано id через GET, то используется оно.
// * На случай если кто-то решит вручную ввести id в строке браузера делает проверка является ли
// * переданное через GET положительным целым числом, а не, например, строкой. Если проверка не проходится
// * запускается функция die() прекращающая работу скрипта. Вместо неё можно сделать перенаправление на главную страницу.
// * Все меню оборачивается в div с классом catalog-menu.
// *
function show_menu($id = 0) {
    if (isset($_GET['id'])) {
        if (intval($_GET['id']) && $_GET['id'] > 0 && round($_GET['id']) == $_GET['id']) {
            $parents_and_child = all_parents_and_child_by_id($_GET['id']);
            echo '<div class="catalog-menu">'.render_menu($parents_and_child).'</div>';
        } else {
            die();
        }
    } else {
        $parents_and_child = all_parents_and_child_by_id($id);
        echo '<div class="catalog-menu">'.render_menu($parents_and_child).'</div>';
    }
}