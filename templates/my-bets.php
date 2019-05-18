<?= $nav_content; ?>
<section class="rates container">
  <h2>Мои ставки</h2>
  <table class="rates__list">
    <?php foreach ($bets as $bet): ?>
    <tr class="rates__item <?=$item_win ? 'rates__item--win' : ''; ?> <?=$item_end ? 'rates__item--end' : ''; ?>">
      <td class="rates__info">
        <div class="rates__img">
          <img src="<?=htmlspecialchars($bet['img_path']); ?>" width="54" height="40" alt="<?=htmlspecialchars($bet['title']); ?>">
        </div>
        <div>
          <h3 class="rates__title"><a href="lot.php?lot_id=<?=$bet['lot_id']?>"><?=htmlspecialchars($bet['title']); ?></a></h3>
          <p><?=$item_win ? htmlspecialchars($bet['contact']) : ''; ?></p>
        </div>
      </td>
      <td class="rates__category">
        <?=$bet['category']; ?>
      </td>
      <td class="rates__timer">
        <div class="timer <?=$item_win ? 'timer--win' : ''; ?> <?=$item_end ? 'timer--end' : ''; ?> <?=(count_time($bet['dt_end']) < 3600) ? "timer--finishing" : ""; ?>"><?=$item_win ? 'Ставка выиграла' : ''; ?><?=$item_end ? 'Торги окончены' : ''; ?><?=(!$item_win && !$item_end) ? gmdate("d:H:i", count_time($bet['dt_end'])) : ''; ?></div>
      </td>
      <td class="rates__price">
         <?=htmlspecialchars(number_format($bet['bet_price'], 0, "", " ") . " р"); ?>
      </td>
      <td class="rates__time">
       <?=count_format_date($bet['dt_add']); ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
</section>
