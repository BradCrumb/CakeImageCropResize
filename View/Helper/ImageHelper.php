<?php
App::uses('AppHelper', 'View/Helper');
App::uses('Image', 'Sofia.Lib');
class ImageHelper extends AppHelper {

	public $helpers = array('Html');

/**
 * Automatically resizes an image and returns formatted IMG tag
 *
 * @param string $path Path to the image file, relative to the webroot/img/ directory.
 * @param integer $width Image of returned image
 * @param integer $height Height of returned image
 * @param boolean $aspect Maintain aspect ratio (default: true)
 * @param array    $htmlAttributes Array of HTML attributes.
 * @param boolean $return Wheter this method should return a value or output it. This overrides AUTO_OUTPUT.
 *
 * @return mixed    Either string or echos the value, depends on AUTO_OUTPUT and $return.
 *
 * @access public
 */
	public function resize($path, $width, $height, $options = array()) {
		$options = array_merge(array(
									'aspect' => true,
									'crop' => false,
									'cropvars' => array(),
									'autocrop' => false,
									'htmlAttributes' => array(),
									'return' => false,
									'quality' => 90,
									'urlOnly' => false), $options
									);

		foreach ($options as $key => $option) {
			${$key} = $option;
		}

		$relFile = Image::resize($path, $width, $height,$options);

		if ($options['urlOnly']) {
			return $relFile;
		}

		return $this->Html->image($relFile,$options['htmlAttributes']);
	}
}