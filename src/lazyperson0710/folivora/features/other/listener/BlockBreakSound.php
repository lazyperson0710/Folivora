<?php

declare(strict_types = 1);

namespace lazyperson0710\folivora\features\other\listener;

use lazyperson0710\folivora\util\packet\SoundPacket;
use lazyperson0710\PlayerSetting\object\PlayerSettingPool;
use lazyperson0710\PlayerSetting\object\settings\normal\DestructionSoundSetting;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;

class BlockBreakSound implements Listener {

    /**
     * @param BlockBreakEvent $event
     * @return void
     */
    public function onBreak(BlockBreakEvent $event): void {
        if (PlayerSettingPool::getInstance()->getSettingNonNull($event->getPlayer())->getSetting(DestructionSoundSetting::getName())?->getValue() === true) {
            $volume = mt_rand(1, 2);
            $pitch = mt_rand(5, 10);
            SoundPacket::Send($event->getPlayer(), 'random.orb', $volume, $pitch, true, 10);
        }
    }
}
