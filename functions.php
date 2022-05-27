<?php

/**
 * Защита от XSS атак, заменяет специальные символы на безопасные
 *
 * @param  string $string - Конвертируемая строка
 * @return string - отконвертированная строка
 */
function esc($string)
{
    return htmlspecialchars($string);
}

/**
 *  Функция преобразует текстовое представление даты на английском языке
 *  в метку времени Unix и возвращает разницу от текущего времени в часах,
 *  округленные в меньшую сторону
 *
 * @param  string $date - дата на английском языке
 * @return int - Количество часов
 */
function get_hours($date)
{
    if ($date) {
        return floor((strtotime($date) - time()) / 60 * 60);
    }

    return null;
}

/**
 * проверка даты. Дата должна быть больше или роавна текущей в формате Y-m-d
 *
 * @param  string $date
 * @return bool
 */
function check_correct_date($date)
{
    return date('Y-m-d', strtotime($date)) >= date('Y-m-d');
}

/**
 * Проверяет количество символов в сообщении на максимальное и минимальное значение,
 * возвращает true или false
 *
 * @param  string $string - проверяемая строка
 * @param  int $min - минимальное допустимое количество символов
 * @param  int $max - максимальное допустимое количество символов
 * @return bool
 */
function check_length_of_string($string, $min, $max)
{
    if ($string) {
        $len = strlen($string);

        if ($len >= $min or $len <= $max) {
            return true;
        }
    }

    return false;
}

/**
 * Функция принимает список задач, проверяет статус выполнения каждой задачи,
 * если задача не завершена, вносит задачу в возвращаемый список
 *
 * @param  array $tasks - массив с задачами
 * @return array - отфильтрованный массив
 */
function get_user_no_completed_tasks($tasks)
{
    $no_completed_tasks = [];

    foreach($tasks as $task) {
        if (!$task['is_complete']) {
            $no_completed_tasks[] = $task;
        }
    }

    return $no_completed_tasks;
}

/**
 * Функция принимает список задач, сравнивает дату срока выполнения задачи
 * с сегодняшней, если даты равны, вносит задачу в возвращаемый список
 *
 * @param  array $tasks - массив с задачами
 * @return array - отфильтрованный массив
 */
function get_today_tasks($tasks)
{
    $result = [];
    $today = date('Y-m-d');

    foreach($tasks as $task) {
        $deadline = date('Y-m-d', strtotime($task['deadline']));

        if ($deadline === $today) {
            $result[] = $task;
        }
    }

    return $result;
}

/**
 * Функция принимает список задач, сравнивает дату срока выполнения задачи
 * с завтрашней, если даты равны, вносит задачу в возвращаемый список
 *
 * @param  array $tasks - массив с задачами
 * @return array - отфильтрованный массив
 */
function get_tomorrow_tasks($tasks)
{
    $result = [];
    $tomorrow = date('Y-m-d', strtotime('tomorrow'));

    foreach($tasks as $task) {
        $deadline = date('Y-m-d', strtotime($task['deadline']));

        if ($deadline === $tomorrow) {
            $result[] = $task;
        }
    }

    return $result;
}

/**
 * Функция принимает список задач, сравнивает дату срока выполнения задачи
 * с сегодняшней, если дата задачи меньше, вносит задачу в возвращаемый список
 * с просроченными задачами
 *
 * @param  array $tasks - массив с задачами
 * @return array - отфильтрованный массив
 */
function get_overday_tasks($tasks)
{
    $result = [];
    $today = date('Y-m-d');

    foreach($tasks as $task) {
        $deadline = date('Y-m-d', strtotime($task['deadline']));

        if ($deadline < $today) {
            $result[] = $task;
        }
    }

    return $result;
}
