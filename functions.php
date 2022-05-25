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
 * Получение незавершенных задач
 *
 * @param  mixed $tasks
 * @return void
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
 * Получение задач на повестке дня
 *
 * @param  mixed $tasks
 * @param  mixed $filter
 * @return void
 */
function get_today_tasks($tasks)
{
    $result = [];
    $current_date = time();
    $today = strtotime("tomorrow 00:00:00");

    foreach($tasks as $task) {
        if ($task['deadline'] > $current_date && $task['deadline'] < $today) {
            $result[] = $task;
        }
    }

    return $result;
}

/**
 * Получение задач на завтра
 *
 * @param  mixed $tasks
 * @param  mixed $filter
 * @return array
 */
function get_tomorrow_tasks($tasks)
{
    $result = [];
    $today = strtotime("tomorrow 00:00:00");
    $tommorow = strtotime("tomorrow 29:59:59");

    foreach($tasks as $task) {
        if ($task['deadline'] > $today && $task['deadline'] <= $tommorow) {
            $result[] = $task;
        }
    }

    return $result;
}

/**
 * Получение просроченных задач
 *
 * @param  mixed $tasks
 * @param  mixed $filter
 * @return array
 */
function get_overday_tasks($tasks)
{
    $result = [];
    $current_date = time();

    foreach($tasks as $task) {
        if ($task['deadline'] < $current_date) {
            $result[] = $task;
        }
    }

    return $result;
}
