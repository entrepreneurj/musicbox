<?php
require_once('vendor/getid3.php.lib/getid3/getid3.php');
error_reporting(E_ERROR | E_WARNING | E_PARSE);

$getID3 = getID3_init();

$song_library=(get_songs($getID3));
$album_library = sort_songs_by_album($song_library);
$json = json_encode($album_library);
$myfile = fopen("library.js", "w") or die("Unable to open file!");
fwrite($myfile, "var library=\n ".$json); // splits js line for easy parsing later
fclose($myfile);
// FUNCTIONS ///


// RECURSIVE GLOB
// http://thephpeffect.com/recursive-glob-vs-recursive-directory-iterator/
function rglob($pattern, $flags = 0) {
    $files = glob($pattern, $flags); 
    foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir) {
        $files = array_merge($files, rglob($dir.'/'.basename($pattern), $flags));
    }
    return $files;
}

function get_songs($getID3) {
    $files = array();
    foreach (rglob("music/*.mp3") as $filename) {
        $file=$getID3->analyze($filename);
        // if no track # replace with hash of file's relative path
        $files[$filename]=array("filename" => $filename, "album" => $file["tags"]["id3v2"]["album"][0], "title" => $file["tags"]["id3v2"]["title"][0], "track" => ($file["tags"]["id3v2"]["track_number"][0]?:substr(md5($filename),-5)), "artist" => ($file["tags"]["id3v2"]["artist"][0] ?: "Various Artists"));
    }
    return $files;
}

function getID3_init() {
    $PageEncoding = 'UTF-8';
    // Initialize getID3 engine
    $getID3 = new getID3;
    $getID3->setOption(array('encoding' => $PageEncoding));

    return $getID3;
}

function sort_songs_by_album($library) {
    $albums = array();
    foreach ($library as $song) {
         $album_name = $song["album"];
         //if the album does not exist
        if (!$albums[$album_name]) {
            // Create new album in list and populate name
            $albums[$album_name] = array();
            $albums[$album_name]["name"] = $album_name;
        }
        $albums[$album_name][$song["track"]] = $song;
    }
    return $albums;

}
?>
