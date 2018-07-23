<?php

namespace duncan3dc\Speaker;

/**
 * Convert a string of a text to spoken word audio.
 */
interface TextToSpeechInterface {
	/**
	 * Get the audio for this text.
	 *
	 * @return string The audio data
	 */
	public function getAudioData();

	/**
	 * Generate the filename to be used for this text.
	 *
	 * @return string
	 */
	public function generateFilename();

	/**
	 * Create an audio file on the filesystem.
	 *
	 * @param string $filename The filename to write to
	 *
	 * @return $this
	 */
	public function save(string $filename): TextToSpeechInterface;

	/**
	 * Store the audio file on the filesystem.
	 *
	 * @param string $path The path to the directory to store the file in
	 *
	 * @return string The full path and filename
	 */
	public function getFile(string $path = null);
}
