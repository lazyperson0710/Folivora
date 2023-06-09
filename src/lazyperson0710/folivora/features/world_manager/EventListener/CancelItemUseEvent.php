<?php

declare(strict_types = 1);

namespace lazyperson0710\folivora\features\world_manager\EventListener;

use lazyperson0710\folivora\util\message\send_message\SendTip;
use pocketmine\block\BlockLegacyIds;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityTrampleFarmlandEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\ItemIds;
use pocketmine\item\VanillaItems;
use pocketmine\Server;

class CancelItemUseEvent implements Listener {

    public function onUes(PlayerItemUseEvent $event): void {
        $this->banItems($event);
    }

    private function banItems(BlockPlaceEvent|PlayerItemUseEvent|PlayerInteractEvent $event): void {
        switch ($event->getPlayer()->getInventory()->getItemInHand()->getId()) {
            case BlockLegacyIds::INFO_UPDATE;
            case BlockLegacyIds::INFO_UPDATE2;
            case BlockLegacyIds::RESERVED6;
            case BlockLegacyIds::ICE:
                //BanItems
            case ItemIds::BANNER:
            case ItemIds::BANNER_PATTERN:
            case ItemIds::STANDING_BANNER:
            case ItemIds::WALL_BANNER:
            case ItemIds::FIRE_CHARGE:
            case ItemIds::FIRE:
            case ItemIds::FIREBALL:
            case ItemIds::LAVA:
            case ItemIds::FLOWING_LAVA:
            case ItemIds::STILL_LAVA:
            case ItemIds::MINECART:
            case ItemIds::MINECART_WITH_CHEST:
            case ItemIds::MINECART_WITH_COMMAND_BLOCK:
            case ItemIds::MINECART_WITH_HOPPER:
            case ItemIds::MINECART_WITH_TNT:
            case ItemIds::ENCHANTED_BOOK:
            case ItemIds::ENDER_CHEST:
            case ItemIds::ENDER_EYE:
            case ItemIds::LEAD:
            case ItemIds::ARMOR_STAND:
            case ItemIds::CHORUS_FLOWER:
            case ItemIds::CHORUS_FRUIT:
            case ItemIds::CHORUS_FRUIT_POPPED:
            case ItemIds::CHORUS_PLANT:
            case ItemIds::BOAT:
            case ItemIds::FIREWORKS:
            case ItemIds::FIREWORKS_CHARGE:
            case ItemIds::CARROT_ON_A_STICK:
            case ItemIds::LINGERING_POTION:
            case ItemIds::SPLASH_POTION:
            case ItemIds::PAINTING:
            case ItemIds::FLINT_AND_STEEL:
            case ItemIds::PISTON:
            case ItemIds::STICKY_PISTON:
            case ItemIds::REPEATER:
            case ItemIds::COMPARATOR:
                if (!Server::getInstance()->isOp($event->getPlayer()->getName())) {
                    $event->cancel();
                }
                SendTip::Send($event->getPlayer(), 'このアイテムは使用できません', 'Cancel', false);
                break;
        }
        if ($event->getPlayer()->getInventory()->getItemInHand()->getId() === 325) {
            if ($event->getPlayer()->getInventory()->getItemInHand()->getMeta() !== 1) {
                if (!Server::getInstance()->isOp($event->getPlayer()->getName())) {
                    $event->cancel();
                }
                SendTip::Send($event->getPlayer(), 'このアイテムは使用できません', 'Cancel', false);
            }
        }
        if ($event->getPlayer()->getInventory()->getItemInHand()->getNamedTag()->getTag('MiningToolsRangeCostItem') !== null) {
            $event->cancel();
            SendTip::Send($event->getPlayer(), 'このアイテムはMiningToolsの強化にのみ使用可能です/mt', 'Cancel', false);
        }
        if ($event->getPlayer()->getInventory()->getItemInHand()->getNamedTag()->getTag('MiningToolsEnchantCostItem') !== null) {
            $event->cancel();
            SendTip::Send($event->getPlayer(), 'このアイテムはMiningToolsの強化にのみ使用可能です/mt', 'Cancel', false);
        }
        if ($event->getPlayer()->getInventory()->getItemInHand()->getNamedTag()->getTag('EnablingMiningSettingItem') !== null) {
            $event->cancel();
            SendTip::Send($event->getPlayer(), 'このアイテムはMiningToolsの強化にのみ使用可能です/mt', 'Cancel', false);
        }
    }

    public function onPlace(BlockPlaceEvent $event): void {
        $this->banItems($event);
    }

    public function onInteract(PlayerInteractEvent $event): void {
        $this->banItems($event);
    }

    public function onBreak(BlockBreakEvent $event): void {
        foreach ($event->getDrops() as $item) {
            switch ($item->getId()) {
                case BlockLegacyIds::INFO_UPDATE:
                case BlockLegacyIds::INFO_UPDATE2:
                case BlockLegacyIds::RESERVED6:
                    $event->setDrops([
                        VanillaItems::NETHER_STAR()->setCount(1),
                    ]);
            }
        }
    }

    public function onEntityTrampleFarmland(EntityTrampleFarmlandEvent $event): void {
        $event->cancel();
    }

}
