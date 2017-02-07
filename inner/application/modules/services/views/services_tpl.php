<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php if (count($services) > 0): ?>
<!-------------- Послуги -------------->
    <section id="price" class="section section-price">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="section-title dark" data-aos="fade-up">ПОСЛУГИ ТА ЦІНИ</h2>
                    <?php foreach($services as $service): ?>
                    <?php $items = array(); $prices = array();
                        $items = explode('~',$service['text']);
                        $prices = explode('~',$service['price'])?>
                    <div class="service-type" data-aos="fade-up">
                        <h3 class="service-title"><?=$service['title']?></h3>
                        <ul class="service-type-list">
                            <?php for($i = 0; $i<count($items)-1;$i++): ?>
                            <li class="service-item">
                                <span class="service-description"><?=$items[$i];?></span>
                                <span class="service-price"><?=$prices[$i];?> грн</span>
                                <button class="btn btn-default open-popup">ЗАПИСатись НА ПРИЙОМ</button>
                            </li>
                            <?php endfor; ?>
                        </ul>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>
