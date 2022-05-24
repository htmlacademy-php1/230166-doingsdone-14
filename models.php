<?php

/**
 * Показ ошибки
 *
 * @param string
 * @return string
*/
function show_error($error)
{
    if (http_response_code(404)) {
        $page_content = include_template('404.php', ['error' => $error]);
    } elseif (http_response_code(500)) {
        $page_content = include_template('500.php', ['error' => $error]);
    } else {
        $page_content = include_template('error.php', ['error' => $error]);
    }

    exit($page_content);
}

/**
 * получение списка из всех проектов для одного пользователя
 *
 * @param  mysqli $con
 * @param  int $user_id
 * @return array
 */
function get_projects($con, $user_id)
{
    $user_id = mysqli_real_escape_string($con, $user_id);
    $sql = "SELECT name, date,
            (SELECT COUNT(id) FROM task WHERE user_id = $user_id AND project_id = p.id) count_tasks
            FROM project p
            WHERE user_id = $user_id";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    show_error(mysqli_error($con));
}

/**
 * получение списка из всех задач для одного пользователя
 *
 * @param  mysqli $con
 * @param  int $user_id
 * @return array
 */
function get_tasks($con, $user_id)
{
    $user_id = mysqli_real_escape_string($con, $user_id);
    $sql = 'SELECT * FROM `task` WHERE `user_id` = ' . $user_id;
    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    show_error(mysqli_error($con));
}
