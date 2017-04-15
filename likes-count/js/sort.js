// Открывает меню фильтров
$('#show-sort').click(function(){
    var display = $('.types').css('display');
    if (display == 'none') {
        $('.types').show('blind', 500);
    } else {
        $('.types').hide('blind', 500);
    }
});

// Включает фильтр по дате
$('#check-date').click(function(){
    if ($('#check-date').is(':checked')) {
        $('#datetimepicker1').attr('disabled', false);
        $('#datetimepicker2').attr('disabled', false);
        $('#datetimepicker1').datetimepicker({language: 'ru', minuteStepping:10, useCurrent:true, daysOfWeekDisabled:[0,6], pickTime:false});
        $('#datetimepicker2').datetimepicker({language: 'ru',minuteStepping:10,daysOfWeekDisabled:[0,6],pickTime: false});
        $('#datetimepicker1').css('cursor', 'text');
        $('#datetimepicker2').css('cursor', 'text');
        $('#datetimepicker1').css('background-color', '#dff0e4');
        $('#datetimepicker2').css('background-color', '#dff0e4');
    } else {
        $('#datetimepicker1').attr('disabled', true);
        $('#datetimepicker2').attr('disabled', true);
        $('#datetimepicker1').val('');
        $('#datetimepicker2').val('');
        $('#datetimepicker1').css('cursor', 'not-allowed');
        $('#datetimepicker2').css('cursor', 'not-allowed');
        $('#datetimepicker1').css('background-color', '#c2d0cc');
        $('#datetimepicker2').css('background-color', '#c2d0cc');
    }
});

// Включает фильтр по дню недели
$('#check-weekday').click(function(){
    if ($('#check-weekday').is(':checked')) {
        $('#week-day').attr('disabled', false);
        $('#week-day').css('disabled', false);
        $('#week-day').css('background-color', '#dff0e4');
    } else {
        $('#week-day').attr('disabled', true);
        $("#week-day").val([]);
        $('#week-day').css('background-color', '#c2d0cc');
    }
});

// Включает фильтр по часу
$('#check-hour').click(function(){
    if ($('#check-hour').is(':checked')) {
        $('#hours').attr('disabled', false);
        $('#hours').css('background-color', '#dff0e4');
    } else {
        $('#hours').attr('disabled', true);
        $("#hours").val([]);
        $('#hours').css('background-color', '#c2d0cc');
    }
});

// Отправляет ajax запрос на фильтрацию
$('#sort').click(function () {
    // получение дат из input's
    var date1 = $('#datetimepicker1').val(),
    date2 = $('#datetimepicker2').val(),
    // получение выбранных дней недели
    weekdays = $('#week-day').val(),
    hours = $('#hours').val();

    $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {filter: 1, date1: date1, date2: date2, weekdays: weekdays, hours: hours},
        success: function(data){
            data = JSON.parse(data);
            $('.wrap-list').attr('data-filtered', 'filtered');
            $('.wrap-list').attr('data-total-posts', data['total-posts']);
            $('.list table').empty().append(data['list']);

            $('.text h4').text('Всего постов: '+accounting.formatNumber(data['total-posts']));
            $('.total-likes').text('Всего лайков: '+accounting.formatNumber(data['total-likes']));
            if (data['total-posts'] == 0) {
                var middle_like = 0;
            } else {
                var middle_like = (data['total-likes'] / data['total-posts']).toFixed(1);
            }
            $('.middle-likes').text('Лайков на пост: '+accounting.formatNumber(middle_like, 1));

            var last_post = $('.table-row:last .first-row p').text();
            if (last_post == data['total-posts']) {
                $('.more').hide();
            }
        }
    });
});