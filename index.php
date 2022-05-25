<?php

require_once 'init.php';

if (!$current_user || !check_user_id($con, $current_user['id'])) {
    header('Location: quest.php');
    exit();
}

$current_user_id = $current_user['id'];

$project_id = filter_input(INPUT_GET, 'project_id');

if ($project_id && !check_project_id($con, $project_id)) {
    show_error('Такой проект не существует');
}

// $date = filter_input(INPUT_GET, 'date');

$tasks = get_user_tasks($con, $current_user_id, $project_id);
$projects = get_projects($con, $current_user_id);

$search = trim(filter_input(INPUT_GET, 'search')) ?? '';

if ($search) {
    $tasks = get_search_results($con, $search);
}

$content = include_template('main.php', [
    'projects' => $projects,
    'project_id' => $project_id,
    'tasks' => $tasks ?? null,
    'search' => $search,
    'show_complete_tasks' => $show_complete_tasks
]);

$layout = include_template('layout.php', [
    'page_title' => 'Дела в порядке',
    'current_user' => $current_user,
    'content' => $content
]);

print($layout);
