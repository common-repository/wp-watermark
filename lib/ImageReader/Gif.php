<?php

class WP_Watermark_ImageReader_Gif extends WP_Watermark_ImageReader {
	public function read($source) {
		return imagecreatefromgif($source);
	}
}

?>