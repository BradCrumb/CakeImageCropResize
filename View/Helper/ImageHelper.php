<?php
App::uses('AppHelper', 'View/Helper');

class ImageHelper extends AppHelper {

	public $helpers = array('Html');

/**
 * Default Constructor
 *
 * @param View $View The View this helper is being attached to.
 * @param array $settings Configuration settings for the helper.
 */
	public function __construct(View $View, $settings = array()) {
		parent::__construct($View, $settings);

		$explode = explode('/',realpath(__DIR__ . DS . '..' . DS . '..'));
		$pluginName = end($explode);

		App::uses('Image', $pluginName . '.Lib');
	}

/**
 * Automatically resizes and/or crops an image and returns formatted IMG tag or URL
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
									'width' => null,
									'height' => null,
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

		$relFile = Image::resize($path, $options);

		//Return only the URL
		if ($options['urlOnly']) {
			return $relFile;
		}

		return $this->Html->image($relFile,$options['htmlAttributes']);
	}
}