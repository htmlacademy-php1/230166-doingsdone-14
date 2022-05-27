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
        <h2 class="content__main-heading">Добавление проекта</h2>

        <form class="form" action="add-project.php" method="post" autocomplete="off">
            <div class="form__row">
                <label class="form__label" for="project_name">Название <sup>*</sup></label>
                <input
                    class="form__input <?= isset($errors['project_name']) ? 'form__input--error' : ''; ?>"
                    type="text"
                    name="project_name"
                    id="project_name"
                    value="<?= esc($project_name); ?>"
                    placeholder="Введите название проекта"
                >
                <?php if (isset($errors['project_name'])) : ?>
                    <p class="form__message"><?= esc($errors['project_name']); ?></p>
                <?php endif; ?>
            </div>

            <div class="form__row form__row--controls">
                <input class="button" type="submit" name="" value="Добавить">
            </div>
        </form>
    </main>
</div>
