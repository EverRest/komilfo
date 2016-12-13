<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	if (!extension_loaded('ímagick'))
	{
		require_once ROOT_PATH . 'inner/application/libraries/Image_moo.php';
	}

	class Image_lib
	{
		protected $imagick_load = FALSE;
		protected $image = NULL;

		public function __construct()
		{
			$this->imagick_load = extension_loaded('imagick');

			if ($this->imagick_load)
			{
				$this->image = new Imagick();
			}
			else
			{
				$this->image = new Image_moo();
			}
		}

		public function resize_crop($source_image, $new_image, $width, $height, $padding = FALSE)
		{
                    
			if ($this->imagick_load)
			{
                          
				$this->image->readimage($source_image);

				$sizes = $this->image->getimagegeometry();

				$ext = pathinfo($new_image, PATHINFO_EXTENSION);

					if ($ext == 'jpg')
					{
						$this->image->setimagecompression(imagick::COMPRESSION_JPEG);
						$this->image->setimagecompressionQuality(0);
					}

					if ($padding)
					{
						$this->image->scaleImage($width, $height, TRUE);
					}
					else
					{
						$this->image->cropthumbnailimage($width, $height);
					}
					return $this->save($new_image);
                                
                                /*
                                        if ($sizes['width'] > $width OR $sizes['height'] > $height) 
				{
					
				}
				else
				{
					//if ($source_image == $new_image) return TRUE;

					if (file_exists($new_image)) unlink($new_image);
					return copy($source_image, $new_image);
				}
                                 
                                 */
			}
			else
			{
				$this->image->set_jpeg_quality(100)->load($source_image)->resize_crop($width, $height)->save($new_image, TRUE);
				return TRUE;
			}
		}

		public function resize($source_image, $new_image, $width, $height)
		{
			if ($this->imagick_load)
			{
				$this->image->readimage($source_image);

				$sizes = $this->image->getimagegeometry();

				if ($sizes['width'] > $width OR $sizes['height'] > $height)
				{
					$ext = pathinfo($new_image, PATHINFO_EXTENSION);

					if ($ext == 'jpg')
					{
						$this->image->setimagecompression(imagick::COMPRESSION_JPEG);
						$this->image->setimagecompressionQuality(0);
					}

					$this->image->thumbnailimage($width, $height, TRUE);
					return $this->save($new_image);
				}
				else
				{
					if ($source_image == $new_image) return TRUE;

					if (file_exists($new_image)) unlink($new_image);
					return copy($source_image, $new_image);
				}
			}
			else
			{
				$this->image->set_jpeg_quality(100)->load($source_image)->resize($width, $height)->save($new_image, TRUE);
				return TRUE;
			}
		}

		public function crop($source_image, $new_image, $width, $height, $x, $y, $need_width = NULL, $need_height = NULL)
		{
			if ($this->imagick_load)
			{
				$this->image->readimage($source_image);

				$sizes = $this->image->getimagegeometry();

				if ($sizes['width'] > $width OR $sizes['height'] > $height)
				{
					$ext = pathinfo($source_image, PATHINFO_EXTENSION);

					if ($ext == 'jpg')
					{
						$this->image->setimagecompression(imagick::COMPRESSION_JPEG);
						$this->image->setimagecompressionQuality(0);
					}

					$this->image->cropimage($width, $height, $x, $y);
					if (($need_width !== NULL AND $need_height !== NULL) AND ($width > $need_width OR $height > $need_height)) $this->image->thumbnailimage($need_width, $need_height, TRUE);
					return $this->save($new_image);
				}
				else
				{
					if ($source_image == $new_image) return TRUE;

					if (file_exists($new_image)) unlink($new_image);
					return copy($source_image, $new_image);
				}
			}
			else
			{
				$this->image->set_jpeg_quality(100)->load($source_image)->crop($x, $y, $x + $width, $y + $height)->save($new_image, TRUE);
				if (($need_width !== NULL AND $need_height !== NULL) AND ($width > $need_width OR $height > $need_height))
					$this->image->set_jpeg_quality(100)->load($new_image)->resize_crop($need_width, $need_height)->save($new_image, TRUE);
				return TRUE;
			}
		}

		public function watermark($source_image, $new_image, $watermark_image, $position = 5, $padding = 20, $opacity = 0.5)
		{
			if ($this->imagick_load)
			{

				$this->image->readimage($source_image);
				$source_sizes = $this->image->getimagegeometry();

				$watermark = new Imagick($watermark_image);
				$watermark->setimageopacity($opacity > 1 ? $opacity / 100 : $opacity);
				$watermark_sizes = $watermark->getimagegeometry();

				$ext = pathinfo($new_image, PATHINFO_EXTENSION);

				if ($ext == 'jpg')
				{
					$this->image->setimagecompression(imagick::COMPRESSION_JPEG);
					$this->image->setimagecompressionQuality(0);
				}

				switch ($position)
				{
					case "7":
					case "4":
					case "1":
						$x = $padding;
						break;

					case "8":
					case "5":
					case "2":
						$x = ($source_sizes['width'] - $watermark_sizes['width']) / 2;
						break;
					// x right
					case "9":
					case "6":
					case "3":
						$x = $source_sizes['width'] - $padding - $watermark_sizes['width'];
						break;
					default:
						$x = $padding;
				}

				switch ($position)
				{
					case "7":
					case "8":
					case "9":
						$y = $padding;
						break;
					case "4":
					case "5":
					case "6":
						$y = ($source_sizes['height'] - $watermark_sizes['height']) / 2;
						break;
					case "1":
					case "2":
					case "3":
						$y = $source_sizes['height'] - $padding - $watermark_sizes['height'];
						break;
					default:
						$y = $padding;
				}

				$this->image->compositeimage($watermark, imagick::COMPOSITE_OVER, $x, $y);

				return $this->save($new_image);
			}
			else
			{
				$this->image->set_jpeg_quality(100)->load($source_image)->load_watermark($watermark_image)->watermark($position, $padding)->save($new_image, TRUE);
				return TRUE;
			}
		}

		public function save($file_name, $overwrite = TRUE)
		{
			if ($this->imagick_load)
			{
				if (file_exists($file_name))
				{
					if ($overwrite AND is_writable($file_name))
					{
						unlink($file_name);
					}
					else
					{
						return FALSE;
					}
				}

				$this->image->writeimage($file_name);
				$this->image->clear();
			}

			return TRUE;
		}
	}