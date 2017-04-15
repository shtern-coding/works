<?php

class VkApi {

    // Токен
    public $token;

    /**
     * VkApi constructor.
     * @param $token
     */
    public function __construct($token) {
        $this->token = $token;
    }

    /**
     * Возвращает колличество постов в группе
     * @param $id
     * @return mixed
     */
    public function postsCount($id) {
        return $this->getWallList($id)->response->count;
    }

    /**
     * Возвращает буквенное название группы из URL
     * @param $id
     * @return mixed
     */
    public function screenName($id) {
        return $this->groupInfo($id)->response[0]->screen_name;
    }

    /**
     * Проверяет открыта ли группа.
     * @param $id
     * @return mixed
     */
    public function isClosed($id) {
        return $this->groupInfo($id)->response[0]->is_closed;
    }

    /**
     * Возвращает информацию о группе
     * @param $id
     * @return mixed
     */
    public function groupInfo($id) {
        return $this->request('groups.getById', [
            'group_id' => $id
        ]);
    }

    /**
     * Возвращает посты на стене. Максимум 100 + отбивка
     * @param $id ИД группы
     * @param int $offset Отбивка
     * @return mixed
     */
    public function getWallList($id, $offset = 0) {
        return $this->request('wall.get', [
            'owner_id' => '-'.$id,
            'extended' => 0,
            'count' => 100,
            'offset' => $offset
        ]);
    }

    /**
     * Возвращает все сообщества на который подписан пользователь
     * @param $id Пользователь
     * @return mixed
     */
    public function getUserGroups($id) {
        return $this->request('groups.get', [
            'user_id' => $id,
            'extended' => 1
        ]);
    }

    /**
     * Возвращает все диалоги владельца токена
     * @param $count
     * @return mixed
     */
    public function getDialogs($count) {
        return $this->request('messages.getDialogs', [
            'count' => $count
        ]);
    }

    /**
     * Посылает сообщение
     * @param $id Кому послать
     * @param $message Сообщение
     * @return mixed
     */
    public function sendMessage($id, $message) {
        return $this->request('messages.send', [
            'user_id' => $id,
            'message' => $message
        ]);
    }

    /**
     * Отправляет запрос в API вконтакта и возвращает результат
     * @param $method
     * @param $params
     * @return mixed
     */
    public function request($method, $params) {
        $params['access_token'] = $this->token;
        $params['v'] = '5.52';
        $params = http_build_query($params);
        return json_decode(file_get_contents('https://api.vk.com/method/'.$method.'?'.$params.''));
    }

    /**
     * Принимает буквенное название паблика. Возвращает его цифровой id.
     * Открывает страницу и регулярным выражением находит на ней ссылку на аватар, в названии которого всегда есть id.
     * @param $name Буквенное название
     * @return mixed
     */
    public function idByName($name) {
        $url = 'https://vk.com/'.$name;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $html = curl_exec($ch);
        curl_close($ch);
        preg_match('/\[group\]=(\d*)/', $html, $match);
        if(count($match) == 0) {return false;};
        return $match[1];
    }

    /**
     * Использование метода Execute для получения постов по 2500 штук за раз
     * @param $id
     * @param $offset
     * @return mixed
     */
    public function execute($id, $offset) {
        $code = '
        var iters = 25;
        var lists = [];
        var params = {
            "owner_id": -'.$id.',
            "count": 100,
            "offset": 0,
            "v": "5.52"
        };
        var i = 0;
        while(i<iters) {
            params.offset = '.$offset.' + i*100;
            var posts = API.wall.get(params).items;
            if (posts.length == 0) {
                return lists;
            }
            
            var tmp = {};
            tmp.id = posts@.id;
            tmp.date = posts@.date;
            tmp.likes = posts@.likes@.count;
            
            lists.push(tmp);
            i = i + 1;
        }
        return lists;
        ';
        return $this->request('execute', [
            'code' => $code
        ])->response;
    }
}

