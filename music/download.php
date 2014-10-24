<?php
error_reporting(E_ERROR);
require_once("../vendor/zipstream.php.lib/zipstream-php-0.2.2/zipstream.php");
$json = file('../library.js')[1];
$albums = json_decode($json, true);
//var_dump($albums);
$album = $_GET["album"];
// Check to see if album is in array (i.e. stops people injecting other paths
if ($albums[$album]) {
    //echo $album;
    $filename = 'album-'.$album.'.zip';
    header($_SERVER['SERVER_PROTOCOL'].' 200 OK');
    header("Content-Type: application/zip");
    header("Content-disposition: attachment; filename=$filename");
    $zip = new ZipStream($filename,array("send_http_headers"=>true) );
    foreach($albums[$album] as $key =>$song){
        if ($key!="name"){
            $zip->add_file_from_path(basename($song["filename"]), dirname(dirname(__FILE__))."/".$song["filename"]);
        }
    }
   // header("Content-Transfer-Encoding: Binary");
    $zip->finish();
}
else{
    header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
    echo "This album does not exist :(";
}


?>

