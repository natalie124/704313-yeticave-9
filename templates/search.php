<?= $nav_content; ?>
</nav>
<div class="container">
  <section class="lots">
    <h2>Результаты поиска по запросу «<span><?=$_GET['search']; ?></span>»</h2>
    <ul class="lots__list">
      <?php foreach ($lots as $lot): ?>
      <li class="lots__item lot">
        <div class="lot__image">
          <img src="<?=htmlspecialchars($lot["img_path"]); ?>" width="350" height="260" alt="<?=htmlspecialchars($lot["title"]); ?>">
        </div>
        <div class="lot__info">
          <span class="lot__category"><?=htmlspecialchars($lot["category"]); ?></span>
          <h3 class="lot__title"><a class="text-link" href="lot.html"><?=htmlspecialchars($lot["title"]); ?></a></h3>
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
  </section>
  <ul class="pagination-list">
    <li class="pagination-item pagination-item-prev"><a>Назад</a></li>
    <li class="pagination-item pagination-item-active"><a>1</a></li>
    <li class="pagination-item"><a href="#">2</a></li>
    <li class="pagination-item"><a href="#">3</a></li>
    <li class="pagination-item"><a href="#">4</a></li>
    <li class="pagination-item pagination-item-next"><a href="#">Вперед</a></li>
  </ul>
</div>
