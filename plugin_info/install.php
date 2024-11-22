<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';

// Fonction exécutée automatiquement après l'installation du plugin
  function wled_install() {

  }

// Fonction exécutée automatiquement après la mise à jour du plugin
  function wled_update() {
        // normalizeName ne marche pas sur Allumer et Eteindre ce qui crée un bug sur le widget.
        foreach (eqLogic::byType('wled') as $eqLogic) {
            $cmd = $eqLogic->getCmd(null, 'on');
            if (is_object($cmd)) {
                $cmd->setName('On');
                $cmd->save();
            }
            $cmd = $eqLogic->getCmd(null, 'off');
            if (is_object($cmd)) {
                $cmd->setName('Off');
                $cmd->save();
            }
            $cmd = $eqLogic->getCmd(null, 'effect_name');
            if ( ! is_object($cmd) ) {
                $cmd->setName(__('Nom effet', __FILE__));
                $cmd->setEqLogic_id($eqLogic->getId());
                $cmd->setLogicalId('effect_name');
                $cmd->setType('info');
                $cmd->setSubType('string');
                $cmd->setIsVisible(1);
                $cmd->setOrder(13);
                $cmd->save();
            }
            $cmd = $eqLogic->getCmd(null, 'preset');
            if (is_object($cmd)) {
                $cmd->setDisplay('title_disable', 1);
                $cmd->setDisplay('message_placeholder', __('Preset', __FILE__));
                $cmd->setName(__('Preset par numéro', __FILE__));
                $cmd->save();
            }
            $numseg = $eqLogic->getConfiguration('segment', 0);
            if ($numseg == 0) {
                // création des commandes globales
                $powerOnCmd = $eqLogic->getCmd(null, "power_on");
                if (!is_object($powerOnCmd)) {
                    $powerOnCmd = new wledCmd();
                    $powerOnCmd->setName('Ruban on');
                    $powerOnCmd->setEqLogic_id($eqLogic->getId());
                    $powerOnCmd->setLogicalId('power_on');
                    $powerOnCmd->setType('action');
                    $powerOnCmd->setSubType('other');
                    $powerOnCmd->setGeneric_type('LIGHT_ON');
                    $powerOnCmd->setIsVisible(1);
                    $powerOnCmd->setDisplay('icon','<i class="icon jeedom-lumiere-on"></i>');
                    $powerOnCmd->setTemplate('dashboard', 'light');
                    $powerOnCmd->setTemplate('mobile', 'light');
                    $powerOnCmd->setOrder(0);
                    $powerOnCmd->save();
                }

                $powerOffCmd = $eqLogic->getCmd(null, "power_off");
                if (!is_object($powerOffCmd)) {
                    $powerOffCmd = new wledCmd();
                    $powerOffCmd->setName('Ruban off');
                    $powerOffCmd->setEqLogic_id($eqLogic->getId());
                    $powerOffCmd->setLogicalId('power_off');
                    $powerOffCmd->setType('action');
                    $powerOffCmd->setSubType('other');
                    $powerOffCmd->setGeneric_type('LIGHT_OFF');
                    $powerOffCmd->setIsVisible(1);
                    $powerOffCmd->setDisplay('icon','<i class="icon jeedom-lumiere-off"></i>');
                    $powerOffCmd->setTemplate('dashboard', 'light');
                    $powerOffCmd->setTemplate('mobile', 'light');
                    $powerOffCmd->setOrder(1);
                    $powerOffCmd->save();
                }
                $powerStateCmd = $eqLogic->getCmd(null, "power_state");
                if (!is_object($powerStateCmd)) {
                    $powerStateCmd = new wledCmd();
                    $powerStateCmd->setName(__('Ruban état', __FILE__));
                    $powerStateCmd->setEqLogic_id($eqLogic->getId());
                    $powerStateCmd->setLogicalId('power_state');
                    $powerStateCmd->setType('info');
                    $powerStateCmd->setSubType('binary');
                    $powerStateCmd->setGeneric_type('LIGHT_STATE');
                    $powerStateCmd->setIsVisible(0);
                    $powerStateCmd->setOrder(2);
                    $powerStateCmd->save();
                } 
                $globalBrightnessCmd = $eqLogic->getCmd(null, "global_brightness");
                if (!is_object($globalBrightnessCmd)) {
                    $globalBrightnessCmd = new wledCmd();
                    $globalBrightnessCmd->setName(__('Ruban luminosité', __FILE__));
                    $globalBrightnessCmd->setEqLogic_id($eqLogic->getId());
                    $globalBrightnessCmd->setLogicalId('global_brightness');
                    $globalBrightnessCmd->setType('action');
                    $globalBrightnessCmd->setSubType('slider');
                    $globalBrightnessCmd->setGeneric_type('LIGHT_SLIDER');
                    $globalBrightnessCmd->setConfiguration('minValue','0');
                    $globalBrightnessCmd->setConfiguration('maxValue','255');
                    $globalBrightnessCmd->setIsVisible(1);
                    $globalBrightnessCmd->setOrder(3);
                    $globalBrightnessCmd->save();
                }
                $globalBrightnessStateCmd = $eqLogic->getCmd(null, "global_brightness_state");
                if (!is_object($globalBrightnessStateCmd)) {
                    $globalBrightnessStateCmd = new wledCmd();
                    $globalBrightnessStateCmd->setName(__('Ruban état luminosité', __FILE__));
                    $globalBrightnessStateCmd->setEqLogic_id($eqLogic->getId());
                    $globalBrightnessStateCmd->setLogicalId('global_brightness_state');
                    $globalBrightnessStateCmd->setType('info');
                    $globalBrightnessStateCmd->setSubType('numeric');
                    $globalBrightnessStateCmd->setGeneric_type('LIGHT_BRIGHTNESS');
                    $globalBrightnessStateCmd->setIsVisible(0);
                    $globalBrightnessStateCmd->setOrder(4);
                    $globalBrightnessStateCmd->save();
                }
                $presetByListCmd = $eqLogic->getCmd(null, 'presetbylist');
                if (!is_object($presetByListCmd)) {
                    $presetByListCmd = new wledCmd();
                    $presetByListCmd->setName(__('Preset', __FILE__));
                    $presetByListCmd->setEqLogic_id($eqLogic->getId());
                    $presetByListCmd->setLogicalId('presetbylist');
                    $presetByListCmd->setType('action');
                    $presetByListCmd->setSubType('select');
                    // The listValue will be updated later.
                    $presetByListCmd->setGeneric_type('LIGHT_MODE');
                    $presetByListCmd->setIsVisible(1);
                    $presetByListCmd->setOrder(37);
                    $presetByListCmd->save();
                }
                $presetStateCmd = $eqLogic->getCmd(null, "preset_state");
                if (!is_object($presetStateCmd)) {
                    $presetStateCmd = new wledCmd();
                    $presetStateCmd->setName(__('Etat preset', __FILE__));
                    $presetStateCmd->setEqLogic_id($eqLogic->getId());
                    $presetStateCmd->setLogicalId('preset_state');
                    $presetStateCmd->setType('info');
                    $presetStateCmd->setSubType('numeric');
                    $presetStateCmd->setIsVisible(0);
                    $presetStateCmd->setOrder(38);
                    $presetStateCmd->save();
                }
                // Liens entre les commandes
                $powerOnCmd->setValue($powerStateCmd->getId());
                $powerOnCmd->save();
                $powerOffCmd->setValue($powerStateCmd->getId());
                $powerOffCmd->save();
                $globalBrightnessCmd->setValue($globalBrightnessStateCmd->getId());
                $globalBrightnessCmd->save();
                $presetByListCmd->setValue($presetStateCmd->getId());
                $presetByListCmd->save();
            } else {
                $presetByListCmd = $eqLogic->getCmd(null, 'presetbylist');
                if (is_object($presetByListCmd)) {
                    $presetByListCmd->remove();
                }
                $presetStateCmd = $eqLogic->getCmd(null, "preset_state");
                if (is_object($presetStateCmd)) {
                    $presetStateCmd->remove();
                }
                $presetCmd = $eqLogic->getCmd(null, 'preset');
                if (is_object($presetCmd)) {
                    $presetCmd->remove();
                }
                $psaveCmd = $eqLogic->getCmd(null, 'psave');
                if (is_object($psaveCmd)) {
                    $psaveCmd->remove();
                }
            }
        }
  }

// Fonction exécutée automatiquement après la suppression du plugin
  function wled_remove() {

  }
