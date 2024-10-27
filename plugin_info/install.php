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
				$cmd->save();
			}
		}
  }

// Fonction exécutée automatiquement après la suppression du plugin
  function wled_remove() {

  }
