# Audio Files Directory

This directory contains audio files for the 30-second song previews.

## File Naming Convention

Audio files should be named exactly like the song titles in your library, with spaces replaced by hyphens and special characters removed.

### Examples:
- `Blinding Lights.mp3` → `Blinding-Lights.mp3`
- `Lose Yourself.mp3` → `Lose-Yourself.mp3`
- `Bohemian Rhapsody.mp3` → `Bohemian-Rhapsody.mp3`

## Supported Formats

- **MP3** (recommended)
- **WAV**
- **OGG**

## How to Add Audio Files

1. **Get the song file** (MP3 format recommended)
2. **Rename it** to match the song title in your library
3. **Place it** in this `audio/` directory
4. **Ensure the filename** matches exactly (case-sensitive)

## Demo Mode

If no audio file is found for a song, the system will show a demo message and simulate the 30-second preview with a countdown timer.

## Example Files

For the demo songs in your library, you would need:
- `Blinding-Lights.mp3`
- `Lose-Yourself.mp3`
- `Bohemian-Rhapsody.mp3`
- `Shape-of-You.mp3`
- `Hotel-California.mp3`
- `BeyondSakha.mp3`


## Testing

To test the feature:
1. Add an MP3 file with the correct name
2. Click the "Play 30s" button on any song
3. The audio should play for exactly 30 seconds
4. Use the "Stop" button to stop early 