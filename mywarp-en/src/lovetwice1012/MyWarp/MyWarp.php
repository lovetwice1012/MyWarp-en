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
    public $language;
    public $menudescription;
    public $menuwarpbutton;
    public $menuaddbutton;
    public $menudeletebutton;
    public $warpmenudescription;
    public $addmenudescription;
    public $addtextinputbox;
    public $deletemenudescription;
    public $addwarppointsuccessresponse;
    public $deletewarppointsuccessresponse;
    public $teleportsuccessresponse;
    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->language = new Config($this->getDataFolder() . "language.yml", Config::YAML);
        if(!$this->language->exists("configsetupfinish")){
            $this->language->set("menudescription","Please select the desired operation");
            $this->language->set("menuwarpbutton","Warp to the warp point");
            $this->language->set("menuaddbutton","Add a warp point");
            $this->language->set("menudeletebutton","Delete the warp point");
            $this->language->set("warpmenudescription","Please select the point to warp");
            $this->language->set("addmenudescription","Please fill in the items");
            $this->language->set("addtextinputbox","Enter the warp point name");
            $this->language->set("deletemenudescription","Select the location name you want to delete");
            $this->language->set("addwarppointsuccessresponse","Added!");
            $this->language->set("deletewarppointsuccessresponse","Deleted!");
            $this->language->set("teleportsuccessresponse","Warped!!");
            $this->language->set("configsetupfinish",true);
            $this->language->save();
        }
            $this->menudescription = $this->language->get("menudescription");
            $this->menuwarpbutton = $this->language->get("menuwarpbutton");
            $this->menuaddbutton = $this->language->get("menuaddbutton");
            $this->menudeletebutton = $this->language->get("menudeletebutton");
            $this->warpmenudescription = $this->language->get("warpmenudescription");
            $this->addmenudescription = $this->language->get("addmenudescription");
            $this->addtextinputbox = $this->language->get("addtextinputbox");
            $this->deletemenudescription = $this->language->get("deletemenudescription");
            $this->addwarppointsuccessresponse = $this->language->get("addwarppointsuccessresponse");
            $this->deletewarppointsuccessresponse = $this->language->get("deletewarppointsuccessresponse");
            $this->teleportsuccessresponse = $this->language->get("teleportsuccessresponse");
            
    }

  public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        switch (strtolower($command->getName())) {
                case "mywarp":
                  if($sender->hasPermission("lovetwice1012.mywarp.mywarp")){
                      $this->sendmanageform($sender);
                  }
                break;
                case "myw":
                  if($sender->hasPermission("lovetwice1012.mywarp.myw")){
                      $this->sendmanageform($sender);
                  }
                break;
        }
        return true;
    }
    public function sendmanageform($sender){
                    if ($sender instanceof Player) {
                    $player = $sender->getPlayer();
                    $window = new SimpleWindowForm("mywarp menu", "§5Mywarp menu", $this->menudescription);
                    $window->addButton("warp", $this->menuwarpbutton);
                    $window->addButton("add", $this->menuaddbutton);
                    $window->addButton("delete", $this->menudeletebutton);            
                    $window->showTo($player);
                    }
    }
    public function onResponse(PlayerWindowResponse $event){
        $player = $event->getPlayer();
        $form = $event->getForm();
        
        if($form->isClosed()) {
            return;
        }

        if(($form instanceof SimpleWindowForm) && $form->getName() === "mywarp menu"){
            switch($form->getClickedButton()->getText()){
                case $this->menuwarpbutton:
                if($player->hasPermission("lovetwice1012.mywarp.warp")){
                $mywarpconfig = new Config($this->getDataFolder() . $player->getName().".yml", Config::YAML);
                $window = new SimpleWindowForm("mywarp warp", "§5Mywarp menu", $this->warpmenudescription);
                $datas = $mywarpconfig->getAll(true);
                foreach($datas as $data){
                    if($data !== null || $data !== undefined){
                        $window->addButton($data, $data);
                    }
                }
                $window->showTo($player);
                }
                break;
                case $this->menuaddbutton:
                if($player->hasPermission("lovetwice1012.mywarp.add")){
                $window = new CustomWindowForm("mywarp add", "§5Mywarp menu", $this->addmenudescription);
                $window->addInput("warpname", $this->addtextinputbox);
                $window->showTo($player);
                }
                break;
                case $this->menudeletebutton:
                if($player->hasPermission("lovetwice1012.mywarp.delete")){
                $mywarpconfig = new Config($this->getDataFolder() . $player->getName().".yml", Config::YAML);
                $window = new SimpleWindowForm("mywarp delete", "§5Mywarp menu", $this->deletemenudescription);
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
        }else if(($form instanceof CustomWindowForm ) && $form->getName() === "mywarp add"){
            $mywarpconfig = new Config($this->getDataFolder() . $player->getName().".yml", Config::YAML);
            $warpname = $form->getElement("warpname")->getFinalValue();
            $mywarpconfig->set($warpname, $player->getX().",".$player->getY().",".$player->getZ().",".$player->getLevel()->getFolderName());
            $mywarpconfig->save();
            $player->sendMessage($this->addwarppointsuccessresponse);
        }else if(($form instanceof SimpleWindowForm) && $form->getName() === "mywarp delete"){
            $mywarpconfig = new Config($this->getDataFolder() . $player->getName().".yml", Config::YAML);
            $warpname = $form->getClickedButton()->getText();
            $mywarpconfig->remove($warpname);
            $mywarpconfig->save();
            $player->sendMessage($this->deletewarppointsuccessresponse);
        }else if(($form instanceof SimpleWindowForm) && $form->getName() === "mywarp warp"){
            $mywarpconfig = new Config($this->getDataFolder() . $player->getName().".yml", Config::YAML);
            $warpname = $form->getClickedButton()->getText();
            $data = $mywarpconfig->get($warpname);
            $value = explode(",", $data);
            $world = Server::getInstance()->getLevelByName($value[3]);
            $player->teleport(new Position((float)$value[0], (float)$value[1], (float)$value[2], $world));
            $player->sendMessage($this->teleportsuccessresponse);
        }
    }

}
