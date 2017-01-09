 <?php
	defined('ROOT_PATH') OR exit('No direct script access allowed');
?>
<?if (isset($slides) AND !empty($slides)): ?>
<section id="works" class="section section-works">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="section-title title-works" data-aos="fade-up">НАШІ РОБОТИ</h2>
            </div>
        </div>
    </div>
        <div class="container-fluid" data-aos="fade-up">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Swiper -->
                    <div class="works-slider swiper-container">
                        <div class="swiper-wrapper">
                            <? foreach ( $slides as $key => $value ): ?>
                            <div class="swiper-slide slide-full-width">
                                <?$dir = 'upload/swiper/' . $menu_id . '/' . $value['slide_id'] . '/' . $value['file_name'];?>
                                <img src="<?=$dir;?>" alt=""/>;
                            </div>
                            <? endforeach; ?>
                        </div>

                        <div class="btn-prev"><i class="icon icon-left"></i>Prev</div>
                        <div class="btn-next"><i class="icon icon-right"></i>Next</div>
                    </div>

                </div>
            </div>
        </div>

        <div class="container">
            <div class="row description-wrapper">
                <div class="col-lg-2 col-sm-2 hidden-xs" data-aos="fade-up">
                    <span class="before-pointer">До</span>
                </div>

                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                    <? foreach ( $slides as $key => $value ): ?>
                        <div class="work-description" data-aos="fade-up"><?=$value['description'];?></div>
                    <? endforeach; ?>
                </div>

                <div class="col-lg-2 col-sm-2 hidden-xs" data-aos="fade-up">
                    <span class="after-pointer">Після</span>
                </div>
            </div>
        </div>
</section>
<? endif; ?>