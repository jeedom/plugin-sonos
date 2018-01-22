---
key_do_not_edit_lang: fr
key_do_not_edit_label:
    key_do_not_edit_titreMenu: Sommaire
    key_do_not_edit_btnAffiche: Déplier
    key_do_not_edit_btnMasquer: Cacher
---
Le plugin Sonos permet de piloter les Sonos Play 1, 3, 5, Sonos Connect,
Sonos Connect AMP et Sonos Playbar. Il va vous permettre de voir l’état
du/des Sonos et d’effectuer des actions dessus (lecture, pause, suivant,
précédent, volume, choix d’une playlist…​)

Configuration du plugin 
=======================

La configuration est très simple, après téléchargement du plugin, il
vous suffit de l’activer et c’est tout. Le plugin va rechercher les
Sonos sur votre réseau et créer les équipements automatiquement. De
plus, s’il y a une correspondance entre les objets Jeedom et les pièces
du Sonos, Jeedom affectera automatiquement les Sonos dans les bonnes
pièces.

> **Tip**
>
> Ce plugin nécessite que les Sonos soit alimentés (même en mode veille)
> pour ne pas provoquer d’erreur.

Si plus tard vous ajoutez un Sonos, vous pouvez soit créer un équipement
Sonos en donnant l’IP à Jeedom ou cliquer sur "Rechercher les
équipements Sonos"

-   **Voix** : choix de la voix lors du TTS

-   **Partage** : nom du partage ainsi que le chemin du dossier

-   **Nom d’utilisateur pour le partage** : nom d’utilisateur pour
    accéder au partage

-   **Mot de passe du partage** : mot de passe du partage

-   **Découverte** : découvre automatiquement les sonos (ne marche pas
    sur une installation de type docker où il faut créer à la main
    chaque sonos)

-   **Dépendance Sonos** : installer les dépendances sonos pour le TTS

> **Important**
>
> Les messages trop longs ne peuvent être transmis en TTS (la limite
> dépend du fournisseur de TTS, en général environ 100 caractères)

Configuration des équipements 
=============================

La configuration des équipements Sonos est accessible à partir du menu
Plugins puis multimedia

Vous retrouvez ici toute la configuration de votre équipement :

-   **Nom de l’équipement Sonos** : nom de votre équipement Sonos

-   **Objet parent** : indique l’objet parent auquel appartient
    l’équipement

-   **Activer** : permet de rendre votre équipement actif

-   **Visible** : le rend visible sur le dashboard

-   **Modèle** : le modèle de votre Sonos (à ne changer que si ce n’est
    pas le bon)

-   **IP** : l’IP de votre Sonos, peut être utile si votre Sonos change
    d’IP ou si vous le remplacez

En-dessous vous retrouvez la liste des commandes :

-   **Nom** : nom de la commande

-   **Configuration avancée (petites roues crantées)** : permet
    d’afficher la configuration avancée de la commande (méthode
    d’historisation, widget…​)

-   **Tester** : permet de tester la commande

Comme commande vous retrouverez :

-   **Jouer playlist** : commande de type message permettant de lancer
    une playlist, il suffit dans le titre de mettre le nom de
    la playlist. Vous pouvez mettre "random" dans message pour mélanger
    la playlist avant la lecture.

-   **Jouer favoris** :  commande de type message permettant de lancer 
    un favoris, il suffit dans le titre de mettre le nom du favoris. Vous 
    pouvez mettre "random" dans message pour mélanger le favoris avant la lecture.

-   **Jouer une radio** : commande de type message permettant de lancer
    une radio, il suffit dans le titre de mettre le nom de la radio
    (ATTENTION celle ci doit être dans les radios favorites).

-   **Ajout un haut-parleur** : permet d’ajouter un haut-parleur
    (un Sonos) au haut-parleur courant (pour associer 2 Sonos
    par exemple). Il faut mettre le nom de la pièce du sonos à ajouter
    dans le titre (le champs message n’est pas utilisé ici).

-   **Supprimer un haut-parleur** : permet de supprimer un haut-parleur
    (un Sonos) au haut-parleur courant (pour dissocier 2 Sonos
    par exemple). Il faut mettre le nom de la piéce du Sonos à supprimer
    dans le titre (le champs message n’est pas utilisé ici).

-   **Aléatoire statut** : indique si on est en mode aléatoire ou non

-   **Aléatoire** : inverse le statut du mode aléatoire

-   **Répéter statut** : indique si on est en mode répété ou non

-   **Répéter** : inverse le statut du mode "répéter"

-   **Image** : lien vers l’image de l’album

-   **Album** : nom de l’album en cours de lecture

-   **Artiste** : nom de l’artiste en cours de lecture

-   **Piste** : nom de la piste en cours de lecture

-   **Muet** : passe en muet

-   **Précédent** : piste précédente

-   **Suivant** : piste suivante

-   **Lecture** : passer en lecture

-   **Pause** : mettre en pause

-   **Stop** : arrêter la lecture

-   **Volume** : modifier le volume (de 0 à 100)

-   **Volume statut** : niveau du volume

-   **Statut** : statut (pause, lecture, transition…​)

-   **Dire** : permet de lire un texte sur le Sonos (voir partie TTS).
    Dans le titre vous pouvez mettre le volume et dans le message, le
    message à lire

> **Note**
>
> Pour la lecture des playlists vous pouvez mettre des options (dans la
> case option). Pour lancer la playlist en lecture aléatoire il faut
> mettre dedans "random"

TTS 
===

Le TTS (text-to-speech) vers le Sonos nécessite d’avoir un partage
Windows (Samba) sur le réseau (imposé par Sonos, pas moyen de faire
autrement). Il faut vous donc un NAS sur le réseau. La configuration est
assez simple il faut mettre le nom ou l’ip du NAS (attention à bien
mettre la même chose que ce qui est déclaré sur Sonos) et le chemain
(relatif), le nom d’utilisateur et le mot de passe (attention
l’utilisateur doit avoir les droits en écriture)

> **Important**
>
> Il faut absolument mettre un mot de passe pour que cela marche

> **Important**
>
> Il faut aussi absolument un sous-répertoire pour que le fichier vocal
> soit correctement créé.

**Voici un exemple de configuration (merci @masterfion) :.**

Côté NAS, voici ma config :

-   le dossier Jeedom est partagé

-   l’utilisateur Sonos a un accès Lecture/Ecriture (nécessaire
    pour Jeedom)

-   l’utilisateur guest a un accès en Lecture seule (nécessaire pour
    les Sonos)

Côté Plugin Sonos, voici ma config :

-   Partage :

    -   Champ 1 : 192.168.xxx.yyy

    -   Champ 2 : Jeedom/TTS

-   Nom d’utilisateur : Sonos et son mot de passe…​

Côté Bibliothèque Sonos (appli PC)

-   le chemin est : //192.168.xxx.yyy/Jeedom/TTS

Le panel 
========

Le plugin Sonos met aussi à disposition un panel qui rassemble tous vos
Sonos. Disponible à partir du menu Accueil → Sonos Controller :

> **Important**
>
> Pour avoir le panel il faut l’activer dans la configuration du plugin