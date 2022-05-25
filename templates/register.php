<div class="content">
    <section class="content__side">
        <p class="content__side-info">Если у вас уже есть аккаунт, авторизуйтесь на сайте</p>
        <a class="button button--transparent content__side-button" href="form-authorization.html">Войти</a>
    </section>

    <main class="content__main">
        <h2 class="content__main-heading">Регистрация аккаунта</h2>
       <form class="form" action="register.php" method="post" autocomplete="off">
            <div class="form__row">
                <label class="form__label" for="email">E-mail <sup>*</sup></label>
                <input
                    class="form__input <?= isset($errors['email']) ? 'form__input--error' : ''; ?>"
                    type="text" name="email"
                    id="email"
                    value="<?= isset($email) ? esc($email) : ''; ?>"
                    placeholder="Введите e-mail"
                >
                <?php if (isset($errors['email'])) : ?>
                <p class="form__message"><?= $errors['email']; ?></p>
                <? endif; ?>
            </div>

            <div class="form__row">
                <label class="form__label" for="password">Пароль <sup>*</sup></label>
                <input
                    class="form__input <?= isset($errors['password']) ? 'form__input--error' : ''; ?>"
                    type="password"
                    name="password"
                    id="password"
                    value="<?= isset($password) ? esc($login) : ''; ?>"
                    placeholder="Введите пароль"
                >
                <?php if (isset($errors['password'])) : ?>
                <p class="form__message"><?= $errors['password']; ?></p>
                <? endif; ?>
            </div>

            <div class="form__row">
                <label class="form__label" for="name">Имя <sup>*</sup></label>
                <input
                    class="form__input <?= isset($errors['login']) ? 'form__input--error' : ''; ?>"
                    type="text"
                    name="login"
                    id="name"
                    value="<?= isset($login) ? esc($login) : ''; ?>"
                    placeholder="Введите имя"
                >
                <?php if (isset($errors['login'])) : ?>
                <p class="form__message"><?= $errors['login']; ?></p>
                <? endif; ?>
            </div>

            <div class="form__row form__row--controls">
                <?php if ($errors) : ?>
                <p class="error-message">Пожалуйста, исправьте ошибки в форме</p>
                <? endif; ?>
                <input class="button" type="submit" name="" value="Зарегистрироваться">
            </div>
        </form>
    </main>
</div>
