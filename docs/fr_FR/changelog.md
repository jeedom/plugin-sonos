# Changelog Sonos controller

>**IMPORTANT**
>
>Pour rappel s'il n'y a pas d'information sur la mise à jour, c'est que celle-ci concerne uniquement de la mise à jour de documentation, de traduction ou de texte

- Ajout d'une configuration optionnelle pour indiquer, uniquement si nécessaire, le sous-réseau (vlan) sur lequel se trouvent vos enceintes Sonos si celui-ci est différent du sous-réseau (vlan) sur lequel se trouve Jeedom
- Corrections pour le message "Subscription renewal failed" et la perte de remontée des informations

# 26-04-2025

> Attention
> Refonte importante du plugin : une très grande partie du plugin a été réécrite dont toute la communication avec Sonos (démon) et certaines fonctionnalités ont été modifiées et ne fonctionnent plus comme précédemment, notamment la gestion des groupes ;
>
> Nécessite Jeedom 4.4.8
>
> Compatible Debian 11 et 12!
>
> Voir également [ce sujet sur community](https://community.jeedom.com/t/erreur-you-cannot-create-a-controller-instance-from-a-speaker-that-is-not-the-coordinator-of-its-group/128862) pour plus de détails

- Réécriture quasi totale du plugin, le démon a été entièrement réécrit en python (au lieu de PHP)
- Compatible Debian 11 et 12!
- Il n'y a plus de découverte à lancer manuellement et il n'est plus nécessaire (ni possible) d'ajouter manuellement un équipement, le plugin découvre automatiquement vos appareils Sonos et crée les équipements correspondants à chaque démarrage du démon.
- Il est également possible de demander de (re)synchroniser les équipements, favoris et listes de lecture sans redémarrer le démon depuis le panneau des équipements.
- Synchro automatique chaque heure pour corriger les désynchronisations éventuelles
- Mise à jour en (quasi) temps réel des commandes infos (un délai de 0,5s à quelques secondes max), plus de cron minute, y compris lorsqu'un changement est effectué hors Jeedom (via l'app Sonos par exemple)
- Refonte de la gestion de groupes (les anciennes commandes seront supprimées et de nouvelles ajoutées, voir documentation). Il est possible de joindre ou quitter un groupe, de contrôler la lecture du groupe depuis n'importe quel équipement du groupe sans se soucier de qui est le contrôleur. Le volume est, lui, toujours contrôlé par enceinte.
- Adaptation sur la fonction Text-to-Speech (TTS), **il sera nécessaire d'adapter la configuration du partage SAMBA**.
- Optimisation : plus de pertes de mémoire sur le démon et il consomme moins qu'auparavant.
- Optimisation de l'affichage de la pochette de la lecture en cours
- Optimisation sur la lecture des favoris
- Ajout de la possibilité de désactiver la tuile pré-configurée : vous êtes alors libre de configurer celle-ci comme vous le souhaitez en utilisant les widgets du core ou vos propres widgets, d'afficher ou de masquer les commandes de votre choix...

- Ajout d'une commande action **TV** pour basculer sur l'entrée *TV* sur les équipements compatibles
- Ajout d'une commande info **Mode de lecture** et action **Choisir mode de lecture** qui permet de sélectionner le mode de lecture parmi les possibilités suivantes: *Normal*, *Répéter tout*, *Aléatoire et répéter tout*, *Aléatoire sans répétition*, *Répéter le morceau*, *Aléatoire et répéter le morceau*
- Ajout d'une commande **Statut de lecture** qui donne la valeur "brut" de l'état de lecture (la commande existante **Statut** donne une valeur traduite en fonction de la langue configurée dans Jeedom)
- Ajout des commandes **Groupe statut** (indique si l'équipement est groupé ou non) et **Nom du groupe** dans le cas où l'équipement est groupé
- Ajout des commandes **Led on**, **Led off** et **Led statut** pour contrôler le voyant d'état
- Ajout d'une commande **Jouer une radio mp3** pour lire une radio mp3 directement via une URL (accessible sur internet par exemple)
- Ajout des commandes **Augmenter le volume** et **Diminuer le volume** de 1%
- Ajout d'une commande **Transition de volume** qui est très intéressante pour gérer les transitions de niveau de volume. 3 modes possible: *LINEAR*, *ALARM*, *AUTOPLAY*. Voir documentation pour plus d'information.
- Ajout des commandes **Loudness statut**, **Loudness on**, **Loudness off**
- Ajout des commandes **Fondu enchaîné statut**, **Fondu enchaîné on**, **Fondu enchaîné off**
- Ajout des commandes **Commandes tactiles statut**, **Commandes tactiles on**, **Commandes tactiles off**
- Ajout des commandes **Balance** (action/curseur) et **Balance statut** qui gère la balance selon une valeur comprise entre -100 (tout à gauche) et 100 (tout à droite)
- Ajout des commandes **Graves** (action/curseur) et **Graves statut** qui gère les graves selon une valeur comprise entre -10 et 10
- Ajout des commandes **Aigus** (action/curseur) et **Aigus statut** qui gèrent les aigus selon une valeur comprise entre -10 et 10
- Ajout de la commande **Mode fête** qui permet de grouper tous les Sonos ensemble
- Ajout de la commande **Mic statut** qui indique si le micro est activé ou non sur les Sonos équipés d'un micro
- Ajout d'une commande info **Batterie** sur les Sonos équipés d'une batterie indiquant le pourcentage de charge de la batterie
- Ajout d'une commande info **Chargement** sur les Sonos équipés d'une batterie qui indique si la charge est en cours ou non
- Ajout d'une commande info **Prochaine alarme** sur chaque Sonos donnant la date de la prochaine alarme programmée sur cette enceinte

# 25/04/2024

- Mise à jour de la documentation
- Nettoyage des accents dans les noms de partage (non supportés par le plugin)
- Suppression de la dépendance à PicoTTS (le plugin utilise le moteur global de TTS de Jeedom)
- Ajout Sonos Beam Gen 2

# 15/01/2024

- Préparation pour Jeedom 4.4
- Ajout Sonos Move 2

# 24/08/2023

- Ajout Ikea Symfonisk Floor Lamp

# 25/05/2023

- Ajout Sonos Era

# 18/10/2022

- Mise à jour liste des commandes pour Jeedom v4.3
- Ajout de la Sonos Ray

# 22/03/2022

- Support de la nouvelle enceinte SYMFONISK

# 01/02/2022

- Correction d'un bug sur le TTS

# 27/01/2022

- Optimisations V4.2

# 14/01/2022

- Ajout de la compatibilité avec la nouvelle enceinte SYMFONISK

# 27/12/2021

- Ajout de la compatibilité avec la nouvelle Sonos One

# 09/10/2021

- Ajout de la Sonos Five
- Ajout Sonos Roam
- Ajout Symfonisk Cadre
- Mise à jour immediate du volume en cas de changement par Jeedom, merci @Domochip

# 24/11/2020

- Nouvelle présentation de la liste des objets
- Ajout du tag "Compatibilité V4"

# 07/08/2020

- Prise en charge du Sonos ARC

# 24/01/2020

- Prise en charge du Sonos One S22

# 11/01/2020

- Prise en charge du Sonos Move
- Optimisation du code en cas de Sonos non connecté

# 16/12/2019

- Correction de bug si un sonos n'est pas joignable

# 21/10/2017

- Amélioration de la reprise suite à un TTS

# 15/10/2019

- Prise en charge du Sonos port
- Amélioration du script d'installation des dépendances

# 07/10/2019

- Amélioration du script d'installation des dépendances (pourra permettre de corriger dans certain cas les soucis de TTS)

# 23/09/2019

- Optimisations

# 01/09/2019

- Prise en charge de l'enceinte lampe Ikea SYMFONISK

# 12/08/2019

- Prise en charge de l'enceinte étagere Ikea SYMFONISK

# 23/04/2019

- Prise en charge des sonos one gen2

# 17/01/2019

- Correction de bugs dans le cas où les sonos ont été ajoutés manuellement

# 15/01/2019

**IMPORTANT NE MARCHE QUE AVEC PHP7, VOIR PAGE SANTE DE JEEDOM POUR VOTRE VERSION**

- Réecriture complète du plugin
- Prise en charge de la nouvelle api Sonos
- Prise en charge des sonos beam et One
- Correction de nombreux bugs
- Optimisations globales

**IMPORTANT**

- Compatible PHP7 uniquement
- Certaines fonctionnalités ont dû être retirées

# 2018

- Ajout de la gestion des favoris sonos
- Prise en charge des Sonos One et Playbase
- Correction de la langue avec PicoTTS
- Ajout d’une commande "Entrée de ligne"
- Mise à jour de la librairie de communication avec les Sonos
- Optimisation du chargement des playlists
- Ajout de picotts pour la génération du TTS en local
- Correction du bouton play/pause lors de la mise à jour du widget.
