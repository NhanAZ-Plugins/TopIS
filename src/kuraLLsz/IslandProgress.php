<?php

declare(strict_types=1);

namespace kuraLLsz;

final class IslandProgress{
	/**
	 * @return array{experience: int, requiredExperience: int, level: int, leveledUp: bool}
	 */
	public static function afterBlockPlacement(int $experience, int $requiredExperience, int $level) : array{
		if($experience + 1 < $requiredExperience){
			return [
				"experience" => $experience + 1,
				"requiredExperience" => $requiredExperience,
				"level" => $level,
				"leveledUp" => false
			];
		}

		return [
			"experience" => 0,
			"requiredExperience" => $requiredExperience + 20,
			"level" => $level + 1,
			"leveledUp" => true
		];
	}
}
