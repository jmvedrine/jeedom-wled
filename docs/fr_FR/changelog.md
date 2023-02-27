# Changelog plugin wled

>**IMPORTANT**
>
>Pour rappel s'il n'y a pas d'information sur la mise à jour, c'est que celle-ci concerne uniquement de la mise à jour de documentation, de traduction ou de texte.

# 04/03/2021

Première version béta

# 16/02/2022

Correction d'un problème dans le widget a cause du nom des commandes Allumer/Eteindre changé en On/Off

# 19/10/2022

Compatibilité avec Jeedom 4.3 de l'onglet Commandes (affichage de l'état des commandes info).

# 10/11/2022

Nouvelle commande info de type chaîne donnant le nom de l'effet sélectionné (la commande Etat effet ne retournant que son numéro, cela peut être utile de récupérer son nom)

# 24/02/2023

Le plugin ne fonctionne pas avec Jeedom 3.x donc il nécessite maintenant Jeedom 4.2.
Les effets avec les noms 'RSVD' ou "-" ne sont pas valable (varie suivant le matériel) donc il sont maintenant retirés de la liste.

# 01/03/2023

Support des segments
Conformémént à la doc de WLED le segment 0 (segment principal) est toujours présent et comprends toute les leds. Eventuellement si d'autres segments ont été définis ils apparaissent avec le  numéro du segment dans le nom.
Les commandes on, off et luminosité agissent sur toutes les leds pour le segment 0 (et la commande off réinitialise les effets poour tous les segments) alors que pour les autres segments ces commandes agissent seulemnt sur les leds du segment.
