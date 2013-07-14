<?php
class ImageComponent extends Component {

	public $helpers = array('Html');

/**
 * Constructor
 *
 * @param ComponentCollection $collection A ComponentCollection this component can use to lazy load its components
 * @param array $settings Array of configuration settings.
 */
	public function __construct(ComponentCollection $collection, $settings = array()) {
		parent::__construct($collection, $settings);

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
									'urlOnly' => true), $options
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