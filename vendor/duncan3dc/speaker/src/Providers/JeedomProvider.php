<?php

namespace duncan3dc\Speaker\Providers;

/**
 * Convert a string of a text to spoken word audio.
 */
class JeedomProvider extends AbstractProvider {

	private $url = null;

	public function __construct(string $url = null) {
		if ($url !== null) {
			$this->url = $url;
		}
	}

	public function getOptions(): array
	{
		return [
			"url" => $this->url,
		];
	}

	public function textToSpeech(string $text): string {
		return file_get_contents($this->url . '&text=' . urlencode($text));
	}
}
