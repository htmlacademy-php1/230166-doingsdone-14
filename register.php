<?php

require_once 'init.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim(filter_input(INPUT_POST, 'email'));
    $password = trim(filter_input(INPUT_POST, 'password'));
    $login = trim(filter_input(INPUT_POST, 'login'));

    $errors = get_register_errors($con, $email, $password, $login);
    var_dump($login);

    var_dump($errors);

    if (empty($errors)) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        add_user($con, [$email, $password, $login]);
        header('Location: index.php');
        exit();
    }
}

$content = include_template('register.php', [
    'email' => $email ?? null,
    'password' => $password ?? null,
    'login' => $login ?? null,
    'errors' => $errors ?? null,
]);

$layout = include_template('layout.php', [
    'page_title' => 'Регистрация',
    'content' => $content
]);

print($layout);
