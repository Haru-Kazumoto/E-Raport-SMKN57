
function previewYT(tempat,urlYoutube,sSize){
	if (sSize==undefined) sSize=640;
	if (urlYoutube.indexOf("/embed/")<0){
		idy=urlYoutube;
		idy=idy.replaceAll("https://youtu.be/","");
		idy=idy.replaceAll("https://youtube.com/watch/v=","");
		urlYoutube="https://www.youtube.com/embed/"+idy+"?enablejsapi=1&version=3&playerapiid=ytplayer";
	}
	
	wIFrame=sSize;
	hIFrame=(240/320)*sSize;
	
	sOption=" height='"+hIFrame+"'  width='"+wIFrame+"'";
	html="<iframe id='iframe1' "+sOption+" src='"+urlYoutube+"' frameborder='1' allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe> ";
	
	$("#"+tempat).html(html);
	$("#"+tempat).dialog({width:700});
}


// For testing.
var urlsY = [
    '//www.youtube-nocookie.com/embed/up_lNV-yoK4?rel=0',
    'http://www.youtube.com/user/Scobleizer#p/u/1/1p3vcRhsYGo',
    'http://www.youtube.com/watch?v=cKZDdG9FTKY&feature=channel',
    'http://www.youtube.com/watch?v=yZ-K7nCVnBI&playnext_from=TL&videos=osPknwzXEas&feature=sub',
    'http://www.youtube.com/ytscreeningroom?v=NRHVzbJVx8I',
    'http://www.youtube.com/user/SilkRoadTheatre#p/a/u/2/6dwqZw0j_jY',
    'http://youtu.be/6dwqZw0j_jY',
    'http://www.youtube.com/watch?v=6dwqZw0j_jY&feature=youtu.be',
    'http://youtu.be/afa-5HQHiAs',
    'http://www.youtube.com/user/Scobleizer#p/u/1/1p3vcRhsYGo?rel=0',
    'http://www.youtube.com/watch?v=cKZDdG9FTKY&feature=channel',
    'http://www.youtube.com/watch?v=yZ-K7nCVnBI&playnext_from=TL&videos=osPknwzXEas&feature=sub',
    'http://www.youtube.com/ytscreeningroom?v=NRHVzbJVx8I',
    'http://www.youtube.com/embed/nas1rJpm7wY?rel=0',
    'http://www.youtube.com/watch?v=peFZbP64dsU',
    'http://youtube.com/v/dQw4w9WgXcQ?feature=youtube_gdata_player',
    'http://youtube.com/vi/dQw4w9WgXcQ?feature=youtube_gdata_player',
    'http://youtube.com/?v=dQw4w9WgXcQ&feature=youtube_gdata_player',
    'http://www.youtube.com/watch?v=dQw4w9WgXcQ&feature=youtube_gdata_player',
    'http://youtube.com/?vi=dQw4w9WgXcQ&feature=youtube_gdata_player',
    'http://youtube.com/watch?v=dQw4w9WgXcQ&feature=youtube_gdata_player',
    'http://youtube.com/watch?vi=dQw4w9WgXcQ&feature=youtube_gdata_player',
    'http://youtu.be/dQw4w9WgXcQ?feature=youtube_gdata_player'
];

var i, r, rx = /^.*(?:(?:youtu\.be\/|v\/|vi\/|u\/\w\/|embed\/)|(?:(?:watch)?\?v(?:i)?=|\&v(?:i)?=))([^#\&\?]*).*/;

function testingY(){
	for (i = 0; i < urlsY.length; ++i) {
		r = urls[i].match(rx);
		//console.log(r[1]);
	}
}


/**
* Get YouTube ID from various YouTube URL
* @author: takien
* @url: http://takien.com
* For PHP YouTube parser, go here http://takien.com/864
*/

function YouTubeGetID(url){
  var ID = '';
  url = url.replace(/(>|<)/gi,'').split(/(vi\/|v=|\/v\/|youtu\.be\/|\/embed\/)/);
  if(url[2] !== undefined) {
    ID = url[2].split(/[^0-9a-z_\-]/i);
    ID = ID[0];
  }
  else {
    ID = url;
  }
    return ID;
}

function createIframeYT(idPlayer,xVideoId,xWidth,Height){
	 if (xVideoId==undefined) xVideoId= 'GPotpP5ZSpw';
	 if (xWidth=undefined) xWidth=910;
	 if (xHeight==undefined) xHeight=682.5;
	 if (xIdPlayer==undefined) idPlayer='player1';
		var tag = document.createElement('script');
		tag.src = 'https://www.youtube.com/iframe_api';
		var firstScriptTag = document.getElementsByTagName('script')[0];
		firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
 
		var player1;
		function onYouTubeIframeAPIReady() {
			player1 = new YT.Player(idPlayer, {
				  height: xHeight, 
				  width: xWidth, 
				  videoId: xVideoId,
				  events: {
					'onReady': onPlayerReady,
					'onStateChange': onPlayerStateChange
				  }
			});
		}

	 
		function onPlayerReady(event) {
			event.target.playVideo();
		}

	 
		var done = false;
		function onPlayerStateChange(event) {
			if (event.data == YT.PlayerState.PLAYING && !done) {
			  //setTimeout(stopVideo, 16000);
			  done = true;
			}
		}
		function stopVideo() {
			player1.stopVideo();
		}
		";
	
}


/*
* Tested URLs:
var url = 'http://youtube.googleapis.com/v/4e_kz79tjb8?version=3';
url = 'https://www.youtube.com/watch?feature=g-vrec&v=Y1xs_xPb46M';
url = 'http://www.youtube.com/watch?feature=player_embedded&v=Ab25nviakcw#';
url = 'http://youtu.be/Ab25nviakcw';
url = 'http://www.youtube.com/watch?v=Ab25nviakcw';
url = '<iframe width="420" height="315" src="http://www.youtube.com/embed/Ab25nviakcw" frameborder="0" allowfullscreen></iframe>';
url = '<object width="420" height="315"><param name="movie" value="http://www.youtube-nocookie.com/v/Ab25nviakcw?version=3&amp;hl=en_US"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube-nocookie.com/v/Ab25nviakcw?version=3&amp;hl=en_US" type="application/x-shockwave-flash" width="420" height="315" allowscriptaccess="always" allowfullscreen="true"></embed></object>';
url = 'http://i1.ytimg.com/vi/Ab25nviakcw/default.jpg';
url = 'https://www.youtube.com/watch?v=BGL22PTIOAM&feature=g-all-xit';
url = 'BGL22PTIOAM';
*/