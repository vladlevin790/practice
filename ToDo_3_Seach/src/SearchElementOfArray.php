<?php
class SearchElementOfArray
{
    private $arrayData = [];
    private $critery = [];

    public function __construct(array $data, array $critery){
        $this->arrayData = $data;
        $this->critery = $critery;
    }

    public function search() {
        [$priceCritery, $nameCritery] = $this->critery;

        $data = [];
        foreach ($this->arrayData as $category => $item) {
            $data[] = array_merge(['category' => $category], $item);
        }

        usort($data, function($a, $b) {
            return $a['price'] <=> $b['price'];
        });

        $priceResults = [];
        $index = $this->binarySearch($data, 'price', $priceCritery);
        if ($index != -1) {
            $priceResults[] = $data[$index];
            $i = $index - 1;
            while ($i >= 0 && $data[$i]['price'] == $priceCritery) {
                $priceResults[] = $data[$i];
                $i--;
            }
            $i = $index + 1;
            while ($i < count($data) && $data[$i]['price'] == $priceCritery) {
                $priceResults[] = $data[$i];
                $i++;
            }
        }

        usort($data, function($a, $b) {
            return $a['name'] <=> $b['name'];
        });

        $nameResults = [];
        $index = $this->binarySearch($data, 'name', $nameCritery);
        if ($index != -1) {
            $nameResults[] = $data[$index];
            $i = $index - 1;
            while ($i >= 0 && $data[$i]['name'] == $nameCritery) {
                $nameResults[] = $data[$i];
                $i--;
            }
            $i = $index + 1;
            while ($i < count($data) && $data[$i]['name'] == $nameCritery) {
                $nameResults[] = $data[$i];
                $i++;
            }
        }

        $results = array_merge($priceResults, $nameResults);
        $uniqueResults = array_unique($results, SORT_REGULAR);

        $finalResults = [];

        foreach ($uniqueResults as $item) {
            $category = $item['category'];
            unset($item['category']);
            $finalResults[$category] = $item;
        }

        return $finalResults;
    }

    private function binarySearch($data, $key, $value) {
        $left = 0;
        $right = count($data) - 1;

        while ($left <= $right) {
            $mid = floor(($left + $right) / 2);
            if ($data[$mid][$key] < $value) {
                $left = $mid + 1;
            } elseif ($data[$mid][$key] > $value) {
                $right = $mid - 1;
            } else {
                return $mid;
            }
        }

        return -1;
    }


}