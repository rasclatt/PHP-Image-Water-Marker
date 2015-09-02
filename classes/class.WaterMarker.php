<?php
	class	WaterMarker
		{
			protected	$imagepath;
			protected	$stamp;
			protected	$mime_type;
			protected	$filename;
			protected	$save_folder;
			
			public	function __construct($filepath = "images/default.png")
				{
					$this->stamp		=	$filepath;
					$this->save_folder	=	false;
				}
			
			public	function Initialize($imagepath = false)
				{
					$this->imagepath	=	$imagepath;
					$this->mime_type	=	$this->CheckFileType();
					
					return $this;
				}
			
			protected	function CheckFileType()
				{
					if(empty($this->imagepath) || !is_file($this->imagepath))
						return false;
						
					return mime_content_type($this->imagepath);
				}
				
			public	function BuildImage($settings = false)
				{
					$to_width	=	(!empty($settings['width']) && is_numeric($settings['width']))? $settings['width'] : false;
					$to_height	=	(!empty($settings['height']) && is_numeric($settings['height']))? $settings['height'] : false;
					$quality	=	(!empty($settings['quality']) && is_numeric($settings['quality']))? $settings['quality'] : 80;
					$output		=	(!empty($settings['to_browser']))? $settings['to_browser'] : false;
					// If the file name has not already been set by the UseName method, then try assigning to a hardcoded name
					// or use the original image to overwrite
					if(empty($this->filename))
						$this->filename	=	(!empty($settings['filename']))? $settings['filename'] : $this->imagepath;
					// If the save folder is set BUT the file path does not equal the filename prepend the folder string
					// If you prepend the folder string to the $this->imagepath, you may get the file placed in a strange spot
					if(($this->save_folder != false) && ($this->imagepath != $this->filename))
						$this->filename	=	str_replace("//","/",$this->save_folder."/".$this->filename);
					// Set the underlayed file
					switch($this->mime_type) {
							case ('image/png'):
								 $imgOverlay	=	imagecreatefrompng($this->imagepath);
								 $imgType		=	'png';
								 break;
							case ('image/jpeg'):
								 $imgOverlay	=	imagecreatefromjpeg($this->imagepath);
								 $imgType		=	'jpg';
								 break;
							case ('image/gif'):
								 $imgOverlay	=	imagecreatefromgif($this->imagepath);
								 $imgType		=	'gif';
								 break;
							default:
								$imgOverlay	=	false;
						}
						
					if(!$imgOverlay)
						return false;
					// Set the overlay file
					$imgAvatar	=	imagecreatefrompng($this->stamp);
					$width		=	imagesx($imgOverlay);
					$height		=	imagesy($imgOverlay);
					$to_width	=	($to_width != false)? $to_width:$width;
					$to_height	=	($to_height != false)? $to_height:$height;
					$imgBanner	=	imagecreatetruecolor($to_width, $to_height);
					imagecopyresampled($imgBanner, $imgOverlay, 0, 0, 0, 0, $to_width, $to_height, $width, $height);
					imagecopyresampled($imgBanner, $imgAvatar, 0, 0, 0, 0, $to_width, $to_height, imagesx($imgAvatar), imagesy($imgAvatar));
					// If file is to be output to browser, use header
					if($output)
						header('Content-type: '.$this->mime_type);
					// Create final image
					switch ($imgType) {
							case("png"):
								$negCalc	=	(10-($quality/10));
								imagepng($imgBanner,(($output)? NULL:$this->filename),$negCalc);
								break;
							case("jpg"):
								imagejpeg($imgBanner,(($output)? NULL:$this->filename),$quality);
								break;
							case("gif"):
								if($output)
									imagegif($imgBanner);
								else
									imagegif($imgBanner,$this->filename);
						}
					// Remove from memory
					imagedestroy($imgBanner);
					exit;
				}
			public	function UseName($filename = false)
				{
					$this->filename	=	preg_replace('/[^0-9a-zA-Z\_\-\.]/',"",$filename);
					
					if(empty($this->filename))
						return $this;
						
					$ext	=	$this->return_ext($this->mime_type);
					
					if(!$ext)
						return $this;
					
					$this->filename	=	"{$this->filename}.{$ext}";
					
					return $this;
				}
			
			public	function SaveTo($save_folder = false)
				{
					if(empty($save_folder))
						return $this;
						
					$this->save_folder	=	$save_folder;
						
					if(!is_dir($this->save_folder))
						mkdir($this->save_folder,0755,true);
					
					if(!is_dir($this->save_folder))
						$this->save_folder	=	false;
					
					return $this;
				}
			
			protected	function return_ext($mime = false)
				{
					switch ($mime) {
							case('image/jpeg'):
								return "jpg";
							case('image/png'):
								return "png";
							case('image/gif'):
								return "gif";
							default:
								return false;
						}
				}
		}
?>
