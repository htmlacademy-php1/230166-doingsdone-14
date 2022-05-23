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
 *  Проверка даты, до которой осталось меньше 24 часов
 *
 * @param  mixed $date
 * @return void
 */
function check_less_than_day($date)
{
    $current_date = time();
    $date = strtotime($date);
    $dif = $date - $current_date;

    if ($dif <= 86400) {
        return true;
    }

    return false;
}
