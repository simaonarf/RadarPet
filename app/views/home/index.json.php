<?php

$postsToJson = [];

foreach ($posts as $post) {
    $postsToJson[] = ['id' => $post->id, 'title' => $post->title];
}

$json['posts'] = $postsToJson;
$json['pagination'] = [
    'page'                       => $paginator->getPage(),
    'per_page'                   => $paginator->perPage(),
    'total_of_pages'             => $paginator->totalOfPages(),
    'total_of_registers'         => $paginator->totalOfRegisters(),
    'total_of_registers_of_page' => $paginator->totalOfRegistersOfPage(),
];
