<?php

require_once 'init.php';


$user_id = 1;

$project_id = filter_input(INPUT_GET, 'project_id');

if ($project_id && check_project_id($con, $project_id)) {
    $tasks = get_project_user_tasks($con, $user_id, $project_id);
} else {
    $tasks = get_user_tasks($con, $user_id);
}

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
