<?php 
if(date_default_timezone_set('Asia/Jakarta') == 0) {
            print "<!-- Error uknown timezone using UTC as default -->\n";
            date_default_timezone_set('UTC');
}
phpinfo();
 ?>