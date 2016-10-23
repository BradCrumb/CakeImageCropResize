CakeImageCropResize
===================

A small CakePHP plugin for resizing and cropping of images. Includes a Helper and Component.

The Helper and/or Component gives you the ability to leave your original images in tact and resize/crop them for View only.

## Requirements

The master branch has the following requirements:

* CakePHP 3.0.0 or greater.
* PHP 5.5.9 or greater.

## Installation

* Clone/Copy the files in this directory into `plugins/ImageCropResize`
* Ensure the plugin is loaded in `config/bootstrap.php` by calling `Plugin::load('ImageCropResize');` unless you already use `Plugin::loadAll()`
* Include the component in your `AppController.php`:
	* `Inside the initialize() method: $this->loadComponent('Image')`
* Or include the helper in your `AppView.php`:
	* `Inside the initialize() method: $this->loadHelper('Image');`

## Documentation
Both the Helper and Component have resize method that can be used as follow:

	$options = array(
		'width'				=> null,	//Width of the new Image, Default is Original Width
		'height'			=> null,	//Height of the new Image, Default is Original Height
		'aspect'			=> true,	//Keep aspect ratio
		'crop'				=> false,	//Crop the Image
		'cropvars'			=> array(), //How to crop the image, array($startx, $starty, $endx, $endy);
		'autocrop'			=> false,	//Auto crop the image, calculate the crop according to the size and crop from the middle
		'htmlAttributes'	=> array(),	//Html attributes for the image tag
		'quality'			=> 90,		//Quality of the image
		'urlOnly'			=> false	//Return only the URL or return the Image tag
	);

	echo $this->Image->resize($imagePath, $options);

Note: the urlOnly feature isn't available for the component so it only generates the URL for the cached image.
The resized image will be cached inside the webroot/img/cache directory. So we only have cached images inside or webroot and leave the originals outside the webroot.