<?php

require_once 'init.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim(filter_input(INPUT_POST, 'email'));
    $password = trim(filter_input(INPUT_POST, 'password'));
    $login = trim(filter_input(INPUT_POST, 'login'));

    if (!$email) {
        $errors['email'] = 'Поле надо заполнить';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Неправильный формат почты';
    } elseif (check_user_email($con, $email)) {
        $errors['email'] = 'Пользователь с такой почтой уже зарегистрирован';
    }

    if (!$password) {
        $errors['password'] = 'Поле надо заполнить';
    } elseif (!check_length($password, 1, 20)) {
        $errors['password'] = 'Пароль должен быть не более 20 символов';
    }

    if (!$login) {
        $errors['login'] = 'Поле надо заполнить';
    } elseif (!check_length($login, 1, 255)) {
        $errors['login'] = 'Количество символов должно быть не более 255';
    }

    array_filter($errors);

    if (empty($errors)) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        add_user($con, [$email, $password, $login]);
        header('Location: auth.php');
        exit();
    }
}

$content = include_template('register.php', [
    'email' => $email ?? null,
    'password' => $password ?? null,
    'login' => $login ?? null,
    'errors' => $errors,
]);

$layout = include_template('main-layout.php', [
    'page_title' => 'Регистрация',
    'content' => $content
]);

print($layout);
