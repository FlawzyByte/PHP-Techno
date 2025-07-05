<?php


class Song
{
    private string $title;
    private string $artist;
    private string $genre;
    private string $duration;


    public function __construct(string $title, string $artist, string $genre, string $duration)
    {
        $this->title = $title;
        $this->artist = $artist;
        $this->genre = $genre;
        $this->duration = $duration;
    }


    public function getTitle(): string
    {
        return $this->title;
    }


    public function getArtist(): string
    {
        return $this->artist;
    }


    public function getGenre(): string
    {
        return $this->genre;
    }


    public function getDuration(): string
    {
        return $this->duration;
    }


    public function displaySong(): string
    {
        $safeId = $this->getSafeId();
        return sprintf(
            '<div class="song-item">
                <div class="song-info">
                    <h3>%s by %s</h3>
                    <p><strong>Genre:</strong> %s | <strong>Duration:</strong> %s</p>
                </div>
                <div class="song-controls">
                    <div class="control-buttons">
                        <button class="play-btn" onclick="playSongPreview(\'%s\', \'%s\', \'%s\')">
                            <span class="play-icon">▶</span>
                            <span class="play-text">Play</span>
                        </button>
                        <button class="delete-btn" onclick="deleteSong(\'%s\', \'%s\')" title="Delete song">
                            <span class="delete-icon">🗑️</span>
                        </button>
                    </div>
                    <div class="audio-player" id="player-%s" style="display: none;">
                        <audio id="audio-%s" preload="none">
                            <source src="audio/%s.mp3" type="audio/mpeg">
                            Your browser does not support the audio element.
                        </audio>
                        <div class="player-controls">
                            <button class="stop-btn" onclick="stopSongPreview(\'%s\')">⏹ Stop</button>
                            <button class="full-track-btn" onclick="playFullTrack(\'%s\')" title="Play full track">🎵 Full Track</button>
                            <span class="timer" id="timer-%s">30s</span>
                        </div>
                    </div>
                </div>
            </div>',
            htmlspecialchars($this->title),
            htmlspecialchars($this->artist),
            htmlspecialchars($this->genre),
            htmlspecialchars($this->duration),
            htmlspecialchars($this->title),
            htmlspecialchars($this->artist),
            htmlspecialchars($this->genre),
            htmlspecialchars($this->title),
            htmlspecialchars($this->artist),
            $safeId,
            $safeId,
            $safeId,
            $safeId,
            $safeId,
            $safeId
        );
    }


    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'artist' => $this->artist,
            'genre' => $this->genre,
            'duration' => $this->duration
        ];
    }


    public function getSafeId(): string
    {
        return preg_replace('/[^a-zA-Z0-9-]/', '', str_replace(' ', '-', $this->title));
    }
} 