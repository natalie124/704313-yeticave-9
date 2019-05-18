<?= $nav_content; ?>
<section class="lot-item container">
    <h2><?=htmlspecialchars($lot['title']); ?></h2>
    <div class="lot-item__content">
    <div class="lot-item__left">
      <div class="lot-item__image">
        <img src="<?=$lot['img_path']; ?>" width="730" height="548" alt="<?=$lot['title']; ?>">
      </div>
      <p class="lot-item__category">Категория: <span><?=htmlspecialchars($lot['name']); ?></span></p>
      <p class="lot-item__description"><?=htmlspecialchars($lot['description']); ?></p>
    </div>
    <div class="lot-item__right">
      <?php if ($is_auth && (count_time($lot['dt_end']) > 1) && ($lot['user_id'] !== $cur_user_id) && !$cur_user_bet): ?>
      <div class="lot-item__state">
        <div class="lot-item__timer timer <?=((count_time($lot['dt_end'])) < 3600) ? "timer--finishing" : ""; ?>">
          <?=gmdate("d:H:i", count_time($lot['dt_end'])); ?>
        </div>
        <div class="lot-item__cost-state">
          <div class="lot-item__rate">
            <span class="lot-item__amount">Текущая цена</span>
            <span class="lot-item__cost"><?=format_price($cur_price); ?></span>
          </div>
          <div class="lot-item__min-cost">
            Мин. ставка <span><?=number_format($min_bet, 0, "", " ") . " р"; ?></span>
          </div>
        </div>
        <form class="lot-item__form" action="" method="post" autocomplete="off">
          <p class="lot-item__form-item form__item <?=count($errors) ? 'form__item--invalid' : ''; ?>">
            <label for="cost">Ваша ставка</label>
            <input id="cost" type="text" name="cost" placeholder="<?=number_format($min_bet, 0, "", " "); ?>">
            <span class="form__error"><?=count($errors) ? $errors['cost'] : ''; ?></span>
          </p>
          <button type="submit" class="button">Сделать ставку</button>
        </form>
      </div>
      <?php endif; ?>
      <div class="history">
        <h3>История ставок (<span><?=count($bets); ?></span>)</h3>
        <table class="history__list">
          <?php foreach ($bets as $bet): ?>
          <tr class="history__item">
            <td class="history__name"><?=htmlspecialchars($bet['name']); ?></td>
            <td class="history__price"><?=htmlspecialchars(number_format($bet['bet_price'], 0, "", " ") . " р"); ?></td>
            <td class="history__time"><?=count_format_date($bet['dt_add']); ?></td>
          </tr>
          <?php endforeach; ?>
        </table>
      </div>
    </div>
  </div>
</section>

