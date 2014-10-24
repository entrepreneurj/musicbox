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
