<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use App\Lib\Image;
use Cake\View\Helper\HtmlHelper;

class ImageComponent extends Component {

	public $helpers = array('Html');

/**
 * Automatically resizes and/or crops an image and returns formatted URL only.
 * The helper class provides a means to getting fully formatted IMG tags
 *
 * @param string $path Path to the image file
 * @param array $options
 *
 * @return mixed Image tag or URL of the resized/cropped image
 *
 * @access public
 */
	public function resize($path, $options = array()) {
		$options = array_merge(array(
			'width'				=> null,	//Width of the new Image, Default is Original Width
			'height'			=> null,	//Height of the new Image, Default is Original Height
			'aspect'			=> true,	//Keep aspect ratio
			'crop'				=> false,	//Crop the Image
			'cropvars'			=> array(), //How to crop the image, array($startx, $starty, $endx, $endy);
			'autocrop'			=> false,	//Auto crop the image, calculate the crop according to the size and crop from the middle
			'htmlAttributes'	=> array(),	//Html attributes for the image tag
			'quality'			=> 90,		//Quality of the image
		), $options);

		foreach ($options as $key => $option) {
			${$key} = $option;
		}

		$relFile = Image::resize($path, $options);

		return $relFile;
	}
}
