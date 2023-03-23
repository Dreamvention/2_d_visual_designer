<?php

class d_webp_image {
	private $file;
	private $image;
	private $width;
	private $height;
	private $bits;
	private $mime;

	public function __construct($file) {
		if (!extension_loaded('gd')) {
			exit('Error: PHP GD is not installed!');
		}
		
		if (is_file($file)) {
			$this->file = $file;

			$info = getimagesize($file);

			$this->width  = $info[0];
			$this->height = $info[1];
			$this->bits = isset($info['bits']) ? $info['bits'] : '';
			$this->mime = isset($info['mime']) ? $info['mime'] : '';

			if ($this->mime == 'image/webp') {
				$this->image = imagecreatefromwebp($file);
			}
		} else {
			exit('Error: Could not load image ' . $file . '!');
		}
	}
	
	public function getFile() {
		return $this->file;
	}

	public function getImage() {
		return $this->image;
	}
	
	public function getWidth() {
		return $this->width;
	}
	
	public function getHeight() {
		return $this->height;
	}
	
	public function getBits() {
		return $this->bits;
	}
	
	public function getMime() {
		return $this->mime;
	}
	
	public function save($file, $quality = 90) {
		$info = pathinfo($file);

		$extension = strtolower($info['extension']);

		if (is_resource($this->image) || is_object($this->image)) {
			if ($extension == 'webp') {
				imagewebp($this->image, $file, $quality);
			}

			imagedestroy($this->image);
		}
	}
	
	public function resize($width = 0, $height = 0, $default = '') {
		if (!$this->width || !$this->height) {
			return;
		}

		$xpos = 0;
		$ypos = 0;
		$scale = 1;

		$scale_w = $width / $this->width;
		$scale_h = $height / $this->height;

		if ($default == 'w') {
			$scale = $scale_w;
		} elseif ($default == 'h') {
			$scale = $scale_h;
		} else {
			$scale = min($scale_w, $scale_h);
		}

		if ($scale == 1 && $scale_h == $scale_w && $this->mime != 'image/png') {
			return;
		}

		$new_width = (int)($this->width * $scale);
		$new_height = (int)($this->height * $scale);
		$xpos = (int)(($width - $new_width) / 2);
		$ypos = (int)(($height - $new_height) / 2);

		$image_old = $this->image;
		$this->image = imagecreatetruecolor($width, $height);

		if ($this->mime == 'image/png') {
			imagealphablending($this->image, false);
			imagesavealpha($this->image, true);
			$background = imagecolorallocatealpha($this->image, 255, 255, 255, 127);
			imagecolortransparent($this->image, $background);
		} else {
			$background = imagecolorallocate($this->image, 255, 255, 255);
		}

		imagefilledrectangle($this->image, 0, 0, $width, $height, $background);

		imagecopyresampled($this->image, $image_old, $xpos, $ypos, 0, 0, $new_width, $new_height, $this->width, $this->height);
		imagedestroy($image_old);

		$this->width = $width;
		$this->height = $height;
	}
	
	
}