// Настройка плагина, форматирующего числа
accounting.settings = {
    number: {
        precision : 0,
        thousand: " ",
        decimal : "."
    }
};

// Отмечает посещенные ссылки
$('.list').on('click', '#url' ,function(){
    $(this).addClass('visited');
});

// Изменение иконки при наведении курсора
$('#show-sort').hover(function(){
    if ($('#show-sort').attr('src') == 'img/btn-filter.png') {
        $('#show-sort').attr('src', 'img/btn-filter(hover).png');
    } else {
        $('#show-sort').attr('src', 'img/btn-filter.png');
    }
});

// Изменение иконки при наведении курсора
$('#upend').hover(function(){
    if ($('#upend').attr('src') == 'img/btn-upend.png') {
        $('#upend').attr('src', 'img/btn-upend(hover).png');
    } else {
        $('#upend').attr('src', 'img/btn-upend.png');
    }
});

// Ajaх запрос на показ ещё 250-и постов
$('#more').on('click', function(){
    var more_posts,
    shown = Number($('.wrap-list').attr('data-posts-shown')),
    total = $('.wrap-list').attr('data-total-posts');
    // проверка список фильтрованный или нет
    if ($('.wrap-list').attr('data-filtered') == 'filtered') {
        more_posts = 'filtered'
    } else {
        more_posts = 'notfiltered';
    }
    // запрос
    $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {more_posts: more_posts, shown: shown},
        success: function(data){
            $('.list table').append(data);
            shown = shown+250;
            $('.wrap-list').attr('data-posts-shown', shown);
            var last_post = $('.table-row:last .first-row p').text();
            if (last_post == total) {
                $('.more').hide();
            }
        }
    });
});

// Ajaх запрос на переворот списка
$('#upend').on('click', function(){
    var posts;
    // проверка список фильтрованный или нет
    if ($('.wrap-list').attr('data-filtered') == 'filtered') {
        posts = 'filtered'
    } else {
        posts = 'notfiltered';
    }
    // запрос
    $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {upend: posts},
        success: function(data){
            $('.list table').empty().append(data);
        }
    });
});


var xhr,
loading,
active = false;
// Ajax запрос на вывод списка для запрошенного паблика
$('#start').click(function(e) {
    e.preventDefault();
    var url = $('#vk-url').val(),
    name = test_url(url);

    if (name) {
        stop_php();
        if (active) {
            clearInterval(loading);
            $('#progress').attr('value', 0).hide();
            $('#loading-posts').text('');
            xhr.abort();
        }
        $('.more').css('display', 'none');
        $(".wrap-list").css('display', 'none');
        $('#label-vk').empty();
        $('input').removeClass('wrong');
        $('.ajax-loader').css('display', 'block');
        $('.loading').css('display', 'block');

        active = true;
        xhr = $.ajax({
            url: 'ajax.php',
            method: 'POST',
            data: {exist: name},
            dataType: 'html',
            success: function (data) {
                if (data == 'loading') {
                    return false;
                } else if (data == 'none') {
                    wrong("Такого паблика не существует");
                    $('.ajax-loader').css('display', 'none');
                    $('.wrap-main').css('margin-top', '17.5em');
                } else if (data == 'closed') {
                    wrong("Паблик закрыт");
                    $('.ajax-loader').css('display', 'none');
                    $('.wrap-main').css('margin-top', '17.5em');
                } else {
                    data = JSON.parse(data);
                    getList(data);
                }
            }
        });
    }
});
