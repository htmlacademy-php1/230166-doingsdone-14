<?php

require_once 'init.php';

$user_id = 1;
$projects = get_projects($con, $user_id);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task_name = trim(filter_input(INPUT_POST, 'task_name'));
    $project_id = filter_input(INPUT_POST, 'project_id');
    $deadline = filter_input(INPUT_POST, 'deadline');

    if (!$deadline) {
        $deadline = null;
    }

    $errors = get_task_errors($con, $task_name, $project_id, $deadline);

    $file_url = get_file_url('file');

    if (empty($errors)) {
        add_task($con, [$task_name, $file_url, $deadline, $project_id, $user_id]);
        header('Location: index.php');
        exit();
    }
}

$content = include_template('form-task.php', [
    'projects' => $projects ?? null,
    'task_name' => $task_name ?? null,
    'project_id' => $project_id ?? null,
    'deadline' => $deadline ?? null,
    'errors' => $errors ?? null
]);

$layout = include_template('layout.php', [
    'page_title' => 'Добавление задачи',
    'content' => $content
]);

print($layout);
