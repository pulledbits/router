<?php
$renderer = new \BaconQrCode\Renderer\Image\Png();
$renderer->setHeight(400);
$renderer->setWidth(400);
$writer = new \BaconQrCode\Writer($renderer);

header('Content-Type: image/png');
print $writer->writeString($data);
exit();