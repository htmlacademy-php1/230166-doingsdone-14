<?php

/**
 * Функция проверяет код состояния ответа, и возвращает один из
 * трёх шаблонов с текстом ошибки, для 404, для 500 и для остальных
 *
 * @param string - текст ошибки
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

    exit();
}

/**
 * Функция принимает id пользователя и возвращает список проектов
 * созданных этим пользователем и количество задач для каждого проекта
 *
 * @param  mysqli $con - Ресурс соединения
 * @param  int $user_id - id пользователя
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
 * Функция принимает id пользователя и id проекта. Id проекта по умолчанию равен null
 * Если id проекта равено null, то функция возвращает все задачи пользователя
 * Если не равен, возвращает список задач для одного проекта
 *
 * @param  mysqli $con - Ресурс соединения
 * @param  int $user_id - id пользователя
 * @param  int $project_id - id проекта
 * @return array
 */
function get_user_tasks($con, $user_id, $project_id = null)
{
    $user_id = mysqli_real_escape_string($con, $user_id);

    if ($project_id) {
        $project_id = mysqli_real_escape_string($con, $project_id);

        $sql = "SELECT * FROM task
                    WHERE
                        user_id = $user_id
                        AND project_id = $project_id
                    ORDER BY date_add";
    } else {
        $sql = "SELECT * FROM task
                    WHERE
                        user_id = $user_id
                    ORDER BY date_add";
    }

    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    show_error('get_user_tasks ' . mysqli_error($con));
}

/**
 * Функция принимает email пользователя и возвращает запись из БД с этим пользователем
 *
 * @param mysqli $con Ресурс соединения
 * @param string $email почта
 * @return array
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
 * Функция принимает строку и ищет записи в таблице по нестрогому
 * совпадению текста в названии задачи. Возвращает список задач
 *
 * @param mysqli $con Ресурс соединения
 * @param string $search строка поиска
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
 * Функция принимает дату и ищет записи в таблице пользователей,
 * таблица пользователей объединается с таблицей задач и выводит список пользователей,
 * у которых есть невыполненные задачи, и список задач, где дата выполнения задачи ровна принимаемой дате
 *
 * @param mysqli $con Ресурс соединения
 * @param string $date дата в формате Y-m-d
 * @return array
*/
function get_users_for_mailing($con, $date)
{
    $sql = "SELECT u.id, u.email, u.login FROM user u
            JOIN task t ON t.user_id = u.id
            WHERE
                DATE_FORMAT(t.deadline, '%Y-%m-%d') = '$date'
                AND t.is_complete = 0";
    $result = mysqli_query($con, $sql);

    if ($result) {
        $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $result = [];

        foreach ($users as $user) {
            $user['tasks'] = get_user_tasks($con, $user['id']);
            $result[] = $user;
        }

        return $result;
    }

    show_error('get_users_for_mailing ' . mysqli_error($con));
}

/**
 * Функция принимает id проекта и ищет запись с таким же id в таблице проектов,
 * если находит возвращает массив с id, или пустой массив, который приводит к булеву типу
 *
 * @param  mysqli $con Ресурс соединения
 * @param  int $project_id - id проекта
 * @return bool
 */
function check_project_id($con, $project_id)
{
    $project_id = mysqli_real_escape_string($con, $project_id);
    $sql = "SELECT id FROM project WHERE id = $project_id";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return (bool) mysqli_fetch_assoc($result);
    }

    show_error('check_project_id ' . mysqli_error($con));
}

/**
 * Функция принимает id пользователя и ищет запись с таким же id в таблице пользователей,
 * если находит возвращает массив с id, или пустой массив, который приводит к булеву типу
 *
 * @param  mysqli $con Ресурс соединения
 * @param  int $project_id - id проекта
 * @return bool
 */
function check_user_id($con, $user_id)
{
    $user_id = mysqli_real_escape_string($con, $user_id);
    $sql = "SELECT id FROM user WHERE id = $user_id";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return (bool) mysqli_fetch_assoc($result);
    }

    show_error('check_user_id ' . mysqli_error($con));
}

/**
 * Функция принимает email пользователя и ищет запись с таким же email в таблице пользователей,
 * если находит возвращает массив с email, или пустой массив, который приводит к булеву типу
 *
 * @param  mysqli $con Ресурс соединения
 * @param  string $email
 * @return bool
 */
function check_user_email($con, $email)
{
    $email = mysqli_real_escape_string($con, $email);
    $sql = "SELECT email FROM user WHERE email = '$email'";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return (bool) mysqli_fetch_assoc($result);
    }

    show_error('check_user_email ' . mysqli_error($con));
}

/**
 * Функция принимает название проекта и ищет запись в таблице проектов, если находит
 * возвращает массив с названием, или пустой массив, который приводит к булеву типу
 *
 * @param  mysqli $con Ресурс соединения
 * @param  string $project_name - название проекта
 * @return bool
 */
function check_project_name($con, $project_name)
{
    $project_name = mysqli_real_escape_string($con, $project_name);
    $sql = "SELECT name FROM project WHERE name = '$project_name'";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return (bool) mysqli_fetch_assoc($result);
    }

    show_error('check_project_name ' . mysqli_error($con));
}

/**
 * Функция принимает название id задачи и ищет запись в таблице задач, если запись есть
 * возвращает массив с id, или пустой массив, который приводит к булеву типу
 *
 * @param  mysqli $con Ресурс соединения
 * @param  int $task_id - id задачи
 * @return bool
 */
function check_task_id($con, $task_id)
{
    $task_id = mysqli_real_escape_string($con, $task_id);
    $sql = "SELECT id FROM task WHERE id = $task_id";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return (bool) mysqli_fetch_assoc($result);
    }

    show_error('check_task_id ' . mysqli_error($con));
}

/**
 * Функция принимает массив с полученными данными от пользвателя и добавляет новую задачу в БД
 * через подставленные выражения для защиты от XSS атак или возвращает ошибку
 *
 * @param  mysqli $con Ресурс соединения
 * @param  string $task_name - название задачи
 * @param  string $file_url - путь для файла
 * @param  string $deadline - срок выполнения
 * @param  int $project_id - id проекта
 * @param  int $user_id - id пользователя
 * @return void
 */
function add_task($con, $task_name, $file_url, $deadline, $project_id, $user_id)
{
    $sql = "INSERT INTO task (name, file_url, deadline, project_id, user_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = db_get_prepare_stmt($con, $sql, [$task_name, $file_url, $deadline, $project_id, $user_id]);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        show_error('add_task ' . mysqli_error($con));
    }
}

/**
 * Функция принимает массив с полученными данными от пользвателя и добавляет нового пользователя в БД
 * через подставленные выражения для защиты от XSS атак или возвращает ошибку
 *
 * @param  mysqli $con
 * @param  string $email
 * @param  string $password
 * @param  string $login
 * @return void
 */
function add_user($con, $email, $password, $login)
{
    $sql = "INSERT INTO user (email, password, login) VALUES (?, ?, ?)";
    $stmt = db_get_prepare_stmt($con, $sql, [$email, $password, $login]);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        show_error('add_user ' . mysqli_error($con));
    }
}

/**
 * Функция принимает массив с полученными данными от пользвателя и добавляет новый проект в БД
 * через подставленные выражения для защиты от XSS атак или возвращает ошибку
 *
 * @param  mysqli $con
 * @param  string $project_name
 * @param  int $user_id
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
 * @param  int $task_id - id задачи
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
 * @param  int $task_id - id задачи
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
