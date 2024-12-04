<?php
@session_start();

$js_path="../";
$idup=strip_tags($_REQUEST['idup']);

if (!isset($_SESSION[$idup])) {
	echo "invalid";
	exit;
}

$sup=$_SESSION[$idup];
$cap=$_SESSION[$idup]['cap'];

echo $idup;
echo "
<!DOCTYPE html>
<html>
    <head>
        <title>$idup</title>
        <meta content='text/html; charset=UTF-8' http-equiv='Content-Type' />
        <script type='text/javascript' src='$js_path"."jquery/jquery.js'></script>
        <script type='text/javascript' src='$js_path"."uploader1/js/uploader1.js'></script>
        <!--link href='$js_path"."fonts/font-awesome.min.css' rel='stylesheet'/-->
        <link rel='stylesheet' href='css/style.css' />
    </head>
    <body>
        <div class='main-container'>
            <div class='section'>
                <form id='ajax-upload-form' enctype='multipart/form-data'>
                    <div class='row'>
                        
						<div class='col-10'>
                            <input type='file' class='file-input' name='ajax_file' multiple='multiple'/>
                        </div>
                        <div class='col-2 text-right'>
                            <button type='submit' class='btn btn-blue'> Upload</button>
                        </div>
                    </div>
                <input type='hidden' name='idup' value='$idup'>
				</form>
                <div class='progress-container'></div>
            </div>
        </div>
    </body>
</html>";
