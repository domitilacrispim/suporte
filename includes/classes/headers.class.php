<?php

class headers {

	function set_title($param){
		print "<html>\n<head><title>$param</title></head><body>";	
	}

	function set_foot (){
	
		print "\n</body>\n</html>";
	}


}


?>