<?php
require_once 'classes/RequestWatch.php';

// Останавливает отправленные ajax запрос на сервере. Меняет статус запроса, после чего ненужный процесс выполнет 'die'
if (isset($_POST['stop'])) {
    $request = new RequestWatch($_SERVER['REMOTE_ADDR']);
    $request->changeStatus(1);
}