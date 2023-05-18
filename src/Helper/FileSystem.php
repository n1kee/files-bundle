<?php

namespace FilesBundle\Helper;

/**
 * Class with filesystem helper functions.
 *
 */
class FileSystem {
	/**
	 * Merges path parts into one.
	 *
	 *  @param mixed $pathParts,... Path parts to be merged.
	 *  @return string aaa
	 */
	static function mergePath(string ...$pathParts): string {
		$pathString = implode(DIRECTORY_SEPARATOR, $pathParts);
		return preg_replace(
			"/(\/|\\\){2,}/",
			DIRECTORY_SEPARATOR,
			"/{$pathString}"
		);
	}

	/**
	 * Adds path to the origin.
	 *
	 *  @param string $origin URL origin.
	 *  @param string $path URL path.
	 *  @return number aaa
	 */
	static function addPath(string $origin, string $path): string {
		return preg_replace("/^(?!.+:)\/?/", "{$origin}/", $path);
	}

	/**
	 * Resolves src attribute, relatively to the URL.
	 *
	 *  @param string $url URL of the web page.
	 *  @param string $src Src attribute link to be resolved.
	 *  @return string Absolute URL for the src attribute.
	 */
	static function resolveSrc(string $url, string $src): string {
		$parsedSrc = parse_url($src);
		if (isset($parsedSrc["host"])) return $src;
		$parsedUrl = parse_url($url);
		$urlPath = dirname($parsedUrl["path"] ?? "/");
		$newPath = preg_replace("/^((\.\/)|(^(?!\/)))/", "{$urlPath}/", $src);
		return str_replace($parsedUrl["path"], $newPath, $url);
	}
}
