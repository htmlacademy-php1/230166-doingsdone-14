INSERT INTO user (login, email, password)
  VALUES
    ('Константин', 'email@email.com', '$2y$10$zfMssWTVc6cqOKYYZvh5Med7QJT0ADMebKf2pKxDKPLOwoAcpMsAS'),
    ('Эльвира', 'email2@email.com', 'pass2');

INSERT INTO project (name, user_id)
  VALUES
    ('Входящие', 1),
    ('Учеба', 2),
    ('Работа', 1),
    ('Домашние дела', 2),
    ('Авто', 1);

INSERT INTO task (name, file_url, deadline, user_id, project_id)
  VALUES
    ('Собеседование в IT компании', 'home.psd', '01.12.2022', 1, 3),
    ('Выполнить тестовое задание', 'home.psd', '02.12.2022', 1, 3),
    ('Сделать задание первого раздела', 'home.psd', '03.12.2022', 2, 2),
    ('Встреча с другом', '', '03.12.2022', 2, 1),
    ('Купить корм для кота', '', '03.12.2022', 2, 4),
    ('Заказать пиццу', '', '03.12.2022', 2, 4);

-- получить список из всех проектов для одного пользователя
SELECT * FROM project WHERE user_id = 1;

-- получить список из всех задач для одного проекта
SELECT * FROM task WHERE project_id = 3;

-- пометить задачу как выполненную
UPDATE task SET is_complete = 1 WHERE id = 4;

-- обновить название задачи по её идентификатору
UPDATE task SET name = 'Встреча с подругой' WHERE id = 4;
