<?php

require_once 'init.php';

$content = include_template('guest.php');

$layout = include_template('main-layout.php', [
    'page_title' => 'Дела в порядке',
    'content' => $content
]);

print($layout);
