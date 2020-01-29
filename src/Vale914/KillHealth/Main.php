<?php
declare(strict_types=1);

namespace Vale914\KillHealth;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;

class Main extends PluginBase implements Listener{

    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveDefaultConfig();
    }

    /**
     * @param PlayerDeathEvent $event
     */
    public function onDeath(PlayerDeathEvent $event) : void{
        $cause = $event->getPlayer()->getLastDamageCause();
        if($cause instanceof EntityDamageByEntityEvent){
            $damager = $cause->getDamager();
            if($damager instanceof Player){
                if(in_array($event->getPlayer()->getLevel()->getFolderName(), $this->getConfig()->get("worlds"))){
                    $damager->addEffect(new EffectInstance(Effect::getEffect(Effect::REGENERATION), 2, 2, true));
                    $damager->addEffect(new EffectInstance(Effect::getEffect(Effect::STRENGTH), 1, 1, true));
                    $damager->sendMessage(str_replace(["{player}", "{killername}"], [$event->getPlayer()->getName(), $damager->getName()], $this->getConfig()->get("killer-message")));
                    $event->setDeathMessage(str_replace(["{player}", "{killername}"], [$event->getPlayer()->getName(), $damager->getName()], $this->getConfig()->get("death-message")));
                }
            }
        }
    }
}
