<?php
session_start();

$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
  case 'POST':
    if (isset($_POST['url']) && !empty($_POST['url'])) {
	    exec('pgrep youtube-dl', $pids);  //youtube-dl process
	    exec('pgrep avconv', $pids);
	    if (empty($pids)) {
	       exec("touch /var/log/airpi/youtube-to-mp3.log ; echo `date`: ".$_SERVER['REMOTE_ADDR']." ".escapeshellarg($_POST['url'])." >> /var/log/airpi/youtube-to-mp3.log");
               $command = exec("youtube-dl --no-warnings --no-cache-dir --no-overwrites --no-playlist --no-part --no-post-overwrites -x --audio-format 'mp3' ".escapeshellarg($_POST['url'])." -o '%(title)s.%(ext)s' --restrict-filenames >> /var/log/airpi/youtube-to-mp3.log 2>&1 && echo 'success' || echo 'fail'");
               if ($command == 'success') {
                   $_SESSION['file'] = shell_exec("youtube-dl --no-warnings --no-cache-dir --get-filename --no-overwrites --no-playlist --no-part --no-post-overwrites ".escapeshellarg($_POST['url'])." -o '%(title)s' --restrict-filenames 2>&1");
                   $_SESSION['file'] = str_replace(PHP_EOL, '', $_SESSION['file']);
	           $res = '{"code":1, "file":"'.$_SESSION['file'].'","message":"Download successful"}';
	       }
	       else {
		   $res = '{"code":0, "message":"Your video is not available for download at the moment, please try again later"}';
	       }
	    } else {
	       $res = '{"code":0, "mesage":"The server is overloaded"}';
	    }
	    echo $res;
    }
    exit(0);
    break;
  default:
    break;
}

if (isset($_GET['download']) && !empty($_GET['download']) && $_GET['download'] == "1") {
    if (isset($_SESSION['file']) && !empty($_SESSION['file'])) {
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary"); 
        header("Content-disposition: attachment; filename=\"" . $_SESSION['file'] . ".mp3\""); 
        readfile($_SESSION['file'].'.mp3');
        exec("rm ".escapeshellarg($_SESSION['file']).".* -f");
        unset($_SESSION['file']);
    }
    else {
        echo "Nothing to download. Each MP3 is only available once! Try to download again!";
    }
    exit(0);
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1.0" />
    <meta name="robots" content="noindex, nofollow, noarchive"> <!-- Prevent indexing if connected to the Internet -->
    <title>Youtube to MP3</title>
    <meta name="viewport" content="initial-scale=1.0" />
    <link rel="stylesheet" media="all" href="style.css" />
    <script src="jquery-1.10.2.min.js" type="text/javascript"></script>
    <script type="text/javascript">
  	function ajaxx() {
	    $('#loader').css("visibility", "visible");
	    $('a').css("visibility", "hidden");
	    $('input[type="submit"]').prop('disabled', true);
	    $.ajax({
	        type: "POST",
	        url: 'index.php',
	        data: $('#link').serialize(),
	        dataType: "json",
	        timeout: 900000, // 15min
	        success: function(data, textStatus, jqXHR) {
	            $('#loader').css("visibility", "hidden");
	            $('input[type="submit"]').prop('disabled', false);
	            if (data.code == "1") {
	                $('a').css("visibility", "visible");
	                alert(data.message + ": "+data.file);
	                window.location.href = "?download=1";
	            }
	            else {
	                alert(data.message);
	            }
	        },
	        error: function(jqXHR, textStatus, errorThrown) {
	           console.log("Upload failed: " + textStatus);
	           $('#loader').css("visibility", "hidden");
               $('input[type="submit"]').prop('disabled', false);
               $('a').css("visibility", "hidden");
	           alert("Someting wrong happened, please email-me the following details: "+textStatus+" ; code : "+errorThrown);
	       }
	    });
	    alert("Download started... Please wait till a pop-up appears!");
	  }
	  $(function() {
		$("form").submit(function(e){
     	  e.preventDefault();
		    ajaxx();
		  });
	  });
    </script>
</head>
<body>
<form action="" method="POST" >
	<input type="text" id="link" name="url" placeholder="Paste URL here" /><input type="submit" value="Download MP3" name="submit" />
</form>
<br>
<br>
<img id="loader" style="visibility: hidden;" src="ajax.gif" />
<br>
<a target="_blank" href="?download=1" title="Click-me if the download hasn't started automatically" >Click-me if the download hasn't started automatically</a>
<div id="contact">If you encounter problems with a video, please send me the URL via email</div>
</body>
</html>
