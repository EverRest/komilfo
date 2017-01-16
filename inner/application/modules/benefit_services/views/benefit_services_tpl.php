<?php defined('ROOT_PATH') OR exit('No direct script access allowed'); ?>

<?php $count = $_COOKIE['count']; ?>
<?php $rows = $_COOKIE['rows']; ?>
<?php $c = count($services); ?>
<?php if (isset($services) AND !empty($services)):?>

    <?php if($count == 1): ?>
    <section id="price" class="section section-price">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="section-title dark service-header" data-aos="fade-up">ПОСЛУГИ ТА ЦІНИ</h2>
                    <?php $count++;$_COOKIE['count'] = $count; ?>
    <?php endif; ?>

    <?php if ( $c == count($services) ): ?>
    <div class="service-type" data-aos="fade-up">
        <h3 class="service-title service-item-title"><?php echo $services[0]['header']?></h3>
            <ul class="service-type-list">
    <?php endif; ?>
            <?php foreach ($services as $service): ?>
                    <li class="service-item">
                        <span class="service-description"><?php echo $service['description'];?></span>
                        <span class="service-price"><?php echo $service['price']; ?> грн</span>
                        <button class="btn btn-default btn-sub open-popup">ЗАПИСатись НА ПРИЙОМ</button>
                    </li>
                    <?php $rows--;$_COOKIE['rows'] = $rows;$c--; ?>
            <?php endforeach; ?>

    <?php if ($c == 0): ?>
        </ul>
    </div>
    <?php endif; ?>

    <?php if ($rows == 0): ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

<?php endif; ?>