<?= $nav_content; ?>
<div class="container">
  <section class="lots">
    <h2><?=htmlspecialchars($title); ?></h2>
    <ul class="lots__list">
      <?php foreach ($lots as $lot): ?>
      <li class="lots__item lot">
        <div class="lot__image">
          <img src="<?=$lot['img_path']; ?>" width="350" height="260" alt="Сноуборд">
        </div>
        <div class="lot__info">
          <span class="lot__category"><?=htmlspecialchars($lot['category']); ?></span>
          <h3 class="lot__title"><a class="text-link" href="lot.php?lot_id=<?=(int)$lot['id']; ?>"><?=htmlspecialchars($lot['title']); ?></a></h3>
          <div class="lot__state">
            <div class="lot__rate">
              <span class="lot__amount">Стартовая цена</span>
              <span class="lot__cost"><?=format_price($lot["price"]); ?></span>
            </div>
            <div class="lot__timer timer LIMIT $limit OFFSET $offset">
              <?=gmdate("H:i", count_time($lot['dt_end'])); ?>
            </div>
          </div>
        </div>
      </li>
      <?php endforeach; ?>
    </ul>
  </section>
  <?php if (!empty($lots) && count($pages) > 1): ?>
  <ul class="pagination-list">
    <li class="pagination-item pagination-item-prev">
      <?php if ((int)$cur_page > 1): ?>
      <a href="?cat_id=<?=$cat_id ?? ''; ?>&page=<?=$cur_page - 1 ?? '';?>">
        Назад
      </a>
      <?php endif; ?>
    </li>
    <?php foreach ($pages as $page): ?>
    <li class="pagination-item <?=((int)$page === (int)$cur_page) ? 'pagination-item-active' : ''; ?>">
      <a href="?cat_id=<?=$cat_id ?? ''; ?>&page=<?=$page ?? '';?>">
        <?=$page ?? ''; ?>
      </a>
    </li>
    <?php endforeach; ?>
    <li class="pagination-item pagination-item-next">
      <?php if ((int)$cur_page < count($pages)): ?>
      <a href="?cat_id=<?=$cat_id ?? ''; ?>&page=<?=$cur_page + 1 ?? '';?>">
        Вперед
      </a>
      <?php endif; ?>
    </li>
  </ul>
  <?php endif; ?>
</div>
