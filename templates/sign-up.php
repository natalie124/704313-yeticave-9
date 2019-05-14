<?= $nav_content; ?>
<form class="form container <?=$form_invalid ?>" action="sign-up.php" method="post" autocomplete="off"> <!-- form--invalid -->
  <h2>Регистрация нового аккаунта</h2>
  <div class="form__item <?=$errors['email'] ? $field_invalid : '' ?>"> <!-- form__item--invalid -->
    <label for="email">E-mail <sup>*</sup></label>
    <input id="email" type="text" name="email" placeholder="Введите e-mail">
    <span class="form__error"><?=$errors['email']; ?></span>
  </div>
  <div class="form__item <?=empty($_POST['password']) ? $field_invalid : '' ?>">
    <label for="password">Пароль <sup>*</sup></label>
    <input id="password" type="password" name="password" placeholder="Введите пароль">
    <span class="form__error">Введите пароль</span>
  </div>
  <div class="form__item <?=empty($_POST['name']) ? $field_invalid : '' ?>">
    <label for="name">Имя <sup>*</sup></label>
    <input id="name" type="text" name="name" placeholder="Введите имя">
    <span class="form__error">Введите имя</span>
  </div>
  <div class="form__item <?=empty($_POST['message']) ? $field_invalid : '' ?>">
    <label for="message">Контактные данные <sup>*</sup></label>
    <textarea id="message" name="message" placeholder="Напишите как с вами связаться"></textarea>
    <span class="form__error">Напишите как с вами связаться</span>
  </div>
  <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
  <button type="submit" class="button">Зарегистрироваться</button>
  <a class="text-link" href="#">Уже есть аккаунт</a>
</form>
