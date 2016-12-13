<?php defined('ROOT_PATH') or exit('No direct script access allowed');

	/**
	 * Class Image_lib
	 *
	 * @property Imagick $image
	 */
	class Image_lib
	{
		protected $imagick_load = false;
		protected $image;

		public function __construct()
		{
			$this->imagick_load = extension_loaded('imagick');

			if ($this->imagick_load) {
				$this->image = new Imagick();
			} else {
				require_once ROOT_PATH . 'inner/application/libraries/Image_moo.php';
				$this->image = new Image_moo();
			}
		}

		/**
		 * Обрізка та зміна розмірів зображення
		 *
		 * @param string $source_image
		 * @param string $new_image
		 * @param int $width
		 * @param int $height
		 * @param bool|true $padding
		 * @return bool
		 */
		public function resize_crop($source_image, $new_image, $width, $height, $padding = true)
		{
			if ($this->imagick_load) {
				$this->image->readImage($source_image);

				$sizes = $this->image->getImageGeometry();

				if ($sizes['width'] > $width or $sizes['height'] > $height) {
					$ext = pathinfo($new_image, PATHINFO_EXTENSION);

					if ($ext === 'jpg') {
						$this->image->stripImage();
						$this->image->setImageCompression(imagick::COMPRESSION_JPEG);
						$this->image->setImageCompressionQuality(0);
					}

					if ($ext === 'png') {
						$this->image->setBackgroundColor(new ImagickPixel('transparent'));
					}

					if ($padding) {
						$this->image->scaleImage($width, $height, true);
					} else {
						$this->image->cropThumbnailImage($width, $height);
					}

					return $this->save($new_image);
				} else {
					if ($source_image === $new_image) {
						return true;
					}

					if (file_exists($new_image)) {
						unlink($new_image);
					}

					return copy($source_image, $new_image);
				}
			} else {
				$this->image
					->set_jpeg_quality(100)
					->load($source_image)
					->resize_crop($width, $height)
					->save($new_image, true);

				return true;
			}
		}

		/**
		 * Зміна розмірів зображення
		 *
		 * @param string $source_image
		 * @param string $new_image
		 * @param int $width
		 * @param int $height
		 * @param bool|false $strip
		 * @return bool
		 */
		public function resize($source_image, $new_image, $width, $height, $strip = false)
		{
			if ($this->imagick_load) {
				$this->image->readImage($source_image);

				$sizes = $this->image->getImageGeometry();

				if ($sizes['width'] > $width or $sizes['height'] > $height) {
					$ext = pathinfo($new_image, PATHINFO_EXTENSION);

					if ($ext === 'jpg') {
						$this->image->stripImage();
						$this->image->setImageCompression(imagick::COMPRESSION_JPEG);
						$this->image->setImageCompressionQuality(0);
					}

					if ($ext === 'png') {
						$this->image->setBackgroundColor(new ImagickPixel('transparent'));
					}

					$this->image->thumbnailImage($width, $height, true);

					return $this->save($new_image);
				} else {
					if ($source_image === $new_image) {
						return true;
					}

					if (file_exists($new_image)) {
						unlink($new_image);
					}

					return copy($source_image, $new_image);
				}
			} else {
				$this->image
					->set_jpeg_quality(100)
					->load($source_image)
					->resize($width, $height)
					->save($new_image, true);

				return true;
			}
		}

		/**
		 * Обрізка зображення
		 *
		 * @param string $source_image
		 * @param string $new_image
		 * @param int $width
		 * @param int $height
		 * @param int $x
		 * @param int $y
		 * @param null|int $need_width
		 * @param null|int $need_height
		 * @return bool
		 */
		public function crop($source_image, $new_image, $width, $height, $x, $y, $need_width = null, $need_height = null)
		{
			if ($this->imagick_load) {
				$this->image->readImage($source_image);

				$sizes = $this->image->getImageGeometry();

				if ($sizes['width'] > $width OR $sizes['height'] > $height) {
					$ext = pathinfo($source_image, PATHINFO_EXTENSION);

					if ($ext === 'jpg') {
						$this->image->stripImage();
						$this->image->setImageCompression(imagick::COMPRESSION_JPEG);
						$this->image->setImageCompressionQuality(0);
					}

					if ($ext === 'png') {
						$this->image->setBackgroundColor(new ImagickPixel('transparent'));
					}

					$this->image->cropImage($width, $height, $x, $y);

					if (($need_width !== null and $need_height !== null) and ($width > $need_width or $height > $need_height)) {
						$this->image->thumbnailImage($need_width, $need_height, true);
					}

					return $this->save($new_image);
				} else {
					if ($source_image === $new_image) {
						return true;
					}

					if (file_exists($new_image)) {
						unlink($new_image);
					}

					return copy($source_image, $new_image);
				}
			}
			else
			{
				$this->image
					->set_jpeg_quality(100)
					->load($source_image)
					->crop($x, $y, $x + $width, $y + $height)
					->save($new_image, true);

				if (($need_width !== null and $need_height !== null) and ($width > $need_width or $height > $need_height)) {
					$this->image
						->set_jpeg_quality(100)
						->load($new_image)
						->resize_crop($need_width, $need_height)
						->save($new_image, true);
				}

				return true;
			}
		}

		/**
		 * Накладання водяного знаку
		 *
		 * @param string $source_image
		 * @param string $new_image
		 * @param string $watermark_image
		 * @param int $position
		 * @param int $padding
		 * @param float $opacity
		 * @return bool
		 */
		public function watermark($source_image, $new_image, $watermark_image, $position = 5, $padding = 20, $opacity = 0.5)
		{
			if ($this->imagick_load) {
				$this->image->readImage($source_image);
				$source_sizes = $this->image->getImageGeometry();

				$watermark = new Imagick($watermark_image);
				$watermark->evaluateImage(Imagick::EVALUATE_DIVIDE, $opacity, Imagick::CHANNEL_ALPHA);

				$watermark_sizes = $watermark->getImageGeometry();

				$ext = pathinfo($new_image, PATHINFO_EXTENSION);

				if ($ext === 'jpg') {
					$this->image->stripImage();
					$this->image->setImageCompression(imagick::COMPRESSION_JPEG);
					$this->image->setImageCompressionQuality(0);
				}

				if ($ext === 'png') {
					$this->image->setBackgroundColor(new ImagickPixel('transparent'));
				}

				switch ($position) {
					case 7:
					case 4:
					case 1:
						$x = $padding;
						break;

					case 8:
					case 5:
					case 2:
						$x = ($source_sizes['width'] - $watermark_sizes['width']) / 2;
						break;

					case 9:
					case 6:
					case 3:
						$x = $source_sizes['width'] - $padding - $watermark_sizes['width'];
						break;

					default:
						$x = $padding;
				}

				switch ($position) {
					case 7:
					case 8:
					case 9:
						$y = $padding;
						break;

					case 4:
					case 5:
					case 6:
						$y = ($source_sizes['height'] - $watermark_sizes['height']) / 2;
						break;

					case 1:
					case 2:
					case 3:
						$y = $source_sizes['height'] - $padding - $watermark_sizes['height'];
						break;

					default:
						$y = $padding;
				}

				$this->image->compositeImage($watermark, imagick::COMPOSITE_OVER, $x, $y);

				return $this->save($new_image);
			}
			else
			{
				$this->image
					->set_jpeg_quality(100)
					->load($source_image)
					->load_watermark($watermark_image)
					->watermark($position, $padding)
					->save($new_image, true);

				return true;
			}
		}

		/**
		 * Збереження зображення
		 *
		 * @param string $file_name
		 * @param bool|true $overwrite
		 * @return bool
		 */
		public function save($file_name, $overwrite = true)
		{
			if ($this->imagick_load) {
				if (file_exists($file_name)) {
					if ($overwrite and is_writable($file_name)) {
						unlink($file_name);
					} else {
						return false;
					}
				}

				$this->image->writeImage($file_name);
				$this->image->clear();
			}

			return true;
		}
	}