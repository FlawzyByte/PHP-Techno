<?php
require_once 'classes/Song.php';
require_once 'classes/MusicLibrary.php';

header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}


if (!isset($_POST['action']) || $_POST['action'] !== 'delete_song' || 
    !isset($_POST['song_title']) || !isset($_POST['artist'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
    exit;
}

$songTitle = trim($_POST['song_title']);
$artist = trim($_POST['artist']);


if (empty($songTitle) || empty($artist)) {
    echo json_encode(['success' => false, 'message' => 'Song title and artist are required']);
    exit;
}

try {

    $musicLibrary = new MusicLibrary();
    
    if (!$musicLibrary->loadFromJson('data/songs.json')) {
        echo json_encode(['success' => false, 'message' => 'Could not load music library']);
        exit;
    }
    

    error_log("Delete request - Song: '$songTitle', Artist: '$artist'");
    

    $songs = $musicLibrary->getAllSongs();
    $found = false;
    

    foreach ($songs as $index => $song) {
        $songTitleLower = strtolower($song->getTitle());
        $artistLower = strtolower($song->getArtist());
        $searchTitleLower = strtolower($songTitle);
        $searchArtistLower = strtolower($artist);
        
        error_log("Comparing: '$songTitleLower' vs '$searchTitleLower' and '$artistLower' vs '$searchArtistLower'");
        
        if ($songTitleLower === $searchTitleLower && $artistLower === $searchArtistLower) {

            unset($songs[$index]);
            $found = true;
            error_log("Song found and removed at index $index");
            break;
        }
    }
    
    if (!$found) {
        echo json_encode(['success' => false, 'message' => 'Song not found in library']);
        exit;
    }
    

    $newLibrary = new MusicLibrary();
    foreach ($songs as $song) {
        $newLibrary->addSong($song);
    }
    

    $audioDeleted = false;
    $audioFormats = ['mp3'];
    $baseFileName = str_replace(' ', '-', $songTitle);
    
    foreach ($audioFormats as $format) {
        $audioFilePath = 'audio/' . $baseFileName . '.' . $format;
        if (file_exists($audioFilePath)) {
            if (unlink($audioFilePath)) {
                $audioDeleted = true;
                break; 
            }
        }
    }
    

    if ($newLibrary->saveToJson('data/songs.json')) {
        $message = 'Song deleted successfully';
        if ($audioDeleted) {
            $message .= ' and audio file removed';
        }
        echo json_encode(['success' => true, 'message' => $message]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Could not save updated library']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?> 