<?php

/**
 * Creates function imagecreatefrombmp, since PHP doesn't have one
 * @return resource An image identifier, similar to imagecreatefrompng
 * @param string $filename Path to the BMP image
 * @see imagecreatefrompng
 * @author Glen Solsberry <glens@networldalliance.com>
 */
if (!function_exists("imagecreatefrombmp")) {
	function imagecreatefrombmp( $filename ) {
		$file = fopen( $filename, "rb" );
		$read = fread( $file, 10 );
		while( !feof( $file ) && $read != "" )
		{
			$read .= fread( $file, 1024 );
		}
		$temp = unpack( "H*", $read );
		$hex = $temp[1];
		$header = substr( $hex, 0, 104 );
		$body = str_split( substr( $hex, 108 ), 6 );
		if( substr( $header, 0, 4 ) == "424d" )
		{
			$header = substr( $header, 4 );
			// Remove some stuff?
			$header = substr( $header, 32 );
			// Get the width
			$width = hexdec( substr( $header, 0, 2 ) );
			// Remove some stuff?
			$header = substr( $header, 8 );
			// Get the height
			$height = hexdec( substr( $header, 0, 2 ) );
			unset( $header );
		}
		$x = 0;
		$y = 1;
		$image = imagecreatetruecolor( $width, $height );
		foreach( $body as $rgb )
		{
			$r = hexdec( substr( $rgb, 4, 2 ) );
			$g = hexdec( substr( $rgb, 2, 2 ) );
			$b = hexdec( substr( $rgb, 0, 2 ) );
			$color = imagecolorallocate( $image, $r, $g, $b );
			imagesetpixel( $image, $x, $height-$y, $color );
			$x++;
			if( $x >= $width )
			{
				$x = 0;
				$y++;
			}
		}
		return $image;
	}
}


// files storage folder
$dir = '../../content/uploads/';

$_FILES['file']['type'] = strtolower($_FILES['file']['type']);
$type = $_FILES['file']['type'];

$max_upload = (int)(ini_get('upload_max_filesize'));
$max_post = (int)(ini_get('post_max_size'));
$memory_limit = (int)(ini_get('memory_limit'));
$upload_mb = min($max_upload, $max_post, $memory_limit);

$file_size = filesize($_FILES['file']['tmp_name']) / 1024 / 1024;

if($file_size > $upload_mb){
	echo json_encode(array('error' => 'size', 'errortxt' => 'max upload size: '.$upload_mb.' MB', 'filelink' => ''));
	exit();
}

if( $type == 'image/png' || $type == 'image/jpg' || $type == 'image/gif' || $type == 'image/jpeg' || $type == 'image/pjpeg' || $type == 'image/bmp' || $type == 'image/wbmp' ) {

	// setting file's mysterious name
	$file = $dir . md5(date('YmdHis')) . '.jpg';
	$thumb = $dir . 'thumb_' . md5(date('YmdHis')) . '.jpg';

	// copying
	if($type == 'image/png'){
		$img = imagecreatefrompng($_FILES['file']['tmp_name']);
	} else if($type == 'image/jpg' || $type == 'image/pjpeg' || $type == 'image/jpeg'){
		$img = imagecreatefromjpeg($_FILES['file']['tmp_name']);
	} else if($type == 'image/gif'){
		$img = imagecreatefromgif($_FILES['file']['tmp_name']);
	} else if($type == 'image/bmp'){
		copy($_FILES['file']['tmp_name'], $file.'.bmp');
		$img = imagecreatefrombmp($file.'.bmp');
		unlink($file.'.bmp');
	} else if($type == 'image/wbmp'){
		$img = imagecreatefromwbmp($_FILES['file']['tmp_name']);
	}

	$tmp_img = imagecreatetruecolor(imagesx($img), imagesy($img));
	imagecopy($tmp_img, $img, 0, 0, 0, 0, imagesx($img), imagesy($img));
	imagejpeg($tmp_img, $file);
	imagedestroy($tmp_img);
	//copy($_FILES['file']['tmp_name'], $file);

	// displaying file
	$array = array('filelink' => $file);

	echo stripslashes(json_encode($array));

	// now creating the thumbnail
	$thumb_width = 100;
	$img = imagecreatefromjpeg($file);
	$width = imagesx($img);
	$height = imagesy($img);

	// calculate thumbnail size
	$new_width = $thumb_width;
	$new_height = floor( $height * ( $thumb_width / $width ) );

	// create a new temporary image
	$tmp_img = imagecreatetruecolor($new_width, $new_height);

	// copy and resize old image into new image 
	imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );

	imagejpeg($tmp_img, $thumb);
	imagedestroy($tmp_img);
}

?>