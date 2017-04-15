<?php

class HTMLMaker
{

    private function monthName($num) {
        switch ($num) {
            case 1:
                return 'Янв.';
                break;
            case 2:
                return 'Фев.';
                break;
            case 3:
                return 'Мар.';
                break;
            case 4:
                return 'Апр.';
                break;
            case 5:
                return 'Май.';
                break;
            case 6:
                return 'Июн.';
                break;
            case 7:
                return 'Июл.';
                break;
            case 8:
                return 'Авг.';
                break;
            case 9:
                return 'Сен.';
                break;
            case 10:
                return 'Окт.';
                break;
            case 11:
                return 'Ноя.';
                break;
            case 12:
                return 'Дек.';
                break;
        }
        return false;
    }

    private function weekDay($num) {
        switch ($num) {
            case 1:
                return "Пн.";
                break;
            case 2:
                return "Вт.";
                break;
            case 3:
                return "Ср.";
                break;
            case 4:
                return "Чт.";
                break;
            case 5:
                return "Пт.";
                break;
            case 6:
                return "Сб.";
                break;
            case 7:
                return "Вс.";
                break;
        }
        return false;
    }

    private function dateNormal($unixtime) {
        $date = [];
        $date['year'] = date('Y', $unixtime);
        $date['month'] = '';
        $date['week-day'] = $this->weekDay(date('N', $unixtime));
        $date['day'] = date('d', $unixtime);
        $date['hour'] = date('H', $unixtime);
        $date['min'] = date('i', $unixtime);
        $date['time'] = date('H', $unixtime) . ':' . date('i', $unixtime);
        $date['day-month'] = date('d', $unixtime) . ' ' . $this->monthName(date('n', $unixtime));
        return $date;
    }

    public function htmlList($list, $totalPosts, $start = 0, $stop = 250) {
        $string = '';
        $n = $start+1;
        for ($i = $start; $i < $stop; $i++) {
            if ($i == $totalPosts) {break;}
            $row = $list[$i];
            $date = $this->dateNormal($row['date']);
            $string .= "<tr class='table-row'>";
            $string .= "<td class='first-row'><p>" . $n . "</p></td>";
            $string .= "<td class='second-row'><p class='like-count'>" . number_format($row['likes'], 0, '.', ' ') . "</p></td>";
            $string .= "<td class='third-row'><p><a id='url' href='" . $row['url'] . "' target='blank_" . $n . "'>Ссылка на пост</a></p></td>";
            $string .= "<td class='forth-row'><p>" . $date['time'] . "</p></td>";
            $string .= "<td class='fivth-row'><p>" . $date['week-day'] . "</p></td>";
            $string .= "<td class='sixth-row'><p>" . $date['day-month'] . "</p></td>";
            $string .= "<td class='seven-row'><p class='year'>" . $date['year'] . "</p></td>";
            $string .= "</tr>";
            $n++;
        }
        return $string;
    }
}