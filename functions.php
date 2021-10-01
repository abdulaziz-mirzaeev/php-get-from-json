<?php
function prent_r($array, $name = '')
{
    echo $name;
    echo '<pre>';
    print_r($array);
    echo '</pre>';
}
$exactParam = function ($parampath, $array) {
    $paramArr = explode('.', $parampath);
    $paramArr = array_filter($paramArr, function ($item){ return strlen($item) > 0;});
    $param = $array[$paramArr[0]];
    unset($paramArr[0]);
    foreach ($paramArr as $item) {
        $param = $param[$item];
    }

    return $param;
};

function pre($value) {
    echo '<pre>';
    echo $value;
    echo '</pre>';
}

