<?php 
$a='uploads';
deleteDirectory($a);
function deleteDirectory($dirPath) {
   // echo "hu";
   $pathWholeDataset=pathinfo(realpath($dirPath), PATHINFO_DIRNAME);
    echo $pathWholeDataset;
    // if (is_dir($dirPath)) {
        $objects = scandir($dirPath);
        echo "..........................";
        print_r($objects);
        foreach ($objects as $object) {
            if ($object != "." && $object !="..") {
                if (filetype($dirPath . DIRECTORY_SEPARATOR . $object) == "dir") {
                    deleteDirectory($dirPath . DIRECTORY_SEPARATOR . $object);
                } else {
                    unlink($dirPath . DIRECTORY_SEPARATOR . $object);
                }
            }
        }
    reset($objects);
   // rmdir($dirPath);
    // }
}
?>