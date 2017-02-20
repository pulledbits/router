<?php
header("Content-type: image/png");
$height = 100;
$im = imagecreatetruecolor(500, $height);
imagealphablending($im, false);
imagesavealpha($im, true);
$transparent = imagecolorallocatealpha($im, 255, 255, 255, 127);
imagefilledrectangle($im, 0, 0, 500, $height, $transparent);
$star = imagecreatefromstring($starData);
$unstar = imagecreatefromstring($unstarData);
for ($i = 0; $i < 5; $i ++) {
    if ($i < $rating) {
        $source = $star;
    } else {
        $source = $unstar;
    }
    if (imagecopyresampled($im, $source, 100 * $i, ($height / 2) - (100 / 2), 0, 0, 100, 100, imagesx($source), imagesy($source)) === false) {
        exit('fail to copy image');
    }
}
imagepng($im, null, 0);
imagedestroy($im);
imagedestroy($star);