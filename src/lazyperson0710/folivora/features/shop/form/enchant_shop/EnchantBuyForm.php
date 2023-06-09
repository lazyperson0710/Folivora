<?php

declare(strict_types = 1);

namespace lazyperson0710\folivora\features\shop\form\enchant_shop;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;
use deceitya\MiningLevel\MiningLevelAPI;
use lazyperson0710\folivora\features\electronic_money\currency\Money;
use lazyperson0710\folivora\features\shop\database\EnchantShopAPI;
use lazyperson0710\folivora\features\shop\event\EnchantShopBuyEvent;
use lazyperson0710\folivora\util\message\send_message\SendMessage;
use lazyperson0710\folivora\util\packet\SendForm;
use lazyperson0710\folivora\util\packet\SoundPacket;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\data\bedrock\EnchantmentIds;
use pocketmine\item\Durable;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\player\Player;

class EnchantBuyForm extends CustomForm {

    private int $level;
    private Enchantment $enchantment;
    private string $enchantName;

    public function __construct(Player $player, int $level, Enchantment $enchantment, string $enchantName) {
        $this->level = $level;
        $this->enchantment = $enchantment;
        $this->enchantName = $enchantName;
        $this
            ->setTitle('Enchant Form')
            ->addElements(
                new Label('現在の所持金 -> ' . Money::getInstance()->getFunction($player)->getCurrency() . "円\n"),
                new Label('購入価格 -> ' . EnchantShopAPI::getInstance()->getBuy($enchantName) * $this->level . '円'),
                new Label("{$enchantName}を{$level}レベル付与しますか？\n"),
                new Label('§c注意 : エンチャントレベルは上書きされます(1lvを二度付与しても2lvにはならず1lvになります)§r'),
                new Label('所持しているアイテム -> ' . $player->getInventory()->getItemInHand()->getName()),
            );
    }

    public function handleSubmit(Player $player): void {
        $price = EnchantShopAPI::getInstance()->getBuy($this->enchantName) * $this->level;
        $item = $player->getInventory()->getItemInHand();
        if (Money::getInstance()->getFunction($player)->getCurrency() <= $price) {
            SendMessage::Send($player, "所持金が足りない為処理が中断されました。要求価格 -> {$price}円", 'Enchant', false, 'dig.chain');
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) < EnchantShopAPI::getInstance()->getMiningLevel($this->enchantName)) {
            SendForm::Send($player, (new EnchantSelectForm("§cMiningLevelが足りないためformを開けませんでした\n要求レベル ->" . EnchantShopAPI::getInstance()->getMiningLevel($this->enchantName) . 'lv')));
            SoundPacket::Send($player, 'dig.chain');
            return;
        }
        if (!$player->getInventory()->getItemInHand() instanceof Durable) {
            SendForm::Send($player, (new EnchantSelectForm("§cアイテムが不正です\nアイテム -> " . $player->getInventory()->getItemInHand()->getName())));
            SoundPacket::Send($player, 'dig.chain');
            return;
        }
        if ($this->enchantment === VanillaEnchantments::SILK_TOUCH()) {
            if ($item->hasEnchantment(EnchantmentIdMap::getInstance()->fromId(EnchantmentIds::FORTUNE))) {
                SendMessage::Send($player, '幸運がついているため、シルクタッチはつけられません', 'Enchant', false, 'dig.chain');
                return;
            }
        }
        if ($this->enchantment === EnchantmentIdMap::getInstance()->fromId(EnchantmentIds::FORTUNE)) {
            if ($player->getInventory()->getItemInHand()->getNamedTag()->getTag('MiningTools_Expansion_FortuneEnchant') !== null) {
                SendMessage::Send($player, 'シルクタッチを幸運に変化されたMiningToolsはエンチャントから不正に強化することはできません', 'Enchant', false, 'dig.chain');
                return;
            }
            if ($item->hasEnchantment(VanillaEnchantments::SILK_TOUCH())) {
                SendMessage::Send($player, 'シルクタッチエンチャントがついているため、幸運はつけられません', 'Enchant', false, 'dig.chain');
                return;
            }
        }
        Money::getInstance()->getFunction($player)->reduceCurrency($price);
        $item->addEnchantment(new EnchantmentInstance($this->enchantment, $this->level));
        $player->getInventory()->setItemInHand($item);
        SendMessage::Send($player, "{$this->enchantName}を{$this->level}レベルで付与しました", 'Enchant', true, 'break.amethyst_block');
        $event = new EnchantShopBuyEvent($player, $this->enchantment, $this->enchantName, $this->level, $price);
        $event->call();
    }

}
