<?php

require_once 'init.php';

$content = include_template('main.php', [
    'projects' => $projects,
    'tasks' => $tasks,
    'show_complete_tasks' => $show_complete_tasks
]);

$layout = include_template('layout.php', [
    'page_title' => 'Дела в порядке',
    'content' => $content
]);

print($layout);
