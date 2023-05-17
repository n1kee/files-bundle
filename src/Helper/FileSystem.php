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

	static function addPath($origin, $path): string {
		return preg_replace("|^(?!.+:)/?|", "{$origin}/", $path);
	}
}
