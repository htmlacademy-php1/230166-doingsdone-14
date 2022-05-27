<?php

require_once 'init.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim(filter_input(INPUT_POST, 'email'));
    $password = trim(filter_input(INPUT_POST, 'password'));
    $errors = [];


    if (!$email) {
        $errors['email'] = 'Поле надо заполнить';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Неправильный формат почты';
    } elseif (!check_user_email($con, $email)) {
        $errors['email'] = 'Пользователь с такой почтой не зарегистрирован';
    }

    if (!$password) {
        $errors['password'] = 'Поле надо заполнить';
    }

    array_filter($errors);

    $user = get_сurrent_user($con, $email);
    var_dump($user);

    if (empty($errors) && $user) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
        } else {
            $errors['password'] = "Пароли не совпадают";
        }
    }

    if (isset($_SESSION['user'])) {
        header('Location: index.php');
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
