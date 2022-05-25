<?php

require_once 'init.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim(filter_input(INPUT_POST, 'email'));
    $password = trim(filter_input(INPUT_POST, 'password'));

    $errors = get_login_errors($con, $email, $password);

    $current_user = get_сurrent_user($con, $email);

    if (empty($errors) && $current_user) {
        if (password_verify($password, $current_user['password'])) {
            $_SESSION['current_user'] = $current_user;
        } else {
            $errors['password'] = "Пароли не совпадают";
        }
    }

    if (isset($_SESSION['current_user'])) {
        header('Location: main.php');
        exit();
    }
}

$content = include_template('auth.php', [
    'email' => $email ?? null,
    'password' => $password ?? null,
    'errors' => $errors ?? null,
]);

$layout = include_template('main-layout.php', [
    'page_title' => 'Дела в порядке',
    'content' => $content
]);

print($layout);
