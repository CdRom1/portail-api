<?php
if (!function_exists('convertPipeToArray') && !function_exists('stringToArray')) {
	function convertPipeToArray(string $pipeString) {
		$pipeString = trim($pipeString);

		if (strlen($pipeString) <= 2) {
			return $pipeString;
		}

		$quoteCharacter = substr($pipeString, 0, 1);
		$endCharacter = substr($quoteCharacter, -1, 1);

		if ($quoteCharacter !== $endCharacter) {
			return explode('|', $pipeString);
		}

		if (!in_array($quoteCharacter, ["'", '"'])) {
			return explode('|', $pipeString);
		}

		return explode('|', trim($pipeString, $quoteCharacter));
	}

	function stringToArray($toArray) {
		if (is_string($toArray) && false !== strpos($toArray, '|'))
			$toArray = convertPipeToArray($toArray);
		if (is_string($toArray) || is_numeric($toArray))
			$toArray = [$toArray];
		if (!is_array($toArray))
			return $toArray;

		foreach ($toArray as $key => $value) {
			if (is_numeric($value))
				$toArray[$key] = intval($value);
		}

		return $toArray;
	}
}
