# Sonos plugin

The Sonos plugin allows you to control the Sonos Play 1, 3, 5, Sonos Connect, Sonos Connect AMP, Sonos Playbar, Ikea Symfonisk ... It will allow you to see the state of the Sonos and to perform actions on it (play, pause, next, previous, volume, choice of a playlist…)

# Plugin configuration

The configuration is very simple, after downloading the plugin, you just need to activate it and that's it. The plugin will search for Sonos on your network and create the equipment automatically. In addition, if there is a match between Jeedom objects and Sonos rooms, Jeedom will automatically assign Sonos to the right rooms.

> **Tip**
>
> During the initial discovery it is strongly advised not to have grouped sound systems on pain of having errors

If you later add a Sonos, you can either create a Sonos device by giving Jeedom the IP or click on "Search for Sonos devices"

-   **Voice** : choice of voice during TTS
-   **Sharing** : share name and folder path
-   **Username for sharing** : username to access share
-   **Sharing password** : Sharing password
-   **Discovery** : automatically discover the sound systems (does not work on a docker type installation where each sound system must be created by hand)
-   **Sonos outbuilding** : install sonos dependencies for TTS

> **Important**
>
> Messages that are too long cannot be transmitted in TTS (the limit
> depends on the TTS provider, usually around 100 characters)

# Equipment configuration

The configuration of Sonos equipment is accessible from the Plugins menu then multimedia

Here you find all the configuration of your equipment :

-   **Sonos equipment name** : name of your Sonos equipment
-   **Parent object** : indicates the parent object to which the equipment belongs
-   **Activate** : makes your equipment active
-   **Visible** : makes it visible on the dashboard
-   **Model** : the model of your Sonos (only change if it is not the right one)
-   **IP** : the IP of your Sonos, can be useful if your Sonos changes IP or if you replace it

Below you find the list of orders :

-   **Name** : Name of the order
-   **Advanced configuration (small notched wheels)** : displays the advanced configuration of the command (logging method, widget, etc.))
-   **Test** : Used to test the command

As order you will find :

-   **Play Playlist** : message type command to launch a playlist, just put the name of the playlist in the title. You can put "random" in message to mix the playlist before playing.
-   **Play Favorites** :  command of the message type allowing to launch a favorites, it suffices in the title to put the name of the favorites. You can put "random" in message to mix favorites before reading.
-   **Play a radio** : command type message to launch a radio, just in the title to put the name of the radio (BE CAREFUL this must be in the favorite radios).
-   **Adding a speaker** : allows to add a speaker (a Sonos) to the current speaker (to associate 2 Sonos for example). You have to put the name of the sonos room to add in the title (the message field is not used here).
-   **Remove speaker** : allows you to delete a speaker (a Sonos) from the current speaker (to dissociate 2 Sonos for example). You have to put the name of the Sonos part to delete in the title (the message field is not used here).
-   **Random status** : indicates if we are in random mode or not
-   **Random** : reverse the status of random mode
-   **Repeat status** : indicates if we are in repeat mode or not
-   **Repeat** : reverse the status of the "repeat" mode"
-   **Picture** : link to the album image
-   **Album** : name of album currently playing
-   **Artist** : artist name currently playing
-   **Track** : name of the track currently playing
-   **Mute** : go mute
-   **Previous** : previous track
-   **Next** : next track
-   **Play** : read
-   **Pause** : Pause
-   **STOP** : stop reading
-   **Volume** : change the volume (from 0 to 100)
-   **Status volume** : Volume level
-   **Status** : status (pause, reading, transition…)
-   **Say** : allows to read a text on Sonos (see TTS part). In the title you can set the volume and in the message, the message to read

> **NOTE**
>
> For playing playlists you can put options (in the option box). To start the playlist in random reading you have to put in "random"

# TTS

TTS (text-to-speech) to Sonos requires having Windows (Samba) sharing on the network (imposed by Sonos, no way to do otherwise). So you need a NAS on the network. The configuration is quite simple you have to put the name or the ip of the NAS (be careful to put the same thing as what is declared on Sonos) and the path (relative), the user name and the password ( attention the user must have write rights)

> **Important**
>
> It is absolutely necessary to put a password for this to work

> **Important**
>
> A subdirectory is also absolutely necessary for the voice file to be correctly created.

**Here is an example of configuration (thanks @masterfion) :.**

NAS side, here is my config :

-   Jeedom folder is shared
-   Sonos user has Read / Write access (necessary for Jeedom)
-   guest user has read-only access (required for Sonos)

Sonos Plugin side, here is my config :

-   Sharing :
    -   Field 1 : 192.168.xxx.yyy
    -   Field 2 : Jeedom / TTS
-   Username : Sonos and its password…

Sonos Library side (PC app)
-   the way is : //192.168.xxx.yyy/Jeedom/TTS

> **Important**
>
> ABSOLUTELY add network sharing in the sound library, otherwise Jeedom will create the mp3 for the tts but it cannot be played by the Sonos

> **Important**
>
> Language depends on Jeedom language and uses picotts by default. As of jeedom 3.3.X it will be possible to use Google TTS to have a prettier voice


# The panel

The Sonos plugin also provides a panel that brings together all of your Sonos. Available from the Home menu → Sonos Controller :

> **Important**
>
> To have the panel you have to activate it in the plugin configuration

# FAQ

**"No devices in this collection" error when searching for equipment**
>
> This error occurs if the automatic discovery is blocked (router which blocks the boradcast for example). It does not matter you will just have to add your sonos by hand specifying the model and IP.
