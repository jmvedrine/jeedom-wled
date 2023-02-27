Description
===

Plugin permettant de contrôler des led adressables via le contrôleur WLED.

Mais tout d'abord il faut préciser ce que sont les led adressables qu' on trouve sous forme 

- de ruban,

![ruban](../images/ruban.png)
- de "guirlande" (parfait pour un éclairage extérieur de Noël en mettant alimentation et contrôleur dans un coffret étanche)

![guirlande](../images/guirlande.png)
- ou de panneau (matrice)

![panneau](../images/panneau.png)

Ce sont des led où chaque led (ou plus rarement groupe de 2 ou 3 leds) possède son petit contrôleur qui permet de la commander individuellement.
On peut donc faire de très jolis effets puisque le côntroleur va pouvoir commander l'allumage, la luminosité ou la couleur de chaque led.

On peut les contrôler avec par exemple un contrôleur WifI comme le SP108E qu’on trouve sur Amazon, eBay, Aliexpress, … pour lequel il existe des app Android et IOS.

Si j’ai correctement lu la doc des différents plugins Jeedom que j’ai trouvé aucun ne gère ce type de contrôleur (mais j’ai peut-être mal lu).

Mais ce qui m'a spécialement intéressé c'est qu'il existe un fantastique contrôleur Wifi nommé WLED qu'on peut implanter sur un ESP8266 ou ESP32 (par exemple un Wemos ou un NodeMCU) qui permet de commander ces leds.

Vous pouvez consulter [le dépot Github de WED](https://github.com/Aircoookie/WLED) et [son wiki](https://github.com/Aircoookie/WLED/wiki)

Sachant que ce contrôleur possède une API très riche qui permet de communiquer avec lui par des requêtes JSON ou HTTP, j'ai décidé de faire un plugin pour interfacer ce contrôleur avec Jeedom

Bon assez de généralités. Pour débuter il vous faut

- des leds par exemple un ruban ou une guirlande avec des leds WS2812B ou WS2811 ou SK6812 ou WS2801 ou APA102
- un ESP8266 ou ESP32 avec le programme WLED téléchargé dessus et configuré pour votre réseau Wifi et vos leds. Je ne m'étendrai pas sur comment faire, il existe de multiples tutos et vidéos YouTube qui expliquent cela
- en option plutôt qu'un circuit "nu" ou bricolé sur une plaque je me permet de vous recommander le contrôleur [Dig Uno de Quinled](https://quinled.info/2018/09/15/quinled-dig-uno/) (et son grand frère Dig Quad qui peut contrôler 4 rubans). Il offre plusieurs avantages : il y a un fusible protecteur, il gère les tensions 5V et 12V, il y a un level shifter qui permet d'avoir un cable plus long entre le contrôleur et la première led sans que le signal ne se détériore,...

Ce contrôleur peut être acheté tout fait, [voir la page](https://quinled.info/2020/02/11/quinled-dig-uno-pre-assembled-available/) ou le monter vous-même, on peut dans ce cas acheter juste le circuit imprimé chez DirtyPCB ou PCBWay et les composants, [voir les infos ici](https://quinled.info/2020/05/08/quinled-dig-uno-hardware-guide-2/) ceci dit cela n'est intéressant financièrement que si vous prévoyez d'en monter une série.

Il existe aussi une version  avec 4 sorties (Quinled Dig Quad).

Je vous conseille avant de vous lancer dans le plugin d'installer l'application WLED sur votre smartphone Android ou IOS et de vérifier que tout est OK que vous arrivez bien à commander vos leds. Cela vous permettra ausi de connaître l'adresse IP de votre ruban sur votre réseau local.

Note : le plugin fonctionne en local sur votre réseau Wifi. il est totalement indépendant du web.

Configuration du plugin
===

Rien de spécial il suffit juste d'installer le plugin comme n'importe quel plugin Jeedom et de l'activer

Création des équipements
===

## Découverte des équipements par scan du réseau local

Vous pouvez cliquer sur le bouton Découverte et le plugin scannera votre réseau local à la recherche des contrôleurs Wled. Pour chaque équipement il récupérera l'adresse IP et le nom. Il ne vous restera plus qu'à les placer dans la pièce de votre choix pour pouvoir les utiliser. Par défaut l'intervalle d'actualisation est initialisé à "toutes les minutes" mais vous pouvez le changer.

Si dans l'appli smartphone (ou dans l'interface web) vous avez défini plusieurs segments, un objet Jeedom sera créé pour le segment 0 appelé segment principal (qui comprends toutes les leds) et un objet pour chaque segment.
Pour le segment principal (segment 0), les commandes on, off et luminosité agissent sur toutes les leds branchées sur le contrôleur situé à cette IP (et la commande off réinitialise les effets pour tous les segments) alors que pour les autres segments ces commandes agissent seulemnt sur les leds du segment.


## Création manuelle des équipements

Vous pouvez aussi créer un équipement manuellement en cliquant sur le bouton "+".

Pour chaque équipement en plus des informations habituelles communes à tous les équipements dans les plugins Jeedom, vous devez préciser

- l'adresse IP du contrôleur (en général sous la forme 192.168.x.x)
- le numéro du segment (0 pour le segment principal qui comprends toutes les leds)
- l'intervalle de rafraîchissement (auto-actualisation) des informations de l'équipement sous la forme d'une expression cron. N'hésitez pas à cliquer sur le petit bouton ? à droite si vous n'êtes pas familier avec les expressions cron et l'assistant fera le boulot pour vous.

Sauvegardez. Voila c'est fini.

Commandes
===

Pour le moment le nombre de commandes disponibles dans cette première version est faible. le plugin s'enrichira par la suite.

| Nom                                  | Type    | Sous type  | Rôle                                                                                                                                                               |
| :--:                                 | :---:   | :---:      | :---:                                                                                                                                                              |
| **On**                               | action  | autre      | Allume l'équipement (pour le segment principal allume toutes les leds de tous les segments contrôlés à cette ip).                                                                                                                                               |
| **Off**                              | action  | autre      | Éteint l'équipement (pour le segment principal allume toutes les leds de tous les segments contrôlés à cette ip).  .                                                                                                                                               |
| **Etat**                             | info    | binaire    | Indique si l'équipement est allumé ou éteint.                                                                                                                      |
| **Luminosité**                       | action  | curseur    | Règle la luminosité( min = 0, max =255, pour le segment principal règle la luminosité de toutes les leds de tous les segments contrôlés à cette ip).                                                                                                                            |
| **Etat Luminosité**                  | info    | numerique  | Valeur de la luminosité entre 0 et 255.                                                                                                                            |
| **Couleur**                          | action  | couleur    | Couleur principale (RVB, pour le moment le plugin ne gère pas les leds RVBW)                                                                                        |
| **Etat couleur**                     | info    | chaine     | Valeur hexadécimale de la couleur principale RVB.                                                                                                                  |
| **Effet**                            | action  | liste      | Effet (la liste est récupérée sur le contrôleur et peut donc varier suivant la version de WLED)                                                                     |
| **Etat effet**                       | info    | numerique  | Numéro de l'effet 
| **Nom effet**                        | info    | chaine     | Nom de l'effet (récupéré sur le contrôleur et peut donc varier suivant la version de WLED)                                                                                                                                        |
| **Vitesse effet**                    | action  | curseur    | Vitesse de l'effet (min = 0 est le plus lent, max = 255 est le plus rapide)                                                                                        |
| **Etat vitesse effet**               | info    | numerique  | Valeur de la vitesse de l'effet entre 0 et 255                                                                                                                     |
| **Intensité effet**                  | action  | curseur    | Intensité de l'effet (le résultat est variable suivant l'effet pour certains effets cette commande ne fait rien)                                                   |
| **Etat intensité effet**             | info    | numerique  | Valeur de l'intensité de l'effet                                                                                                                                   |
