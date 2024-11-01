<?php

class WP_Watermark_ImageWriter_Png extends WP_Watermark_ImageWriter {	
	public function write($img_resource, $destination) {
		if (isset($destination) && is_string($destination)) {
			imagepng($img_resource, $destination);
		} else {
			imagepng($img_resource);
		}		
	}
}

?>