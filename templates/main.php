<div class="content">
    <section class="content__side">
        <h2 class="content__side-heading">Проекты</h2>

        <?= include_template('main-nav.php', [
                'project_id' => $project_id,
                'projects' => $projects
            ])
        ?>

        <a class="button button--transparent button--plus content__side-button"
            href="add-project.php" target="project_add">Добавить проект</a>
    </section>

    <main class="content__main">
        <h2 class="content__main-heading">Список задач</h2>

        <form class="search-form" action="index.php" method="get" autocomplete="off">
            <input
                class="search-form__input"
                type="text"
                name="search"
                value=""
                placeholder="Поиск по задачам"
            >
            <input class="search-form__submit" type="submit" name="" value="Искать">
        </form>

        <div class="tasks-controls">
            <nav class="tasks-switch">
                <a
                    href="/"
                    class="tasks-switch__item tasks-switch__item--active"
                >Все задачи</a>
                <a
                    href="?filter=today&task_id=<?= $task_id ?>&check=<?= $task_check ?>&show_completed=<?= $show_completed_tasks ?>"
                    class="tasks-switch__item"
                >Повестка дня</a>
                <a
                    href="?filter=tomorrow&task_id=<?= $task_id ?>&check=<?= $task_check ?>&show_completed=<?= $show_completed_tasks ?>"
                    class="tasks-switch__item"
                >Завтра</a>
                <a
                    href="?filter=overdue&task_id=<?= $task_id ?>&check=<?= $task_check ?>&show_completed=<?= $show_completed_tasks ?>"
                    class="tasks-switch__item"
                >Просроченные</a>
            </nav>

            <label class="checkbox">
                <!--добавить сюда атрибут "checked", если переменная $show_complete_tasks равна единице-->
                <input
                    class="checkbox__input visually-hidden show_completed"
                    type="checkbox"
                    name="show_completed"
                    value=""
                    <?= $show_completed_tasks ? 'checked' : ''; ?>
                >
                <span class="checkbox__text">Показывать выполненные</span>
            </label>
        </div>

        <table class="tasks">
            <?php if ($search && !$tasks) : ?>
            <p>Ничего не найдено по вашему запросу</p>
            <? endif; ?>
            <?php foreach ($tasks as $task) : ?>

            <tr class="tasks__item task
                <?= $task['is_complete'] ? 'task--completed' : ''; ?>
                <?= $task['deadline'] && get_hours($task['deadline']) <= 24 ? 'task--important' : ''; ?>">
                <td class="task__select">
                    <label class="checkbox task__checkbox">
                        <input
                            class="checkbox__input visually-hidden task__checkbox"
                            type="checkbox"
                            name="is_complete"
                            value="<?= $task['id']; ?>"
                            <?= $task['is_complete'] ? 'checked' : ''; ?>
                        >
                        <span class="checkbox__text">
                            <?= esc($task['name']); ?>
                        </span>
                    </label>
                </td>

                <td class="task__file">
                    <?php if ($task['file_url']) : ?>
                    <a class="download-link" href="<?= $task['file_url'] ?>">
                        Скачать файл
                    </a>
                    <? endif; ?>
                </td>

                <td class="task__date">
                    <?= $task['deadline'] ? date('Y-m-d', strtotime(esc($task['deadline']))) : 'Без даты'; ?>
                </td>
            </tr>
            <? endforeach; ?>
        </table>
    </main>
</div>
