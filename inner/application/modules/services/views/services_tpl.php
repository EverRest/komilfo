<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>
<!----------------------------section Services--------------------------------------------->
<?php
    $items = array();
    foreach ($menu[0] as $item){
        $item['items'] = $menu[$item['id']];
        array_push($items, $item);
    }
?>
<section id="price" class="section section-price">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="section-title dark" data-aos="fade-up">ПОСЛУГИ ТА ЦІНИ</h2>
                <?php foreach ( $items as $item ): ?>
                <div class="service-type" data-aos="fade-up">
                    <h3 class="service-title"><?=$item['name'];?></h3>
                    <ul class="service-type-list">
                        <?php foreach ($item['items'] as $i): ?>
                        <?php $parts = explode('-', $i['name']); ?>
                        <li class="service-item">
                            <span class="service-description"><?=$parts[0];?></span>
                            <span class="service-price"><?=$parts[1];?> грн</span>
                            <button class="btn btn-default open-popup">ЗАПИСатись НА ПРИЙОМ</button>
                        </li>
                        <? endforeach; ?>
                    </ul>
                </div>
                <? endforeach; ?>
            </div>
        </div>
    </div>
</section>