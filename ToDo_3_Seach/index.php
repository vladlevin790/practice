<?php
require_once "./src/SearchElementOfArray.php";

$data = [
    'category1' => ['price' => 10, 'name' => 'item1'],
    'category2' => ['price' => 200, 'name' => 'item2'],
    'category3' => ['price' => 100, 'name' => 'item4'],
    'category4' => ['price' => 150, 'name' => 'item4'],
    'category5' => ['price' => 300, 'name' => 'item4'],
    'category6' => ['price' => 300, 'name' => 'item2'],
    'category7' => ['price' => 300, 'name' => 'item1']
];

$critery = [10, 'item1'];
$searchData = new SearchElementOfArray($data,$critery);
$results = $searchData->search();
print_r($results);
