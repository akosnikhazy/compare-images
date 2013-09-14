<?php
require 'image.compare.class.php';
 
/*
	these two images are almost the same so the hammered distance will be less than 10
	try it with images like this:
		1. two complatly different image
		2. the same image (returned number should be 0)
		3. the same image but with different size, even different aspect ratio (returned number should be 0)
		4. the same image with slightly different colors / lights etc. (returned number should be under 10)
	you will see how the returned number will represent the similarity of the images.
*/ 
$class = new compareImages;
echo $class->compare('1.jpg','2.jpg');
  
?>
