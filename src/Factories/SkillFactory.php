<?php

declare(strict_types=1);

namespace Heroes\Factories;

use Heroes\Entities\Skill;
use Heroes\Providers\DataProvider;
use ArrayIterator;
use Traversable;

/**
 * Skill Factory
 *
 * Common Factory methods for
 * Abilities and Talents.
 */
abstract class SkillFactory extends BaseFactory
{
	/**
	 * Group to use for the Data Provider
	 *
	 * @var string
	 */
	protected $group = DataProvider::HERO;

	/**
	 * Path to the Entity's game Strings. Set by child.
	 *
	 * @var string
	 */
	protected $stringsPath = 'abiltalent';

	/**
	 * Keys to check for Entity game Strings. Set by child.
	 *
	 * @var string[]
	 */
	protected $stringsKeys = [
		'full',
		'name',
		'short',
		'cooldown',
	];

	/**
	 * Returns a Hero's Skills
	 *
	 * @param string $heroId
	 *
	 * @return array<Skill>
	 */
	abstract public function hero(string $heroId): array;

	/**
	 * Returns an iterable version of all Skills.
	 *
	 * @return Traversable
	 */
	public function getIterator(): Traversable
	{
		$skills = [];

		foreach ($this->data->getContents() as $heroId => $heroContents)
		{
			$skills = array_merge($skills, $this->hero($heroId));
		}

		return new ArrayIterator($skills);
	}
}
