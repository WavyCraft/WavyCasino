<?php

declare(strict_types=1);

namespace wavycraft\wavycasino\manager;

use pocketmine\player\Player;

use pocketmine\Server;

use wavycraft\wavycasino\WavyCasino;

use wavycraft\wavycasino\task\RouletteTask;

final class Roulette {

    public static function spin(Player $player, int $amount, string $choice) : void{
        $result = mt_rand(0, 1) === 0 ? "red" : "black";

        WavyCasino::getInstance()->getScheduler()->scheduleDelayedTask(new RouletteTask($player, $amount, $choice, $result), 20 * 5);
    }
}