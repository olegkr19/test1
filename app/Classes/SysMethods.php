<?php

namespace App\Classes;

class SysMethods {
    //Экранирование телефона
    public function hEscapePhone($phone) {
        return strtr($phone,['+'  => '','.'  => '','-'  => '',' '  => '','_'  => '','/'  => '','&'  => '','"'  => "",'\'' => '','<'  => '','>'  => '']);
    }

    //Текущая дата + колво дней и UNIXTIME
    public function getLifeTime($days){
        $lifeTime = new DateTime();
        $lifeTime->modify('+'.(int)$days.' days');
        return date_timestamp_get($lifeTime);
    }

    //Зашифровать пароль и посыпать солью
    public function passhash($pass){
        //Позже можно будет сделать соль динамичной
        return password_hash($pass, PASSWORD_BCRYPT, ['salt' => "y2aJnxgTCHzEZvB0Ru9AFSnHnjV/5A==", 'cost' => 10]);
    }

    public function generatePassword($length = 32){
        if($length < 10){
            $chars = 'abdefhiknrstyz23456789';
        }else{
            $chars = 'abdefhiknrstyzABDEFGHKNQRSTYZ23456789';
        }
        $numChars = strlen($chars);
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= substr($chars, rand(1, $numChars) - 1, 1);
        }
        return $string;
    }

    public function get_country($phone){
        $arr_country = json_decode(file_get_contents("./config/country.json"), true);
        $phone = ltrim($phone, '+');
        $r = [];
        foreach($arr_country as $iso=>$country){
            if($country["code"]==substr($phone, 0, strlen($country["code"]))){
                        $r = $country;
            }
        }
        return $r;
    }

    public static function addError($error){
        global $errors;
        $errors[] = $error;
    }

    public static function sendError($message){
        $subject = "Auth server";
        fopen("https://api.telegram.org/bot726831211:AAGFMUtqm1rmS-UTn0YpzglneXggCwvi36I/sendMessage?chat_id=514575284&parse_mode=html&text=".$subject.": ".$message,"r");
    }

    //Обратится к API с повтором 5 раз и промежутком 2 сек
    public function getUrl($url){
                $a = false;
                $i = 0;
                while($a == false && $i < 5)
                {
                    $a = file_get_contents($url, "r", $ctx);
                    $i++;
                    if($a == false){
                        usleep(2000000); //2секунды
                    }

                }

                return json_decode($a, true);
    }

}
