<?php

function handleData(int $data): int {
    if ($data < 7) {
        return round(low_quantity($data));
    } elseif ($data == 10) {
        return round(medium_quantity());
    } elseif ($data > 40) {
        return round(high_quantity($data));
    } else {
        return $data;
    }
}

function low_quantity(int $data): float {
    return $data - ($data * 0.5);
}

function medium_quantity(): int {
    return 0;
}

function high_quantity(int $data): float {
    return $data * 0.5;
}

function countUniqueResultsBetween(int $start, int $end): int {
    $uniqueResults = [];
    for ($i = $start; $i <= $end; $i++) {
        $result = handleData($i);
        $uniqueResults[$result] = true;
    }
    return count($uniqueResults);
}

$count1 = countUniqueResultsBetween(1, 15);
$count2 = countUniqueResultsBetween(3, 55);
$count3 = countUniqueResultsBetween(9, 43);

echo "Количество уникальных результатов от 1 до 15: $count1\n";
echo "Количество уникальных результатов от 3 до 55: $count2\n";
echo "Количество уникальных результатов от 9 до 43: $count3\n";

?>
