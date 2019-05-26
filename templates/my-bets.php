<?= $nav_content; ?>
<section class="rates container">
  <h2>Мои ставки</h2>
  <table class="rates__list">
    <?php foreach ($bets as $bet): ?>
    <tr class="rates__item
               <?=(count_time($bet['dt_end']) < 1 && $bet['win_id'] !== $cur_user_id) ? 'rates__item--end' : ''; ?>
               <?=(count_time($bet['dt_end']) < 1 && $bet['win_id'] === $cur_user_id) ? 'rates__item--win' : ''; ?>">
      <td class="rates__info">
        <div class="rates__img">
          <img src="<?=htmlspecialchars($bet['img_path']); ?>" width="54" height="40" alt="<?=htmlspecialchars($bet['title']); ?>">
        </div>
        <div>
          <h3 class="rates__title"><a href="lot.php?lot_id=<?=(int)$bet['lot_id']?>"><?=htmlspecialchars($bet['title']); ?></a></h3>
          <p><?=(count_time($bet['dt_end']) < 1 && $bet['win_id'] === $cur_user_id) ? htmlspecialchars($bet['contact']) : ''; ?></p>
        </div>
      </td>
      <td class="rates__category">
        <?=$bet['category']; ?>
      </td>
      <td class="rates__timer">
        <div class="timer
            <?=(count_time($bet['dt_end']) < 1 && $bet['win_id'] !== $cur_user_id) ? 'timer--end' : ''; ?>
            <?=(count_time($bet['dt_end']) < 1 && $bet['win_id'] === $cur_user_id) ? 'timer--win' : ''; ?>
            <?=(count_time($bet['dt_end']) >= 1 && count_time($bet['dt_end']) < 3600) ? 'timer--finishing' : ''; ?>">
            <?=(count_time($bet['dt_end']) < 1 && $bet['win_id'] === $cur_user_id) ? 'Ставка выиграла' : ''; ?>
            <?=(count_time($bet['dt_end']) < 1 && $bet['win_id'] !== $cur_user_id) ? 'Торги окончены' : ''; ?>
            <?=(count_time($bet['dt_end']) >= 1) ? gmdate("H:i", count_time($bet['dt_end'])) : ''; ?></div>
      </td>
      <td class="rates__price">
         <?=isset($bet) ? htmlspecialchars(number_format($bet['bet_price'], 0, "", " ") . " р") : ''; ?>
      </td>
      <td class="rates__time">
       <?=count_format_date($bet['dt_add']); ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
</section>
