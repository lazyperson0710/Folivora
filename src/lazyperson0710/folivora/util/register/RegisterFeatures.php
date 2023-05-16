<?php

declare(strict_types = 1);

namespace lazyperson0710\folivora\util\register;

use lazyperson0710\folivora\features\debug\Debug;
use lazyperson0710\folivora\features\electronic_money\ElectronicMoney;
use lazyperson0710\folivora\util\plugin_base\IPluginBase;
use pocketmine\Server;

class RegisterFeatures {

    /** @var IPluginBase[] */
    private array $features = [];

    /**
     * 仕組み上、登録した順に読み込みますので優先度調整に気を付けてください。
     *
     * @param Server $server
     * @return void
     */
    public static function enableFeatures(Server $server) : void {
        $registerFeatures = new RegisterFeatures();
        $registerFeatures->setFeatures(new Debug());
        $registerFeatures->setFeatures(new ElectronicMoney());
        foreach ($registerFeatures->getFeatures() as $pluginBase) {
            $pluginBase->onEnable($server);
        }
    }

    /**
     * 追加はこの関数のみで行ってください。
     *
     * @param IPluginBase $pluginBase
     * @return void
     */
    private function setFeatures(IPluginBase $pluginBase) : void {
        $this->features[] = $pluginBase;
    }

    /**
     * 追加された機能を取得します。
     *
     * @return IPluginBase[]
     */
    public function getFeatures() : array {
        return $this->features;
    }

    /**
     * 登録した順にdisableを実行します。
     * 実行順を変更したい場合は配列を操作して下さい。@param Server $server
     *
     * @return void
     * @see $features
     *
     */
    public static function disableFeatures(Server $server) : void {
        $registerFeatures = new RegisterFeatures();
        foreach ($registerFeatures->getFeatures() as $pluginBase) {
            $pluginBase->onDisable($server);
        }
    }

}
