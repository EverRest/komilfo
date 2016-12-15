<?php defined('ROOT_PATH') OR exit('No direct script access allowed'); ?>
<? if (isset($benefits) AND !empty($benefits)): ?>
<!--   --><?// echo '<pre>';print_r($benefits);exit; ?>
<section id="about" class="section section-about">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h2 class="section-title" data-aos="fade-up"><?=$benefits['title'];?></h2>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6" data-aos="fade-right">
                <blockquote class="quote">
                    <p><?=$benefits['quote'];?></p>
                    <div class="quote-title"><?=$benefits['author'];?></div>

                </blockquote>
            </div>
            <div class="col-md-6" data-aos="fade-left">
                <article class="article-about"><?=$benefits['text'];?></article>
            </div>
        </div>
    </div>
</section>
<? endif; ?>