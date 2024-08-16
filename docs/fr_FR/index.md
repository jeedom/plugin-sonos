# Plugin Sonos

Le plugin Sonos permet de piloter les Sonos Play 1, 3, 5, Sonos Connect, Sonos Connect AMP, Sonos Playbar, Ikea Symfonisk... Il va vous permettre de voir l’état du/des Sonos et d’effectuer des actions (lecture, pause, suivant, précédent, volume, choix d’une playlist…).

# Configuration du plugin

La configuration est très simple, après téléchargement du plugin, il vous suffit de l’activer, d'installer les dépendances et de démarrer le démon.
Le plugin va rechercher les Sonos sur votre réseau et créer les équipements automatiquement. De plus, s’il y a une correspondance entre les objets Jeedom et les pièces du Sonos, Jeedom affectera automatiquement les Sonos dans les bonnes pièces.

> **Important**
> Vos équipements Sonos doivent être joignable directement par la machine hébergeant Jeedom (broadcast / multicast possible sur le même réseau) et ils doivent être capable de joindre Jeedom en retour sur le port TCP 1400.

Si plus tard vous ajoutez un Sonos, vous pouvez cliquer sur **Synchroniser** dans la page des équipements ou redémarrer le démon.

- **Partage**: Configurez ici le nom d'hôte de la machine (ou son IP), le nom du partage (sans le chemin, sans '/') et le chemin vers le dossier.
- **Nom d’utilisateur du partage**: nom d’utilisateur pour accéder au partage.
- **Mot de passe du partage**: mot de passe du partage.

# Configuration des équipements

La configuration des équipements Sonos est accessible à partir du menu Plugins puis multimédia.

Vous retrouvez ici toute la configuration habituelle de votre équipement :

- **Nom du Sonos**: nom de votre équipement Sonos.
- **Objet parent**: indique l’objet parent auquel appartient l’équipement.
- **Activer**: permet de rendre votre équipement actif.
- **Visible**: le rend visible sur le dashboard.

Ainsi que des informations sur votre Sonos: *Modèle*, *Versions*, *Numéro de série*, *Identifiant*, *Adresse MAC* et *Adresse IP*.

Il n'y a aucune configuration spécifique à effectuer.

# Les commandes

Les commandes infos seront mises à jour en quasi temps réel (délai de quelques secondes maximum normalement) mais l'image de l'album en cours de lecture peut mettre un peu plus de temps à s'afficher sur le widget lors d'un changement de piste, ceci est parfaitement normal et indépendant du plugin: il doit récupérer l'image depuis une source externe (sur un Sonos ou sur internet) et cela prend parfois plusieurs secondes (en principe maximum une dizaine de secondes).

## Commandes de volume & contrôle du Sonos

Ces commandes vont toujours contrôler l'équipement correspondant, y compris lorsque celui-ci est dans un groupe.

- **Volume**: modifier le volume *(de 0 à 100)*
- **Volume statut**: niveau du volume (en %)
- **Augmenter le volume**: augmente le volume de 1%; peut être utile pour l'intégration avec d'autres systèmes ou plugins
- **Diminuer le volume**: diminue le volume de 1%; peut être utile pour l'intégration avec d'autres systèmes ou plugins
- **Transition de volume** permet d'effectuer des transitions de niveau de volume directement géré par l'enceinte Sonos, ce n'est pas le plugin qui s'en charge et donc ce n'est pas bloquant mais les délais ne sont pas configurable puisque défini par Sonos. Le type de transition et le volume cible doivent être choisi lors de l'exécution de la commande. Il existe 3 modes:
  - *LINEAR*: transition linéaire depuis le volume actuel vers le volume cible (augmentation ou diminution), la vitesse est de 1.25 par seconde (une transition *LINEAR* de 50% à 30% prendra 16s)
  - *ALARM*: initialise le volume à 0, pause d'environ 30 secondes et ensuite augmente jusqu'au volume demandé à une vitesse de 2.5 par seconde (une transition *ALARM* de 0% à 10% prendra 4s)
  - *AUTOPLAY*: initialise le volume à 0 et augmente rapidement jusqu'au volume demandé à une vitesse de 50 par seconde (un transition *AUTOPLAY* de 0% à 50% prendra 1s)
- **Muet**: Active le mode muet.
- **Non Muet**: Désactive le mode muet.
- **Muet statut**: indique si on est en mode muet ou non.
- **Balance** (action/curseur) et **Balance statut** qui gère la balance selon une valeur comprise entre -100 (tout à gauche) et 100 (tout à droite) pour les Sonos compatibles
- **Loudness**, **Loudness on**, **Loudness off** contrôle le loudness

- **TV**: pour basculer sur l'entrée *TV* sur les équipements compatibles
- **Entrée audio analogique**: pour basculer sur l'*Entrée audio analogique* (*Line-in*) sur les équipements compatibles
- **Led on** et **Led off**: Active et désactive la led, le voyant d'état
- **Led statut**: indique si le voyant d'état est allumé ou non. Cette info n'est mise à jour qu'une fois par minute dans le cas où elle est modifiée hors Jeedom
- **Commandes tactiles on** et **Commandes tactiles off** Active et désactive les boutons physiques ou tactiles sur le Sonos
- **Commandes tactiles** indique si les commandes tactiles sont activées ou pas

## Commandes de lecture

Ces commandes indiqueront et contrôleront la lecture en cours sur l'équipement ou sur le groupe si celui-ci est groupé et cela de façon transparente, vous ne devez pas vous préoccuper de savoir si l'équipement est groupé ou pas pour les utiliser.

- **Statut**: statut de lecteur traduit dans la langue configurée sous Jeedom. Par exemple: *Lecture*, *Pause*, *Arrêté*.
- **Statut de lecture** qui donne la valeur "brut" du statut de lecture: *PLAYING*, *PAUSED_PLAYBACK*, *STOPPED*; plus adapté pour les scénarios.
- **Lecture**: passer en lecture.
- **Pause**: mettre en pause.
- **Stop**: arrêter la lecture.
- **Précédent**: piste précédente.
- **Suivant**: piste suivante.
- **Aléatoire statut**: indique si on est en mode aléatoire ou non.
- **Aléatoire**: inverse le statut du mode aléatoire.
- **Répéter statut**: indique si on est en mode répété ou non.
- **Répéter**: inverse le statut du mode "répéter".
- **Fondu enchaîné**, **Fondu enchaîné on**, **Fondu enchaîné off** pour contrôler et activer ou non le *Fondu enchaîné*
- **Choisir mode de lecture** permet de choisir parmi les possibilités suivantes:
  - *Normal* (répétition off, aléatoire off),
  - *Répéter tout* (aléatoire off),
  - *Aléatoire et répéter tout*,
  - *Aléatoire sans répétition*,
  - *Répéter le morceau* (aléatoire off),
  - *Aléatoire et répéter le morceau*.

  Je recommande d'utiliser cette commande-ci dans un scénario au lieu de **Répéter** & **Aléatoire** afin d'arriver dans la configuration désirée même si toutes agissent sur les mêmes paramètres. Cette commande est par contre le seul moyen de passer en mode *Répéter le morceau* ou *Aléatoire et répéter le morceau*.
- **Mode de lecture** donnant l'état actuel qui sera une des valeurs citées ci-dessus.
- **Jouer playlist**: commande de type message permettant de lancer une playlist, il suffit dans le titre de mettre le nom de la playlist. Dans un scénario, une liste de possibilité sera automatiquement affichée lorsque vous commencerez à taper.
- **Jouer favoris**:  commande de type message permettant de lancer un favoris, il suffit dans le titre de mettre le nom du favori. Dans un scénario, une liste de possibilité sera automatiquement affichée lorsque vous commencerez à taper.
- **Jouer une radio**: commande de type message permettant de lancer une radio, il suffit dans le titre de mettre le nom de la radio *(ATTENTION : celle ci doit être dans les radios favorites)*. Dans un scénario, une liste de possibilité sera automatiquement affichée lorsque vous commencerez à taper. Ne fonctionne plus sur les modèles "S2", il est normal d'avoir une liste vide sur tous les modèles utilisant l'app Sonos S2.
- **Jouer une radio mp3**: permet de lire une radio mp3 via une URL (par exemple depuis internet). Vous devez mettre un titre dans la zone *Titre* et l'url (format http(s)://...mp3) dans la zone *Message*.
- **Image**: lien vers l’image de l’album.
- **Album**: nom de l’album en cours de lecture.
- **Artiste**: nom de l’artiste en cours de lecture.
- **Piste**: nom de la piste en cours de lecture.
- **Dire**: permet de lire un texte sur le Sonos (voir partie TTS). Dans le titre vous pouvez mettre le volume et dans le message, le message à lire.

> **Hint**
> Les playlists et les favoris doivent être créés via l'app Sonos (sur mobile ou sur ordi) ensuite il faut faire une synchronisation pour mettre à jour les équipements et pouvoir les utiliser dans un scénario.

## Commandes pour gérer les groupes

Ces commandes agissent toujours sur l'équipement correspondant.

- **Groupe statut**: indique si l'équipement est groupé ou non.
- **Nom du groupe** dans le cas où l'équipement est groupé, donne le nom du groupe.
- **Rejoindre un groupe**: permet de rejoindre le groupe du haut-parleur (un Sonos) donné (pour associer 2 Sonos par exemple). Il faut mettre le nom de la pièce du sonos à rejoindre. Cela peut-être n'importe quel membre d'un groupe existant, cela ne doit pas nécessairement être le coordinateur du groupe, ou un Sonos isolé. Dans un scénario, une liste de possibilité sera automatiquement affichée lorsque vous commencerez à taper.
- **Quitter le groupe**: permet de quitter le groupe.

# TTS

Le TTS (text-to-speech) vers le Sonos nécessite d’avoir un partage SAMBA sur le réseau (imposé par Sonos, pas moyen de faire autrement). Il faut vous donc un NAS ou équivalent sur le réseau. La configuration est assez simple il faut mettre le nom ou l’ip du NAS (attention à bien mettre la même chose que ce qui est déclaré sur Sonos) et le chemin vers le dossier qui doit contenir les fichiers audio ainsi que le nom d’utilisateur et le mot de passe (attention l’utilisateur doit avoir les droits en écriture)

La création du fichier audio est géré par le core de Jeedom: la langue sera celle configurée dans Jeedom et le moteur TTS utilisé peut également être sélectionné dans la configuration Jeedom.

Lors de l'utilisation du TTS (commande **Dire**), le plugin va effectuer les actions suivantes:

- génération du fichier audio contenant le message avec le support du core de Jeedom
- écriture du fichier sur le partage SAMBA
- forcer la lecture en mode "Normal", sans répétition
- forcer le mode "non muet" (uniquement sur l'équipement, pas sur tout le groupe)
- Modification du volume sur la valeur choisie lors de l'utilisation de la commande (uniquement sur l'équipement, pas sur tout le groupe)
- lecture du message
- restauration de l'état du Sonos avant la lecture (donc le mode de lecture, muet ou pas, répétition ou pas...) et redémarrage du flux si le Sonos était en cours de lecture

> **IMPORTANT**
>
> Il faut absolument mettre un mot de passe pour que cette procédure fonctionne.
>
> Il faut aussi absolument un sous-répertoire pour que le fichier vocal soit correctement créé.
>
> Il ne faut surtout pas d'accent dans le nom du partage ou le dossier, d'espace ou de caractères spéciaux.
>
> Les messages trop longs ne peuvent être transmis en TTS (la limite dépend du fournisseur de TTS, en général environ 100 caractères).

## Exemple de configuration

Côté NAS, la config suivante doit être effectuée:

- le dossier *Jeedom* est partagé et il contient un dossier *TTS*
- l’utilisateur *jeedom* a un accès lecture/écriture (nécessaire pour Jeedom).
- l’utilisateur *sonos* a un accès en lecture seule (nécessaire pour les Sonos).

Côté Plugin Sonos, la config :

- Partage :
  - Champ 1: 192.168.xxx.yyy
  - Champ 2: *Jeedom*
  - Champ 3: *TTS*
- Nom d’utilisateur (*jeedom* dans l'exemple) et son mot de passe…​

Côté Bibliothèque Sonos (appli PC)

- le chemin est : //192.168.xxx.yyy/Jeedom/TTS
- l'utilisateur sera *sonos* (dans cet exemple) + mot de passe

# Le panel

Le plugin Sonos met aussi à disposition un panel qui rassemble tous vos Sonos. Disponible à partir du menu Accueil → Sonos Controller :

> **IMPORTANT**
>
> Pour avoir le panel il faut l’avoir activé dans la configuration du plugin.
