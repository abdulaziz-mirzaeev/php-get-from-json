<?php
$answer = '';

function selectNodes($selector, $json) {
    $array = json_decode($json, true);
    $stringNodes = explode(',', $selector);

    $stringNodes = array_filter($stringNodes, function ($node) use ($array) {
        $keys = explode('.', $node);
        $extra = $array;
        $exists = true;
        foreach ($keys as $key) {
            if (!(isset($extra[$key]) && $extra[$key])) {
                $exists = false;
                break;
            }
            $extra = $extra[$key];
        }
        return $exists;
    });

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

    function array_merge_recursive_distinct ( array &$array1, array &$array2 )
    {
        $merged = $array1;

        foreach ( $array2 as $key => &$value )
        {
            if ( is_array ( $value ) && isset ( $merged [$key] ) && is_array ( $merged [$key] ) )
            {
                $merged [$key] = array_merge_recursive_distinct ( $merged [$key], $value );
            }
            else
            {
                $merged [$key] = $value;
            }
        }

        return $merged;
    }

    $node_values = array_map(function ($item) use ($getExactParam, $array) {
        return $getExactParam($item, $array);
    }, $stringNodes);

    $result = [];
    foreach ($stringNodes as $i => $stringNode) {
        $keys = array_reverse(explode('.', $stringNode));
        $temp = $node_values[$i];

        foreach ($keys as $key) {
            if (is_numeric($key)) {
                $temp = [$temp];
            } else {
                $temp = [$key => $temp];
            }
        }
        $result = array_merge_recursive_distinct($result, $temp);
    }

    return json_encode($result, JSON_UNESCAPED_SLASHES);

}

$json = $_POST['json'] ?? '';

$selector = $_POST['selector'] ?? '';

if ($json && $selector) {
    $answer = selectNodes($selector, $json);
}
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
    <textarea id="json" name="json" cols="30" rows="10"><?php echo $json ?></textarea><br>

    <label for="selector">Selector</label><br>
    <input id="selector" name="selector" type="text" style="width: 250px" value="<?php echo $selector ?>"><br>

    <input type="submit">
</form>
</body>
</html>

<?php

echo '<pre>';
echo $answer;
echo '</pre>';

?>

