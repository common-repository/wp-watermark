<?php

require_once('wp-watermark-consts.php');
require_once('lib/ImageHandlerFactory.php');

$image =  $root . $_SERVER['REDIRECT_URL'];
$overlay = WP_WATERMARK_DIR . '/watermark.png';

if (!file_exists($image)) {
    die("Image does not exist.");
}

$image = realpath($image);
$overlay = realpath($overlay);

function output_image($file_name) {
	header("Content-type: " . mime_content_type($file_name));
	header("Content-Disposition: filename=" . basename($file_name));
	header("Content-Length: " . filesize($file_name));
	readfile($file_name);
}

function create_watermarked_image($original, $overlay, $watermarked,
	$pos_x, $pos_y, $offset_x, $offset_y, $alpha) {
	$image_handler = WP_Watermark_ImageHandlerFactory::createImageHandlerFromFile($original);
	$image_handler->setAlphaBlending(true);
	$overlay_handler = WP_Watermark_ImageHandlerFactory::createImageHandlerFromFile($overlay);
	$overlay_handler->setAlphaBlending(true);
	
	// Make sure the width of the overlay never exceeds 1/3 of the original image
	if ($overlay_handler->getWidth() > $image_handler->getWidth() / 3) {
		$reduction = 3 * $overlay_handler->getWidth() / $image_handler->getWidth();
	}
	else {
		$reduction = 1;
	}
	
	$resized_overlay_handler = WP_Watermark_ImageHandlerFactory::createBlankImageHandler(
		floor($overlay_handler->getWidth() / $reduction), 
		floor($overlay_handler->getHeight() / $reduction)
	);  
	$overlay_handler->copyResampledTo($resized_overlay_handler);
	$resized_overlay_handler->copyMergeTo($image_handler, $pos_x, $pos_y, $offset_x, $offset_y, $alpha);
	$image_handler->output($watermarked);	
}

function ensure_watermarked_image_exists($original, $overlay, $force_create = false) {
	$extension = strtolower(substr($original, strrpos($original, ".") + 1));
	$watermarked = str_replace('.' . $extension, '-watermarked.' . $extension, $original);
	
	if (realpath(dirname($original)) == realpath(WP_WATERMARK_DIR . '/examples'))
		$force_create = true;
	
	if (!file_exists($watermarked) || $force_create) {
		create_watermarked_image($original, $overlay, $watermarked, -2, -2, -3, -3, 60);
	}
	return $watermarked;
}

$watermarked_image = ensure_watermarked_image_exists($image, $overlay, false);
output_image($watermarked_image);
 
?> 