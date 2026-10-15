<?php

declare(strict_types=1);

namespace kuraLLsz;

final class Ranking{
	public const PAGE_SIZE = 5;

	/** @param array<string, int> $levels */
	public static function getTotalPages(array $levels) : int{
		return max(1, (int) ceil(count($levels) / self::PAGE_SIZE));
	}

	/** @param array<string, int> $levels
	 * @return array<string, int>
	 */
	public static function getPage(array $levels, int $page) : array{
		arsort($levels, SORT_NUMERIC);
		return array_slice($levels, (max(1, $page) - 1) * self::PAGE_SIZE, self::PAGE_SIZE, true);
	}
}
