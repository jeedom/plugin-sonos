# Sonos plugin

The Sonos plugin allows you to control the Sonos Play 1, 3, 5, Sonos Connect, Sonos Connect AMP, Sonos Playbar, Ikea Symfonisk... It will allow you to see the status of the Sonos and to perform actions ( play, pause, next, previous, volume, choice of a playlist…).

# Plugin configuration

The configuration is very simple, after downloading the plugin, you just need to activate it, install the dependencies and start the daemon.
The plugin will search for Sonos on your network and create the equipment automatically. In addition, if there is a match between Jeedom objects and Sonos rooms, Jeedom will automatically assign Sonos to the right rooms.

> **Important**
> Your Sonos equipment must be reachable directly by the machine hosting Jeedom (broadcast / multicast possible on the same network) and they must be able to reach Jeedom in return on TCP port 1400.

If you later add a Sonos, you can click **Synchronize** in the equipment page or restart the daemon.

- **Sharing**: Configure here the host name of the machine (or its IP), the name of the share (without the path, without '/') and the path to the folder.
- **Share username** : username to access share.
- **Sharing password** : Sharing password.

# Equipment configuration

The configuration of Sonos equipment is accessible from the Plugins menu then multimedia.

Here you find all the usual configuration of your equipment :

- **Sonos name** : name of your Sonos equipment.
- **Parent object** : indicates the parent object to which the equipment belongs.
- **Activate** : makes your equipment active.
- **Visible** : makes it visible on the dashboard.

As well as information about your Sonos: *Model*, *Releases*, *Serial number*, *Identifier*, *MAC address* And *IP adress*.

There is no specific configuration to perform.

# The orders

The information controls will be updated in near real time (normally a few seconds maximum) but the image of the album currently playing may take a little longer to display on the widget when a change occurs. track, this is perfectly normal and independent of the plugin: he must retrieve the image from an external source (on a Sonos or on the internet) and this sometimes takes several seconds (in principle maximum around ten seconds).

## Volume and source controls

These commands will always control the corresponding equipment, including when it is in a group.

- **Volume** : change the volume *(from 0 to 100)*.
- **Status volume** : Volume level.
- **Mute** : Activate mute mode.
- **No Mute** : Turn off mute.
- **Mute status** : indicates whether we are in mute mode or not.

## Control commands

These commands will always control the corresponding equipment, including when it is in a group.

- **TV** : to switch to the input *TV* on compatible equipment
- **Analog audio input** : to switch to'*Analog audio input* (*Line-in*) on compatible equipment
- **The gift** : Activates the LED, the status indicator.
- **Led off** : Disables the LED, the status indicator.
- **Status LED** : indicates whether the status light is on or not. This information is only updated once per minute in case it is modified outside Jeedom.

## Playback controls

These commands will indicate and control the current playback on the equipment or on the group if it is grouped and this in a transparent way, you do not have to worry about knowing if the equipment is grouped or not to use them.

- **Status** : reader status translated into the language configured under Jeedom. For example: *Play*, *Pause*, *Stopped*.
- **Reading status** which gives the "raw" value of the reading status: *PLAYING*, *PAUSED_PLAYBACK*, *STOPPED*; more suitable for scenarios.
- **Play** : read.
- **Pause** : Pause.
- **STOP** : stop reading.
- **Previous** : previous track.
- **Next** : next track.
- **Random status** : indicates if we are in random mode or not.
- **Random** : reverse the status of random mode.
- **Repeat status** : indicates if we are in repeat mode or not.
- **Repeat** : reverse the status of the "repeat" mode".
- **Reading mode** giving status and command **Choose reading mode** which allows you to choose from the following possibilities: *Normal*, *Repeat all*, *Random*, *Random without repetition*, *Repeat song*, *Random and repeat song*. This action is equivalent to using the commands **Repeat** & **Random** in order to arrive in the desired configuration. However, this is the only way to switch to mode *Repeat song* Or *Random and repeat song*.
- **Play Playlist** : message type command to launch a playlist, just put the name of the playlist in the title. In a scenario, a list of possibilities will automatically be displayed when you start typing.
- **Play Favorites** :  message type command to launch a favorite, all you have to do in the title is to put the name of the favorite. In a scenario, a list of possibilities will automatically be displayed when you start typing.
- **Play a radio** : message type command to launch a radio station, all you have to do is put the name of the radio in the title *(Be careful : this must be in the favorite radios)*. In a scenario, a list of possibilities will automatically be displayed when you start typing. No longer works on "S2" models, it is normal to have an empty list on all models using the Sonos S2 app.
- **Picture** : link to the album image.
- **Album** : name of album currently playing.
- **Artist** : artist name currently playing.
- **Track** : name of the track currently playing.
- **Say** : allows to read a text on Sonos (see TTS part). In the title you can set the volume and in the message, the message to read.

> **Hint**
> Playlists and favorites must be created via the Sonos app (on mobile or computer) then synchronization must be done to update the equipment and be able to use it in a scenario.

## Commands for managing groups

These commands always act on the corresponding equipment.

- **Group status** : indicates whether the equipment is grouped or not.
- **Name of the group** if the equipment is grouped, give the name of the group.
- **Join a group** : allows you to join the group of the given speaker (a Sonos) (to associate 2 Sonos for example). You must enter the name of the sound system room to join. This can be any member of an existing group, it doesn't have to be the group coordinator, or an isolated Sonos. In a scenario, a list of possibilities will automatically be displayed when you start typing.
- **Leave the group** : allows you to leave the group.

# TTS

TTS (text-to-speech) to Sonos requires SAMBA sharing on the network (imposed by Sonos, no way to do otherwise). You therefore need a NAS or equivalent on the network. The configuration is quite simple, you must enter the name or IP of the NAS (be careful to put the same thing as what is declared on Sonos) and the path to the folder which must contain the audio files as well as the name of user and password (note that the user must have write rights)

The creation of the audio file is managed by the Jeedom core: the language will be the one configured in Jeedom and the TTS engine used can also be selected in the Jeedom configuration.

When using TTS (command **Say**), the plugin will perform the following actions:

- generation of the audio file containing the message with Jeedom core support
- writing the file to the SAMBA share
- force playback in “Normal” mode, without repetition
- force “unmute” mode (only on the equipment, not on the entire group)
- Changing the volume to the chosen value when using the command (only on the equipment, not on the entire group)
- reading message
- restoring the state of the Sonos before playback (i.e. the playback mode, mute or not, repeat or not, etc.) and restarting the stream if the Sonos was playing

> **Important**
>
> It is absolutely necessary to put a password for this procedure to work.
>
> A subdirectory is also absolutely necessary for the voice file to be correctly created.
>
> Above all, there must be no accent in the name of the share or folder, spaces or special characters.
>
> Messages that are too long cannot be transmitted in TTS (the limit depends on the TTS provider, generally around 100 characters).

## Configuration example

On the NAS side, the following configuration must be carried out:

- the folder *Jeedom* is shared and it contains a folder *TTS*
- the user *Jeedom* has read/write access (necessary for Jeedom).
- the user *his bone* has read-only access (necessary for Sonos).

On the Sonos Plugin side, the config :

- Sharing :
  - Field 1: 192.168.xxx.yyy
  - Field 2: *Jeedom*
  - Field 3: *TTS*
- Username (*Jeedom* in the example) and its password…​

Sonos Library side (PC app)

- the way is : //192.168.xxx.yyy/Jeedom/TTS
- the user will be *his bone* (in this example) + password

# The panel

The Sonos plugin also provides a panel that brings together all of your Sonos. Available from the Home menu → Sonos Controller :

> **Important**
>
> To have the panel you must have activated it in the plugin configuration.
