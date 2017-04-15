// Выдает ошибку, если запрос не проходит проверку
function wrong(text) {
    $('#label-vk').text(text);
    $('input').addClass('wrong');
    return false;
}

// Вычисляет примерную скорость загрузки на основе количества постов
function speed_count(posts_num) {
    var n = 20;
    if (posts_num > 15000) {
        n = 150;
    } else if (posts_num > 8000) {
        n = -19;
    } else if (posts_num > 6000) {
        n = -19;
    } else if (posts_num > 4000) {
        n = -12;
    } else if (posts_num > 500) {
        n = 18;
    } else if (posts_num > 1000) {
        n = Number(String(posts_num).substring(0,1))+1;
    } else if (posts_num > 10000) {
        n = Number(String(posts_num).substring(0,2));
    }
    return (posts_num / 63) + n;
}

// Запускает полосу загрузки и выводит колличество постов
function progress_bar(posts_num) {
    var num = 0,
    speed = speed_count(posts_num);
    $('#loading-posts').text(accounting.formatNumber(posts_num));
    $('#progress').show();
    loading = setInterval(function () {
        num = num + 0.01;
        $('.progress').attr('value', num);
    }, speed);
}

// Останаливает php скрипт на сервере
function stop_php() {
    $.ajax({
        url: 'stop.php',
        method: 'POST',
        data: {stop: 1},
        dataType: 'html',
        success: function() {
        }
    })
}

// Основной Ajax запрос списка постов
function getList(info) {
    xhr = $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {public_id: info['id']},
        dataType: 'html',
        beforeSend: function () {
            progress_bar(info['posts-count']);
        },
        success: function (data) {
            data = JSON.parse(data);
            active = false;
            $('.text h3').text(data['name']);
            $('.text h4').text('Всего постов: ' + accounting.formatNumber(data['total-posts']));
            $('.total-likes').text('Всего лайков: ' + accounting.formatNumber(data['total-likes']));
            if (data['total-posts'] == 0) {
                var middle_like = 0;
            } else {
                var middle_like = (data['total-likes'] / data['total-posts']).toFixed(1);
            }
            $('.middle-likes').text('Лайков на пост: ' + accounting.formatNumber(middle_like, 1));
            $('.info img').attr('src', data['photo']);

            $('.wrap-list').attr('data-total-posts', data['total-posts']);
            $('.wrap-list').attr('data-posts-shown', 250);
            $('.wrap-list').attr('data-filtered', 'notfiltered');

            $('.list table').empty().append(data['list']);
            if (data['total-posts'] > 250) {
                $('.more').css('display', 'block');
            }

            // Поднимаем главное меню вверх
            $('.wrap-main').css('margin-top', '3.75em');
            // Прячем гифку с анимацией загрузки и делаем видимым окно со списком постов.
            // (+ небольшая задержка для плавности)
            setTimeout(function () {
                $('.loading').css('display', 'none');
                $('.types').hide();
                $(".wrap-list").css('display', 'block');

                $('#loading-posts').text('');
                clearInterval(loading);
                $('#progress').attr('value', 0).hide();
            }, 1600);
        }
    });
}

function test_url(url) {
    var url_parts = url.split('/'),
        url_lenght = url_parts.length,
        regexp = /[а-яё]/i,
        regexp2 = /[a-z]/i,
        right_urls = [
            'www.vk.com',
            'vk.com',
            'm.vk.com',
            'www.m.vk.com',
            'www.vkontakte.ru',
            'www.vkontakte.com'
        ],
        right_protocols = [
            'http:',
            'https:'
        ];

// проверки введеного адреса и выделение имени паблика или его id
    if (url == '') { // если пустое поле
        wrong('Заполните поле');
    } else if (regexp.test(url)) { // если кириллические символы
        wrong('Нельзя использовать кириллические символы');
    } else if (url_lenght == 5 && url_parts[4].length == 0) {
        var name = url_parts[3];
    } else if (url_lenght > 4) {
        wrong("Неверная ссылка. Используйте форматы 'vk.com/название', 'название', 'id'");
    } else if (url_lenght == 4 && (jQuery.inArray(url_parts[2], right_urls) == -1 || jQuery.inArray(url_parts[0], right_protocols) == -1 || url_parts[1] != 0)) {
        wrong("Неверная ссылка");
    } else if (url_lenght == 4) {
        var name = url_parts[3];
    } else if (url_lenght == 2 && jQuery.inArray(url_parts[0], right_urls) == -1) {
        wrong("Неверная ссылка");
    } else if (url_lenght == 2) {
        var name = url_parts[1];
    } else if (url_lenght == 1) {
        var name = url_parts[0];
    }

    if (name.indexOf('?') != -1) {
        name = name.split('?')[0];
    }

    if (name) {
        if (!regexp2.test(name)) {
            name = 'public' + name;
        }
    }

    return name;
}
