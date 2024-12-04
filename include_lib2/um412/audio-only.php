<?php
cekVar("nf,jenis");


if ($nf=="") {
	echo "no file selected";
	exit;
} else {
 
	$nf="../sekolah/file/$jenis/$nf";
	if (file_exists($nf)) {
		
	} else {
		//echo "File $nf tidak ditemukan....";
		//exit;
	}
}

?>

<!DOCTYPE html>
    <html lang="fr">
        <head>  
            <meta charset="utf-8">
            <title>Clean jPlayer skin: Example</title>   
           
            <link rel="stylesheet" href="<?=$tppath?>plugins/jPlayer2/player.css">
            <script type="text/javascript" src="<?=$tppath?>plugins/jPlayer/lib/jquery.min.js"></script>
            <script type="text/javascript" src="<?=$tppath?>plugins/jPlayer2/jquery.jplayer.js"></script>
            <script type="text/javascript" src="<?=$tppath?>plugins/jPlayer2/jplayer.cleanskin.js"></script>
            <script type="text/javascript">
                $(document).ready(function(){
                    $(document).find('.webPlayer').each(function() { $('#'+this.id).videoPlayer(); });
                });
            </script>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>
        
<body bgcolor="#fff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div class="box">
			<div id="uniquePlayer-5" class="webPlayer light audioPlayer">
				<div id="uniqueContainer-5" class="videoPlayer"></div>
				<div style="display:none;" class="playerData">
					{
					"name": "<?=$nf?>",
					"autoplay": "false",
					"size": {
					"width": "510px" },
					"media": {
					"mp3": "<?=$nf?>"
					}
					}
				</div>
			</div>
		</div>
        </body>
</html>