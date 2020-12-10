<?php

declare(strict_types=1);

namespace Tatter\Heroes\Factories;

use Tatter\Heroes\Entities\Ability;
use Tatter\Heroes\Providers\DataProvider;
use ArrayIterator;
use Traversable;

/**
 * Ability Factory
 *
 * Uses a Hero DataProvider to
 * create Abilities.
 */
class AbilityFactory extends SkillFactory
{
	/**
	 * Returns a Hero's Abilities
	 *
	 * @param string $heroId
	 *
	 * @return array<Ability>
	 */
	public function hero(string $heroId): array
	{
		$heroContent = $this->data->getContents()->$heroId;

		$abilities = $this->unit($heroId, $heroContent->unitId, $heroContent);

		if (isset($heroContent->heroUnits))
		{
			foreach ($heroContent->heroUnits as $unitCollection)
			{
				foreach ($unitCollection as $unitId => $unitContent)
				{
					$abilities = array_merge($abilities, $this->unit($heroId, $unitId, $unitContent));
				}
			}
		}

		return $abilities;
	}

	/**
	 * Returns a unit's Abilities
	 *
	 * @param string $heroId
	 * @param string $unitId
	 * @param object $unitContent
	 *
	 * @return array<Ability>
	 */
	protected function unit(string $heroId, string $unitId, object $unitContent): array
	{
		$heroContent = $this->data->getContents()->$heroId;
		$abilities   = [];

		// Abilities
		foreach ($unitContent->abilities as $type => $collection)
		{
			foreach ($collection as $contents)
			{
				$this->injectStrings($contents);

				$abilities[] = new Ability($heroId, $unitId, $type, false, $contents);
			}
		}

		// Sub-abilities
		if (isset($unitContent->subAbilities))
		{
			foreach ($unitContent->subAbilities as $subs)
			{
				foreach ($subs as $subId => $types)
				{
					foreach ($types as $type => $collection)
					{
						foreach ($collection as $contents)
						{

							$this->injectStrings($contents);

							$abilities[] = new Ability($heroId, $unitId, $type, true, $contents);
						}
					}
				}
			}
		}

		return $abilities;
	}
}
