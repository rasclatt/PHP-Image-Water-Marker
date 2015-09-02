<?php
	include_once(__DIR__.'/classes/class.WaterMarker.php');
	// This sets the default watermark file (overlay)
	$engine	=	new WaterMarker(__DIR__.'/watermarks/stamp.png');
	// This sets the default background image that will have the overlay
	$engine	->Initialize(__DIR__.'/images/test.jpg')
			// This can be used to change the name of the destination file
			// This will filter to filename-friendly value 
			->UseName("french fry \$lx_DS+F-sdfa!@#%;'#%^&&")
			// This is used to assign the save location of the document
			// This can take the original document and save to a new destination
			// The folder will be created (attempt to create) if not exists
			->SaveTo(__DIR__.'/fragooo/')
			// This will create the document
			// Without size settings, the file will be the size of the background image
			// Quality by default is 80%
			// The "filename" attribute here is overwridden if the UseName() method is used.
			// The "to_browser" by default is "false." If set to "true" it will not save the file
			// to disk but rather display in the browswer. Output to the browser is only possible if there is
			// no other browswer output on the page.
			->BuildImage(array("quality"=>60,"width"=>60,"height"=>100,"filename"=>'/imagemaker.jpg',"to_browser"=>false));
?>
