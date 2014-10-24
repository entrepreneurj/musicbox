<?php
require_once('musicbox.php');
error_reporting(E_ERROR | E_WARNING | E_PARSE);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>MusicBox</title>
        <meta charset="UTF-8">
        <script src="static/js/skel.min.js"></script>
        <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
        <script src="library.js"></script>
        <script src="static/js/librarian.js"></script>
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
