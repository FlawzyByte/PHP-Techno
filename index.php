<?php
require_once 'classes/Song.php';
require_once 'classes/MusicLibrary.php';

$musicLibrary = new MusicLibrary();

if (!$musicLibrary->loadFromJson('data/songs.json')) {
    $musicLibrary->saveToJson('data/songs.json');
}

$searchResult = '';
$searchQuery = '';

if (isset($_GET['message']) && isset($_GET['type'])) {
    $searchResult = '<div class="search-result ' . htmlspecialchars($_GET['type']) . '">' . 
                   htmlspecialchars($_GET['message']) . '</div>';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['search_title']) && !empty($_POST['search_title'])) {
        $searchQuery = $_POST['search_title'];
        $foundSong = $musicLibrary->findSongByTitle($searchQuery);
        
        if ($foundSong) {
            $searchResult = '<div class="search-result success">' . $foundSong->displaySong() . '</div>';
        } else {
            $searchResult = '<div class="search-result error">Song not found: "' . htmlspecialchars($searchQuery) . '"</div>';
        }
    }
    
    if (isset($_POST['search_artist']) && !empty($_POST['search_artist'])) {
        $searchQuery = $_POST['search_artist'];
        $foundSongs = $musicLibrary->findSongsByArtist($searchQuery);
        
        if (!empty($foundSongs)) {
            $searchResult = '<div class="search-result success"><h3>Songs by ' . htmlspecialchars($searchQuery) . ':</h3>';
            foreach ($foundSongs as $song) {
                $searchResult .= $song->displaySong();
            }
            $searchResult .= '</div>';
        } else {
            $searchResult = '<div class="search-result error">No songs found by artist: "' . htmlspecialchars($searchQuery) . '"</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Techno Player</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
            background: transparent;
        }

        .header {
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .stats {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #e9ecef;
        }

        .search-section {
            padding: 30px;
            background: #f8f9fa;
        }

        .search-form {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .content {
            padding: 30px;
        }

        .song-item {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            border-left: 4px solid #4ecdc4;
            transition: all 0.3s ease;
            transform: scale(0.95);
            opacity: 0.8;
            cursor: pointer;
        }

        .song-item:hover {
            transform: scale(1.05) translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2), 0 0 20px rgba(78, 205, 196, 0.3);
            opacity: 1;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-left: 4px solid #ff6b6b;
        }

        .song-item:hover h3 {
            color: #2c3e50;
            transform: scale(1.02);
            transition: all 0.3s ease;
        }

        .song-item:hover p {
            color: #34495e;
            transform: scale(1.01);
            transition: all 0.3s ease;
        }

        .song-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .song-info {
            flex: 1;
        }

        .song-controls {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .control-buttons {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .delete-btn {
            background: #e74c3c;
            color: white;
            border: none;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(231, 76, 60, 0.3);
        }

        .delete-btn:hover {
            background: #c0392b;
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(231, 76, 60, 0.4);
        }

        .delete-icon {
            font-size: 12px;
        }

        .play-btn {
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
            color: white;
            border: none;
            border-radius: 25px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
        }

        .play-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 107, 107, 0.4);
            background: linear-gradient(45deg, #ff5252, #26a69a);
        }

        .play-icon {
            font-size: 16px;
        }

        .play-text {
            font-size: 12px;
        }

        .audio-player {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border: 2px solid #4ecdc4;
        }

        .player-controls {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .stop-btn {
            background: #e74c3c;
            color: white;
            border: none;
            border-radius: 20px;
            padding: 8px 15px;
            cursor: pointer;
            font-size: 12px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .stop-btn:hover {
            background: #c0392b;
            transform: translateY(-1px);
        }

        .full-track-btn {
            background: #27ae60;
            color: white;
            border: none;
            border-radius: 20px;
            padding: 8px 15px;
            cursor: pointer;
            font-size: 12px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .full-track-btn:hover {
            background: #229954;
            transform: translateY(-1px);
        }

        .timer {
            font-weight: bold;
            color: #2c3e50;
            font-size: 14px;
        }

        .playing .play-btn {
            background: linear-gradient(45deg, #27ae60, #2ecc71);
            animation: pulse 1s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .song-item h3 {
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 1.3em;
        }

        .song-item p {
            color: #7f8c8d;
            margin: 0;
        }

        .no-songs {
            text-align: center;
            color: #6c757d;
            font-style: italic;
            padding: 40px;
        }

        .songs-list {
            margin-top: 20px;
        }

        .modal {
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 15% auto;
            padding: 30px;
            border-radius: 15px;
            width: 80%;
            max-width: 500px;
            position: relative;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            position: absolute;
            right: 20px;
            top: 15px;
        }

        .close:hover {
            color: #fff;
        }

        .about-content h2 {
            color: white;
            margin-bottom: 20px;
            text-align: center;
            font-size: 2em;
        }

        .about-text {
            color: white;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .about-text p {
            margin-bottom: 10px;
        }

        .github-link {
            text-align: center;
        }

        .github-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 12px 25px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .github-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .github-icon {
            width: 20px;
            height: 20px;
        }

        @media (max-width: 600px) {
            .search-form {
                flex-direction: column;
            }
            
            .header h1 {
                font-size: 2em;
            }
        }
    </style>
</head>
<body>
    <div class="trippy-bg"></div>
    <div class="strobe-light"></div>
    <div class="laser-beam"></div>
    <div class="floating-particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>
    
    <div class="container">
        <div class="header">
            <h1>🎵🔥 My Techno Lib ⛓️🎶</h1>
            <p>Add and listen to your personal collection of EDM and only EDM please.</p>
        </div>

        <div class="stats">
            <h3>📊 Libr Stats</h3>
            <p>Total Songs: <strong><?php echo $musicLibrary->getSongCount(); ?></strong></p>
        </div>

        <div class="search-section">
            <h3>🔍 Search Songs</h3>
            <form method="POST" class="search-form">
                <input type="text" name="search_title" placeholder="Search by song title..." 
                       class="search-input" value="<?php echo htmlspecialchars($searchQuery); ?>">
                <button type="submit" class="search-btn">Search by Title</button>
            </form>
            
            <form method="POST" class="search-form">
                <input type="text" name="search_artist" placeholder="Search by artist..." 
                       class="search-input">
                <button type="submit" class="search-btn">Search by Artist</button>
            </form>
            
            <div style="text-align: center; margin-top: 20px;">
                <a href="add-song.php" class="search-btn" style="text-decoration: none; display: inline-block;">
                    ➕ Add New Song
                </a>
                <button onclick="showAbout()" class="search-btn" style="margin-left: 10px;">
                    ℹ️ About
                </button>
            </div>
        </div>

        <?php if ($searchResult): ?>
            <?php echo $searchResult; ?>
        <?php endif; ?>

        <div class="content">
            <h2>📚 All Songs in Library</h2>
            <?php echo $musicLibrary->displayAllSongs(); ?>
        </div>
    </div>

    <div id="aboutModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="closeAbout()">&times;</span>
            <div class="about-content">
                <h2>🐲 About FlawzyByte</h2>
                <div class="about-text">
                    <p><strong>Techno Lover & Developer</strong></p>
                    <p>Passionate about underground electronic music and coding. Can code anything from web apps to complex systems.</p>
                    <p>Project for mastering PHP, JavaScript, and modern web technologies.</p>
                    <p>Love for techno, acid, Dubstep and all electronic music.</p>
                </div>
                <div class="github-link">
                    <a href="https://github.com/FlawzyByte" target="_blank" class="github-btn">
                        <svg class="github-icon" viewBox="0 0 24 24" width="24" height="24">
                            <path fill="currentColor" d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                        </svg>
                        GitHub Profile
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const songItems = document.querySelectorAll('.song-item');
            songItems.forEach((item, index) => {
                item.style.animationDelay = (index * 0.2) + 's';
                item.style.animation = 'trackAppear 1s ease-out forwards';
                
                item.addEventListener('mouseenter', function() {
                    this.style.transition = 'all 0.3s ease';
                });
                
                item.addEventListener('mouseleave', function() {
                    this.style.transition = 'all 0.3s ease';
                });
            });

            const style = document.createElement('style');
            style.textContent = `
                @keyframes fadeInUp {
                    from {
                        opacity: 0;
                        transform: translateY(20px) scale(0.9);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0) scale(1);
                    }
                }

                        @keyframes trackAppear {
            0% {
                opacity: 0;
                transform: scale(0.8) translateY(30px);
            }
            50% {
                opacity: 0.4;
                transform: scale(0.9) translateY(15px);
            }
            100% {
                opacity: 0.8;
                transform: scale(0.95) translateY(0);
            }
        }
            `;
            document.head.appendChild(style);
        });

        let currentAudio = null;
        let currentTimer = null;
        let currentSongItem = null;

        function playSongPreview(songTitle, artist, genre) {
            if (currentAudio) {
                stopSongPreview();
            }

            const songItem = event.target.closest('.song-item');
            const safeSongTitle = songTitle.replace(/\s+/g, '-').replace(/[^a-zA-Z0-9-]/g, '');
            const playerId = `player-${safeSongTitle}`;
            const audioId = `audio-${safeSongTitle}`;
            const timerId = `timer-${safeSongTitle}`;

            const player = document.getElementById(playerId);
            const audio = document.getElementById(audioId);
            const timer = document.getElementById(timerId);

            if (player && audio) {
                player.style.display = 'block';
                
                songItem.classList.add('playing');
                
                audio.currentTime = 0;
                audio.play().then(() => {
                    currentAudio = audio;
                    currentSongItem = songItem;
                    
                    let timeLeft = 30;
                    timer.textContent = `${timeLeft}s`;
                    
                    currentTimer = setInterval(() => {
                        timeLeft--;
                        timer.textContent = `${timeLeft}s`;
                        
                        if (timeLeft <= 0) {
                            stopSongPreview();
                        }
                    }, 1000);
                    
                    audio.addEventListener('ended', stopSongPreview);
                    
                }).catch(error => {
                    console.log('Audio playback failed:', error);
                    alert(`🎵 Preview for "${songTitle}" by ${artist}\n\nThis is a demo - in a real app, this would play the actual song for 30 seconds!`);
                    
                    let timeLeft = 30;
                    timer.textContent = `${timeLeft}s`;
                    
                    currentTimer = setInterval(() => {
                        timeLeft--;
                        timer.textContent = `${timeLeft}s`;
                        
                        if (timeLeft <= 0) {
                            stopSongPreview();
                        }
                    }, 1000);
                });
            }
        }

        function stopSongPreview() {
            if (currentAudio) {
                currentAudio.pause();
                currentAudio.currentTime = 0;
                currentAudio = null;
            }
            
            if (currentTimer) {
                clearInterval(currentTimer);
                currentTimer = null;
            }
            
            if (currentSongItem) {
                currentSongItem.classList.remove('playing');
                currentSongItem = null;
            }
            
            const players = document.querySelectorAll('.audio-player');
            players.forEach(player => {
                player.style.display = 'none';
            });
        }

        function playFullTrack(songTitle) {
            const safeSongTitle = songTitle.replace(/\s+/g, '-').replace(/[^a-zA-Z0-9-]/g, '');
            const audioId = `audio-${safeSongTitle}`;
            const timerId = `timer-${safeSongTitle}`;
            const audio = document.getElementById(audioId);
            const timer = document.getElementById(timerId);

            if (audio) {
                if (currentTimer) {
                    clearInterval(currentTimer);
                    currentTimer = null;
                }

                audio.currentTime = 0;
                audio.play().then(() => {
                    currentAudio = audio;
                    
                    timer.textContent = 'Full Track';
                    timer.style.color = '#27ae60';
                    timer.style.fontWeight = 'bold';
                    
                    audio.addEventListener('ended', () => {
                        stopSongPreview();
                    });
                    
                }).catch(error => {
                    console.log('Audio playback failed:', error);
                    alert(`🎵 Full track for "${songTitle}"\n\nThis is a demo - in a real app, this would play the complete song!`);
                });
            }
        }

        function deleteSong(songTitle, artist) {
            const confirmed = confirm(`🗑️ Delete "${songTitle}" by ${artist}?\n\nThis action cannot be undone.`);
            
            if (confirmed) {
                const songItem = event.target.closest('.song-item');
                
                const formData = new FormData();
                formData.append('action', 'delete_song');
                formData.append('song_title', songTitle);
                formData.append('artist', artist);
                
                fetch('delete-song.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Server response:', data);
                    
                    if (data.success) {
                        if (songItem) {
                            songItem.style.transition = 'all 0.5s ease';
                            songItem.style.transform = 'scale(0.8) translateX(100px)';
                            songItem.style.opacity = '0';
                            
                            setTimeout(() => {
                                songItem.remove();
                                const songCount = document.querySelectorAll('.song-item').length;
                                const statsElement = document.querySelector('.stats p strong');
                                if (statsElement) {
                                    statsElement.textContent = songCount;
                                }
                            }, 500);
                        }
                        
                        showMessage(data.message + '!', 'success');
                    } else {
                        showMessage('Error deleting song: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMessage('Error deleting song. Please try again.', 'error');
                });
            }
        }

        function showMessage(message, type) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `search-result ${type}`;
            messageDiv.textContent = message;
            messageDiv.style.animation = 'searchAppear 0.5s ease-out';
            
            const searchSection = document.querySelector('.search-section');
            searchSection.parentNode.insertBefore(messageDiv, searchSection.nextSibling);
            
            setTimeout(() => {
                messageDiv.style.transition = 'all 0.5s ease';
                messageDiv.style.opacity = '0';
                messageDiv.style.transform = 'translateY(-10px)';
                setTimeout(() => messageDiv.remove(), 500);
            }, 3000);
        }

        function showAbout() {
            const modal = document.getElementById('aboutModal');
            modal.style.display = 'block';
            modal.style.animation = 'fadeIn 0.3s ease-out';
        }

        function closeAbout() {
            const modal = document.getElementById('aboutModal');
            modal.style.animation = 'fadeOut 0.3s ease-out';
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        }

        window.onclick = function(event) {
            const modal = document.getElementById('aboutModal');
            if (event.target === modal) {
                closeAbout();
            }
        }
    </script>
    <script src="scripts.js"></script>
</body>
</html>