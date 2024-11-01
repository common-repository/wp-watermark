<?php

class WP_Watermark_ImageReader_Png extends WP_Watermark_ImageReader {
	public function read($source) {
		return imagecreatefrompng($source);
	}
}

?>