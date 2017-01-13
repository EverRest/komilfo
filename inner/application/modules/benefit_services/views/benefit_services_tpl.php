<?php defined('ROOT_PATH') OR exit('No direct script access allowed'); ?>

<section id="price" class="section section-price">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="section-title dark service-header" data-aos="fade-up">ПОСЛУГИ ТА ЦІНИ</h2>
                    <div class="service-type" data-aos="fade-up">
                        <h3 class="service-title service-item-title"><?php echo $services[0]['header']?></h3>
                        <ul class="service-type-list">
                            <?php foreach ($services as $service): ?>
                            <li class="service-item">
                                <span class="service-description"><?php echo $service['description'];?></span>
                                <span class="service-price"><?php echo $service['price']; ?> грн</span>
                                <button class="btn btn-default btn-sub open-popup">ЗАПИСатись НА ПРИЙОМ</button>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div
            </div>
        </div>
    </div>
</section>