<?php

class WP_Watermark_ImageWriter_Gif extends WP_Watermark_ImageWriter {	
	public function write($img_resource, $destination) {
		if (isset($destination) && is_string($destination)) {
			imagegif($img_resource, $destination);
		} else {
			imagegif($img_resource);
		}		
	}
}

?>