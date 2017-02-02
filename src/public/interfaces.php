<?php
exec("ipconfig", $ipconfigData, $exitCode);
if ($exitCode !== 0) {
    exit('failed retrieving ipconfig');
}
$ipv4Adresses = [];
foreach ($ipconfigData as $line) {
    if (preg_match('/IPv4 Address(\.\s)+:\s(?<ipv4>\d+\.\d+\.\d+\.\d+)/', $line, $matches) === 1) {
        $ipv4Adresses[] = $matches['ipv4'];
    }
}

foreach ($ipv4Adresses as $ipv4Adress) {
    print '<a href="http://' . htmlentities($ipv4Adress) . '/feedback">' . htmlentities($ipv4Adress) . '</a><br />';
}