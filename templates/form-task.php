<div class="content">
    <section class="content__side">
        <h2 class="content__side-heading">Проекты</h2>
        <?= include_template('main-nav.php', [
                'project_id' => null,
                'projects' => $projects
            ]);
        ?>
        <a class="button button--transparent button--plus content__side-button" href="add-project.php">Добавить проект</a>
    </section>

    <main class="content__main">
        <h2 class="content__main-heading">Добавление задачи</h2>

        <form class="form" action="add-task.php" method="post" autocomplete="off" enctype="multipart/form-data">
            <div class="form__row">
                <label class="form__label" for="name">Название <sup>*</sup></label>
                <input
                    class="form__input <?= isset($errors['task_name']) ? 'form__input--error' : ''; ?>"
                    type="text"
                    name="task_name"
                    id="name"
                    value="<?= esc($task_name); ?>"
                    placeholder="Введите название"
                >
                <?php if (isset($errors['task_name'])) : ?>
                    <p class="form__message"><?= esc($errors['task_name']); ?></p>
                <? endif; ?>
            </div>

            <div class="form__row">
                <label class="form__label" for="project">Проект <sup>*</sup></label>
                <select
                    class="form__input form__input--select <?= isset($errors['project_id']) ? 'form__input--error' : ''; ?>"
                    name="project_id"
                    id="project"
                    value="<?= $project_id ? esc($project_id) : $projects[0]['id']; ?>"
                >
                    <?php foreach ($projects as $project) : ?>
                    <option value="<?= esc($project['id']); ?>">
                        <?= esc($project['name']); ?>
                    </option>
                    <? endforeach; ?>
                </select>
                <?php if (isset($errors['project_id'])) : ?>
                    <p class="form__message"><?= esc($errors['project_id']); ?></p>
                <? endif; ?>
            </div>

            <div class="form__row">
                <label class="form__label" for="date">Дата выполнения</label>
                <input
                    class="form__input form__input--date <?= isset($errors['deadline']) ? 'form__input--error' : ''; ?>"
                    type="text"
                    name="deadline"
                    id="date"
                    value="<?= $deadline ? esc($deadline) : null; ?>"
                    placeholder="Введите дату в формате ГГГГ-ММ-ДД"
                >
                <?php if (isset($errors['deadline'])) : ?>
                    <p class="form__message"><?= esc($errors['deadline']); ?></p>
                <? endif; ?>
            </div>

            <div class="form__row">
                <label class="form__label" for="file">Файл</label>

                <div class="form__input-file">
                    <input
                        class="visually-hidden"
                        type="file"
                        name="file"
                        id="file"
                        value=""
                    >
                    <label class="button button--transparent" for="file">
                        <span>Выберите файл</span>
                    </label>
                </div>
            </div>

            <div class="form__row form__row--controls">
                <input class="button" type="submit" name="" value="Добавить">
            </div>
        </form>
    </main>
</div>