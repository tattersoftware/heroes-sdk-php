<?php

declare(strict_types=1);

namespace Tatter\Heroes\Factories;

use Tatter\Heroes\Entities\Hero;
use Tatter\Heroes\Providers\DataProvider;
use ArrayIterator;
use Traversable;

/**
 * Hero Factory
 *
 * Uses or creates a Hero DataProvider
 * to generate hero data.
 */
class HeroFactory extends BaseFactory
{
	/**
	 * Group to use for the Data Provider
	 *
	 * @var string
	 */
	protected $group = DataProvider::HERO;

	/**
	 * Returns a hero identified by $heroId
	 *
	 * @param string $heroId
	 *
	 * @return Hero
	 */
	public function get(string $heroId): Hero
	{
		// Use the other factories to get child content
		$abilities = new AbilityFactory($this->strings->getGroup(), $this->data->getPatch());
		$talents   = new TalentFactory($this->strings->getGroup(), $this->data->getPatch());

		return new Hero($heroId, $abilities->hero($heroId), $talents->hero($heroId));
	}

	/**
	 * Returns an iterable version of all Heroes.
	 *
	 * @return Traversable
	 */
	public function getIterator(): Traversable
	{
		$heroes = [];

		foreach ($this->data->getContents() as $heroId => $heroContents)
		{
			$heroes[] = $this->get($heroId);
		}

		return new ArrayIterator($heroes);
	}
}