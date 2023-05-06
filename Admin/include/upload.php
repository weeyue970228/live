<?php


// Code for to workaround the Flash Player Session Cookie bug
	if (isset($_POST["PHPSESSID"])) {
		session_id($_POST["PHPSESSID"]);
	} else if (isset($_GET["PHPSESSID"])) {
		session_id($_GET["PHPSESSID"]);
	}

	session_start();

// Check post_max_size (http://us3.php.net/manual/en/features.file-upload.php#73762)
	$POST_MAX_SIZE = ini_get('post_max_size');
	$unit = strtoupper(substr($POST_MAX_SIZE, -1));
	$multiplier = ($unit == 'M' ? 1048576 : ($unit == 'K' ? 1024 : ($unit == 'G' ? 1073741824 : 1)));

	if ((int)$_SERVER['CONTENT_LENGTH'] > $multiplier*(int)$POST_MAX_SIZE && $POST_MAX_SIZE) {
		header("HTTP/1.1 500 Internal Server Error"); // This will trigger an uploadError event in SWFUpload
		echo "POST exceeded maximum allowed size.";
		exit(0);
	}

// Settings
	$save_path = "../../Public/vote/";				// The path were we will save the file (getcwd() may not be reliable and should be tested in your environment)
	$upload_name = "Filedata";
	$max_file_size_in_bytes = 2147483647;				// 2GB in bytes
	$extension_whitelist = array("jpg", "gif", "png");	// Allowed file extensions
	$valid_chars_regex = '.A-Z0-9_ !@#$%^&()+={}\[\]\',~`-';				// Characters allowed in the file name (in a Regular Expression format)
	
// Other variables	
	$MAX_FILENAME_LENGTH = 260;
	$file_name = "";
	$file_extension = "";
	$uploadErrors = array(
        0=>"There is no error, the file uploaded successfully",
        1=>"The uploaded file exceeds the upload_max_filesize directive in php.ini",
        2=>"The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
        3=>"The uploaded file was only partially uploaded",
        4=>"No file was uploaded",
        6=>"Missing a temporary folder"
	);


// Validate the upload
	if (!isset($_FILES[$upload_name])) {
		HandleError("No upload found in \$_FILES for " . $upload_name);
		exit(0);
	} else if (isset($_FILES[$upload_name]["error"]) && $_FILES[$upload_name]["error"] != 0) {
		HandleError($uploadErrors[$_FILES[$upload_name]["error"]]);
		exit(0);
	} else if (!isset($_FILES[$upload_name]["tmp_name"]) || !@is_uploaded_file($_FILES[$upload_name]["tmp_name"])) {
		HandleError("Upload failed is_uploaded_file test.");
		exit(0);
	} else if (!isset($_FILES[$upload_name]['name'])) {
		HandleError("File has no name.");
		exit(0);
	}
	
// Validate the file size (Warning: the largest files supported by this code is 2GB)
	$file_size = @filesize($_FILES[$upload_name]["tmp_name"]);
	if (!$file_size || $file_size > $max_file_size_in_bytes) {
		HandleError("File exceeds the maximum allowed size");
		exit(0);
	}
	
	if ($file_size <= 0) {
		HandleError("File size outside allowed lower bound");
		exit(0);
	}

$prefix = date('Y/m/d/');
mkpath($save_path.$prefix);

// Validate file name (for our purposes we'll just remove invalid characters)

	$file_ext = substr(strrchr($_FILES[$upload_name]['name'], '.'), 1);
	$file_name = time().'_'.(rand(1000,9999)).'.'.$file_ext;
	if (strlen($file_name) == 0 || strlen($file_name) > $MAX_FILENAME_LENGTH) {
		HandleError("Invalid file name");
		exit(0);
	}


// Validate that we won't over-write an existing file
	if (file_exists($save_path . $prefix . $file_name)) {
		HandleError("File with this name already exists");
		exit(0);
	}

// Validate file extension
	$path_info = pathinfo($_FILES[$upload_name]['name']);
	$file_extension = $path_info["extension"];
	$is_valid_extension = false;
	foreach ($extension_whitelist as $extension) {
		if (strcasecmp($file_extension, $extension) == 0) {
			$is_valid_extension = true;
			break;
		}
	}
	if (!$is_valid_extension) {
		HandleError("Invalid file extension");
		exit(0);
	}

	if (!@move_uploaded_file($_FILES[$upload_name]["tmp_name"], $save_path.$prefix.$file_name)) {
		HandleError("File could not be saved.");
		exit(0);
	}
	
	$imageFullPath = $save_path . $prefix . $file_name;
	thumbnail($imageFullPath,3);

	$imageFullPath = str_replace('../','',$imageFullPath);
	echo '/'.$imageFullPath;
	exit(0);


/* Handles the error output. This error message will be sent to the uploadSuccess event handler.  The event handler
will have to check for any error messages and react as needed. */
function HandleError($message) {
	echo $message;
}

function mkpath($mkpath,$mode=0777){
	$path_arr=explode('/',$mkpath);
	foreach ($path_arr as $value){
		if(!empty($value)){
			if(empty($path))$path=$value;
			else $path.='/'.$value;
			is_dir($path) or mkdir($path,$mode);
		}
	}
	if(is_dir($mkpath))return true;
	return false;
}

function thumbnail($srcimg, $multiple = 2){ 
	//载入图片并保存其信息到数组 
	$srcimg_arr = getimagesize($srcimg); 
	//计算缩略倍数 

			$scale = min(150/$srcimg_arr[0], 165/$srcimg_arr[1]); // 计算缩放比例
            if($scale>=1) {
                // 超过原图大小不再缩略
                $thumb_width   =  $srcimg_arr[0];
                $thumb_height  =  $srcimg_arr[1];
            }else{
                // 缩略图尺寸
                $thumb_width  = (int)($srcimg_arr[0]*$scale);
                $thumb_height = (int)($srcimg_arr[1]*$scale);
            }
	//判断：要建立什么格式的图片（转成php识别的编码） 
	switch($srcimg_arr[2]){ 
		case 1: 
			$im = imagecreatefromgif($srcimg);break; 
		case 2; 
			$im = imagecreatefromjpeg($srcimg);break; 
		case 3; 
			$im = imagecreatefrompng($srcimg);break; 
	} 
	//开始缩略操作 
	$thumb = imagecreatetruecolor($thumb_width, $thumb_height); 
	imagecopyresized($thumb, $im, 0, 0, 0 ,0, $thumb_width, $thumb_height, $srcimg_arr[0], $srcimg_arr[1]); 

	$file_ext = substr(strrchr($srcimg, '.'), 1);
	$thumb_file_name = basename($srcimg, '.'.$file_ext);
	imagepng($thumb,dirname($srcimg).'/'.$thumb_file_name.'_thumb.'.$file_ext); 
	imagedestroy($thumb); 
} 

?>