<?php

require_once 'init.php';

$user_id = 1;

$project_id = filter_input(INPUT_GET, 'project_id');

if ($project_id && !check_project_id($con, $project_id)) {
    show_error('Такой проект не существует');
}

$date = filter_input(INPUT_GET, 'date');

$tasks = get_user_tasks($con, $user_id, $project_id);

$projects = get_projects($con, $user_id);

$content = include_template('main.php', [
    'projects' => $projects,
    'project_id' => $project_id,
    'tasks' => $tasks,
    'show_complete_tasks' => $show_complete_tasks
]);

$layout = include_template('layout.php', [
    'page_title' => 'Дела в порядке',
    'content' => $content
]);

print($layout);
