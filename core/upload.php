<?php

if(isset($_POST['acf_post']))
{
	if($_FILES['acf_image']['name'] == '')
	{ 
		//echo '<div class="result">0</div>';
	}
	else
	{
		require_once('../../../../wp-load.php');
		require_once('../../../../wp-admin/admin.php');

		$override = array('test_form' => false);
		$file = wp_handle_upload( $_FILES['acf_image'], $override );
		//echo '<div class="result">'.$file['url'].'</div>';
	}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="utf-8" />
	<title>Upload</title>

	<style type="text/css">
		body {
			padding: 0; 
			margin: 0;
		}
		
		.result {display: none;}
		
		form {
			display: block;
			position: relative;
			overflow: hidden;
			padding: 2px;
			float: left;
		}
	
	</style>

</head>
<body>

<?php
if(isset($_POST['acf_post']) && $_FILES['acf_image']['name'] == '')
{
	echo '<div class="result">0</div>';
}

if(!empty($file))
{
	echo '<div class="result">'.$file['url'].'</div>';
}
?>

<form id="acf_upload" method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" name="acf_upload" enctype="multipart/form-data">

<input type="hidden" name="acf_post" value="true" /> 
<input type="file" name="acf_image" id="acf_image" /> 
<input type="submit" class="button" value="Upload" />

</form>

</body>
</html>
