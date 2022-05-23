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
