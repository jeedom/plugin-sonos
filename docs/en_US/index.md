Sonos plugin allows you to control Sonos Play 1, 3, 5, Sonos Connect,
Sonos Connect AMP and Sonos Playbar. It will allow you to see the status
Sonos and perform actions on them (play, pause, next,
previous, volume, choice of a playlist…)

# Plugin configuration

The configuration is very simple, after downloading the plugin, it
you just activate it and that&#39;s it. The plugin will search for
Sonos on your network and create the equipment automatically. Of
plus, if there is a match between Jeedom objects and parts
Sonos, Jeedom will automatically assign Sonos to the right
rooms.

> **Tip**
>
> During the initial discovery it is strongly advised not to have grouped sound systems on pain of having errors

If you later add a Sonos, you can either create a device
Sonos by giving the IP to Jeedom or click on "Search for
Sonos equipment"

-   **Voix** : choice of voice during TTS
-   **Partage** : share name and folder path
-   **Username for sharing** : username for
    access sharing
-   **Sharing password** : Sharing password
-   **Discovery** : automatically discover the sound systems (does not work
    on a docker type installation where you have to create by hand
    each sonos)
-   **Sonos outbuilding** : install sonos dependencies for TTS

> **Important**
>
> Messages that are too long cannot be transmitted in TTS (the limit
> depends on the TTS provider, usually around 100 characters)

# Equipment configuration

Sonos equipment configuration is accessible from the menu
Plugins then multimedia

Here you find all the configuration of your equipment :

-   **Sonos equipment name** : name of your Sonos equipment
-   **Parent object** : indicates the parent object to which belongs
    equipment
-   **Activer** : makes your equipment active
-   **Visible** : makes it visible on the dashboard
-   **Model** : your Sonos model (do not change unless
    not the right one)
-   **IP** : the IP of your Sonos, can be useful if your Sonos changes
    of IP or if you replace it

Below you find the list of orders :

-   **Nom** : Name of the order
-   **Advanced configuration (small notched wheels)** : allows
    display the advanced configuration of the command (method
    history, widget…)
-   **Tester** : Used to test the command

As order you will find :

-   **Play Playlist** : message type command to launch
    a playlist, just put the name of in the title
    the playlist. You can put "random" in message to mix
    the playlist before reading.
-   **Play Favorites** :  message type command to launch
    a favorites, it is enough in the title to put the name of the favorites. You
    can put "random" in message to mix favorites before reading.
-   **Play a radio** : message type command to launch
    a radio, just in the title put the name of the radio
    (BE CAREFUL this must be in the favorite radio stations).
-   **Adding a speaker** : allows to add a speaker
    (a Sonos) to the current speaker (to associate 2 Sonos
    for example). You have to put the name of the sonos room to add
    in the title (the message field is not used here).
-   **Remove speaker** : allows you to delete a speaker
    (a Sonos) to the current speaker (to dissociate 2 Sonos
    for example). You have to put the name of the Sonos room to delete
    in the title (the message field is not used here).
-   **Random status** : indicates if we are in random mode or not
-   **Random** : reverse the status of random mode
-   **Repeat status** : indicates if we are in repeat mode or not
-   **Repeat** : reverse the status of the "repeat" mode"
-   **Image** : link to the album image
-   **Album** : name of album currently playing
-   **Artiste** : artist name currently playing
-   **Piste** : name of the track currently playing
-   **Muet** : go mute
-   **Previous** : previous track
-   **Suivant** : next track
-   **Lecture** : read
-   **Pause** : Pause
-   **Stop** : stop reading
-   **Volume** : change the volume (from 0 to 100)
-   **Status volume** : Volume level
-   **Statut** : status (pause, reading, transition…)
-   **Dire** : allows to read a text on Sonos (see TTS part).
    In the title you can set the volume and in the message, the
    message to read

> **Note**
>
> To play playlists you can put options (in the
> option box). To start the playlist in random playback you must
> put in "random"

# TTS

TTS (text-to-speech) to Sonos requires sharing
Windows (Samba) on the network (imposed by Sonos, no way to do
other). So you need a NAS on the network. The configuration is
pretty simple you have to put the name or the ip of the NAS (be careful
put the same as what is stated on Sonos) and the chemain
(relative), username and password (attention
the user must have write rights)

> **Important**
>
> It is absolutely necessary to put a password for this to work

> **Important**
>
> It is also absolutely necessary a sub-directory so that the voice file
> be correctly created.

**Here is an example of configuration (thank you @masterfion) :.**

NAS side, here is my config :

-   Jeedom folder is shared
-   Sonos user has Read / Write access (required
    for Jeedom)
-   the guest user has read-only access (required to
    Sonos)

Sonos Plugin side, here is my config :

-   Sharing :
    -   Field 1 : 192.168.xxx.yyy
    -   Field 2 : Jeedom / TTS
-   Username : Sonos and its password…

Sonos Library side (PC app)
-   the way is : //192.168.xxx.yyy/Jeedom / TTS

> **Important**
>
> ABSOLUTELY add network sharing in the sound library, otherwise Jeedom will create the mp3 for the tts but it cannot be played by the Sonos

> **Important**
>
> Language depends on Jeedom language and uses picotts by default. As of jeedom 3.3.X it will be possible to use Google TTS to have a prettier voice


# The panel

The Sonos plugin also provides a panel that brings together all of your
His bone. Available from the Home menu → Sonos Controller :

> **Important**
>
> To have the panel you have to activate it in the plugin configuration

# FAQ

** "No devices in this collection" error when searching for equipment **
>
> This error occurs if the automatic discovery is blocked (router which blocks the boradcast for example). It does not matter you will just have to add your sonos by hand specifying the model and IP.
