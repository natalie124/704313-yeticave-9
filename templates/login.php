<?= $nav_content; ?>
<form class="form container <?=$form_invalid ?>" action="login.php" method="post"> <!-- form--invalid -->
  <h2>Вход</h2>
  <div class="form__item <?=$errors['email'] ? $field_invalid : '' ?>"> <!-- form__item--invalid -->
    <label for="email">E-mail <sup>*</sup></label>
    <input id="email" type="text" name="email" placeholder="Введите e-mail">
    <span class="form__error"><?=$errors['email']; ?></span>
  </div>
  <div class="form__item form__item--last <?=$errors['password'] ? $field_invalid : '' ?>">
    <label for="password">Пароль <sup>*</sup></label>
    <input id="password" type="password" name="password" placeholder="Введите пароль">
    <span class="form__error"><?=$errors['password']; ?></span>
  </div>
  <button type="submit" class="button">Войти</button>
</form>
