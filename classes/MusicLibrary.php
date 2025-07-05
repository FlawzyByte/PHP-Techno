<?php


class MusicLibrary
{
    private array $songs = [];


    public function addSong(Song $song): void
    {
        $this->songs[] = $song;
    }


    public function getAllSongs(): array
    {
        return $this->songs;
    }


    public function getSongCount(): int
    {
        return count($this->songs);
    }


    public function findSongByTitle(string $title): ?Song
    {
        $searchTerm = strtolower(trim($title));
        foreach ($this->songs as $song) {
            if (strpos(strtolower($song->getTitle()), $searchTerm) !== false) {
                return $song;
            }
        }
        return null;
    }


    public function findSongsByArtist(string $artist): array
    {
        $foundSongs = [];
        foreach ($this->songs as $song) {
            if (strtolower($song->getArtist()) === strtolower($artist)) {
                $foundSongs[] = $song;
            }
        }
        return $foundSongs;
    }


    public function findSongsByGenre(string $genre): array
    {
        $foundSongs = [];
        foreach ($this->songs as $song) {
            if (strtolower($song->getGenre()) === strtolower($genre)) {
                $foundSongs[] = $song;
            }
        }
        return $foundSongs;
    }


    public function displayAllSongs(): string
    {
        if (empty($this->songs)) {
            return '<p class="no-songs">No songs in the library yet.</p>';
        }

        $output = '<div class="songs-list">';
        foreach ($this->songs as $song) {
            $output .= $song->displaySong();
        }
        $output .= '</div>';
        
        return $output;
    }


    public function saveToJson(string $filename): bool
    {
        $songsArray = [];
        foreach ($this->songs as $song) {
            $songsArray[] = $song->toArray();
        }
        
        $jsonData = json_encode($songsArray, JSON_PRETTY_PRINT);
        return file_put_contents($filename, $jsonData) !== false;
    }


    public function loadFromJson(string $filename): bool
    {
        if (!file_exists($filename)) {
            return false;
        }

        $jsonData = file_get_contents($filename);
        $songsArray = json_decode($jsonData, true);

        if ($songsArray === null) {
            return false;
        }

        $this->songs = [];
        foreach ($songsArray as $songData) {
            $song = new Song(
                $songData['title'],
                $songData['artist'],
                $songData['genre'],
                $songData['duration']
            );
            $this->addSong($song);
        }

        return true;
    }
} 