<?php

class WP_Watermark_ImageReader_Jpeg extends WP_Watermark_ImageReader {
	public function read($source) {
		return imagecreatefromjpeg($source);
	}
}

?>