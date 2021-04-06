<?php

namespace lovetwice1012\MyWarp;


use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\level\Level;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\level\Position;
use lovetwice1012\MyWarp\window\SimpleWindowForm;
use lovetwice1012\MyWarp\response\PlayerWindowResponse;
use lovetwice1012\MyWarp\window\CustomWindowForm;

class MyWarp extends PluginBase implements Listener
{
    
    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

  public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        switch (strtolower($command->getName())) {
                case "mywarp":
                  if($sender->hasPermission("lovetwice1012.mywarp.mywarp")){
                      $this->sendmanageform($sender);
                  }
                case "myw":
                  if($sender->hasPermission("lovetwice1012.mywarp.myw")){
                      $this->sendmanageform($sender);
                  }
               }
        }
        return true;
    }
    public function sendmanageform($sender){
                    if ($sender instanceof Player) {
                    $player = $sender->getPlayer();
                    $window = new SimpleWindowForm("mywarp menu", "§5Mywarp menu", "Please select the desired operation");
                    $window->addButton("warp", "Warp to the warp point");
                    $window->addButton("add", "Add a warp point");
                    $window->addButton("delete", "Delete the warp point");            
                    $window->showTo($player);
                    }
    }
    public function onResponse(PlayerWindowResponse $event){
        $player = $event->getPlayer();
        $form = $event->getForm();
        
        if($form->isClosed()) {
            return;
        }

        if($form->getName() === "mywarp menu"){
            switch($form->getClickedButton()->getText()){
                case "Warp to the warp point":
                if($player->hasPermission("lovetwice1012.mywarp.warp")){
                $mywarpconfig = new Config($this->getDataFolder() . $player->getName().".yml", Config::YAML);
                $window = new SimpleWindowForm("mywarp warp", "§5Mywarp menu", "Please select the point to warp");
                $datas = $mywarpconfig->getAll(true);
                foreach($datas as $data){
                    if($data !== null || $data !== undefined){
                        $window->addButton($data, $data);
                    }
                }
                $window->showTo($player);
                }
                break;
                case "Add a warp point":
                if($player->hasPermission("lovetwice1012.mywarp.add")){
                $window = new CustomWindowForm("mywarp add", "§5Mywarp menu", "Please fill in the items");
                $window->addInput("warpname", "Enter the warp point name");
                $window->showTo($player);
                }
                break;
                case "Delete the warp point":
                if($player->hasPermission("lovetwice1012.mywarp.delete")){
                $mywarpconfig = new Config($this->getDataFolder() . $player->getName().".yml", Config::YAML);
                $window = new SimpleWindowForm("mywarp delete", "§5Mywarp menu", "Select the location name you want to delete");
                $datas = $mywarpconfig->getAll(true);
                foreach($datas as $data){
                    if($data !== null || $data !== undefined){
                        $window->addButton($data, $data);
                    }
                }
                $window->showTo($player);
                }
                break;
                default:
                $player->sendMessage($form->getClickedButton()->getText());
                break;
            }
        }else if($form->getName() === "mywarp add"){
            $mywarpconfig = new Config($this->getDataFolder() . $player->getName().".yml", Config::YAML);
            $warpname = $form->getElement("warpname")->getFinalValue();
            $mywarpconfig->set($warpname, $player->getX().",".$player->getY().",".$player->getZ().",".$player->getLevel()->getFolderName());
            $mywarpconfig->save();
            $player->sendMessage("Added!");
        }else if($form->getName() === "mywarp delete"){
            $mywarpconfig = new Config($this->getDataFolder() . $player->getName().".yml", Config::YAML);
            $warpname = $form->getClickedButton()->getText();
            $mywarpconfig->remove($warpname);
            $mywarpconfig->save();
            $player->sendMessage("Deleted!");
        }else if($form->getName() === "mywarp warp"){
            $mywarpconfig = new Config($this->getDataFolder() . $player->getName().".yml", Config::YAML);
            $warpname = $form->getClickedButton()->getText();
            $data = $mywarpconfig->get($warpname);
            $value = explode(",", $data);
            $world = Server::getInstance()->getLevelByName($value[3]);
            $player->teleport(new Position(floatval($value[0]), floatval($value[1]), floatval($value[2]), $world));
            $player->sendMessage("Warped!");
        }
    }

}
