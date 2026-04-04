<?php

namespace kuraLLsz;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\Command\CommandSender;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Event;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\scheduler\CallbackTask;
use _64FF00\PureChat\PureChat;
use pocketmine\item\Item;
use pocketmine\block\Block;
use pocketmine\{Player, Server};
use pocketmine\entity\Entity;
use pocketmine\entity\Human;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener{
	public $bar = [];
	public $cfg;
	public $config;
	public $level;
	public $exp;
	public $saxp;
	public function onEnable(){
		@mkdir($this->getDataFolder());		
		$this->saxp = new Config($this->getDataFolder() . "SeviyeAtlamaXp.yml", Config::YAML);
	$this->exp = new Config($this->getDataFolder() . "Exp.yml", Config::YAML);
$this->level = new Config($this->getDataFolder() . "Seviyeler.yml", Config::YAML);
	        $this->cfg = new Config($this->getDataFolder() . "AdaLevel.yml", Config::YAML,[
             "komut-baslik" => "§f•§aThông Tin Đảo Của Bạn:",
             "ToplevelBaslik" => "§f•§aXếp Hạng Cấp Độ Đảo §f[§a",
             "Seviyen" => "§f•§aCấp Độ Hiện Tại:§f",
             "MevcutXp" => "§f•§aKinh Nghiệm Hiện Tại:§f",
             "seviyeatla" => "§f•§aChúc Mừng Bạn Đã Lên Cấp Độ Đảo",
             "seviyeatla2" => "§f•§aCấp Độ Đảo Hiện Tại:§f ",
             "seviyeatla3" => "§f•§aKinh Nghiệm Hiện Tại:§f "
             
        ]);      
	$this->getServer()->getPluginManager()->registerEvents($this, $this);
	    $this->PureChat = $this->getServer()->getPluginManager()->getPlugin("PureChat");
	$this->getLogger()->info("§f•§aPlugin Đã Hoạt Động");
}


public function blockKoyma(BlockPlaceEvent $e){
		$user = $e->getPlayer()->getName();
		$user2 = $e->getPlayer();	
	$block = $e->getBlock();
	$oyuncu = $e->getPlayer();
	$isim = $e->getPlayer()->getName();
		if($this->exp->get($user) < $this->saxp->get($user)){
$this->exp->set($user, $this->exp->get($user) +1);
     } else {
               $this->exp->set($user, 0);
               $this->level->set($user,$this->level->get($user) +1);
               $this->PureChat->setPrefix($this->level->get($user), $e->getPlayer());
   $this->saxp->set($user, $this->saxp->get($user) + 20);
 $oyuncu->sendTitle($this->cfg->get("seviyeatla"), $this->cfg->get("seviyeatla2") . $this->level->get($user)."\n".$this->cfg->get("seviyeatla3").$this->exp->get($user));
         $this->saxp->save();
        $this->level->save();
       $this->exp->save();
           }
       }
       
       public function onCommand(CommandSender $s, Command $kmt, $label, array $args){
       		$user = $s->getPlayer()->getName();
		$user2 = $s->getPlayer();
       	$o = $s->getPlayer()->getName();
       	if($kmt->getName() == "is"){
       		if(isset($args[0])){
       		if(strtolower($args[0] == "khongbiettacdung")){
#Không Biết Tác Dụng Ở Khúc Này =). Nên Đừng Bấm Trong Server
       		
       		if(isset($this->bar[strtolower($user2->getName())])){
					$s->sendMessage("§f•§aKhông Biết Tác Dụng");
					unset($this->bar[strtolower($user2->getName())]);
				}
				else{
					$s->sendMessage("§f•§aKhông Biết Tác Dụng");
		$this->bar[strtolower($user2->getName())] = [];
		
				}
				}
				if(strtolower($args[0] == "info")){
					$s->sendMessage($this->cfg->get("komut-baslik"));
					$s->sendMessage($this->cfg->get("Seviyen")." §f".$this->level->get($user));
					$s->sendMessage($this->cfg->get("MevcutXp")." §f".$this->exp->get($user));
				
				
				}
				} else {
					$s->sendMessage("§f•§aGhi §f/is info §aĐể Xem Thông Tin Đảo");
				}
       	}
       	if($kmt->getName() == "topis"){
 	$max = 0;
				foreach($this->level->getAll() as $c){
				$max += count($c);
				}
				$max = ceil(($max / 5));
				$page = array_shift($args);
				$page = max(1, $page);
				$page = min($max, $page);
				$page = (int)$page;
				$s->sendMessage($this->cfg->get("ToplevelBaslik") . $page."§f/§a".$max."§f]");
				$aa = $this->level->getAll();
				arsort($aa);
				$i = 0;
				foreach($aa as $b=>$a){
				if(($page - 1) * 5 <= $i && $i <= ($page - 1) * 5 + 4){
				$i1 = $i + 1;
				$s->sendMessage("§f[§a".$i1."§f] §a".$b." §f: §a ".$a);
				}
				$i++;
				}
				}
       	}
      
   public function oG(PlayerJoinEvent $event){
      	$user = $event->getPlayer()->getName();
      	if($this->saxp->get($user) > 0){
        } else {
            $this->saxp->set($user, 150);
            $this->level->set($user, 1);
            $this->exp->set($user, 1);
            	}
            	 $this->PureChat->setPrefix($this->level->get($user), $event->getPlayer());
            	$oy = $event->getPlayer();
		$this->bar[strtolower($oy->getName())] = [];
    }
  }