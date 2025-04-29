<?php

declare(strict_types=1);

namespace wavycraft\wavycasino\command;

use pocketmine\command\CommandSender;

use pocketmine\player\Player;

use pocketmine\utils\Config;

use wavycraft\wavycasino\WavyCasino;
use wavycraft\wavycasino\games\Roulette;

use wavycraft\wavyeconomy\api\WavyEconomyAPI;

use terpz710\messages\Messages;

use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;

class RouletteCommand extends BaseCommand {

    protected function prepare() : void{
        $this->setPermission("wavycasino.roulette");
        
        $this->registerArgument(0, new IntegerArgument("amount"));
        $this->registerArgument(1, new RawStringArgument("color"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
        $config = new Config(WavyCasino::getInstance()->getDataFolder() . "roulette_messages.yml");

        if (!$sender instanceof Player) {
            $sender->sendMessage("This command can only be used in-game!");
            return;
        }

        $amount = (int)($args["amount"]);
        $color = strtolower($args["color"]);

        if ($amount <= 0) {
            $sender->sendMessage((string) new Messages($config, "invalid-amount"));
            return;
        }

        if (!in_array($color, ["red", "black"])) {
            $sender->sendMessage((string) new Messages($config, "invalid-color"));
            return;
        }

        $balance = WavyEconomyAPI::getInstance()->getBalance($sender->getName());

        if ($balance < $amount) {
            $sender->sendMessage((string) new Messages($config, "not-enough-money", ["{balance}"], [(string)number_format($balance)]));
            return;
        }

        $sender->sendMessage((string) new Messages($config, "spin-wheel", ["{amount}", "{color}"], [number_format($amount), $color]));
        WavyEconomyAPI::getInstance()->removeMoney($sender->getName(), $amount);
        Roulette::spin($sender, $amount, $color);
    }
}
