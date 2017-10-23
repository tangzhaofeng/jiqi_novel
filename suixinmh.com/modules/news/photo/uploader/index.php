<?php 
require("../config.php"); 
?> 
<?php 
$logok=0; 
$uploading=$_POST['uploading'];
if (isset($_POST['submit'])) 
{ 
for ($i=0;$i<sizeof($users);$i++) 
{ 
if ($users[$i]==$_POST['username']) 
{ 
if ($passw[$i]==$_POST['password']) 
{ 
$logok=1; 
} 
} 
}
if ($logok==1 && $uploading=="") 
{ 
echo 
'
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="rsrc/style.css" rel="stylesheet" type="text/css">
<title>Upload Images</title>
</head>
<body>
<div class="e2_photo_gallery">
  <div class="hd">
    <div class="c"></div>
  </div>
  <div class="bd">
    <div class="c">
      <div class="s">
        <!-- Content -->
			 <fieldset>
				
				  <legend><h2>Select files to upload <img src="rsrc/e2.png" align="absmiddle" /></h2></legend>
				<form name="form3" enctype="multipart/form-data" method="post" action="'.$_SERVER['PHP_SELF'] .'">
					<p>Image Folders Location <input type="text" size="32" name="folder" value="" /></p>
					<p>Thumbnail Folder Location <input type="text" size="32" name="thumbfolder" value="" /></p>
					<div class="float50">
					<p><input type="file" size="25" name="my_field[]" value="" /> 
					<br />
					  Crop Thumb 
					  <select name="crop1">
							<option value="C">Center</option>
							<option value="L">Left (horizotal Images)</option>	
							<option value="R">Right (horizotal Images)</option>
							<option value="T">Top (vertical images)</option>
							<option value="B">Bottom (vertical images)</option>
					  </select></p>
					<p><input type="file" size="25" name="my_field[]" value="" /><br />
						Crop Thumb 
						<select name="crop2">
							<option value="C">Center</option>
							<option value="L">Left (horizotal Images)</option>	
							<option value="R">Right (horizotal Images)</option>
							<option value="T">Top (vertical images)</option>
							<option value="B">Bottom (vertical images)</option>
						</select>
					</p>
					<p><input type="file" size="25" name="my_field[]" value="" /><br />
						Crop Thumb 
						<select name="crop3">
							<option value="C">Center</option>
							<option value="L">Left (horizotal Images)</option>	
							<option value="R">Right (horizotal Images)</option>
							<option value="T">Top (vertical images)</option>
							<option value="B">Bottom (vertical images)</option>
						</select>
					</p>
					</div>
					<div class="float50">
					<p><input type="file" size="25" name="my_field[]" value="" /><br />
						Crop Thumb 
						<select name="crop4">
							<option value="C">Center</option>
							<option value="L">Left (horizotal Images)</option>	
							<option value="R">Right (horizotal Images)</option>
							<option value="T">Top (vertical images)</option>
							<option value="B">Bottom (vertical images)</option>
						</select>
					</p>
					<p><input type="file" size="25" name="my_field[]" value="" /><br />
						Crop Thumb 
						<select name="crop5">
							<option value="C">Center</option>
							<option value="L">Left (horizotal Images)</option>	
							<option value="R">Right (horizotal Images)</option>
							<option value="T">Top (vertical images)</option>
							<option value="B">Bottom (vertical images)</option>
						</select>
					</p>
					<p><input type="file" size="25" name="my_field[]" value="" /><br />
						Crop Thumb 
						<select name="crop6">
							<option value="C">Center</option>
							<option value="L">Left (horizotal Images)</option>	
							<option value="R">Right (horizotal Images)</option>
							<option value="T">Top (vertical images)</option>
							<option value="B">Bottom (vertical images)</option>
						</select>
					</p>
					</div>
					<div class="clear"></div>
					<p class="button"><input type="hidden" name="type" value="multiple" />
					<input type="hidden" name="uploading" value="yes" />
					<input type="hidden" name="username" value="'.$users[0].'"> 
					<input type="hidden" name="password" value="'.$passw[0].'"> 
					<input type="submit" name="submit" value="submit" /> </p>
				</form>
			</fieldset>
        <!-- Content -->
      </div>
    </div>
  </div>
  <div class="ft">
    <div class="c"></div>
  </div>
</div>
</div>
</body> 
</html>
'; 
} 
else if($logok==1 && $uploading=="yes")
{ 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="rsrc/style.css" rel="stylesheet" type="text/css">
<title>Images Uploaded</title>
</head>
<body>
<div class="e2_photo_gallery">
  <div class="hd">
    <div class="c"></div>
  </div>
  <div class="bd">
    <div class="c">
      <div class="s">
        <!-- Content -->
<h2>File Upload Results <img src="rsrc/e2.png" align="absmiddle" /></h2>
	

    <?php
echo 
'<p>
	<form name="form1" method="post" action="'.$_SERVER['PHP_SELF'] .'">  
		<input type="hidden" name="username" value="'.$users[0].'"> 
		<input type="hidden" name="password" value="'.$passw[0].'"> 
		<input name="submit" value=submit type="image" id="submit" src="rsrc/return.png" alt="Submit" class="highlight"  />
    </form>
	<a href="'.$_SERVER['PHP_SELF'] .'"><img src="rsrc/logout.png" /></a>
</p>
';
error_reporting(E_ALL); 

// we first include the upload class, as we will need it here to deal with the uploaded file
include_once'class.upload.php';

if ($_POST['type'] == 'multiple') {
	
	$crop1=$_POST['crop1'];
	$crop2=$_POST['crop2'];
	$crop3=$_POST['crop3'];
	$crop4=$_POST['crop4'];
	$crop5=$_POST['crop5'];
	$crop6=$_POST['crop6'];
	$croparray=array($crop1, $crop2, $crop3, $crop4, $crop5, $crop6);
	$folder=$_POST['folder'];
	$thumbs=$_POST['thumbfolder'];
	
    // ---------- MULTIPLE UPLOADS ----------

    // as it is multiple uploads, we will parse the $_FILES array to reorganize it into $files
    $files = array();
    foreach ($_FILES['my_field'] as $k => $l) {
        foreach ($l as $i => $v) {
            if (!array_key_exists($i, $files)) 
                $files[$i] = array();
            $files[$i][$k] = $v;
        }
    }
	$j=0;
    // now we can loop through $files, and feed each element to the class
    foreach ($files as $file) {
    	$crop=$croparray[$j];
		$j++;		
        // we instanciate the class for each element of $file
        $handle = new Upload($file);
        
        // then we check if the file has been uploaded properly
        // in its *temporary* location in the server (often, it is /tmp)
        if ($handle->uploaded) {
			
			
			$handle->image_resize          = true;
			$handle->image_ratio           = true;
			$handle->image_y               = 600;
			$handle->image_x               = 600;
			$handle->jpeg_quality		   = 95;
            // now, we start the upload 'process'. That is, to copy the uploaded file
            // from its temporary location to the wanted location
            // It could be something like $handle->Process('/home/www/my_uploads/');
            $handle->Process($folder);
            // we check if everything went OK
            if ($handle->processed) {
                // everything was fine !
                echo '<fieldset>';
                echo '  <legend><h3>Large Image Uploaded</h3></legend>';
                echo '  <p>' . round(filesize($handle->file_dst_pathname)/256)/4 . 'KB</p>';
                echo '  View Image Uploaded: <a href="'.$folder.'' . $handle->file_dst_name . '">' . $handle->file_dst_name . '</a>';
                echo '</fieldset>';
            } else {
                // one error occured
                echo '<fieldset>';
                echo '  <legend><h3>Large Image Not Uploaded</h3></legend>';
                echo '  Error: ' . $handle->error . '';
                echo '</fieldset>';
            }
			$handle->image_resize          = true;
			$handle->image_y               = 100;
			$handle->image_x               = 100;
			$handle->jpeg_quality		   = 95;
			if($crop=="C"){
				$handle->image_ratio_crop      = true;
			}
			if($crop=="L"){
				$handle->image_ratio_crop      = "L";
			}
			if($crop=="R"){
				$handle->image_ratio_crop      = "R";
			}
			if($crop=="T"){
				$handle->image_ratio_crop      = "T";
			}
			if($crop=="B"){
				$handle->image_ratio_crop      = "B";
			}
            // now, we start the upload 'process'. That is, to copy the uploaded file
            // from its temporary location to the wanted location
            // It could be something like $handle->Process('/home/www/my_uploads/');
            $handle->Process($thumbs);

            // we check if everything went OK
            if ($handle->processed) {
                // everything was fine !
                echo '<fieldset>';
                echo '  <legend><h3>Thumbnail Created and Uploaded</h3></legend>';
                echo '  <p>' . round(filesize($handle->file_dst_pathname)/256)/4 . 'KB</p>';
                echo '  View Thumbnail Uploaded: <a href="'.$thumbs.'' . $handle->file_dst_name . '">' . $handle->file_dst_name . '</a>';
                echo '</fieldset>';
            } else {
                // one error occured
                echo '<fieldset>';
                echo '  <legend><h3>Thumbnail not created and not uploaded</h3></legend>';
                echo '  Error: ' . $handle->error . '';
                echo '</fieldset>';
            }
            
        } else {
      		
        }
    }
    
} 

echo 
'<p>
	<form name="form1" method="post" action="'.$_SERVER['PHP_SELF'] .'">  
		<input type="hidden" name="username" value="'.$users[0].'"> 
		<input type="hidden" name="password" value="'.$passw[0].'"> 
		<input name="submit" value=submit type="image" id="submit" src="rsrc/return.png" alt="Submit" class="highlight"  />
    </form>
	<a href="'.$_SERVER['PHP_SELF'] .'"><img src="rsrc/logout.png" /></a>
</p>
';

?>
        <!-- Content -->
      </div>
    </div>
  </div>
  <div class="ft">
    <div class="c"></div>
  </div>
</div>
</div>
</body> 
</html>

<?php
} 
else 
{ 
echo 'WRONG DETAILS - <a href="'.$_SERVER['PHP_SELF'] .'">CLICK TO TRY AGAIN</a>'; 
} 
} 
else 
{ 
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title> Password loging</title> 
<link href="rsrc/style.css" rel="stylesheet" type="text/css">
</head> 
<body> 
<div id="login">
	<form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF'] ;?>"> 
		<label id="username">Username</label><br />
		<input type="text" name="username"><br /> 
		<label id="password">Password</label><br />
		<input type="password" name="password"><br> 
		<input type="submit" name="submit" value="Login"> 
	</form>
</div>
</body> 
</html> 
<?php 
} 
?> 