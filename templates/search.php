<?= $nav_content; ?>
</nav>
<div class="container">
  <section class="lots">
    <h2><?=$search_title; ?></h2>
    <?php if (!empty($lots)): ?>
    <ul class="lots__list">
      <?php foreach ($lots as $lot): ?>
      <li class="lots__item lot">
        <div class="lot__image">
          <img src="<?=htmlspecialchars($lot["img_path"]); ?>" width="350" height="260" alt="<?=htmlspecialchars($lot["title"]); ?>">
        </div>
        <div class="lot__info">
          <span class="lot__category"><?=htmlspecialchars($lot["category"]); ?></span>
          <h3 class="lot__title"><a class="text-link" href="lot.php?lot_id=<?=(int)$lot['id']; ?>"><?=htmlspecialchars($lot["title"]); ?></a></h3>
          <div class="lot__state">
            <div class="lot__rate">
              <span class="lot__amount">Стартовая цена</span>
              <span class="lot__cost"><?=format_price($lot["price"]); ?></span>
            </div>
            <div class="lot__timer timer <?=((count_time($lot['dt_end'])) < 3600) ? "timer--finishing" : ""; ?>">
              <?=gmdate("d:H:i", count_time($lot['dt_end'])); ?>
            </div>
          </div>
        </div>
      </li>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>
  </section>
  <?php if (!empty($lots) && count($pages) > 1): ?>
  <ul class="pagination-list">
    <li class="pagination-item pagination-item-prev">
      <?php if ((int)$cur_page > 1): ?>
      <a href="?search=<?=$search ?? ''; ?>&page=<?=$cur_page - 1 ?? '';?>">
        Назад
      </a>
      <?php endif; ?>
    </li>
    <?php foreach ($pages as $page): ?>
    <li class="pagination-item <?=((int)$page === (int)$cur_page) ? 'pagination-item-active' : ''; ?>">
      <a href="?search=<?=$search ?? ''; ?>&page=<?=$page ?? '';?>">
        <?=$page ?? ''; ?>
      </a>
    </li>
    <?php endforeach; ?>
    <li class="pagination-item pagination-item-next">
      <?php if ((int)$cur_page < count($pages)): ?>
      <a href="?search=<?=$search ?? ''; ?>&page=<?=$cur_page + 1 ?? '';?>">
        Вперед
      </a>
      <?php endif; ?>
    </li>
  </ul>
  <?php endif; ?>
</div>
