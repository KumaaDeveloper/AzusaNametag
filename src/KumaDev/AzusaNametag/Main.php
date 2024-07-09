<?php

namespace KumaDev\AzusaNametag;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\Server;

class Main extends PluginBase implements Listener {

    private Config $config;
    private array $hiddenNameTags = [];
    private bool $isGlobalHide = false;

    public function onEnable(): void {
        $this->saveResource('config.yml');
        $this->config = new Config($this->getDataFolder() . 'config.yml', Config::YAML);
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if (!$sender instanceof Player) {
            $sender->sendMessage("This command can only be used in-game.");
            return true;
        }

        switch ($command->getName()) {
            case "hidenametag":
                if ($this->isGlobalHide) {
                    $sender->sendMessage($this->config->get("messages")["already_hidden_nametag"]);
                } else {
                    $this->hideAllNameTags($sender);
                    $sender->sendMessage($this->config->get("messages")["hide_nametag_success"]);
                    Server::getInstance()->broadcastMessage($this->config->get("broadcast")["hide_nametag"]);
                }
                return true;
            case "shownametag":
                if (!$this->isGlobalHide) {
                    $sender->sendMessage($this->config->get("messages")["already_visible_nametag"]);
                } else {
                    $this->showAllNameTags($sender);
                    $sender->sendMessage($this->config->get("messages")["remove_nametag_success"]);
                    Server::getInstance()->broadcastMessage($this->config->get("broadcast")["show_nametag"]);
                }
                return true;
            default:
                return false;
        }
    }

    public function hideAllNameTags(Player $sender): void {
        $this->isGlobalHide = true;
        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
            $this->hiddenNameTags[$player->getName()] = $player->getNameTag();
            $player->setNameTag("");
        }
    }

    public function showAllNameTags(Player $sender): void {
        $this->isGlobalHide = false;
        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
            if (isset($this->hiddenNameTags[$player->getName()])) {
                $player->setNameTag($this->hiddenNameTags[$player->getName()]);
                unset($this->hiddenNameTags[$player->getName()]);
            }
        }
    }

    public function onPlayerJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        if ($this->isGlobalHide) {
            $this->hiddenNameTags[$player->getName()] = $player->getNameTag();
            $player->setNameTag("");
        }
    }
}