<?php
if ($media=='print') {
	echo "	</div>";//akhir class=page
	//include_once $js_path."um412-jsfunc-v21.php";
	echo "

	<script>
	//menghapus link....
	function removeTagsOnly(tag) {
		var b = document.getElementsByTagName(tag);
	
		while(b.length) {
			var parent = b[ 0 ].parentNode;
			while( b[ 0 ].firstChild ) {
				parent.insertBefore(  b[ 0 ].firstChild, b[ 0 ] );
			}
			 parent.removeChild( b[ 0 ] );
		}
	}
	
	removeTagsOnly('a');
	window.print();
	//window.close();
	</script>
	";
	}
		


?>