<?php

declare(strict_types=1);

namespace wavycraft\wavycasino\task;

use pocketmine\scheduler\Task;

use pocketmine\player\Player;

use pocketmine\utils\Config;

use wavycraft\wavycasino\WavyCasino;
use wavycraft\wavyeconomy\api\WavyEconomyAPI;

use terpz710\messages\Messages;

class RouletteTask extends Task {

    public function __construct(
        private Player $player,
        private int $amount,
        private string $choice,
        private string $result
    ) {}

    public function onRun() : void{
        $config = new Config(WavyCasino::getInstance()->getDataFolder() . "roulette_messages.yml");

        if (!$this->player->isOnline()) return;

        $this->player->sendMessage((string) new Messages($config, "result-message", ["{result}"], [$this->result]));

        if (strtolower($this->choice) === $this->result) {
            $winAmount = $this->amount * 2;
            $this->player->sendMessage((string) new Messages($config, "win-message", ["{win_amount}"], [number_format($winAmount)]));
            WavyEconomyAPI::getInstance()->addMoney($this->player->getName(), $winAmount);
        } else {
            $this->player->sendMessage((string) new Messages($config, "loss-message", ["{loss_amount}"], [$this->amount]));
        }
    }
}
