<?php

declare(strict_types=1);

namespace kuraLLsz;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

final class Main extends PluginBase implements Listener{
	private Config $experienceConfig;
	private Config $levelConfig;
	private Config $requiredExperienceConfig;
	private Config $messagesConfig;
	private ?Plugin $pureChat = null;

	protected function onEnable() : void{
		$dataFolder = $this->getDataFolder();
		if(!is_dir($dataFolder) && !mkdir($dataFolder, 0777, true) && !is_dir($dataFolder)){
			throw new \RuntimeException("Could not create TopIS data folder");
		}

		$this->requiredExperienceConfig = new Config($dataFolder . "SeviyeAtlamaXp.yml", Config::YAML);
		$this->experienceConfig = new Config($dataFolder . "Exp.yml", Config::YAML);
		$this->levelConfig = new Config($dataFolder . "Seviyeler.yml", Config::YAML);
		$this->messagesConfig = new Config($dataFolder . "AdaLevel.yml", Config::YAML, [
			"komut-baslik" => "§f•§aThông Tin Đảo Của Bạn:",
			"ToplevelBaslik" => "§f•§aXếp Hạng Cấp Độ Đảo §f[§a",
			"Seviyen" => "§f•§aCấp Độ Hiện Tại:§f",
			"MevcutXp" => "§f•§aKinh Nghiệm Hiện Tại:§f",
			"seviyeatla" => "§f•§aChúc Mừng Bạn Đã Lên Cấp Độ Đảo",
			"seviyeatla2" => "§f•§aCấp Độ Đảo Hiện Tại:§f ",
			"seviyeatla3" => "§f•§aKinh Nghiệm Hiện Tại:§f "
		]);

		$this->pureChat = $this->getServer()->getPluginManager()->getPlugin("PureChat");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	protected function onDisable() : void{
		$this->savePlayerData();
	}

	public function onJoin(PlayerJoinEvent $event) : void{
		$this->initializePlayer($event->getPlayer());
		$this->applyPureChatPrefix($event->getPlayer());
	}

	public function onBlockPlace(BlockPlaceEvent $event) : void{
		if($event->isCancelled()){
			return;
		}

		$player = $event->getPlayer();
		$this->initializePlayer($player);

		$name = $player->getName();
		$experience = $this->getStoredInteger($this->experienceConfig, $name, 1, 0);
		$requiredExperience = $this->getStoredInteger($this->requiredExperienceConfig, $name, 150, 1);
		$level = $this->getStoredInteger($this->levelConfig, $name, 1, 1);
		$progress = IslandProgress::afterBlockPlacement($experience, $requiredExperience, $level);

		$this->experienceConfig->set($name, $progress["experience"]);
		$this->requiredExperienceConfig->set($name, $progress["requiredExperience"]);
		$this->levelConfig->set($name, $progress["level"]);

		if($progress["leveledUp"]){
			$this->applyPureChatPrefix($player);
			$player->sendTitle(
				$this->getMessage("seviyeatla", "§aĐảo của bạn đã lên cấp!"),
				$this->getMessage("seviyeatla2", "§aCấp độ hiện tại: ") . $progress["level"] . "\n" .
				$this->getMessage("seviyeatla3", "§aKinh nghiệm hiện tại: ") . $progress["experience"]
			);
			$this->savePlayerData();
		}
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
		$commandName = strtolower($command->getName());
		if($commandName === "is"){
			if(!$sender instanceof Player){
				$sender->sendMessage("Chỉ người chơi đang online mới có thể xem thông tin đảo.");
				return true;
			}

			if(!isset($args[0]) || strtolower($args[0]) !== "info"){
				$sender->sendMessage("§f•§aDùng /is info để xem thông tin đảo.");
				return true;
			}

			$this->initializePlayer($sender);
			$name = $sender->getName();
			$sender->sendMessage($this->getMessage("komut-baslik", "Thông Tin Đảo Của Bạn:"));
			$sender->sendMessage($this->getMessage("Seviyen", "Cấp Độ Hiện Tại:") . " §f" . $this->getStoredInteger($this->levelConfig, $name, 1, 1));
			$sender->sendMessage($this->getMessage("MevcutXp", "Kinh Nghiệm Hiện Tại:") . " §f" . $this->getStoredInteger($this->experienceConfig, $name, 1, 0));
			return true;
		}

		if($commandName === "topis"){
			$page = isset($args[0]) && ctype_digit($args[0]) ? (int) $args[0] : 1;
			$totalPages = Ranking::getTotalPages($this->getLevels());
			$page = min(max(1, $page), $totalPages);
			$sender->sendMessage($this->getMessage("ToplevelBaslik", "Xếp Hạng Cấp Độ Đảo [") . $page . "§f/§a" . $totalPages . "§f]");

			$rank = ($page - 1) * Ranking::PAGE_SIZE + 1;
			foreach(Ranking::getPage($this->getLevels(), $page) as $name => $level){
				$sender->sendMessage("§f[§a" . $rank . "§f] §a" . $name . " §f: §a" . $level);
				$rank++;
			}
			return true;
		}

		return false;
	}

	private function initializePlayer(Player $player) : void{
		$name = $player->getName();
		$changed = false;
		foreach([
			[$this->requiredExperienceConfig, 150],
			[$this->levelConfig, 1],
			[$this->experienceConfig, 1]
		] as [$config, $default]){
			if(!$config->exists($name)){
				$config->set($name, $default);
				$changed = true;
			}
		}

		if($changed){
			$this->savePlayerData();
		}
	}

	private function applyPureChatPrefix(Player $player) : void{
		if($this->pureChat === null || !is_callable([$this->pureChat, "setPrefix"])){
			return;
		}

		$level = $this->getStoredInteger($this->levelConfig, $player->getName(), 1, 1);
		call_user_func([$this->pureChat, "setPrefix"], (string) $level, $player);
	}

	private function getMessage(string $key, string $fallback) : string{
		$value = $this->messagesConfig->get($key, $fallback);
		return is_string($value) ? $value : $fallback;
	}

	private function getStoredInteger(Config $config, string $key, int $default, int $minimum) : int{
		$value = $config->get($key, $default);
		if(!is_numeric($value)){
			return $default;
		}

		$value = (int) $value;
		return $value >= $minimum ? $value : $default;
	}

	/** @return array<string, int> */
	private function getLevels() : array{
		$levels = [];
		foreach($this->levelConfig->getAll() as $name => $level){
			if(is_string($name) && is_numeric($level)){
				$levels[$name] = max(1, (int) $level);
			}
		}
		return $levels;
	}

	private function savePlayerData() : void{
		$this->requiredExperienceConfig->save();
		$this->experienceConfig->save();
		$this->levelConfig->save();
	}
}
