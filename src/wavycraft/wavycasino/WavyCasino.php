<?php

declare(strict_types=1);

namespace wavycraft\wavycasino;

use pocketmine\plugin\PluginBase;

use wavycraft\wavycasino\command\RouletteCommand;

use CortexPE\Commando\PacketHooker;

class WavyCasino extends PluginBase {

    protected static self $instance;

    protected function onLoad() : void{
        self::$instance = $this;
    }

    protected function onEnable() : void{
        $this->saveResource("roulette_messages.yml");

        if (!PacketHooker::isRegistered()) {
            PacketHooker::register($this);
        }

        $this->getServer()->getCommandMap()->registerAll("WavyCasino", [
            new RouletteCommand($this, "roulette", "Take a chance to double your money"),
        ]);
    }

    public static function getInstance() : self{
        return self::$instance;
    }
}
