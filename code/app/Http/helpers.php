<?php

	function generateRandomPassword($length = 15) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    function generateRandomId($name, $length = 10) {
        $arr = explode(' ',trim($name));
        $nameFirst = $arr[0];

        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $nameFirst.$randomString;
    }


    function bookaway_encrypt($string){
        $string = "r5(Sb$|||".$string;
        return base64_encode($string);
    }

    function bookaway_decrypt($string){
        $string =  explode("|||",base64_decode($string));
        return $string[1];
    }


?>
    