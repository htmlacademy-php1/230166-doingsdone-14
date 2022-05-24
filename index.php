<?php

require_once 'init.php';

$user_id = 1;
$projects = get_projects($con, $user_id);
$tasks = get_tasks($con, $user_id);

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
