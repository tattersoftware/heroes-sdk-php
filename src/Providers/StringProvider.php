<?php

declare(strict_types=1);

namespace Heroes\Providers;

use Heroes\Locator;
use JsonException;
use RuntimeException;

/**
 * Gamestring Provider Class
 *
 * Handles locating, reading, and storing
 * locale-specific games strings from
 * Heroes Tool Chest.
 */
class StringProvider extends BaseProvider
{
	use \Heroes\Traits\ProviderTrait;

	/**
	 * Locale Groups and Filenames
	 */
	const LOCALE = [
		'Germany' => 'dede',
		'USA'     => 'enus',
		'Spain'   => 'eses',
		'Mexico'  => 'esmx',
		'France'  => 'frfr',
		'Italy'   => 'itit',
		'Korea'   => 'kokr',
		'Poland'  => 'plpl',
		'Brazil'  => 'ptbr',
		'Russia'  => 'ruru',
		'China'   => 'zhcn',
		'Taiwan'  => 'zhtw',
	];

	/**
	 * Returns the pattern used to locate the source
	 * file within its de-duplicated directory.
	 *
	 * @return string
	 */
	protected function getPattern(): string
	{
		return $this->getDirectory() . 'gamestrings_*_' . $this->getGroup() . '.json';
	}
	
	/**
	 * Returns the de-duplicated directory.
	 *
	 * @return string
	 *
	 * @throws RuntimeException For missing directory
	 */
	private function getDirectory(): string
	{
		$guesses = [Locator::getPatchPath($this->getPatch())];

		// If this patch has a duplicate then try it too
		$metaData = $this->getMetaData();
		if (isset($metaData->duplicate->gamestring))
		{
			$guesses[] = Locator::getPatchPath($metaData->duplicate->gamestring);
		}

		foreach ($guesses as $patchDir)
		{
			if (is_dir($patchDir . 'gamestrings'))
			{
				return $patchDir . 'gamestrings' . DIRECTORY_SEPARATOR;
			}
		}

		throw new RuntimeException('Unable to locate gamestring directory for patch ' . $this->getPatch());
	}
}
