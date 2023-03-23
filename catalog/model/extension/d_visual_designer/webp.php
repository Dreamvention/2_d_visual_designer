<?php
class ModelExtensionDVisualDesignerWebp extends Model {
    public function toWebp($path) {
		if (!is_file($path)) return false;
		
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if ($extension == 'webp') return true;

        $image_object = (new Image($path))->getImage();

        if(is_resource($image_object) || is_object($image_object)) {
            $webp_path = dirname($path) . '/' . basename($path, '.' . $extension) . '.webp';
            if (!is_file($webp_path) && @imagewebp($image_object, $webp_path, 100)) {
                return true;
            } elseif (is_file($webp_path)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function resize($filename, $width, $height) {
        if (!is_file(DIR_IMAGE . $filename) || substr(str_replace('\\', '/', realpath(DIR_IMAGE . $filename)), 0, strlen(DIR_IMAGE)) != str_replace('\\', '/', DIR_IMAGE)) {
			return;
		}

		$extension = pathinfo($filename, PATHINFO_EXTENSION);

		$image_old = $filename;
		$image_new = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . (int)$width . 'x' . (int)$height . '.' . $extension;

		if (!is_file(DIR_IMAGE . $image_new) || (filemtime(DIR_IMAGE . $image_old) > filemtime(DIR_IMAGE . $image_new))) {
			list($width_orig, $height_orig, $image_type) = getimagesize(DIR_IMAGE . $image_old);
				 
			if (!in_array($image_type, array(IMAGETYPE_WEBP))) { 
				if ($this->request->server['HTTPS']) {
                    return $this->config->get('config_ssl') . 'image/' . $image_old;
                } else {
                    return $this->config->get('config_url') . 'image/' . $image_old;
                }
			}
						
			$path = '';

			$directories = explode('/', dirname($image_new));

			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;

				if (!is_dir(DIR_IMAGE . $path)) {
					@mkdir(DIR_IMAGE . $path, 0777);
				}
			}

			if ($width_orig != $width || $height_orig != $height) {
				$image = new d_webp_image(DIR_IMAGE . $image_old);

				$image->resize($width, $height);
				$image->save(DIR_IMAGE . $image_new);
			} else {
				copy(DIR_IMAGE . $image_old, DIR_IMAGE . $image_new);
			}
		}
		
		$image_new = str_replace(' ', '%20', $image_new);  // fix bug when attach image on email (gmail.com). it is automatic changing space " " to +
		
		if ($this->request->server['HTTPS']) {
			return $this->config->get('config_ssl') . 'image/' . $image_new;
		} else {
			return $this->config->get('config_url') . 'image/' . $image_new;
		}
    }
}