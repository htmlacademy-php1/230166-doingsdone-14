INSERT INTO `user` (`login`, `email`, `password`)
  VALUES
    ('Константин', 'email1@email.com', 'password1'),
    ('Эльвира', 'email2@email.com', 'password2');

INSERT INTO `project` (`name`, `user_id`)
  VALUES
    ('Входящие', 1),
    ('Учеба', 2),
    ('Работа', 1),
    ('Домашние дела', 2),
    ('Авто', 1);

INSERT INTO `task` (`name`, `is_ready`, `file_url`, `finish_date`, `user_id`, `project_id`)
  VALUES
    ('Собеседование в IT компании', 0, 'home.psd', '01.12.2022', 1, 3),
    ('Выполнить тестовое задание', 0, 'home.psd', '02.12.2022', 1, 3),
    ('Сделать задание первого раздела', 0, 'home.psd', '03.12.2022', 2, 2),
    ('Встреча с другом', 0, '', '03.12.2022', 2, 1),
    ('Купить корм для кота', 0, '', '03.12.2022', 2, 4),
    ('Заказать пиццу', 0, '', '03.12.2022', 2, 4);

-- получить список из всех проектов для одного пользователя
SELECT * FROM `project` WHERE `user_id` = 1;

-- получить список из всех задач для одного проекта
SELECT * FROM `task` WHERE `project_id` = 3;

-- пометить задачу как выполненную
UPDATE `task` SET `is_ready` = 1 WHERE `id` = 4;

-- обновить название задачи по её идентификатору
UPDATE `task` SET `name` = 'Встреча с подругой' WHERE `id` = 4;
