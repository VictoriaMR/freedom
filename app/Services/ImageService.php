<?php

namespace App\Services;

use App\Services\Base as BaseService;

class ImageService extends BaseService
{
	/**
	 * 图片缩略图
	 */
	public function thumbImage($src, $moveto, $outputWidth = 600, $outputHeight = 600)
	{
		if (!extension_loaded('gd')) {
			return false;
		}
		if (!is_file($src)) return false;
		//图片信息
		$srcImageInfo = getimagesize($src);
		$srcImageWidth = $srcImageInfo[0];
		$srcImageHeight = $srcImageInfo[1];
		$srcImageMime = $srcImageInfo['mime'];

		if ($srcImageWidth == $outputWidth && $srcImageHeight == $outputHeight) {
			if ($src != $moveto)
				copy($src, $moveto);
			return true;
		}
	 	$imagecreatefromfunc = $imagefunc = null;
		switch($srcImageMime) {
			case 'image/jpeg':
				$imagecreatefromfunc = function_exists('imagecreatefromjpeg') ? 'imagecreatefromjpeg' : '';
				$imagefunc = function_exists('imagejpeg') ? 'imagejpeg' : '';
				break;
			case 'image/gif':
				$imagecreatefromfunc = function_exists('imagecreatefromgif') ? 'imagecreatefromgif' : '';
				$imagefunc = function_exists('imagegif') ? 'imagegif' : '';
				break;
			case 'image/png':
				$imagecreatefromfunc = function_exists('imagecreatefrompng') ? 'imagecreatefrompng' : '';
				$imagefunc = function_exists('imagepng') ? 'imagepng' : '';
				break;
		}
		if ($imagecreatefromfunc == '' || $imagefunc == '') {
			return false;
		}
	 
		$srcImage = $imagecreatefromfunc($src);

		//计算有效长度
		if ($outputWidth > $srcImageWidth && $outputHeight > $srcImageHeight) {
			if ($srcImageWidth > $srcImageHeight) {
				$outputWidth = $outputHeight = $srcImageWidth;
			} else {
				$outputWidth = $outputHeight = $srcImageHeight;
			}
		} 

	 	//创建画布
		$returnPic = imagecreatetruecolor($outputWidth, $outputHeight);

		//returnPic-输出图,img-拷贝的原图,dst_x-目标X坐标,dst_y-目标Y坐标,src_x-源X坐标,src_y-源Y坐标,dst_w-目标宽,dst_h-目标高,src_w-源宽,src_h-源高
		$dst_x = $dst_y = $src_x = $src_y = $diff_x = $diff_y = 0;

		$src_w = $srcImageWidth;
		$src_h = $srcImageHeight;

		if ($srcImageWidth > $srcImageHeight) {
			$ratio = $outputWidth / $srcImageWidth;
		} else {
			$ratio = $outputHeight / $srcImageHeight;
		}
		$real_h = $srcImageHeight * $ratio;
		//上下留白
		$diff_y = ($outputHeight - $real_h) / 2;

		$real_w = $srcImageWidth * $ratio;
		//左右留白
		$diff_x = ($outputWidth - $real_w) / 2;

		// imagecopy($img, $srcImage, 0, 0, 0, 0, $srcImageWidth, $srcImageHeight);
		$white = imagecolorallocate($returnPic, 255, 255, 255);//白色
		imagefill($returnPic, 0, 0, $white);

		imagecopyresampled($returnPic, $srcImage, $dst_x + $diff_x, $dst_y + $diff_y, $src_x, $src_y, $real_w, $real_h, $src_w, $src_h);
		$dirPath = dirname($moveto);
		if (!is_dir($dirPath)) {
			mkdir($dirPath, 0755, true);
		}
		$imagefunc($returnPic, $moveto);
		imagedestroy($returnPic);
		imagedestroy($srcImage);
		clearstatcache();
		return true;
	}

	//图片压缩
	public function compressImg($src, $moveto = '', $percent = 1)
	{
		if (!extension_loaded('gd')) {
			return false;
		}
		if (!is_file($src)) return false;
		//图片信息
		$srcImageInfo = getimagesize($src);
		$srcImageWidth = $srcImageInfo[0];
		$srcImageHeight = $srcImageInfo[1];
		$srcImageMime = $srcImageInfo['mime'];
		$imagecreatefromfunc = $imagefunc = null;
		switch($srcImageMime) {
			case 'image/jpeg':
				$imagecreatefromfunc = function_exists('imagecreatefromjpeg') ? 'imagecreatefromjpeg' : '';
				$imagefunc = function_exists('imagejpeg') ? 'imagejpeg' : '';
				break;
			case 'image/gif':
				$imagecreatefromfunc = function_exists('imagecreatefromgif') ? 'imagecreatefromgif' : '';
				$imagefunc = function_exists('imagegif') ? 'imagegif' : '';
				break;
			case 'image/png':
				$imagecreatefromfunc = function_exists('imagecreatefrompng') ? 'imagecreatefrompng' : '';
				$imagefunc = function_exists('imagepng') ? 'imagepng' : '';
				break;
		}
		if ($imagecreatefromfunc == '' || $imagefunc == '') {
			return false;
		}
	 
		$srcImage = $imagecreatefromfunc($src);

		$new_w = $srcImageWidth * $percent;
		$new_h = $srcImageHeight * $percent;
		$returnPic = imagecreatetruecolor($new_w, $new_h);
		$white = imagecolorallocate($returnPic, 255, 255, 255);//白色
	 	//图片填充白色背景
	 	imagecolortransparent($returnPic, $white);
		imagefill($returnPic, 0, 0, $white);

		imagecopyresampled($returnPic, $srcImage, 0, 0, 0, 0, $new_w, $new_h, $srcImageWidth, $srcImageHeight);
		if (empty($moveto))
			$moveto = $src;

		$dirPath = dirname($moveto);
		if (!is_dir($dirPath)) {
			mkdir($dirPath, 0755, true);
		}

		$imagefunc($returnPic, $moveto);
		imagedestroy($returnPic);
		imagedestroy($srcImage);
		clearstatcache();
		return true;
	}
}