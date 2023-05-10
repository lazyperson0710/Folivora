<?php

declare(strict_types = 1);

namespace lazyperson0710\folivora\util\message\send_message;

use lazyperson710\core\packet\SoundPacket;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class SendActionBarMessage {

    public static function Send(Player $player, string $message, string $prefix, bool $success, ?string $sound = null) : void {
        if ($success === true) {
            if ($sound === null) {
                $sound = 'note.harp';
            }
            $type = TextFormat::GREEN;
        } else {
            if ($sound === null) {
                $sound = 'note.bass';
            }
            $type = TextFormat::RED;
        }
        SoundPacket::Send($player, $sound);
        $player->sendActionBarMessage(TextFormat::AQUA . $prefix . TextFormat::GRAY . ' >> ' . $type . $message);
    }
}