# Plugin Sonos

Le plugin Sonos permet de piloter les Sonos Play 1, 3, 5, Sonos Connect, Sonos Connect AMP, Sonos Playbar, Ikea Symfonisk... Il va vous permettre de voir l’état du/des Sonos et d’effectuer des actions (lecture, pause, suivant, précédent, volume, choix d’une playlist…).

# Configuration du plugin

La configuration est très simple, après téléchargement du plugin, il vous suffit de l’activer, d'installer les dépendances et de démarrer le démon.
Le plugin va rechercher les Sonos sur votre réseau et créer les équipements automatiquement. De plus, s’il y a une correspondance entre les objets Jeedom et les pièces du Sonos, Jeedom affectera automatiquement les Sonos dans les bonnes pièces.

> **Important**
> Vos équipements Sonos doivent être joignable directement par la machine hébergeant Jeedom et ils doivent être capable de joindre Jeedom en retour sur le port TCP 1400.

> **ASTUCE**
>
> Lors de la découverte initiale, il est fortement conseillé de ne pas avoir de sonos groupés sous peine d'avoir des erreurs.

Si plus tard vous ajoutez un Sonos, vous pouvez cliquer sur **Synchroniser** dans la page des équipements ou redémarrer le démon.

- **Partage**: Configurez ici le nom d'hôte de la machine (ou son IP), le nom du partage (sans le chemin, sans '/') et le chemin vers le dossier.
- **Nom d’utilisateur du partage** : nom d’utilisateur pour accéder au partage.
- **Mot de passe du partage** : mot de passe du partage.

> **IMPORTANT**
>
> Les messages trop longs ne peuvent être transmis en TTS (la limite
> dépend du fournisseur de TTS, en général environ 100 caractères).

# Configuration des équipements

La configuration des équipements Sonos est accessible à partir du menu Plugins puis multimédia.

Vous retrouvez ici toute la configuration de votre équipement :

- **Nom du Sonos** : nom de votre équipement Sonos.
- **Objet parent** : indique l’objet parent auquel appartient l’équipement.
- **Activer** : permet de rendre votre équipement actif.
- **Visible** : le rend visible sur le dashboard.

Ainsi que des informations sur votre Sonos: *Modèle*, *Versions*, *Numéro de série*, *Identifiant*, *Adresse MAC* et *Adresse IP*.

Comme commande vous retrouverez :

- **Jouer playlist** : commande de type message permettant de lancer une playlist, il suffit dans le titre de mettre le nom de la playlist.
- **Jouer favoris** :  commande de type message permettant de lancer un favoris, il suffit dans le titre de mettre le nom du favori.
- **Jouer une radio** : commande de type message permettant de lancer une radio, il suffit dans le titre de mettre le nom de la radio *(ATTENTION : celle ci doit être dans les radios favorites)*. Ne fonctionne plus sur les modèles "S2".
- **Rejoindre un groupe** : permet de rejoindre le groupe du haut-parleur (un Sonos) donné (pour associer 2 Sonos par exemple). Il faut mettre le nom de la pièce du sonos à rejoindre. Cela peut-être n'importe quel membre d'un groupe existant ou un isolé.
- **Quitter le groupe** : permet de quitter le groupe
- **Aléatoire statut** : indique si on est en mode aléatoire ou non.
- **Aléatoire** : inverse le statut du mode aléatoire.
- **Répéter statut** : indique si on est en mode répété ou non.
- **Répéter** : inverse le statut du mode "répéter".
- **Image** : lien vers l’image de l’album.
- **Album** : nom de l’album en cours de lecture.
- **Artiste** : nom de l’artiste en cours de lecture.
- **Piste** : nom de la piste en cours de lecture.
- **Muet** : Active le mode muet.
- **Non Muet** : Désactive le mode muet.
- **Muet statut** : indique si on est en mode muet ou non.
- **Précédent** : piste précédente.
- **Suivant** : piste suivante.
- **Lecture** : passer en lecture.
- **Pause** : mettre en pause.
- **Stop** : arrêter la lecture.
- **Volume** : modifier le volume *(de 0 à 100)*.
- **Volume statut** : niveau du volume.
- **Statut** : statut de lecteur traduit dans la langue configurée sous Jeedom. Par exemple: *Lecture*, *Pause*, *Arrêté*.
- **Statut de lecture** qui donne la valeur "brut" du statut de lecture: *PLAYING*, *PAUSED_PLAYBACK*, *STOPPED*; plus adapté pour les scénarios.
- **Dire** : permet de lire un texte sur le Sonos (voir partie TTS). Dans le titre vous pouvez mettre le volume et dans le message, le message à lire.
- **TV** : pour basculer sur l'entrée *TV* sur les équipements compatibles
- **Entrée audio analogique** : pour basculer sur l'*Entrée audio analogique* sur les équipements compatibles
- **Mode de lecture** donnant l'état et commande **Choisir mode de lecture** qui permet de choisir parmi les possibilités suivantes: *Normal*, *Répéter tout*, *Aléatoire*, *Aléatoire sans répétition*, *Répéter le morceau*, *Aléatoire et répéter le morceau*. Cette action est équivalente à l'utilisation des commandes **Répéter** & **Aléatoire** afin d'arriver dans la configuration désirée. C'est par contre le seul moyen de passer en mode "Répéter le morceau".

Les commandes infos seront mises à jour en quasi temps réel (délai de quelques secondes maximum normalement) mais l'image de l'album en cours de lecture peut mettre un peu plus de temps à s'afficher sur le widget lors d'un changement de piste, ceci est parfaitement normal et indépendant du plugin: il doit récupérer l'image depuis une source externe (sur un Sonos ou sur internet) et cela prend parfois plusieurs secondes (en principe maximum une dizaine de secondes)

# TTS

Le TTS (text-to-speech) vers le Sonos nécessite d’avoir un partage Windows (Samba) sur le réseau (imposé par Sonos, pas moyen de faire autrement). Il faut vous donc un NAS sur le réseau. La configuration est assez simple il faut mettre le nom ou l’ip du NAS (attention à bien mettre la même chose que ce qui est déclaré sur Sonos) et le chemin (relatif), le nom d’utilisateur et le mot de passe (attention l’utilisateur doit avoir les droits en écriture)

La création du fichier audio est géré par le core de Jeedom: la langue sera celle configurée dans Jeedom et le moteur TTS utilisé peut également être sélectionné dans les écrans de configuration Jeedom.

> **IMPORTANT**
>
> Il faut absolument mettre un mot de passe pour que cette procédure fonctionne.
>
> Il faut aussi absolument un sous-répertoire pour que le fichier vocal soit correctement créé.
>
> Il ne faut surtout pas d'accent dans le nom du partage ou le dossier, d'espace ou de caractères spéciaux

**Voici un exemple de configuration (merci @masterfion) :**

Côté NAS, voici ma config :

- le dossier Jeedom est partagé.
- l’utilisateur Sonos a un accès Lecture/Ecriture (nécessaire pour Jeedom).
- l’utilisateur guest a un accès en Lecture seule (nécessaire pour les Sonos).

Côté Plugin Sonos, voici ma config :

- Partage :
  - Champ 1: 192.168.xxx.yyy
  - Champ 2: Jeedom
  - Champ 3: TTS
- Nom d’utilisateur : Sonos et son mot de passe…​

Côté Bibliothèque Sonos (appli PC)

- le chemin est : //192.168.xxx.yyy/Jeedom/TTS

> **IMPORTANT**
>
> Il faut ABSOLUMENT ajouter le partage réseau dans la bibliothèque du sonos, sinon Jeedom va bien créer le mp3 du tts mais il ne pourra pas être joué par le Sonos.

# Le panel

Le plugin Sonos met aussi à disposition un panel qui rassemble tous vos Sonos. Disponible à partir du menu Accueil → Sonos Controller :

> **IMPORTANT**
>
> Pour avoir le panel il faut l’avoir activé dans la configuration du plugin.
