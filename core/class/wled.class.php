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

/* * ***************************Includes********************************* */
require_once __DIR__  . '/../../../../core/php/core.inc.php';

class wled extends eqLogic {
	/*	   * *************************Attributs****************************** */
	
  /*
   * Permet de définir les possibilités de personnalisation du widget (en cas d'utilisation de la fonction 'toHtml' par exemple)
   * Tableau multidimensionnel - exemple: array('custom' => true, 'custom::layout' => false)
	public static $_widgetPossibility = array();
   */
	
	/*	   * ***********************Methode static*************************** */
	public static function request($_ip,$_endpoint = '',$_data = null,$_type='GET'){
		$url = 'http://' . $_ip . $_endpoint;
		if($_type=='GET' && is_array($_data) && count($_data) > 0){
		  $url .= '?';
		  foreach ($_data as $key => $value) {
			$url .= $key.'='.urlencode($value).'&';
		  }
		  $url = trim($url,'&');
		}
		log::add('wled','debug',' url : '.$url);
		log::add('wled','debug',' data : '.json_encode($_data));
		$request_http = new com_http($url);
		$request_http->setHeader(array(
		  'Content-Type: application/json'
		));
		if($_data !== null){
		  if($_type == 'POST'){
			$request_http->setPost(json_encode($_data));
		  }elseif($_type == 'PUT'){
			$request_http->setPut(json_encode($_data));
		  }
		}
		$result = $request_http->exec(60,1);
		return $result;
	}
	/*
	 * Fonction exécutée automatiquement toutes les minutes par Jeedom
	 */
	public static function cron() {
		foreach (self::byType('wled') as $eqLogic) {
            $autorefresh = $eqLogic->getConfiguration('autorefresh', '');
			$ipAddress = $eqLogic->getConfiguration('ip_address');
            if ($eqLogic->getIsEnable() == 1 && $ipAddress != '' && $autorefresh != '') {
                try {
                    $c = new Cron\CronExpression($autorefresh, new Cron\FieldFactory);
                    if ($c->isDue()) {
                        try {
                            $eqLogic->getWledStatus();
                            $eqLogic->refreshWidget();
                        } catch (Exception $exc) {
                            log::add('wled', 'error', __('Error in ', __FILE__) . $eqLogic->getHumanName() . ' : ' . $exc->getMessage());
                        }
                    }
                } catch (Exception $exc) {
                    log::add('wled', 'error', __('Expression cron non valide pour ', __FILE__) . $eqLogic->getHumanName() . ' : ' . $autorefresh);
                }
            }
        }
    }

	/*
	 * Fonction exécutée automatiquement toutes les 5 minutes par Jeedom
	  public static function cron5() {
	  }
	 */

	/*
	 * Fonction exécutée automatiquement toutes les 10 minutes par Jeedom
	  public static function cron10() {
	  }
	 */
	
	/*
	 * Fonction exécutée automatiquement toutes les 15 minutes par Jeedom
	  public static function cron15() {
	  }
	 */
	
	/*
	 * Fonction exécutée automatiquement toutes les 30 minutes par Jeedom
	  public static function cron30() {
	  }
	 */
	
	/*
	 * Fonction exécutée automatiquement toutes les heures par Jeedom
	  public static function cronHourly() {
	  }
	 */

	/*
	 * Fonction exécutée automatiquement tous les jours par Jeedom
	  public static function cronDaily() {
	  }
	 */



	/*	   * *********************Méthodes d'instance************************* */
	
 // Fonction exécutée automatiquement avant la création de l'équipement 
	public function preInsert() {
		
	}

 // Fonction exécutée automatiquement après la création de l'équipement 
	public function postInsert() {
		
	}

 // Fonction exécutée automatiquement avant la mise à jour de l'équipement 
	public function preUpdate() {
		
	}

 // Fonction exécutée automatiquement après la mise à jour de l'équipement 
	public function postUpdate() {
		
	}

 // Fonction exécutée automatiquement avant la sauvegarde (création ou mise à jour) de l'équipement 
	public function preSave() {
		
	}

 // Fonction exécutée automatiquement après la sauvegarde (création ou mise à jour) de l'équipement 
	public function postSave() {
        // Création des commandes

	    $onCmd = $this->getCmd(null, "on");
	    if (!is_object($onCmd)) {
            $onCmd = new wledCmd();
            $onCmd->setName(__('On', __FILE__));
            $onCmd->setEqLogic_id($this->getId());
            $onCmd->setLogicalId('on');
            $onCmd->setType('action');
            $onCmd->setSubType('other');
            $onCmd->setGeneric_type('LIGHT_ON');
            $onCmd->setIsVisible(1);
            $onCmd->setValue('on');
            $onCmd->setDisplay('icon','<i class="icon jeedom-lumiere-on"></i>');
            $onCmd->setOrder(0);
            $onCmd->save();
        }

        $offCmd = $this->getCmd(null, "off");
        if (!is_object($offCmd)) {
            $offCmd = new wledCmd();
        	$offCmd->setName(__('Off', __FILE__));
        	$offCmd->setEqLogic_id($this->getId());
        	$offCmd->setLogicalId('off');
        	$offCmd->setType('action');
        	$offCmd->setSubType('other');
            $offCmd->setGeneric_type('LIGHT_OFF');
        	$offCmd->setIsVisible(1);
        	$offCmd->setValue('off');
            $offCmd->setDisplay('icon','<i class="icon jeedom-lumiere-off"></i>');
        	$offCmd->setOrder(1);
        	$offCmd->save();
        }
        $stateCmd = $this->getCmd(null, "state");
        if (!is_object($stateCmd)) {
            $stateCmd = new wledCmd();
        	$stateCmd->setName(__('Etat', __FILE__));
        	$stateCmd->setEqLogic_id($this->getId());
        	$stateCmd->setLogicalId('state');
        	$stateCmd->setType('info');
        	$stateCmd->setSubType('binary');
            $stateCmd->setGeneric_type('LIGHT_STATE');
            $stateCmd->setIsVisible(0);
        	$stateCmd->setOrder(2);
            $stateCmd->save();
        } 
        $brightnessCmd = $this->getCmd(null, "brightness");
        if (!is_object($brightnessCmd)) {
            $brightnessCmd = new wledCmd();
        	$brightnessCmd->setName(__('Luminosité', __FILE__));
        	$brightnessCmd->setEqLogic_id($this->getId());
        	$brightnessCmd->setLogicalId('brightness');
        	$brightnessCmd->setType('action');
        	$brightnessCmd->setSubType('slider');
            $brightnessCmd->setGeneric_type('LIGHT_SLIDER');
            $brightnessCmd->setConfiguration('minValue','0');
            $brightnessCmd->setConfiguration('maxValue','100');
            $brightnessCmd->setConfiguration('lastCmdValue','100');
            $brightnessCmd->setIsVisible(1);
        	$brightnessCmd->setOrder(3);
        	$brightnessCmd->save();
        }
        
        $brightnessStateCmd = $this->getCmd(null, "brightness_state");
        if (!is_object($brightnessStateCmd)) {
            $brightnessStateCmd = new wledCmd();
        	$brightnessStateCmd->setName(__('Etat Luminosité', __FILE__));
        	$brightnessStateCmd->setEqLogic_id($this->getId());
        	$brightnessStateCmd->setLogicalId('brightness_state');
        	$brightnessStateCmd->setType('info');
        	$brightnessStateCmd->setSubType('numeric');
            $brightnessStateCmd->setGeneric_type('LIGHT_STATE');
            $brightnessStateCmd->setIsVisible(0);
        	$brightnessStateCmd->setOrder(4);
            $brightnessStateCmd->save();
        } 
        $colorCmd = $this->getCmd(null, "color");
        if (!is_object($colorCmd)) {
            $colorCmd = new wledCmd();
        	$colorCmd->setName(__('Couleur', __FILE__));
        	$colorCmd->setEqLogic_id($this->getId());
        	$colorCmd->setLogicalId('color');
        	$colorCmd->setType('action');
        	$colorCmd->setSubType('color');
            $colorCmd->setGeneric_type('LIGHT_SET_COLOR');
            $colorCmd->setIsVisible(1);
        	$colorCmd->setOrder(5);
        	$colorCmd->save();
        }
        
        $colorStateCmd = $this->getCmd(null, "color_state");
        if (!is_object($colorStateCmd)) {
            $colorStateCmd = new wledCmd();
        	$colorStateCmd->setName(__('Etat Couleur', __FILE__));
        	$colorStateCmd->setEqLogic_id($this->getId());
        	$colorStateCmd->setLogicalId('color_state');
        	$colorStateCmd->setType('info');
        	$colorStateCmd->setSubType('string');
            $colorStateCmd->setGeneric_type('LIGHT_COLOR');
            $colorStateCmd->setIsVisible(0);
        	$colorStateCmd->setOrder(6);
            $colorStateCmd->save();
        } 
        // Liens entre les commandes
        $onCmd->setValue($stateCmd->getId());
        $onCmd->save();
        $offCmd->setValue($stateCmd->getId());
        $offCmd->save();
        $brightnessCmd->setValue($brightnessStateCmd->getId());
        $brightnessCmd->save();
        $colorCmd->setValue($colorStateCmd->getId());
        $colorCmd->save();
	}

 // Fonction exécutée automatiquement avant la suppression de l'équipement 
	public function preRemove() {
		
	}

 // Fonction exécutée automatiquement après la suppression de l'équipement 
	public function postRemove() {
		
	}

	/*
	 * Non obligatoire : permet de modifier l'affichage du widget (également utilisable par les commandes)
	  public function toHtml($_version = 'dashboard') {

	  }
	 */

	/*
	 * Non obligatoire : permet de déclencher une action après modification de variable de configuration
	public static function postConfig_<Variable>() {
	}
	 */

	/*
	 * Non obligatoire : permet de déclencher une action avant modification de variable de configuration
	public static function preConfig_<Variable>() {
	}
	 */

	/*	   * **********************Getteur Setteur*************************** */
	public function getWledStatus() {
        log::add('wled', 'debug', 'Running getWledStatus');
		$endPoint ='/json/state';
		$ipAddress = $this->getConfiguration('ip_address');
		$result = wled::request($ipAddress, $endPoint, null, 'GET');
		log::add('wled', 'debug', 'request result '. $result);
	}
}

class wledCmd extends cmd {
	/*	   * *************************Attributs****************************** */
	
	/*
	  public static $_widgetPossibility = array();
	*/
	
	/*	   * ***********************Methode static*************************** */


	/*	   * *********************Methode d'instance************************* */

	/*
	 * Non obligatoire permet de demander de ne pas supprimer les commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
	  public function dontRemoveCmd() {
	  return true;
	  }
	 */

  // Exécution d'une commande  
	 public function execute($_options = array()) {
		
	 }

	/*	   * **********************Getteur Setteur*************************** */
}


