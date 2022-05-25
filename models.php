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

    show_error('get_projects ' . mysqli_error($con));
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
 * получение списка из всех задач для одного пользователя
 *
 * @param  mysqli $con
 * @param  int $user_id
 * @return array
 */
function get_complete_tasks($con, $user_id, $project_id = null)
{
    $user_id = mysqli_real_escape_string($con, $user_id);

    if ($project_id) {
        $project_id = mysqli_real_escape_string($con, $project_id);
        $sql = "SELECT * FROM task
                    WHERE
                        user_id = $user_id
                        AND project_id = $project_id
                        AND is_complete = 1
                    ORDER BY date_add";
    } else {
        $sql = "SELECT * FROM task
                    WHERE user_id = $user_id
                        AND is_complete = 1
                    ORDER BY date_add";
    }

    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    show_error('get_user_tasks ' . mysqli_error($con));
}

/**
 * Получение пользователя по email
 *
 * @param mysqli $con Ресурс соединения
 * @param string $email почта пользователя
 * @return int
*/
function get_сurrent_user($con, $email)
{
    $email = mysqli_real_escape_string($con, $email);
    $sql = "SELECT * FROM user WHERE email = '$email'";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_assoc($result);
    }

    show_error('get_сurrent_user ' . mysqli_error($con));
}

/**
 * Получение задач по поиску
 *
 * @param mysqli $con Ресурс соединения
 * @param string $search хэштег или строка поиска
 * @return array
*/
function get_search_results($con, $search)
{
    $sql = "SELECT * FROM task
            WHERE MATCH(name) AGAINST(?)
            ORDER BY date_add";
    $stmt = db_get_prepare_stmt($con, $sql, [$search]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    show_error('get_search_results ' . mysqli_error($con));
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
 * Проверка email
 *
 * @param  mysqli $con
 * @param  string $email
 * @return bool
 */
function check_user_email($con, $email)
{
    $email = mysqli_real_escape_string($con, $email);
    $sql = "SELECT email FROM user WHERE email = '$email'";
    $result = mysqli_query($con, $sql);

    if ($result) {
        $email = mysqli_fetch_assoc($result);

        if ($email) {
            return true;
        }
        return false;
    }

    show_error('check_user_email ' . mysqli_error($con));
}

/**
 * Проверка на существование проекта
 *
 * @param  mysqli $con
 * @param  string $project_name
 * @return bool
 */
function check_project_name($con, $project_name)
{
    $project_name = mysqli_real_escape_string($con, $project_name);
    $sql = "SELECT name FROM project WHERE name = '$project_name'";
    $result = mysqli_query($con, $sql);

    if ($result) {
        $project_name = mysqli_fetch_assoc($result);

        if ($project_name) {
            return true;
        }
        return false;
    }

    show_error('check_project_name ' . mysqli_error($con));
}

/**
 * Проверка на существование задачи по id
 *
 * @param  mysqli $con
 * @param  int $task_id
 * @return bool
 */
function check_task_id($con, $task_id)
{
    $task_id = mysqli_real_escape_string($con, $task_id);
    $sql = "SELECT id FROM task WHERE id = $task_id";
    $result = mysqli_query($con, $sql);

    if ($result) {
        $task_id = mysqli_fetch_assoc($result);

        if ($task_id) {
            return true;
        }
        return false;
    }

    show_error('check_task_id ' . mysqli_error($con));
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
        show_error('add_task ' . mysqli_error($con));
    }
}

/**
 * Добавление нового пользователя
 *
 * @param  mysqli $con
 * @param  array $values
 * @return void
 */
function add_user($con, $values)
{
    $sql = "INSERT INTO user (email, password, login) VALUES (?, ?, ?)";
    $stmt = db_get_prepare_stmt($con, $sql, $values);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        show_error('add_user ' . mysqli_error($con));
    }
}

/**
 * Добавление проекта
 *
 * @param  mysqli $con
 * @param  array $project_name
 * @param  array $user_id
 * @return void
 */
function add_project($con, $project_name, $user_id)
{
    $sql = "INSERT INTO project (name, user_id) VALUES (?, ?)";
    $stmt = db_get_prepare_stmt($con, $sql, [$project_name, $user_id]);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        show_error('add_project ' . mysqli_error($con));
    }
}

/**
 * Отмечает что задача выполнена
 *
 * @param  mysqli $con
 * @param  array $project_name
 * @param  array $user_id
 * @return void
 */
function complete_task($con, $task_id)
{
    $task_id = mysqli_real_escape_string($con, $task_id);
    $sql = "UPDATE task SET is_complete = 1 WHERE id = $task_id";
    $result = mysqli_query($con, $sql);

    if (!$result) {
        show_error('complete_task ' . mysqli_error($con));
    }
}

/**
 * Отмечает что задача не выполнена
 *
 * @param  mysqli $con
 * @param  array $project_name
 * @param  array $user_id
 * @return void
 */
function remove_complete_task($con, $task_id)
{
    $task_id = mysqli_real_escape_string($con, $task_id);
    $sql = "UPDATE task SET is_complete = 0 WHERE id = $task_id";
    $result = mysqli_query($con, $sql);

    if (!$result) {
        show_error('complete_task ' . mysqli_error($con));
    }
}
