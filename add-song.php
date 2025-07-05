<?php
require_once 'classes/Song.php';
require_once 'classes/MusicLibrary.php';

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $artist = trim($_POST['artist'] ?? '');
    $genre = trim($_POST['genre'] ?? '');
    $duration = trim($_POST['duration'] ?? '');

    if (empty($title) || empty($artist) || empty($genre) || empty($duration)) {
        $message = 'All fields are required!';
        $messageType = 'error';
    } else {
        $newSong = new Song($title, $artist, $genre, $duration);
        
        $musicLibrary = new MusicLibrary();
        $musicLibrary->loadFromJson('data/songs.json');
        
        $musicLibrary->addSong($newSong);
        
        if ($musicLibrary->saveToJson('data/songs.json')) {
            header('Location: index.php?message=Song added successfully!&type=success');
            exit();
        } else {
            $message = 'Error saving song to library.';
            $messageType = 'error';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Song - My Tech Lib</title>
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

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            overflow: hidden;
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

        .content {
            padding: 30px;
        }

        @media (max-width: 600px) {
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
            <h1>➕ Add New Song</h1>
            <p>Add a new song to your music library</p>
        </div>

        <div class="content">
            <?php if ($message): ?>
                <div class="message <?php echo $messageType; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="title">Song Title *</label>
                    <input type="text" id="title" name="title" required 
                           value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>"
                           placeholder="Enter song title">
                </div>

                <div class="form-group">
                    <label for="artist">Artist *</label>
                    <input type="text" id="artist" name="artist" required 
                           value="<?php echo htmlspecialchars($_POST['artist'] ?? ''); ?>"
                           placeholder="Enter artist name">
                </div>

                <div class="form-group">
                    <label for="genre">Genre *</label>
                    <select id="genre" name="genre" required>
                        <option value="">Select a genre</option>
                        <option value="Underground Techno" <?php echo ($_POST['genre'] ?? '') === 'Underground Techno' ? 'selected' : ''; ?>>Underground Techno</option>
                        <option value="Acid" <?php echo ($_POST['genre'] ?? '') === 'Acid' ? 'selected' : ''; ?>>Acid</option>
                        <option value="DnB" <?php echo ($_POST['genre'] ?? '') === 'DnB' ? 'selected' : ''; ?>>DnB</option>
                        <option value="Liquid DnB" <?php echo ($_POST['genre'] ?? '') === 'Liquid DnB' ? 'selected' : ''; ?>>Liquid DnB</option>
                        <option value="Progressive House" <?php echo ($_POST['genre'] ?? '') === 'Progressive House' ? 'selected' : ''; ?>>Progressive House</option>
                        <option value="Trance" <?php echo ($_POST['genre'] ?? '') === 'Trance' ? 'selected' : ''; ?>>Trance</option>
                        <option value="Dubstep" <?php echo ($_POST['genre'] ?? '') === 'Dubstep' ? 'selected' : ''; ?>>Dubstep</option>
                        <option value="Electro House" <?php echo ($_POST['genre'] ?? '') === 'Electro House' ? 'selected' : ''; ?>>Electro House</option>
                        <option value="Big Room House" <?php echo ($_POST['genre'] ?? '') === 'Big Room House' ? 'selected' : ''; ?>>Big Room House</option>
                        <option value="Future Bass" <?php echo ($_POST['genre'] ?? '') === 'Future Bass' ? 'selected' : ''; ?>>Future Bass</option>
                        <option value="Moombahton" <?php echo ($_POST['genre'] ?? '') === 'Moombahton' ? 'selected' : ''; ?>>Moombahton</option>
                        <option value="Trap" <?php echo ($_POST['genre'] ?? '') === 'Trap' ? 'selected' : ''; ?>>Trap</option>
                        <option value="Disco House" <?php echo ($_POST['genre'] ?? '') === 'Disco House' ? 'selected' : ''; ?>>Disco House</option>
                        <option value="French House" <?php echo ($_POST['genre'] ?? '') === 'French House' ? 'selected' : ''; ?>>French House</option>
                        <option value="Minimal Techno" <?php echo ($_POST['genre'] ?? '') === 'Minimal Techno' ? 'selected' : ''; ?>>Minimal Techno</option>
                        <option value="Deep House" <?php echo ($_POST['genre'] ?? '') === 'Deep House' ? 'selected' : ''; ?>>Deep House</option>
                        <option value="Tech House" <?php echo ($_POST['genre'] ?? '') === 'Tech House' ? 'selected' : ''; ?>>Tech House</option>
                        <option value="Industrial Techno" <?php echo ($_POST['genre'] ?? '') === 'Industrial Techno' ? 'selected' : ''; ?>>Industrial Techno</option>
                        <option value="Hard Techno" <?php echo ($_POST['genre'] ?? '') === 'Hard Techno' ? 'selected' : ''; ?>>Hard Techno</option>
                        <option value="Neurofunk" <?php echo ($_POST['genre'] ?? '') === 'Neurofunk' ? 'selected' : ''; ?>>Neurofunk</option>
                        <option value="Jump Up" <?php echo ($_POST['genre'] ?? '') === 'Jump Up' ? 'selected' : ''; ?>>Jump Up</option>
                        <option value="Techstep" <?php echo ($_POST['genre'] ?? '') === 'Techstep' ? 'selected' : ''; ?>>Techstep</option>
                        <option value="Atmospheric DnB" <?php echo ($_POST['genre'] ?? '') === 'Atmospheric DnB' ? 'selected' : ''; ?>>Atmospheric DnB</option>
                        <option value="Jungle" <?php echo ($_POST['genre'] ?? '') === 'Jungle' ? 'selected' : ''; ?>>Jungle</option>
                        <option value="Acid House" <?php echo ($_POST['genre'] ?? '') === 'Acid House' ? 'selected' : ''; ?>>Acid House</option>
                        <option value="Acid Techno" <?php echo ($_POST['genre'] ?? '') === 'Acid Techno' ? 'selected' : ''; ?>>Acid Techno</option>
                        <option value="Acid Trance" <?php echo ($_POST['genre'] ?? '') === 'Acid Trance' ? 'selected' : ''; ?>>Acid Trance</option>
                        <option value="Acid Breaks" <?php echo ($_POST['genre'] ?? '') === 'Acid Breaks' ? 'selected' : ''; ?>>Acid Breaks</option>
                        <option value="Detroit Techno" <?php echo ($_POST['genre'] ?? '') === 'Detroit Techno' ? 'selected' : ''; ?>>Detroit Techno</option>
                        <option value="Berlin Techno" <?php echo ($_POST['genre'] ?? '') === 'Berlin Techno' ? 'selected' : ''; ?>>Berlin Techno</option>
                        <option value="Folktronica" <?php echo ($_POST['genre'] ?? '') === 'Folktronica' ? 'selected' : ''; ?>>Folktronica</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="duration">Duration * (MM:SS format)</label>
                    <input type="text" id="duration" name="duration" required 
                           value="<?php echo htmlspecialchars($_POST['duration'] ?? ''); ?>"
                           placeholder="e.g., 3:45" pattern="[0-9]+:[0-5][0-9]">
                </div>

                <button type="submit" class="submit-btn">➕ Add Song to Library</button>
            </form>

            <div class="back-link">
                <a href="index.php">← Back to Music Library</a>
            </div>
        </div>
    </div>

    <script src="scripts.js"></script>
</body>
</html> 