<?php
/* Created by Ákos Nikházy 2013 https://github.com/akosnikhazy/ */
class compareImages
{
	private function mimeType($i)
	{
		/* returns array with mime type and if its jpg or png. Returns false if it isn't jpg or png */
		$mime = getimagesize($i);
		
		$return = array($mime[0], $mime[1]);
      
		switch ($mime['mime'])
		{
			case 'image/jpeg':
				$return[] = 'jpeg';
				return $return;
			case 'image/png':
				$return[] = 'png';
				return $return;
			default:
				return false;
		}
		
		/* 
			// PHP 8 version 
			// $return[] = match($mime['mime'])
			// {
			// 	'image/jpeg' => 'jpeg',
			// 	'image/png'	 => 'png'
			// };
			// 
			// return $return;
			// 
			// OR you can do this too, in 2 lines :D
			// 
			// $return[] = explode('/',$mime['mime'])[1];
			// return $return;
			
			// do not mind I avoid return false in these, I do not check for it anywhere else anyway
		*/
		
    }  
    
    private function createImage($i)
    {
		/* retuns image resource or false if its not jpg or png */
		
      
		if($this -> mimeType($i)[2] == 'jpeg')
		{
			return imagecreatefromjpeg ($i);
		} 
		else if ($this -> mimeType($i)[2] == 'png') 
		{
			return imagecreatefrompng ($i);
		} 
		
		return false; 
		 
    }
    
    private function resizeImage($i,$source)
    {
		/* resizes the image to a 8x8 squere and returns as image resource */
		
		$t = imagecreatetruecolor(8, 8);
		
		imagecopyresized($t, $this -> createImage($source), 0, 0, 0, 0, 8, 8, $this -> mimeType($source)[0], $this -> mimeType($source)[1]);
		
		return $t;
    }
    
    private function colorMeanValue($i)
    {
		/* returns the mean value of the colors and the list of all pixel's colors */
		$colorList = array();
		
		$colorSum = 0;
		
		for($a = 0; $a < 8; $a++)
		{
		
			for($b = 0; $b < 8; $b++)
			{
			
				$rgb = imagecolorat($i, $a, $b);
				$colorList[] = $rgb & 0xFF;
				$colorSum += $rgb & 0xFF;
				
			}
			
		}
		
		return array($colorSum/64,$colorList);
    }
    
    private function bits($colorMean)
    {
		/* returns an array with 1 and zeros. If a color is bigger than the mean value of colors it is 1 */
		$bits = array();
		 
		foreach($colorMean[1] as $color){ $bits[] = ($color >= $colorMean[0])?1:0; }

		return $bits;

    }
	
    public function compare($a,$b)
    {
		/* main function. returns the hammering distance of two images' bit value */
		$i1 = $this -> createImage($a);
		$i2 = $this -> createImage($b);
		
		if(!$i1 || !$i2){return false;}
		
		$i1 = $this -> resizeImage($i1,$a);
		$i2 = $this -> resizeImage($i2,$b);
		
		imagefilter($i1, IMG_FILTER_GRAYSCALE);
		imagefilter($i2, IMG_FILTER_GRAYSCALE);
		
		$colorMean1 = $this -> colorMeanValue($i1);
		$colorMean2 = $this -> colorMeanValue($i2);
		
		$bits1 = $this -> bits($colorMean1);
		$bits2 = $this -> bits($colorMean2);
		
		$hammeringDistance = 0;
		
		for($a = 0; $a < 64; $a++)
		{
		
			if($bits1[$a] != $bits2[$a])
			{
				$hammeringDistance++;
			}
			
		}
		  
		return $hammeringDistance;
    }
}
?>
