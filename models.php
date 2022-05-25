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
    $sql = "SELECT *, (SELECT COUNT(id) FROM task WHERE user_id = $user_id AND project_id = p.id) count_tasks
            FROM project p WHERE user_id = $user_id";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    show_error('get_projects' . mysqli_error($con));
}

/**
 * получение списка из всех задач для одного пользователя
 *
 * @param  mysqli $con
 * @param  int $user_id
 * @return array
 */
function get_user_tasks($con, $user_id, $project_id = null)
{
    $user_id = mysqli_real_escape_string($con, $user_id);

    if ($project_id) {
        $project_id = mysqli_real_escape_string($con, $project_id);
        $sql = "SELECT * FROM task
                    WHERE user_id = $user_id AND project_id = $project_id
                    ORDER BY date_add";
    } else {
        $sql = "SELECT * FROM task
                    WHERE user_id = $user_id
                    ORDER BY date_add";
    }

    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    show_error('get_user_tasks ' . mysqli_error($con));
}

/**
 * Проверка на существование проекта по id
 *
 * @param  mysqli $con
 * @param  int $project_id
 * @return boolean
 */
function check_project_id($con, $project_id)
{
    $project_id = mysqli_real_escape_string($con, $project_id);
    $sql = "SELECT id FROM project WHERE id = $project_id";
    $result = mysqli_query($con, $sql);

    if ($result) {
        $id = mysqli_fetch_assoc($result);

        if ($id) {
            return true;
        }

        else return false;
    }

    show_error('check_project_id ' . mysqli_error($con));
}

/**
 * Проверка на существование пользователя по id
 *
 * @param  mysqli $con
 * @param  int $project_id
 * @return boolean
 */
function check_user_id($con, $user_id)
{
    $user_id = mysqli_real_escape_string($con, $user_id);
    $sql = "SELECT id FROM user WHERE id = $user_id";
    $result = mysqli_query($con, $sql);

    if ($result) {
        $id = mysqli_fetch_assoc($result);

        if ($id) {
            return true;
        }

        else return false;
    }

    show_error('check_user_id ' . mysqli_error($con));
}

/**
 * Добавление задачи
 *
 * @param  mysqli $con
 * @param  array $values
 * @return void
 */
function add_task($con, $values)
{
    $sql = "INSERT INTO task (name, file_url, deadline, project_id, user_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = db_get_prepare_stmt($con, $sql, $values);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        show_error('add_task' . mysqli_error($con));
    }
}
