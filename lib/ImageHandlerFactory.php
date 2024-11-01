<?php

require_once('ImageHandler.php');

require_once('ImageReader.php');
require_once('ImageReader/Png.php');
require_once('ImageReader/Gif.php');
require_once('ImageReader/Jpeg.php');

require_once('ImageWriter.php');
require_once('ImageWriter/Png.php');
require_once('ImageWriter/Gif.php');
require_once('ImageWriter/Jpeg.php');

class WP_Watermark_ImageHandlerFactory {
  protected static function assignSerializers(WP_Watermark_ImageHandler &$image_handler, $mime_type = 'image/png') {
		$gdinfo = gd_info();
		switch ($mime_type)
		{
			case 'image/gif':
				if (!$gdinfo['GIF Create Support']) {
					die("No GIF support");
				}
				$image_handler->setReader(new WP_Watermark_ImageReader_Gif);
				
				if ($gdinfo['GIF Create Support']) {
					$image_handler->setWriter(new WP_Watermark_ImageWriter_Gif);
				} elseif ($gdinfo['PNG Support']) {
					$image_handler->setWriter(new WP_Watermark_ImageWriter_Png);
				} else {
					die("No GIF or PNG support");
				}
				break;
			case 'image/png':
				if (!$gdinfo['PNG Support']) {
					die("No PNG support");
				}
				$image_handler->setReader(new WP_Watermark_ImageReader_Png);
				$image_handler->setWriter(new WP_Watermark_ImageWriter_Png);
				break;
			case 'image/jpeg':
				if (!$gdinfo['JPG Support']) {
					die("No JPEG support");
				}
				$image_handler->setReader(new WP_Watermark_ImageReader_Jpeg);
				$image_handler->setWriter(new WP_Watermark_ImageWriter_Jpeg);
				break;
			default:
				die("Image is of unsupported type");
		}
	}

	public static function createImageHandlerFromFile($file_name) {
		if (!file_exists($file_name)) {
			die("Image does not exist");
		}
		if (!is_readable($file_name)) {
			die("Cannot open file");
		}

		$image_size_data = getimagesize($file_name);
		$mime_type = $image_size_data['mime'];
		
		$image_handler = new WP_Watermark_ImageHandler;
		self::assignSerializers($image_handler, $mime_type);
		$image_handler->initialiseFromFileName($file_name);
		return $image_handler;
	}

	public static function createBlankImageHandler($width, $height) {
		$image_handler = new WP_Watermark_ImageHandler;
		self::assignSerializers($image_handler, 'image/png');
		$image_handler->initialiseFromDimensions($width, $height);
		return $image_handler;
	}

	public static function getFormats() {
		$ret = '';
		$gdinfo = gd_info();
		if ($gdinfo['GIF Read Support'])
			$ret .= 'gif|';
		if ($gdinfo['JPG Support'])
			$ret .= 'jpg|jpeg|';
		if ($gdinfo['PNG Support'])
			$ret .= 'png|';
		$ret = substr($ret, 0, -1);
		return $ret;
	}
}

?>