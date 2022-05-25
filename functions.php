<?php

/**
 * Подсчёт количества задач у каждого из проектов
 *
 * @param  array $tasks
 * @param  string $project
 * @return int
 */
function count_tasks_in_project($tasks, $project)
{
    $count = 0;

    foreach ($tasks as $task) {
        if ($task['project'] == $project) {
            $count++;
        }
    }

    return $count;
}

/**
 * Защита от XSS атак
 *
 * @param  string $string
 * @return string
 */
function esc($string)
{
    return htmlspecialchars($string);
}

/**
 *  Полчение даты в часах
 *
 * @param  mixed $date
 * @return int
 */
function get_hours($date)
{
    if ($date) {
        return floor((strtotime($date) - time()) / 60 * 60);
    }

    return null;
}

/**
 * Прверяет количество символов в сообщении, максимальное и минимальное значение,
 * возвращает сообщение об ошибке
 *
 * @param int $id категория, которую ввел пользователь в форму
 * @param array $allowed_list Список существующих категорий
 * @return string Текст сообщения об ошибке
 */
function check_length($value, $min, $max)
{
    if ($value) {
        $len = strlen($value);
        if ($len >= $min or $len <= $max) {
            return true;
        }
    }

    return null;
}

/**
 * Проверка дата больше или равна текущей
 *
 * @param  mixed $date
 * @return void
 */
function check_correct_date($date)
{
    $current_date = time();
    $date = strtotime($date);

    if ($date >= $current_date) {
        return true;
    }

    return false;
}

/**
 * Валидация для формы добавления задачи
 *
 * @param  mysqli $con
 * @param  string $task_name
 * @param  int $project_id
 * @param  string $deadline
 * @return array;
 */
function get_task_errors($con, $task_name, $project_id, $deadline)
{
    $errors = [];

    if (!$task_name) {
        $errors['task_name'] = 'Поле надо заполнить';
    } elseif (!check_length($task_name, 1, 128)) {
        $errors['task_name'] = 'Количество символов должно быть не более 128';
    }

    if (!$project_id) {
        $errors['project_id'] = 'Поле надо заполнить';
    } elseif (!check_project_id($con, $project_id)) {
        $errors['project_id'] = 'Такой проект не существует';
    }

    if ($deadline && !is_date_valid($deadline)) {
        $errors['deadline'] = 'Неправильный формат даты';
    } elseif ($deadline && !check_correct_date($deadline)) {
        $errors['deadline'] = 'Дата должна быть больше или равна текущей';
    }

    return array_filter($errors);
}

/**
 * Получение ссылки на файл полученный от пользователя
 *
 * @param  string $field - name
 * @return string
 */
function get_file_url($field_name)
{
    if ($_FILES[$field_name]['name']) {
        $tmp_name = $_FILES[$field_name]['tmp_name'];
        $file_name = $_FILES[$field_name]['name'];
        $file_name = uniqid() . '_' . $file_name;
        $file_path = __DIR__ . '/uploads/';

        move_uploaded_file($_FILES[$field_name]['tmp_name'], $file_path . $file_name);

        return 'uploads/' . $file_name;
    }

    return null;
}

/**
 * Валидация формы регистрации
 *
 * @param  mysqli $con
 * @param  string $email
 * @param  string $password
 * @param  string $login
 * @return array
 */
function get_register_errors($con, $email, $password, $login)
{
    $errors = [];

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

    return array_filter($errors);
}

/**
 * Валидация формы для входа пользователя
 *
 * @param  mysqli $con
 * @param  string $email
 * @param  string $password
 * @return array
*/
function get_login_errors($con, $email, $password)
{
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

    return array_filter($errors);
}
