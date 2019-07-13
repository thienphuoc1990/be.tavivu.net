< ?php

$linkName = '/home/taviv713/public_html/be.tavivu.net/public/storage/images';

$target = '/home/taviv713/public_html/be.tavivu.net/storage/app/public/images';

symlink($target, $linkName);

?>