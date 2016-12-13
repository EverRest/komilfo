<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>

<script id="crop_template" type="text/html">

	<div id="crop_overlay" class="confirm_overlay" style="display: block; opacity: 0.5;"></div>

	<div id="crop_modal" class="crop_modal">

		<div class="fm crop_area">

			<div class="fm ca_panel">

				<a id="crop_cancel" href="#" class="fmr ca_cencel"><b></b>Скасувати</a>

				<a id="crop_save" href="#" class="fmr ca_save"><b></b>Зберегти</a>

				<span class="controls"><label class="check_label active"><i><input type="checkbox" name="proportion" checked="checked" value="1"></i>Пропорційно</label></span>

			</div>

			<div class="crop_image">

				<div id="crop_preview" class="fm crop_review crop_prew_border">

					<div style="overflow: hidden">

						<img src="{{ source }}" alt="">

					</div>

				</div>

				<div id="crop_source" class="fm crop_source">

					<img src="{{ source }}" style="width: {{ s_width }}px; height: {{ s_height }}px">

				</div>

			</div>

		</div>

	</div>

</script>



<script id="watermark_template" type="text/html">

	<div id="watermark_overlay" class="confirm_overlay" style="display: block; opacity: 0.5;"></div>

	<div id="watermark_modal" class="watermark_modal" style="width: {{ width }}px; margin: 0 0 0 -{{ margin }}px">

		<div class="fm watermark_area">

			<div class="fm ca_panel">

				<a id="cancel_watermark" href="#" class="fmr ca_cencel"><b></b>Скасувати</a>

				<a id="save_watermark" href="#" class="fmr ca_save"><b></b>Зберегти</a>

			</div>

			<div id="watermark_tiles" class="watermark_tiles">

				<img width="{{ width }}" height="{{ height }}" src="{{ src }}" alt="">

				<a href="#" data-value="7"></a>

				<a href="#" data-value="8"></a>

				<a href="#" data-value="9"></a>

				<a href="#" data-value="4"></a>

				<a href="#" data-value="5"></a>

				<a href="#" data-value="6"></a>

				<a href="#" data-value="1"></a>

				<a href="#" data-value="2"></a>

				<a href="#" data-value="3"></a>

			</div>

		</div>

		<div style="width: 100%; height: 1px; clear: both"></div>

	</div>

</script>