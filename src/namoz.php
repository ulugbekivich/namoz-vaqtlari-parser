<?php

date_default_timezone_set('Asia/Tashkent');

class Namoz
{
    private $city;
    private $type = "bugungi";

    function __construct(string $city = "Toshkent")
    {
        $this->city = $city;
    }

    public function get(): array
    {
        $date = date('N');
        $weekdates="1Dushanba1 2Seshanba2 3Chorshanba3 4Payshanba4 5Juma5 6Shanba6 7Yakshanba7"; 
        $ex=explode("$date",$weekdates); 
        $week_date="$ex[1]";

        $citys = [
            'toshkent' => 27,
            'andijon' => 1,
            'buxoro' => 4,
            'guliston' => 5,
            'samarqand' => 18,
            'namangan' => 15,
            'navoiy' => 14,
            'jizzax' => 9,
            'nukus' => 16,
            'qarshi' => 25,
            'qoqon' => 26,
            'xiva' => 21,
            'margilon' => 13
        ]; //Qabul qilinadigan shaharlar

        
        if (!isset($citys[strtolower($this->city)])) {
            echo json_encode(['status' => false, 'result' => "Kiritilgan hudud topilmadi!"], JSON_PRETTY_PRINT);
            exit;
        }
        $oy = date("m");
        if ($oy[0] == "0") $oy = str_replace("0", "", $oy);

        $get = file_get_contents("https://islom.uz/vaqtlar/" . $citys[str_replace("'", "", strtolower($this->city))] . "/" . $oy);

        $response = [
            'status' => true,
            'date' => date('Y:m:d'),
            'weekdate' => $week_date,
            'result'=>[]
        ];

        if (mb_stripos($get, "</tr><tr class='p_day bugun'>") !== false)
            $array = explode("</tr><tr class='p_day bugun'>", $get)[1];
        elseif (mb_stripos($get, "</tr><tr class='juma bugun'>") !== false)
            $array = explode("</tr><tr class='juma bugun'>", $get)[1];
        else {
            $response['status'] = false;
            $response['result'] = "Ma'lumot topilmadi";
            echo json_encode($response, JSON_PRETTY_PRINT);
            exit;
        }
        $array = explode("\n", strip_tags($array));
        $sahar = trim($array[$this->type == "bugungi" ? 4 : 14]);
        $quyosh = trim($array[$this->type == "bugungi" ? 5 : 15]);
        $peshin = trim($array[$this->type == "bugungi" ? 6 : 16]);
        $asr = trim($array[$this->type == "bugungi" ? 7 : 17]);
        $shom = trim($array[$this->type == "bugungi" ? 8 : 18]);
        $xufton = trim($array[$this->type == "bugungi" ? 9 : 19]);
        $response['result'] = [
            'tong_saharlik' => $sahar,
            'quyosh' => $quyosh,
            'peshin' => $peshin,
            'asr' => $asr,
            'shom_iftor' => $shom,
            'xufton' => $xufton
        ];

        return $response;
    }
}
