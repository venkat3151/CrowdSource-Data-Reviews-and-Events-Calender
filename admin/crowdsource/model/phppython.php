

<?php

$command = escapeshellcmd('python3.6 splitFiles.py ../uploads/test.csv 3 dfhsg');
$output = shell_exec($command);
echo $output;
?>