<?php

require_once('ImageReader.php');
require_once('ImageWriter.php');

define('WP_WATERMARK_HALIGN_LEFT', 0);
define('WP_WATERMARK_HALIGN_CENTER', -1);
define('WP_WATERMARK_HALIGN_RIGHT', -2);

define('WP_WATERMARK_VALIGN_TOP', 0);
define('WP_WATERMARK_VALIGN_MIDDLE', -1);
define('WP_WATERMARK_VALIGN_BOTTOM', -2);

class WP_Watermark_ImageHandler {
	private $img_resource = null;
	private $file_name = '';
	
	private $reader = null;
	private $writer = null;
	
  public function __destruct() {
		if ($this->img_resource) {
			imagedestroy($this->img_resource);
		}
  }
	
  public function setReader(WP_Watermark_ImageReader &$reader) {
		$this->reader = &$reader;
  }
	
  public function setWriter(WP_Watermark_ImageWriter &$writer) {
		$this->writer = &$writer;
  }
	
	public function initialiseFromFileName($file_name) {
		$this->file_name = $file_name;
		$this->img_resource = $this->reader->read($this->file_name);		
	}

	public function initialiseFromDimensions($width, $height) {
		$this->img_resource = imagecreatetruecolor($width, $height);  
	}

	public function output($destination) {
		$this->writer->write($this->img_resource, $destination);		
	}
	
	public function getWidth() {
		return imagesx($this->img_resource);
	}
	
	public function getHeight() {
		return imagesy($this->img_resource);
	}
	
	public function getBaseFileName() {
		return basename($this->file_name);
	}
	
	public function getMimeType() {
		$image_data = getimagesize($this->file_name);
		return $image_data['mime'];
	}
	
	public function setAlphaBlending($blendmode) {
		return imagealphablending($this->img_resource, $blendmode);
	}
	
	public function copyResampledTo(WP_Watermark_ImageHandler &$dest) {
		imagecopyresampled($dest->img_resource, $this->img_resource, 0, 0, 0, 0,
			$dest->getWidth(), $dest->getHeight(), $this->getWidth(), $this->getHeight());
	}

	public function copyMergeTo(WP_Watermark_ImageHandler &$dest,
		$pos_x = 0, $pos_y = 0, $offset_x = 0, $offset_y = 0, $alpha = 100) {
		switch ($pos_x) {
			case WP_WATERMARK_HALIGN_CENTER:
				$pos_x = floor(($dest->getWidth() - $this->getWidth()) / 2);
				break;
			case WP_WATERMARK_HALIGN_RIGHT:
				$pos_x = $dest->getWidth() - $this->getWidth();
				break;
		}
		switch ($pos_y) {
			case WP_WATERMARK_VALIGN_MIDDLE:
				$pos_y = floor(($dest->getHeight() - $this->getHeight()) / 2);
				break;
			case WP_WATERMARK_VALIGN_BOTTOM:
				$pos_y = $dest->getHeight() - $this->getHeight();
				break;
		}
		imagecopymerge($dest->img_resource, $this->img_resource,
			$pos_x + $offset_x, $pos_y + $offset_y, 
			0, 0, $this->getWidth(), $this->getHeight(), 
			$alpha);
	}	
}

?>