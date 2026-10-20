# Sonos plugin

The Sonos plugin lets you control the Sonos Play 1, 3, 5, Sonos Connect, Sonos Connect AMP, Sonos Playbar, IKEA Symfonisk, and more. It allows you to view the status of your Sonos devices and perform actions such as play, pause, next, previous, adjust volume, and select a playlist.

# Plugin configuration

Setup is very simple: after downloading the plugin, just activate it, install the dependencies, and start the daemon.
The plugin will scan your network for Sonos devices and automatically create the devices. Additionally, if there is a match between Jeedom objects and Sonos rooms, Jeedom will automatically assign the Sonos devices to the correct rooms.

> **Important**
> Your Sonos devices must be directly accessible by the machine hosting Jeedom (broadcast/multicast is possible on the same network), and they must be able to communicate with Jeedom in return on TCP port 1400.

If your Jeedom speakers are not on the same subnet as Jeedom, you can configure the subnet—preferably in CIDR format—for example `192.168.1.0/24`. It should also be possible to enter the IP address of one of your speakers directly to discover the others from there, but it is recommended that you configure the entire network. **Warning: Do not configure anything if you are not familiar with this process; test the default configuration first.**

If you add a Sonos device later, you can click **Sync** on the devices page or restart the daemon.

- **Share**: Configure the machine's hostname (or IP address), the share name (without the path, without '/'), and the path to the folder here.
- **Share username**: the username used to access the share.
- **Share password**: Share password.

# Equipment configuration

You can configure Sonos devices from the Plugins menu, then Multimedia.

Here you'll find all the usual settings for your equipment:

- **Sonos Name**: the name of your Sonos device.
- **Parent object**: Specifies the parent object to which the device belongs.
- **Activate**: Turns your device active.
- **Visible**: Makes it visible on the dashboard.

As well as information about your Sonos: *Model*, *Versions*, *Serial Number*, *ID*, *MAC Address*, and *IP Address*.

You also have the option to disable the preconfigured device tile (this option is active by default); in that case, you can configure the tile however you like using Core widgets or your own widgets, and show or hide the commands of your choice...

The preconfigured tile does not take into account whether commands are visible or hidden, nor does it account for advanced display options; its configuration cannot be modified.

# The orders

The commands will be refreshed in near real time (usually within a few seconds at most), but the cover art for the currently playing album may take a little longer to appear on the widget when switching tracks; this is perfectly normal and unrelated to the plugin: it has to retrieve the image from an external source (on a Sonos device or from the internet), and this can sometimes take several seconds (usually no more than about ten seconds).

## Sonos volume controls & controls

These commands will always control the corresponding device, even when it is part of a group.

- **Volume**: Adjust the volume *(from 0 to 100)*
- **Volume status**: volume level (in %)
- **Increase Volume**: Increases the volume by 1%; may be useful for integration with other systems or plugins
- **Lower Volume**: lowers the volume by 1%; may be useful for integration with other systems or plugins
- **Volume Transition** allows you to perform volume level transitions managed directly by the Sonos speaker; the plugin does not handle this, so it does not cause any delays, but the transition times are not configurable since they are defined by Sonos. The transition type and target volume must be selected when executing the command. There are 3 modes:
  - *LINEAR*: linear transition from the current volume to the target volume (increase or decrease); the speed is 1.25 per second (a *LINEAR* transition from 50% to 30% will take 16 seconds)
  - *ALARM*: sets the volume to 0, pauses for about 30 seconds, and then increases to the requested volume at a rate of 2.5 per second (an *ALARM* transition from 0% to 10% will take 4 seconds)
  - *AUTOPLAY*: sets the volume to 0 and quickly increases it to the requested volume at a rate of 50 per second (an *AUTOPLAY* transition from 0% to 50% will take 1 second)
- **Mute**: Turns on mute mode.
- **Unmute**: Turns off mute mode.
- **Mute status**: Indicates whether the system is in mute mode or not.
- **Balance** (action/slider) and **Balance Status**, which controls the balance based on a value ranging from -100 (all the way to the left) to 100 (all the way to the right) for compatible Sonos devices
- **Bass** (action/slider) and **Bass Level**, which controls the bass based on a value between -10 and 10
- **Treble** (action/slider) and **Treble Status**, which controls the treble based on a value between -10 and 10
- **Loudness status**, **Loudness on**, **Loudness off** controls the loudness

- **TV**: to switch to the *TV* input on compatible devices
- **Analog Audio Input**: to switch to the *Analog Audio Input* (*Line-in*) on compatible devices
- **LED On** and **LED Off**: Makes the LED and the status indicator active and off
- **Status LED**: Indicates whether the status light is on or off. This information is updated only once per minute if it is modified outside of Jeedom.
- **Touch Controls On** and **Touch Controls Off** Makes the physical or touch buttons on the Sonos active or disabled
- **Touch Controls Status** indicates whether touch commands are enabled or disabled
- **Mic status**, which indicates whether the microphone is enabled or disabled on Sonos devices equipped with a microphone
- **Battery** on Sonos devices equipped with a battery, showing the battery charge percentage
- **Charging** on battery-powered Sonos devices, indicating whether charging is in progress or not

## Playback controls

These commands will display and control the currently playing track on the device or on the group if the device is part of a group, and they do so transparently—you don't need to worry about whether the device is part of a group or not to use them.

- **Status**: Reader status translated into the language configured in Jeedom. For example: *Playing*, *Paused*, *Stopped*.
- **Playback Status**, which returns the "raw" value of the playback status: *PLAYING*, *PAUSED_PLAYBACK*, *STOPPED*; more suitable for scenarios.
- **Playback**: Switch to playback mode.
- **Pause**: to pause.
- **Stop**: Stop playback.
- **Previous**: previous track.
- **Next**: next track.
- **Random status**: Indicates whether the system is in random mode or not.
- **Random**: Toggles the random mode status.
- **Repeat status**: Indicates whether the system is in repeat mode or not.
- **Repeat**: Toggles the "repeat" mode status.
- **Crossfade Status**, **Crossfade On**, **Crossfade Off** to control and enable or disable *Crossfade*
- **Select Playback Mode** lets you choose from the following options:
  - *Normal* (repeat off, shuffle off),
  - *Repeat All* (random off),
  - *Random and repeat all*,
  - *Random with no repeats*,
  - *Repeat the song* (shuffle off),
  - *Shuffle and repeat the song*.

I recommend using this command in a scenario instead of **Repeat** and **Shuffle** to achieve the desired configuration, even though all of them affect the same settings. However, this command is the only way to switch to *Repeat Song* or *Shuffle and Repeat Song* mode.
- **Read mode** that returns the current status, which will be one of the values listed above.
- **Play playlist**: a message-based command that lets you start a playlist; simply type the playlist name in the title. In a scenario, a list of options will automatically appear when you start typing.
- **Play Favorites**: A message-based command that lets you launch a favorite; simply type the name of the favorite in the subject line. In a scenario, a list of options will automatically appear as you start typing.
- **Play a radio station**: a voice command that lets you start a radio station; simply include the station’s name in the title *(NOTE: The station must be in your favorites)*. In a scenario, a list of options will automatically appear as you start typing. This feature no longer works on “S2” models; it’s normal to see an empty list on all models using the Sonos S2 app.
- **Play an MP3 radio station**: Allows you to play an MP3 radio station via a URL (for example, from the Internet). You must enter a title in the *Title* field and the URL (in the format http(s)://...mp3) in the *Message* field.
- **Image**: link to the image in the album.
- **Album**: Name of the album currently playing.
- **Artist**: Name of the artist currently playing.
- **Track**: Name of the track currently playing.
- **Speak**: Allows you to have text read aloud on Sonos (see the TTS section). In the title, you can set the volume, and in the message field, enter the text to be read.

> **Hint**
> Playlists and favorites must be created using the Sonos app (on a mobile device or computer); then you must sync to update the devices and be able to use them in a scenario.

## Commands for managing groups

These commands always affect the corresponding device.

- **Group status**: Indicates whether the device is grouped or not.
- **Group Name**: If the device is part of a group, enter the group name.
- **Join a Group**: Allows you to join the group of a specific speaker (a Sonos device) (to pair two Sonos devices, for example). You must enter the room name of the Sonos you want to join. This can be any member of an existing group; it does not necessarily have to be the group coordinator or a standalone Sonos. In some cases, a list of options will automatically appear as you start typing.
- **Leave the group**: allows you to leave the group.
- **Party mode** lets you group all your Sonos devices together

# TTS

TTS (text-to-speech) to Sonos requires a SAMBA share on the network (required by Sonos; there’s no other way to do it). So you’ll need a NAS or equivalent on the network. The setup is fairly simple: you need to enter the name or IP address of the NAS (make sure to enter exactly what’s specified in Sonos), the path to the folder containing the audio files, and the username and password (note that the user must have write permissions).

The creation of the audio file is handled by the Jeedom core: the language will be the one configured in Jeedom, and the TTS engine used can also be selected in the Jeedom configuration.

When using TTS (the **Dire** command), the plugin will perform the following actions:

- generation of the audio file containing the message with Jeedom core support
- writing the file to the SAMBA share
- force playback in “Normal” mode, without repetition
- Force "unmuted" mode (only on the device, not on the entire group)
- Adjusts the volume to the value selected when using the command (only on the device, not on the entire group)
- reading message
- restoring the state of the Sonos before playback (i.e. the playback mode, mute or not, repeat or not, etc.) and restarting the stream if the Sonos was playing

> **IMPORTANT**
>
> You must set a password for this procedure to work.
>
> A subdirectory is also absolutely necessary for the voice file to be created correctly.
>
> Be sure not to use any accents, spaces, or special characters in the share name or folder name.
>
> Messages that are too long cannot be transmitted via TTS (the limit depends on the TTS provider; generally, about 100 characters).

## Configuration example

On the NAS side, the following configuration must be performed:

- The *Jeedom* folder is shared and contains a *TTS* folder
- The *jeedom* user has read/write access (required for Jeedom).
- The *sonos* user has read-only access (required for Sonos).

As for the Sonos plugin, here's the configuration:

- Share:
  - Field 1: 192.168.xxx.yyy
  - Field 2: *Jeedom*
  - Field 3: *TTS*
- Username (*jeedom* in the example) and password…​

Sonos Library (PC app)

- The path is: //192.168.xxx.yyy/Jeedom/TTS
- The username will be *sonos* (in this example) + password

# The panel

The Sonos plugin also provides a panel that brings together all your Sonos devices. Available from the Home menu → Sonos Controller:

> **IMPORTANT**
>
> To access the dashboard, you must enable it in the plugin settings.
