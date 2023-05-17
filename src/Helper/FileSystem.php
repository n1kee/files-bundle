<?php

namespace FilesBundle\Helper;

class FileSystem {
	static function mergePath(string ...$pathParts): string {
		$pathString = implode(DIRECTORY_SEPARATOR, $pathParts);
		return preg_replace(
			"/(\/|\\\){2,}/",
			DIRECTORY_SEPARATOR,
			"/{$pathString}"
		);
	}

	static function addPath(string $origin, string $path): string {
		return preg_replace("/^(?!.+:)\/?/", "{$origin}/", $path);
	}

	static function resolveSrc(string $url, string $src): string {
		$parsedSrc = parse_url($src);
		if (isset($parsedSrc["host"])) return $src;
		$parsedUrl = parse_url($url);
		$urlPath = dirname($parsedUrl["path"] ?? "/");
		$newPath = preg_replace("/^((\.\/)|(^(?!\/)))/", "{$urlPath}/", $src);
		return str_replace($parsedUrl["path"], $newPath, $url);
	}
}
