<?= $nav_content; ?>
<form class="form form--add-lot container <?=empty($errors) ? '' : 'form--invalid'; ?>" action="add.php" method="post" enctype="multipart/form-data">
  <h2>Добавление лота</h2>
  <div class="form__container-two">
    <div class="form__item <?=isset($errors['lot-name']) ? 'form__item--invalid' : ''; ?>">
      <label for="lot-name">Наименование <sup>*</sup></label>
      <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" value="<?=$_POST['lot-name'] ?? ''; ?>">
      <span class="form__error">Введите наименование лота</span>
    </div>
    <div class="form__item <?=isset($errors['category']) ? 'form__item--invalid' : ''; ?>">
      <label for="category">Категория <sup>*</sup></label>

      <select name="category" id="category">
          <option value="">Выберите категорию</option>
          <?php foreach ($categories as $cat): ?>
              <option value="<?=(int)$cat['id'] ?>"><?=htmlspecialchars($cat['name']); ?></option>
          <?php endforeach; ?>
      </select>
      <span class="form__error">Выберите категорию</span>
    </div>
  </div>
  <div class="form__item form__item--wide <?=isset($errors['lot-name']) ? 'form__item--invalid' : ''; ?>">
    <label for="message">Описание <sup>*</sup></label>
    <textarea id="message" name="message" placeholder="Напишите описание лота"><?=$_POST['message'] ?? ''; ?></textarea>
    <span class="form__error">Напишите описание лота</span>
  </div>
  <div class="form__item form__item--file <?=isset($errors['lot_img']) ? 'form__item--invalid' : ''; ?>">
    <label>Изображение <sup>*</sup></label>
    <div class="form__input-file">
      <input class="visually-hidden" type="file" id="lot-img" value="" name="lot_img">
      <label for="lot-img">
        Добавить
      </label>
      <span class="form__error">Загрузите изображение в формате: jpg, jpeg, png</span>
    </div>
  </div>
  <div class="form__container-three">
    <div class="form__item form__item--small
    <?=isset($errors['lot-rate']) ? 'form__item--invalid' : ''; ?>
    <?=isset($_POST['lot-rate']) && !is_numeric($_POST['lot-rate']) ? 'form__item--invalid' : ''; ?>
    <?=isset($_POST['lot-rate']) && $_POST['lot-rate'] <= 0 ? 'form__item--invalid' : ''; ?>
    ">
      <label for="lot-rate">Начальная цена <sup>*</sup></label>
      <input id="lot-rate" type="text" name="lot-rate" placeholder="0" value="<?=isset($_POST['lot-rate']) ? ceil($_POST['lot-rate']) : ''; ?>">
      <span class="form__error">Введите начальную цену</span>
    </div>
    <div class="form__item form__item--small
    <?=isset($errors['lot-step']) ? 'form__item--invalid' : ''; ?>
    <?=isset($_POST['lot-step']) && !is_numeric($_POST['lot-step']) ? 'form__item--invalid' : ''; ?>
    <?=isset($_POST['lot-step']) && $_POST['lot-step'] <= 0 ? 'form__item--invalid' : ''; ?>
    ">
      <label for="lot-step">Шаг ставки <sup>*</sup></label>
      <input id="lot-step" type="text" name="lot-step" placeholder="0" value="<?=isset($_POST['lot-step']) ? ceil($_POST['lot-step']) : ''; ?>">
      <span class="form__error">Введите шаг ставки</span>
    </div>
    <div class="form__item form__item--small
    <?=isset($errors['lot-date']) ? 'form__item--invalid' : ''; ?>
    <?=isset($_POST['lot-date']) && !is_date_valid($_POST['lot-date']) ? 'form__item--invalid' : ''; ?>
    <?=isset($_POST['lot-date']) && (strtotime($_POST['lot-date']) < (time() + 86400 )) ? 'form__item--invalid' : ''; ?>
    ">
      <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
      <input class="form__input-date" id="lot-date" type="text" name="lot-date" placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<?=$_POST['lot-date'] ?? ''; ?>">
      <span class="form__error">Введите дату завершения торгов</span>
    </div>
  </div>
  <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
  <button type="submit" class="button">Добавить лот</button>
</form>
