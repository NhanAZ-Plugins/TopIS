<?php

declare(strict_types=1);

namespace{
	require dirname(__DIR__) . "/src/kuraLLsz/IslandProgress.php";
	require dirname(__DIR__) . "/src/kuraLLsz/Ranking.php";

	function check(bool $condition, string $message) : void{
		if(!$condition){ throw new \RuntimeException($message); }
	}

	$progress = \kuraLLsz\IslandProgress::afterBlockPlacement(148, 150, 2);
	check($progress === ["experience" => 149, "requiredExperience" => 150, "level" => 2, "leveledUp" => false], "Below-threshold placement must keep current level and threshold.");
	$progress = \kuraLLsz\IslandProgress::afterBlockPlacement(149, 150, 2);
	check($progress === ["experience" => 0, "requiredExperience" => 170, "level" => 3, "leveledUp" => true], "Reaching the threshold must advance once and preserve the 20-XP step.");

	$levels = ["PlayerA" => 4, "PlayerB" => 20, "PlayerC" => 1, "PlayerD" => 13, "PlayerE" => 8, "PlayerF" => 7, "PlayerG" => 6, "PlayerH" => 5, "PlayerI" => 3, "PlayerJ" => 2, "PlayerK" => 9];
	check(\kuraLLsz\Ranking::getTotalPages($levels) === 3, "Leaderboard page count must use the number of player records.");
	check(array_keys(\kuraLLsz\Ranking::getPage($levels, 1)) === ["PlayerB", "PlayerD", "PlayerK", "PlayerE", "PlayerF"], "Leaderboard entries must be ranked by numeric level.");
	check(array_keys(\kuraLLsz\Ranking::getPage($levels, 3)) === ["PlayerC"], "The final leaderboard page must contain only remaining players.");
	check(\kuraLLsz\Ranking::getTotalPages([]) === 1 && \kuraLLsz\Ranking::getPage([], 1) === [], "An empty leaderboard must render one empty page.");

	echo "TopIS progression thresholds and leaderboard pagination checks passed.\n";
}
