<?php
class Image {

	public static function resize($path, $options = array()) {
		$options = array_merge(array(
			'width'				=> null,	//Width of the new Image, Default is Original Width
			'height'			=> null,	//Height of the new Image, Default is Original Height
			'aspect'			=> true,	//Keep aspect ratio
			'crop'				=> false,	//Crop the Image
			'cropvars'			=> array(), //How to crop the image, array($startx, $starty, $endx, $endy);
			'autocrop'			=> false,	//Auto crop the image, calculate the crop according to the size and crop from the middle
			'htmlAttributes'	=> array(),	//Html attributes for the image tag
			'quality'			=> 90,		//Quality of the image
			'urlOnly'			=> false	//Return only the URL or return the Image tag
		), $options);

		foreach ($options as $key => $option) {
			${$key} = $option;
		}

		$types = array(1 => "gif", "jpeg", "png", "swf", "psd", "wbmp"); // used to determine image type

		$fullpath = ROOT . $path;

		if (!file_exists($fullpath) || !is_file($fullpath)) {
			$path = '/app/uploads/no-image.jpg';
			$fullpath = ROOT . $path;
		}

		if (!file_exists($fullpath) || !($size = getimagesize($fullpath))) {
			return; // image doesn't exist
		}

		if ((!isset($width) && !isset($height)) || ($width == 0 && $height == 0)) {
			$width = $size[0];
			$height = $size[1];
		}
		if ($autocrop) {
			$multiplier = 1.0;
			while (($width * $multiplier < $size[0]) && ($height * $multiplier < $size[1])) {
				$multiplier += .01;
			}

			// make SURE we don't run over
			$multiplier -= .01;

			$cropw = floor($width * $multiplier);
			$croph = floor($height * $multiplier);

			$xindent = ($size[0] - $cropw) / 2.0;
			$yindent = ($size[1] - $croph) / 2.0;

			$startx = floor($xindent);
			$endx = $size[0] - ceil($xindent);

			$starty = floor($yindent);
			$endy = $size[1] - ceil($yindent);

			$cropvars = array($startx, $starty, $endx, $endy);
		}

		if (($width > $size[0] || $height > $size[1]) && $autocrop) {
			$multiplier = 1.0;
			while (($width * $multiplier >= $size[0]) || ($height * $multiplier >= $size[1])) {
				$multiplier -= .01;
			}

			$cropw = floor($width * $multiplier);
			$croph = floor($height * $multiplier);

			$xindent = ($size[0] - $cropw) / 2.0;
			$yindent = ($size[1] - $croph) / 2.0;

			$startx = floor($xindent);
			$endx = $size[0] - ceil($xindent);

			$starty = floor($yindent);
			$endy = $size[1] - ceil($yindent);

			$cropvars = array($startx, $starty, $endx, $endy);
		}

		// check that user supplied full start and stop coordinates
		if (count($cropvars) == 4) {
			if ($cropvars[0] > $size[0] || $cropvars[1] > $size[1] || $cropvars[2] > $size[0] || $cropvars[3] > $size[1] || $cropvars[0] < 0 || $cropvars[1] < 0 || $cropvars[2] < 0 || $cropvars[3] < 0) {
				$crop = false;
			}
		} else {
			$crop = false;
		}

		// temporarily set size to this for aspect checking
		if ($crop) {
			$tempsize = array($size[0], $size[1]);
			$size[0] = $cropvars[2] - $cropvars[0];
			$size[1] = $cropvars[3] - $cropvars[1];
		}

		if ($aspect) {	// adjust to aspect
			if (($size[1] / $height) > ($size[0] / $width))	// $size[0]:width, [1]:height, [2]:type
				$width = ceil(($size[0] / $size[1]) * $height);
			else
				$height = ceil($width / ($size[0] / $size[1]));
		}

		// set size back
		if ($crop) {
			$size[0] = $tempsize[0];
			$size[1] = $tempsize[1];
		}

		if ($crop) {
			$cropstring = $cropvars[0] . $cropvars[1] . $cropvars[2] . $cropvars[3] . '_';
		} else {
			$cropstring = '';
		}

		$relfile = '/img/cache/' . $width . 'x' . $height . '_' . $cropstring . basename($path); // relative file
		$cachefile = WWW_ROOT . DS . 'img' . DS . 'cache' . DS . $width . 'x' . $height . '_' . $cropstring . basename($path);	// location on server

		if (!is_dir(WWW_ROOT . DS . 'img' . DS . 'cache')) {
			mkdir(WWW_ROOT . DS . 'img' . DS . 'cache');
		}

		if (file_exists($cachefile)) {
			$csize = getimagesize($cachefile);
			$cached = ($csize[0] == $width && $csize[1] == $height); // image is cached
			if (filemtime($cachefile) < filemtime($fullpath)) { // check if up to date
				$cached = false;
			}
		} else {
			$cached = false;
		}

		if (!$cached) {
			$resize = ($size[0] > $width || $size[1] > $height) || ($size[0] < $width || $size[1] < $height);
		} else {
			$resize = false;
		}

		if ($resize) {
			$image = call_user_func('imagecreatefrom' . $types[$size[2]], $fullpath);

			if ($crop) {
				if (function_exists("imagecreatetruecolor") && ($tempcrop = imagecreatetruecolor($cropvars[2] - $cropvars[0], $cropvars[3] - $cropvars[1]))) {
					imagealphablending($tempcrop, false);
					imagealphablending($image, false);
					imagecopyresampled($tempcrop, $image, 0, 0, $cropvars[0], $cropvars[1], $cropvars[2] - $cropvars[0], $cropvars[3] - $cropvars[1], $cropvars[2] - $cropvars[0], $cropvars[3] - $cropvars[1]);
				} else {
					$tempcrop = imagecreate($cropvars[2] - $cropvars[0], $cropvars[3] - $cropvars[1]);
					imagecopyresized($tempcrop, $image, 0, 0, $cropvars[0], $cropvars[1], $cropvars[2] - $cropvars[0], $cropvars[3] - $cropvars[1], $size[0], $size[1]);
				}

				$image = $tempcrop;

				$size[0] = $cropvars[2] - $cropvars[0];
				$size[1] = $cropvars[3] - $cropvars[1];
			}
			if (function_exists("imagecreatetruecolor") && ($temp = imagecreatetruecolor($width, $height))) {
				imagealphablending($temp, false);
				imagealphablending($image, false);
				imagecopyresampled($temp, $image, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
			} else {
				$temp = imagecreate($width, $height);
				imagecopyresized($temp, $image, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
			}
			if ($types[$size[2]] == "jpeg") {
				imagejpeg($temp, $cachefile, $quality);
			} else {
				call_user_func("image" . $types[$size[2]], $temp, $cachefile);
			}
			imagedestroy($image);
			imagedestroy($temp);
		} elseif (!$cached) {
			$image = call_user_func('imagecreatefrom' . $types[$size[2]], $fullpath);
			imagejpeg($image, $cachefile, $quality);
		}

		return $relfile;
	}
}