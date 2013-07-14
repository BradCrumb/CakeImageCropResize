<?php
App::uses('Image', 'Sofia.Lib');
class ImageComponent extends Component {

	public $helpers = array('Html');

	public function resize($path, $width, $height, $options = array()) {
		$options = array_merge(array(
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

		$relFile = Image::resize($path, $width, $height,$options);

		if ($options['urlOnly']) {
			return $relFile;
		}

		return sprintf($this->Html->tags['image'], $relFile, $this->Html->_parseAttributes($options['htmlAttributes'], null, '', ' '));
	}

	public function render($path) {
		$size = getimagesize(WWW_ROOT . $path);
		$mime = $size['mime'];

		$data = file_get_contents(WWW_ROOT . $path);

		header("Content-Type: $mime");
		header('Content-Length: ' . strlen($data));
		echo $data;
		exit();
	}
}