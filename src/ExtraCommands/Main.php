<?php
namespace ExtraCommands;


use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\utils\Config;
use pocketmine\Player;
use pocketmine\utils\TextFormat;



class Main extends PluginBase implements Listener{
	
	public function onEnable(){
		$this->getserver()->getPluginManager()->registerEvents($this, $this);
		if(!is_dir($this->getDataFolder())){
			@mkdir($this->getDataFolder());
		}
		$this->config = new Config($this->getDataFolder()."config.yml", CONFIG::YAML, array(
			"ServerName" => "<Enter server name here>",
			"StaffJoinMessage" => "§l§aStaff Member§r§a {player} §r§ajoined!",
			"StaffQuitMessage" => "§l§aStaff Member§r§a {player} §r§aleft us!",
		));
		$this->staff = new Config($this->getDataFolder()."staff.yml", CONFIG::YAML, array(
			"# Staff" => " Staff Names",
			"DRedDog" => true,
			"DRedDogXbox" => true,
		));
	}

	/**
	 * @param PlayerJoinEvent $event
	 *
	 * @priority MONITOR
	 * @ignoreCancelled true
	 */
	public function onJoin(PlayerJoinEvent $event){
		$player = $event->getPlayer();
		$staff = $this->staff->get($player);
		if($staff){
			$name = $player->getDisplayName();
			$event->setJoinMessage("");
			$message = $this->config->get("StaffJoinMessage");
			$message = str_replace("{player}", $name, $message);
			$this->getServer()->broadcastMessage(TextFormat::GREEN.$message);
		}
	}
	
	public function onQuit(PlayerQuitEvent $event){
		$player = $event->getPlayer();
		$staff = $this->staff->get($player);
		if($staff){
			$name = $player->getDisplayName();
			$event->setQuitMessage("");
			$message = $this->config->get("StaffQuitMessage");
			$message = str_replace("{player}", $name, $message);
			$this->getServer()->broadcastMessage(TextFormat::GREEN.$message);
		}
	}
	
	public function onCommand(CommandSender $sender, Command $command, $label, array $args){
		switch($command->getName()){
			case "tps":
				$tps = ( $this->getServer()->getTicksPerSecond() ) ;
				$sender->sendMessage($this->formatMessage("TPS: " . $tps));
				return true;
			case "clearchat":
				for($i = 0; $i < 20; $i ++){
				$this->getServer()->broadcastMessage("\n");
				}
				$sender->sendMessage(TextFormat::GREEN . "Chat Cleared!");
				return true;
		}
	}

	
	public function formatMessage($string, $confirm = false) {
		if($confirm) {
			return TextFormat::BLUE . "[" . $this->config->get("ServerName") . "] \n" . TextFormat::GREEN . "~ " . "$string";
		} else {	
			return TextFormat::BLUE . "[" . $this->config->get("ServerName") . "] \n" . TextFormat::RED . "~ " . "$string";
		}
	}
	


}