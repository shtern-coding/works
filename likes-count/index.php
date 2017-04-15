<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Сортировка по лайкам</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <link rel="icon" type="image/png" href="img/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="img/favicon-16x16.png" sizes="16x16" />

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/media.css">
    <link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" />

    <link href="https://fonts.googleapis.com/css?family=Fira+Sans:400,400i,500,700" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


    <script src="js/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="js/moment-with-locales.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>

</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="wrap-main">
                <h2>СОРТИРОВКА <br> ПО ЛАЙКАМ</h2>
                <h5>Сканирование постов групп и пабликов
                    Вконтакта и возврат полного списка,
                    отсортированного по количеству лайков. <br>
                <span class="plus">+</span> фильтры по часу/дню недели/дате</h5>
                <div class="no-js">
                    <h4>Требуется включенный в браузере JavaScript</h4>
                    <script>$('.no-js').hide();</script>
                </div> <!-- // .no-js  -->
                <div class="window">
                    <form action="" method="post">
                        <div class="form-group">
                            <label id="label-vk" for="vk-url"></label>
                            <input type="text" class="form-control" id="vk-url" placeholder="Ссылка, public-name или public-id" name="vk-id">
                        </div>
                        <button id="start" type="submit" class="btn btn-default">Запустить</button>
                    </form>
                </div> <!-- // .window  -->
                <img class="like" src="img/logo.png" alt="Эмблема ВК">
                <div class="loading">
                    <img class="ajax-loader" src="img/heart.gif" alt="Загрузка">
                    <progress id="progress" class="progress" value="0" max="1"></progress>
                    <p id="loading-posts"></p>
                </div> <!-- // .loading  -->
            </div> <!-- // .wrap-main  -->

            <div class="wrap-list">
                <div class="info">
                    <img src="https://pp.vk.me/c322620/v322620224/4f13/bKKNRWTEqEo.jpg" alt="Аватар группы">
                    <div class="text">
                        <h3>Ментальная прошивка</h3>
                        <h4 class="total-posts">Всего постов: 85</h4>
                        <h4 class="total-likes">Всего лайков: 946</h4>
                        <h4 class="middle-likes">Лайков на пост: 2</h4>
                    </div> <!-- // .text  -->
                </div> <!-- // .info  -->

                <div class="extra-sort">
                    <div class="btns">
                        <img id="show-sort" class="btn-filter" src="img/btn-filter.png" alt="Фильтры" title="Открыть фильтры">
                        <img id="upend" class="btn-upend" src="img/btn-upend.png" alt="Перевернуть" title="Перевернуть список">
                    </div> <!-- // .btns  -->

                    <div class="types">
                        <div class="test">
                        <table>
                            <tr>
                                <th>
                                    <input id="check-hour" type="checkbox" name="filter-time" class="filter-checkbox">
                                    <h4 id="check-hour-header">По часу</h4>
                                </th>
                                <th>
                                    <input id="check-weekday" type="checkbox" name="filter-weekday" class="filter-checkbox">
                                    <h4 id="check-weekday-header">По дню недели</h4>
                                </th>
                                <th>
                                    <input id="check-date" type="checkbox" name="filter-date" class="filter-checkbox">
                                    <h4 id="check-date-header">По дате</h4>
                                </th>
                            </tr>
                            <tr>
                                <td class="sort-first">
                                    <div class="filter-hour-wrapper">
                                        <div class="filter-hour">
                                            <select id="hours" multiple class="form-control" disabled>
                                                <option value="24">00</option>
                                                <option value="1">01</option>
                                                <option value="2">02</option>
                                                <option value="3">03</option>
                                                <option value="4">04</option>
                                                <option value="5">05</option>
                                                <option value="6">06</option>
                                                <option value="7">07</option>
                                                <option value="8">08</option>
                                                <option value="9">09</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                                <option value="13">13</option>
                                                <option value="14">14</option>
                                                <option value="15">15</option>
                                                <option value="16">16</option>
                                                <option value="17">17</option>
                                                <option value="18">18</option>
                                                <option value="19">19</option>
                                                <option value="20">20</option>
                                                <option value="21">21</option>
                                                <option value="22">22</option>
                                                <option value="23">23</option>
                                            </select>
                                        </div> <!-- // .filter-hour  -->
                                    </div> <!-- // .filter-hour-wrapper  -->
                                </td>
                                <td class="sort-second">
                                    <div class="filter-weekday-wrapper">
                                        <div class="filter-date">
                                            <select id="week-day" class="form-control" multiple name="week-day" disabled>
                                                <option value="1">Понедельник</option>
                                                <option value="2">Вторник</option>
                                                <option value="3">Среда</option>
                                                <option value="4">Четверг</option>
                                                <option value="5">Пятница</option>
                                                <option value="6">Суббота</option>
                                                <option value="7">Воскресенье</option>
                                            </select>
                                        </div> <!-- // .filter-weekday-wrapper  -->
                                    </div> <!-- // .sort-second  -->
                                </td>
                                <td>
                                    <div class="filter-date-wrapper">
                                        <div class="filter-date">
                                            <div class='input-group date'>
                                                <input class="filter-input" type='text' class="form-control" id='datetimepicker1' placeholder="От" disabled />
                                            </div> <!-- // .input-group date  -->
                                            <div class='input-group date'>
                                                <input class="filter-input" type='text' class="form-control" id='datetimepicker2' placeholder="До" disabled/>
                                            </div> <!-- // .input-group date  -->
                                        </div> <!-- // .filter-date  -->
                                    </div> <!-- // .filter-date-wrapper  -->
                                </td>
                            </tr>
                        </table>
                        <button id="sort" type="submit" class="btn btn-default">Фильтровать</button>
                        </div> <!-- // .test  -->
                    </div> <!-- // .types  -->

                </div> <!-- // .extra-sort  -->

                <div class="list">
                    <table>
                    </table>
                </div> <!-- // .list  -->
                <h3 id="more" class="more">Ещё 250</h3>
            </div> <!-- // .wrap-list  -->
        </div> <!-- // .col-md-12  -->
    </div> <!-- // .row  -->
</div> <!-- // .container  -->

<script type="text/javascript" src="js/accounting.min.js"></script>
<script type="text/javascript" src="js/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/functions.js"></script>
<script type="text/javascript" src="js/main.js"></script>
<script type="text/javascript" src="js/sort.js"></script>

</body>
</html>