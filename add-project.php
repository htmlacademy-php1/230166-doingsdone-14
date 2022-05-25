<?php

require_once 'init.php';

if (!$user || !check_user_id($con, $user['id'])) {
    header('Location: quest.php');
    exit();
}

$user_id = $user['id'];
$projects = get_projects($con, $user_id);
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $project_name = trim(filter_input(INPUT_POST, 'project_name'));

    if (!$project_name) {
        $errors['project_name'] = 'Поле надо заполнить';
    } elseif (check_project_name($con, $project_name)) {
        $errors['project_name'] = 'Такой проект уже существует';
    }

    if (empty($errors)) {
        add_project($con, $project_name, $user_id);
        header('Location: index.php');
        exit();
    }
}

$content = include_template('form-project.php', [
    'projects' => $projects ?? null,
    'project_name' => $project_name ?? null,
    'errors' => $errors
]);

$layout = include_template('layout.php', [
    'page_title' => 'Добавление задачи',
    'user' => $user,
    'content' => $content
]);

print($layout);
