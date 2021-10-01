<?php
include 'functions.php';

$json = '
{
    "hotels" : { "hotel" : [
        { "ort": "rom", "lioc": "inrom" },
        { "ort": "paris", "lioc": "inpsrisd" },
        { "ort": "berlin", "lioc": "inberlin" },
        { "ort": "milano", "lioc": "inmilano" },
        { "ort": "paris", "lioc": "anotherinpsrisd" },
        { "ort": "muc", "lioc": "inmuc" }
    ]
    }
}';

echo '###Input###';
echo '<pre>';
echo $json;
echo '</pre>';

// JSON to array
$array = json_decode($json, true);

// String notation nodes list
$selector = "hotels.hotel.0,hotels.hotel.5";
echo '###Selector###';
pre($selector);

// Make a array of string notation nodes
$string_nodes = explode(',', $selector);

$node_values = array_map(function ($item) use ($exactParam, $array) {
    return $exactParam($item, $array);
}, $string_nodes);

$result = [];
foreach ($string_nodes as $i => $string_node) {
    $keys = array_reverse(explode('.', $string_node));
    $temp = $node_values[$i];

    foreach ($keys as $key) {
        $temp = [$key => $temp];
    }
    $result = array_merge_recursive($result, $temp);
}



echo "###Output###";
pre(json_encode($result));