<?php
define ( 'DOCUMENT_ROOT', dirname ( __FILE__ ) );
define("img_dir", DOCUMENT_ROOT."/captcha/");
session_start();
mb_internal_encoding("UTF-8");

function generate_code() {    
	  $chars = 'цукенгзхфвапролдэячсмитбю';
	  $length = 4;
	  $numChars = mb_strlen($chars); 
	  $str = '';
	  for ($i = 0; $i < $length; $i++) {
		$str .= mb_substr($chars, rand(1, $numChars) - 1, 1);
	  } 
		$array_mix = preg_split('//', $str, -1, PREG_SPLIT_NO_EMPTY);
		srand ((float)microtime()*1000000);
	return implode("", $array_mix);
}

$captcha = generate_code();

function img_code($code) {

	$linenum = 10; 
	$img_arr = array("1.png");
	$font_arr = array();
		$font_arr["fname"] = "font.ttf";
		$font_arr["size"] = 40;
	$img_fn = $img_arr[rand(0, sizeof($img_arr)-1)];
	$im = imagecreatefrompng(dirname(__FILE__)."/captcha/back.png");
    $color = imagecolorallocate($im, 255, 255, 255);
    $x = -27;				
	for($i = 0; $i < mb_strlen($code); $i++) {
		$y = rand(-20, 20);
		$z = rand(40, 60);
		$x+=35;
		$letter=mb_substr($code, $i, 1);
		imagettftext ($im, $font_arr["size"], $y, $x, $z, $color, img_dir.$font_arr["fname"], $letter);
        $_SESSION['security_code'] = $code;
	}
	for ($i = 0; $i < $linenum; $i++) {
		$color = imagecolorallocate($im, rand(0, 255), rand(0, 200), rand(0, 255));
		imageline($im, rand(0, 20), rand(0, 70), rand(120, 150), rand(0, 70), $color);
	}
 	$im = opsmaz($im, array(108,108,108));
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");                   
	header("Last-Modified: " . gmdate("D, d M Y H:i:s", 10000) . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");         
	header("Cache-Control: post-check=0, pre-check=0", false);           
	header("Pragma: no-cache");                                           
	header("Content-Type:image/png");		
	ImagePNG ($im);
	ImageDestroy ($im);
}

function opsmaz($img,$ncolor){
   $foreground_color =array(250,250,250);
   $background_color =array(250,250,250);
   $width=imagesx($img);
   $height=imagesy($img);
   $center=$width/2;
   $img2=imagecreatetruecolor($width, $height);
   $foreground=imagecolorresolve($img2, $foreground_color[0], $foreground_color[1], $foreground_color[2]);
   $background=imagecolorresolve($img2, $background_color[0], $background_color[1], $background_color[2]);
   imagefilledrectangle($img2, 0, 0, $width-1, $height-1, $background);		
   imagefilledrectangle($img2, 0, $height, $width-1, $height+12, $foreground);    
		$rand1=mt_rand(750000,00000)/10000000;
		$rand2=mt_rand(750000,0000000)/10000000;
		$rand3=mt_rand(750000,000000)/10000000;
		$rand4=mt_rand(750000,000000)/10000000;
		$rand5=mt_rand(0,31415926)/1000000;
		$rand6=mt_rand(0,31415926)/1000000;
		$rand7=mt_rand(0,31415926)/1000000;
		$rand8=mt_rand(0,31415926)/1000000;
		$rand9=mt_rand(330,300)/110;
		$rand10=mt_rand(330,300)/110;
		for($x=0;$x<$width;$x++){
			for($y=0;$y<$height;$y++){
				$sx=$x+(sin($x*$rand1+$rand5)+sin($y*$rand3+$rand6))*$rand9-$width/2+$center+1;
				$sy=$y+(sin($x*$rand2+$rand7)+sin($y*$rand4+$rand8))*$rand10;

				if($sx<0 || $sy<0 || $sx>=$width-1 || $sy>=$height-1){
					continue;
				}else{
					$color=imagecolorat($img, $sx, $sy) & 0xFF;
					$color_x=imagecolorat($img, $sx+1, $sy) & 0xFF;
					$color_y=imagecolorat($img, $sx, $sy+1) & 0xFF;
					$color_xy=imagecolorat($img, $sx+1, $sy+1) & 0xFF;
				}
				if($color==255 && $color_x==255 && $color_y==255 && $color_xy==255){
					continue;
				}else if($color==0 && $color_x==0 && $color_y==0 && $color_xy==0){
					$newred=$foreground_color[0];
					$newgreen=$foreground_color[1];
					$newblue=$foreground_color[2];
				}else{
					$newred=$ncolor[0];
					$newgreen=$ncolor[1];
					$newblue=$ncolor[2];
				}

				imagesetpixel($img2, $x, $y, imagecolorallocate($img2, $newred, $newgreen, $newblue));
			}
		}
  return $img2;
}
img_code($captcha);
?>