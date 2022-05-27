<?php

require_once 'init.php';

if (!$user || !check_user_id($con, $user['id'])) {
    header('Location: quest.php');
    exit();
}

$user_id = $user['id'];
$projects = get_projects($con, $user_id);
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_name = trim(filter_input(INPUT_POST, 'task_name'));
    $project_id = filter_input(INPUT_POST, 'project_id');
    $deadline = filter_input(INPUT_POST, 'deadline');

    if (!$deadline) {
        $deadline = null;
    }

    if (!$task_name) {
        $errors['task_name'] = 'Поле надо заполнить';
    } elseif (!check_length_of_string($task_name, 1, 128)) {
        $errors['task_name'] = 'Количество символов должно быть не более 128';
    }

    if (!$project_id) {
        $errors['project_id'] = 'Поле надо заполнить';
    } elseif (!check_project_id($con, $project_id)) {
        $errors['project_id'] = 'Такой проект не существует';
    }

    if ($deadline && !is_date_valid($deadline)) {
        $errors['deadline'] = 'Неправильный формат даты';
    } elseif ($deadline && $deadline < date('Y-m-d')) {
        $errors['deadline'] = 'Дата должна быть больше или равна текущей';
    }

    array_filter($errors);

    if ($_FILES['file']['name']) {
        $tmp_name = $_FILES['file']['tmp_name'];
        $file_name = $_FILES['file']['name'];
        $file_name = uniqid() . '_' . $file_name;
        $file_path = __DIR__ . '/uploads/';

        move_uploaded_file($_FILES['file']['tmp_name'], $file_path . $file_name);
        $file_url = 'uploads/' . $file_name;
    } else {
        $file_url = null;
    }

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
    'user' => $user,
    'content' => $content
]);

print($layout);
