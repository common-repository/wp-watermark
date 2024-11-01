<?php

class WP_Watermark_ImageWriter_Jpeg extends WP_Watermark_ImageWriter {	
	public function write($img_resource, $destination) {
		if (isset($destination) && is_string($destination)) {
			imagejpeg($img_resource, $destination);
		} else {
			imagejpeg($img_resource);
		}		
	}
}

?>