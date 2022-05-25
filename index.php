<?php

require_once 'init.php';

if (!$user || !check_user_id($con, $user['id'])) {
    header('Location: quest.php');
    exit();
}

$user_id = $user['id'];

$project_id = filter_input(INPUT_GET, 'project_id');

if ($project_id && !check_project_id($con, $project_id)) {
    show_error('Такой проект не существует');
}

$task_id = filter_input(INPUT_GET, 'task_id', FILTER_SANITIZE_NUMBER_INT);
$task_check = filter_input(INPUT_GET, 'check', FILTER_SANITIZE_NUMBER_INT);
$show_completed_tasks = filter_input(INPUT_GET, 'show_completed', FILTER_SANITIZE_NUMBER_INT);
$search = trim(filter_input(INPUT_GET, 'search')) ?? null;
$filter = filter_input(INPUT_GET, 'filter');

$projects = get_projects($con, $user_id);
$tasks = get_all_user_tasks($con, $user_id, $project_id = null);

if ($task_id && !check_task_id($con, $task_id)) {
    show_error('Такой задачи не существует');
}

if ($task_id && $task_check) {
    complete_task($con, $task_id);
} elseif ($task_id && !$task_check) {
    remove_complete_task($con, $task_id);
}

if ($search) {
    $tasks = get_search_results($con, $search);
}

if ($filter === 'today') {
    $tasks = get_today_tasks($tasks);
} elseif ($filter === 'tomorrow') {
    $tasks = get_tomorrow_tasks($tasks);
} elseif ($filter === 'overday') {
    $tasks = get_overday_tasks($tasks);
}

if (!$show_completed_tasks) {
    $tasks = get_user_no_completed_tasks($tasks);
}

$content = include_template('main.php', [
    'projects' => $projects,
    'project_id' => $project_id,
    'task_id' => $task_id ?? null,
    'task_check' => $task_check ?? null,
    'tasks' => $tasks ?? null,
    'search' => $search,
    'filter' => $filter ?? null,
    'show_completed_tasks' => $show_completed_tasks,
    'is_complete' => $is_complete ?? null
]);

$layout = include_template('layout.php', [
    'page_title' => 'Дела в порядке',
    'user' => $user,
    'content' => $content
]);

print($layout);
