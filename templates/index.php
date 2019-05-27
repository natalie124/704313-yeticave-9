<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное
        снаряжение.</p>
    <ul class="promo__list">
        <?php foreach ($categories as $category): ?>
            <li class="promo__item promo__item--<?= $category['symbol_code'] ?>">
                <a class="promo__link"
                   href="all-lots.php?cat_id=<?= htmlspecialchars($category['id']); ?>"><?= htmlspecialchars($category['name']); ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
        <?php foreach ($lots as $lot): ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?= htmlspecialchars($lot['img_path']); ?>" width="350" height="260" alt="">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?= htmlspecialchars($lot["category"]); ?></span>
                    <h3 class="lot__title"><a class="text-link"
                                              href="lot.php?lot_id=<?= (int)$lot['id']; ?>"><?= htmlspecialchars($lot["name"]); ?></a>
                    </h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost"><?= format_price($lot['start_price']); ?></span>
                        </div>
                        <div
                            class="lot__timer timer <?= ((count_time($lot['dt_end'])) < 3600) ? 'timer--finishing' : ''; ?>">
                            <?= gmdate('d:H:i', count_time($lot['dt_end'])); ?>
                        </div>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
