<?php
function selectNodes($selector, $json) {
    $array = json_decode($json, true);
    $stringNodes = explode(',', $selector);

    $getExactParam = function ($parampath, $array) {
        $paramArr = explode('.', $parampath);
        $paramArr = array_filter($paramArr, function ($item){ return strlen($item) > 0;});
        $param = $array[$paramArr[0]];
        unset($paramArr[0]);
        foreach ($paramArr as $item) {
            $param = $param[$item];
        }

        return $param;
    };

    $node_values = array_map(function ($item) use ($getExactParam, $array) {
        return $getExactParam($item, $array);
    }, $stringNodes);

    $result = [];
    foreach ($stringNodes as $i => $stringNode) {
        $keys = array_reverse(explode('.', $stringNode));
        $temp = $node_values[$i];

        foreach ($keys as $key) {
            $temp = [$key => $temp];
        }
        $result = array_merge_recursive($result, $temp);
    }

    return json_encode($result);

}

$json = $_POST['json'];

$selector = $_POST['selector'];

$answer = selectNodes($selector, $json);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form action="/" method="post">
    <label for="json">JSON array</label><br>
    <textarea id="json" name="json" cols="30" rows="10"></textarea><br>

    <label for="selector">Selector</label><br>
    <input id="selector" name="selector" type="text" style="width: 250px"><br>

    <input type="submit">
</form>
</body>
</html>

<?php

echo '<pre>';
echo $answer;
echo '</pre>';

?>

