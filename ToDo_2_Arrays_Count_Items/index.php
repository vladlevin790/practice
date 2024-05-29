<?php
$data = [
    'category' => [
        'one' => [
            'priority' => '3',
            'views' => [
                'user_count' => 345,
                'bot_count' => 9392
            ]
        ],
        'two' => [
            'priority' => '4',
            'views' => [
                'user_count' => 123222,
                'bot_count' => 99
            ]
        ],
        'three' => [
            'priority' => '2',
            'views' => [
                'user_count' => 23,
                'bot_count' => 1
            ]
        ],
        'four' => [
            'priority' => '1',
            'views' => [
                'user_count' => 45,
                'bot_count' => 32,
            ]
        ]
    ]
];
function outputSort($data){
    $max_bot_count = max(array_column(array_column($data['category'], 'views'), 'bot_count'));
    $min_bot_count = min(array_column(array_column($data['category'], 'views'), 'bot_count'));

    usort($data['category'], function($a, $b) {
        return $a['priority'] <=> $b['priority'];
    });

    echo "максимальный 'bot_count': $max_bot_count \n";
    echo "минимальный 'bot_count': $min_bot_count \n";
    echo "значения user_count и bot_count в порядке ASC по значению 'priority': \n";
    foreach ($data['category'] as $category) {
        echo "Priority: {$category['priority']}, user_count: {$category['views']['user_count']}, bot_count: {$category['views']['bot_count']}\n";
    }
}

outputSort($data);

?>
