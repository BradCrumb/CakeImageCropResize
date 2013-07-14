CakeImageCropResize
===================

A small CakePHP plugin for resizing and cropping of images. Includes a Helper and Component.

The Helper and/or Component gives you the ability to leave your original images in tact and resize/crop them for View only.

## Requirements

The master branch has the following requirements:

* CakePHP 2.2.0 or greater.
* PHP 5.3.0 or greater.

## Installation

* Clone/Copy the files in this directory into `app/Plugin/ImageCropResize`
* Ensure the plugin is loaded in `app/Config/bootstrap.php` by calling `CakePlugin::load('ImageCropResize');`
* Include the component in your `AppController.php`:
	* `public $components = array('ImageCropResize.Image');`
* Or include the helper in your `AppController.php`:
	* `public $helpers = array('ImageCropResize.Image');`

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

The resized image will be cached inside the webroot/img/cache directory. So we only have cached images inside or webroot and leave the originals outside the webroot.