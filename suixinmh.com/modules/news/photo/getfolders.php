<?php 
function getImages($path, $galleryName) {

  $files = array();
   $fileNames = array();
   $i = 0;
   
   if (is_dir($path)) {
       if ($dh = opendir($path)) {
           while (($file = readdir($dh)) !== false) {
               if ($file == "." || $file == ".." || $file == ".DS_Store") continue;
               $fullpath = $path . "/" . $file;
               $fkey = strtolower($file);
               while (array_key_exists($fkey,$fileNames)) $fkey .= " ";
               $a = stat($fullpath);
               $files[$fkey]['size'] = $a['size'];
               if ($a['size'] == 0) $files[$fkey]['sizetext'] = "-";
               else if ($a['size'] > 1024) $files[$fkey]['sizetext'] = (ceil($a['size']/1024*100)/100) . " K";
               else if ($a['size'] > 1024*1024) $files[$fkey]['sizetext'] = (ceil($a['size']/(1024*1024)*100)/100) . " Mb";
               else $files[$fkey]['sizetext'] = $a['size'] . " bytes";
               $files[$fkey]['name'] = $file;
               $files[$fkey]['type'] = filetype($fullpath);
               $fileNames[$i++] = $fkey;
           }
           closedir($dh);
       } else die ("Cannot open directory:  $path");
   } else die ("Path is not a directory:  $path");
   sort($fileNames,SORT_STRING);
   $sortedFiles = array();
   $i = 0;
   $photos = array();
   foreach($fileNames as $f) $sortedFiles[$i++] = $files[$f];
   
   	
	echo "var $galleryName = new Array() \n";

   $j = 0;
   foreach ($sortedFiles as $file) {
   	
	// get image sizes 
	list($width, $height, $type, $attr) = getimagesize($path."/".$file[name], $info);
	$size = $file[sizetext];
	$iptc = iptcparse($info["APP13"]);
	// iptc  info
	$iptc = iptcparse($info["APP13"]);
	$title = $iptc['2#005'][0];
	$description = $iptc['2#120'][0];
	$description = str_replace("\r", "<br/>", $description);
	$description = addslashes($description);
	$keywords = $iptc['2#025'][0];
	$author = $iptc['2#080'][0];
	$copyright = $iptc['2#116'][0];
		
		array_push($photos, $width);
		array_push($photos, $height);
		echo "$galleryName";echo"[".$j."]=['".$path."/".$file[name]."', '".$width."', '".$height."', '".$size."', '".$title."', '".$author."', '".$copyright."', '".$description."', '".$j."']\n";
		$j++;
	}
	echo "\n";
	echo "var currentwidth=".$photos[0].";\n";
	echo "var currentheight=".$photos[1].";";
	echo "\n";

}


function getfirstimage($path, $galleryName) {

  $files = array();
   $fileNames = array();
   $i = 0;
   
   if (is_dir($path)) {
       if ($dh = opendir($path)) {
           while (($file = readdir($dh)) !== false) {
               if ($file == "." || $file == ".." || $file == ".DS_Store") continue;
               $fullpath = $path . "/" . $file;
               $fkey = strtolower($file);
               while (array_key_exists($fkey,$fileNames)) $fkey .= " ";
               $a = stat($fullpath);
               $files[$fkey]['size'] = $a['size'];
               if ($a['size'] == 0) $files[$fkey]['sizetext'] = "-";
               else if ($a['size'] > 1024) $files[$fkey]['sizetext'] = (ceil($a['size']/1024*100)/100) . " K";
               else if ($a['size'] > 1024*1024) $files[$fkey]['sizetext'] = (ceil($a['size']/(1024*1024)*100)/100) . " Mb";
               else $files[$fkey]['sizetext'] = $a['size'] . " bytes";
               $files[$fkey]['name'] = $file;
               $files[$fkey]['type'] = filetype($fullpath);
               $fileNames[$i++] = $fkey;
           }
           closedir($dh);
       } else die ("Cannot open directory:  $path");
   } else die ("Path is not a directory:  $path");
   sort($fileNames,SORT_STRING);
   $sortedFiles = array();
   $i = 0;
   $photoarray = array();
   $photoinfo = array();
   foreach($fileNames as $f) $sortedFiles[$i++] = $files[$f];
   echo'<div id="imgloader">';
   
   $j=0;
   foreach ($sortedFiles as $file) {
   	
	// get image sizes 
	list($width, $height, $type, $attr) = getimagesize("$path/$file[name]", $info);
	$size = $file[sizetext];
	$iptc = iptcparse($info["APP13"]);
	// iptc  info
	$iptc = iptcparse($info["APP13"]);
	$title = $iptc['2#005'][0];
	$description = $iptc['2#120'][0];
	$description = str_replace("\r", "<br/>", $description);
	$description = addslashes($description);
	$keywords = $iptc['2#025'][0];
	$author = $iptc['2#080'][0];
	$copyright = $iptc['2#116'][0];
	
		array_push($photoarray, '<img src="'.$path.'/'.$file[name].'" width="'.$width.'" height="'.$height.'" alt="'.$title.'"  />');
		array_push($photoinfo, $title);
		array_push($photoinfo, $author);
		array_push($photoinfo, $copyright);
		array_push($photoinfo, $description);		
	}
	echo ' </div>
		   <div id="iptc_info">
  		    <div class="iptc_left"><br/><br/>
				Title <br/>
				Author <br/>
				Copyright <br/><br/>
				Description <br/><br/>
			</div>
			<div class="iptc_right"><br/><br/>
				'.$photoinfo[0].'<br/>
				'.$photoinfo[1].'<br/>
				'.$photoinfo[2].'<br/><br/>
				'.$photoinfo[3].'<br/><br/>
			</div>
		  </div>	
			';
}

function getthumbnailimages($path, $galleryName, $thumbnailpath) {

  $files = array();
   $fileNames = array();
   $i = 0;
   
   if (is_dir($path)) {
       if ($dh = opendir($path)) {
           while (($file = readdir($dh)) !== false) {
               if ($file == "." || $file == ".." || $file == ".DS_Store") continue;
               $fullpath = $path . "/" . $file;
               $fkey = strtolower($file);
               while (array_key_exists($fkey,$fileNames)) $fkey .= " ";
               $a = stat($fullpath);
               $files[$fkey]['size'] = $a['size'];
               if ($a['size'] == 0) $files[$fkey]['sizetext'] = "-";
               else if ($a['size'] > 1024) $files[$fkey]['sizetext'] = (ceil($a['size']/1024*100)/100) . " K";
               else if ($a['size'] > 1024*1024) $files[$fkey]['sizetext'] = (ceil($a['size']/(1024*1024)*100)/100) . " Mb";
               else $files[$fkey]['sizetext'] = $a['size'] . " bytes";
               $files[$fkey]['name'] = $file;
               $files[$fkey]['type'] = filetype($fullpath);
               $fileNames[$i++] = $fkey;
           }
           closedir($dh);
       } else die ("Cannot open directory:  $path");
   } else die ("Path is not a directory:  $path");
   sort($fileNames,SORT_STRING);
   $sortedFiles = array();
   $i = 0;
   $thumbs = array();
   foreach($fileNames as $f) $sortedFiles[$i++] = $files[$f];
   $j=0;
   foreach ($sortedFiles as $file) {
   	
	// get image sizes 
	list($width, $height, $type, $attr) = getimagesize("$path/$file[name]", $info);
	$size = $file[sizetext];
	$iptc = iptcparse($info["APP13"]);
	// iptc  info
	$iptc = iptcparse($info["APP13"]);
	$title = $iptc['2#005'][0];
	$description = $iptc['2#120'][0];
	$description = str_replace("\r", "<br/>", $description);
	$description = addslashes($description);
	$keywords = $iptc['2#025'][0];
	$author = $iptc['2#080'][0];
	$copyright = $iptc['2#116'][0];
	
		array_push($thumbs, '<a href="#" onclick="getstarted('.$width.', '.$height.', \'imgloader\', '.$j.', \''.$j.'\', current_imgid);return false;"><img src="'.$thumbnailpath.'/'.$file[name].'" alt="'.$title.'" title="'.$title.'" tooltitle="'.$title.'" class="toolTipImg" /></a>');
		echo $thumbs[$j];
		$j++;
	}
	
}


?>



