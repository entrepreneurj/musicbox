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
        $files[$filename]=array("filename" => $filename, "album" => $file["tags"]["id3v2"]["album"][0], "title" => $file["tags"]["id3v2"]["title"][0], "track" => $file["tags"]["id3v2"]["track_number"][0], "artist" => ($file["tags"]["id3v2"]["artist"][0] ?: "Various Artists"));
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
<!DOCTYPE html>
<html>
    <head>
        <title>MusicBox</title>
        <meta charset="UTF-8">
        <script src="static/js/skel.min.js"></script>
        <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
        <script src="library.js"></script>
        <script>
        $( document ).ready(function() {
            skel.init({ 
                breakpoints: {
                    large: {
                    media: '(min-width: 1025px) and (max-width: 1280px)',
                    containers: 960
                    },
                    medium: {
                    media: '(min-width: 769px) and (max-width: 1024px)',
                    containers: '90%'
                    },
                    small: {
                    media: '(max-width: 768px)',
                    containers: '95%',
                    grid: {
                        collapse: true
                    }
                    },
                    xsmall: {
                    media: '(max-width: 480px)'
                    }
                }}            
            );
            //var library=<?php echo $json; ?>;
            for (var album in library) { 
                if (library.hasOwnProperty(album)) {
                    var album_obj = library[album];
                    $( "#album-library").append('<div class="album"><a class="change-album" ><i class="fa fa-music fa-5x"></i><p>'+album_obj.name+'</p></a></div>');
                }
            }
            // Add handler for album change
            $('.change-album').click(function(){ 
                console.log(this);
                var album_name = this.children[1].innerHTML;
                $("#player h4").text(album_name);
                $("#download a").attr("href", "music/download.php?album="+album_name);
                $("#track-list").html("");
                $("#track-list").css("background-image", "url(static/img/default.png)");
                for (var song_id in library[album_name]) {
                    if (song_id != "name") {
                        var song = library[album_name][song_id];
                        $( "#track-list").append('<div class="track"><a class="change-track" id="'+song["track"]+'"><p>'+song["track"]+' - '+song["title"]+'</p></a></div>');
                    }
                }
                // Attach click handlers to tracks
                $('.change-track').click(function(){
                    console.log(this);
                    var track_id = this.attributes.id.value;
                    var track_src= library[album_name][track_id]["filename"];
                    $("audio source").attr("src",track_src);
                    document.getElementById("aud").load()
                    document.getElementById("aud").play()
                });
                    
            });

        });
        </script>
        <link href="static/css/font-awesome.min.css" rel="stylesheet">
        <link href="static/css/style.css" rel="stylesheet">
        <link rel="stylesheet" href="static/css/style-small.css" media="(max-width: 768px)" />
        <link href='http://fonts.googleapis.com/css?family=Permanent+Marker|Walter+Turncoat|Open+Sans' rel='stylesheet' type='text/css'>
    </head>
    <body>
        <div class="container">
            <section class="row">
                <div id="header-container">
                    <header class="12u">
                        <h1>MusicBox</h1>
                        <p>Where your ears melt in pleasure...</p>
                    </header>
                </div>
            </section>
            <section class="row" id="main">
                <div id="library-container" class="3u">
                    <div id="library">
                    <header>
                    <h1>Library</h1>
                    </header>
                        <div id="album-library">
                        </div>
                    </div>
                </div>
                <div id="player-container" class="6u">
                    <div id="player">
                        <header>
                        <h1>Jukebox</h1>
                        <h4></h4>
                        </header>
                        <div id="track-list"><h2>Choose an album...</h2></div>
                        <audio  id = "aud" controls>
                            <source src="" type="audio/mp3">
                            Your browser does not support m4, please download the album instead
                        </audio>
                        <div id="download"><p><a href="javascript:alert('Whoops, please choose an album first');" class="button" download>Download album</a></p></div>
                    </div>
                </div>
                <div id="explanation-container" class="3u">
                    <div id="explanation">
                        <header>
                        <h2>Welcome to Music Box</h2>
                        </header>
                        <p>Hello, traveller from the internet! Welcome to Music Box. Music Box is a simple dynamic application that reads the mp3 files in a folder, sorts them into albums (using embedded ID3 tags) and then lets you listen and download songs!</p>
                        <p>To get started click on an album on the left!</p>
                        <header>
                        <h3>The next steps</h3>
                        </header>
                        <p>The next stage of Music Box will have a cleaner user interface and will also be able to extract embedded album artwork from mp3 files!</p>
                    </div>
                </div>
            </section>
        </div>
    </body>
</html>