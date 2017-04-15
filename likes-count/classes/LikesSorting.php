<?php

class LikesSorting {

    private $vkApi;
    public $request;
    private $groupId;
    private $groupScrName;
    public $groupInfo;

    public $postsCount;
    public $totalLikes;
    public $isClosed;
    public $groupName;
    public $groupPhoto;
    public $posts = [];

    /**
     * LikesSorting constructor.
     * @param $groupId
     * @param $vkApi
     */
    public function __construct($groupId, $vkApi, $request) {
        $this->groupId = $groupId;
        $this->vkApi = $vkApi;
        $this->request = $request;

        $this->groupInfo = $vkApi->groupInfo($groupId);
        $this->groupScrName = $this->groupInfo->response[0]->screen_name;
        $this->groupName = $this->groupInfo->response[0]->name;
        $this->isClosed = $this->groupInfo->response[0]->is_closed;
        $this->groupPhoto = $this->groupInfo->response[0]->photo_200;

        if ($this->isClosed == 0) {
            $this->postsCount = $vkApi->postsCount($groupId);
        }
    }

    /**
     * Подсчитывает сколько нужно сделать отбивок (при условии, что можно запросить за раз только 2500 постов)
     * чтобы получить все посты за все время.
     * @return int
     */
    public function countOffsets() {
        $postNum = $this->postsCount;
        return round($postNum/2500);
    }

    /**
     * @param $array
     * @param $offset
     *
     */
    public function rightArray($array, $offset) {
        $acc = $offset;
        for($i = 0; $i < count($array); $i++) {
            // проверяет нужно ли продолжать работу текущего скрипта, на случай, если пользователь обновил страницу
            // или сделал новый запрос
            $this->request->stopRequest();
            for($g = 0; $g < count($array[$i]->id); $g++) {
                $url = 'https://vk.com/'.$this->groupScrName.'?w=wall-'.$this->groupId.'_'.$array[$i]->id[$g].'';
                $this->posts[$acc]['likes'] = $array[$i]->likes[$g];
                $this->posts[$acc]['url'] = $url;
                $this->posts[$acc]['date'] = $array[$i]->date[$g];
                $acc++;
                $this->totalLikes += $array[$i]->likes[$g];
            }
        }
    }

    /**
     * Создает список.
     * @return array
     */
    public function makeList() {
        $offsetsNum = $this->countOffsets();
        $groupId = $this->groupId;
        for ($i = 0; $i <= $offsetsNum; $i++) {
            $offset = $i*2500;
            $posts = $this->vkApi->execute($groupId, $offset);
            $this->rightArray($posts, $offset);
        }
        array_multisort($this->posts, SORT_DESC);
        return $this->posts;
    }

    /**
     * Производит фильтрацию записей. Проходит циклом по списку и проверяет каждую запись по тем
     * фильтрам, которые были переданы в массиве фильтров
     * @param $list
     * @param $filters
     * @return array
     */
    static function filterList($list, $filters){
        $newList = [
            'list' => [],
            'total-likes' => 0,
            'total-posts' => 0
        ];
        foreach ($list as $key => $row) {
            // фильтры по дате
            if (isset($filters['date1']) && $row['date'] < $filters['date1']) {
                continue;
            }
            if (isset($filters['date2']) && $row['date'] > $filters['date2']) {
                continue;
            }
            // фильтр по дню недели
            $weekday = date('N', $row['date']);
            if (isset($filters['weekdays']) && !in_array($weekday, $filters['weekdays'])) {
                continue;
            }
            // фильтр по часу
            $hours = date('G', $row['date']);
            if (isset($filters['hours']) && !in_array($hours, $filters['hours'])) {
                continue;
            }
            // если не один фильтр не сработал, то строка записывается в новый массив,
            // который будет возвращен в конце
            array_push($newList['list'], $row);
            $newList['total-likes'] += $row['likes'];
            $newList['total-posts']++;
        }
        return $newList;
    }

}