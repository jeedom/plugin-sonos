# Sonos Controller Changelog

>**IMPORTANT**
>
>As a reminder, if there is no information about the update, it means that the update only involves documentation, translations, or text changes.

# 18-05-2026

- Fixed a minor bug in the **Dire** command

# 11-04-2026

- Added a **Station** info command that displays the radio station currently playing (if the information is available)

# 27-01-2026

- Added image for *Ikea Table Lamp*

# 19-01-2026

- Added an optional setting to specify, only if necessary, the subnet (VLAN) on which your Sonos speakers are located if it is different from the subnet (VLAN) on which Jeedom is located
- Fixes for the "Subscription renewal failed" message and the loss of information reporting
- Image corrections

# 26-04-2025

> Caution
> Major plugin overhaul: a large portion of the plugin has been rewritten, including all communication with Sonos (daemon), and certain features have been modified and no longer work as they did before, particularly group management;
>
> Requires Jeedom 4.4.8
>
> Compatible with Debian 11 and 12!
>
> See also [this topic on community](https://community.jeedom.com/t/erreur-you-cannot-create-a-controller-instance-from-a-speaker-that-is-not-the-coordinator-of-its-group/128862) for more details

- The plugin has been almost completely rewritten; the daemon has been entirely rewritten in Python (instead of PHP)
- Compatible with Debian 11 and 12!
- You no longer need to run a manual discovery, and it is no longer necessary (or possible) to manually add a device; the plugin automatically detects your Sonos devices and creates the corresponding devices each time the daemon starts.
- It is also possible to request that devices, favorites, and playlists be (re)synchronized without restarting the daemon from the Devices panel.
- Automatic synchronization every hour to correct any desynchronization
- (Near) real-time updates to control and information commands (a delay of 0.5 seconds to a few seconds at most), no more minute-by-minute cron jobs, even when a change is made outside of Jeedom (via the Sonos app, for example)
- Group management has been redesigned (old commands will be removed and new ones added; see documentation). You can join or leave a group and control playback for the group from any device in the group without worrying about which device is the controller. Volume, however, is still controlled on a per-speaker basis.
- When adapting the Text-to-Speech (TTS) feature, **you will need to adjust the SAMBA sharing configuration**.
- Optimization: No more memory leaks in the daemon, and it uses less memory than before.
- Optimized the display of the cover of the current reading
- Optimization on reading favorites
- Added the ability to disable the preconfigured tile: you are now free to configure it however you like using the core widgets or your own widgets, and to show or hide the commands of your choice...

- Added a **TV** action command to switch to the *TV* input on compatible devices
- Added an **Playback Mode** info command and a **Select Playback Mode** action that allows you to select a playback mode from the following options: *Normal*, *Repeat All*, *Shuffle and Repeat All*, *Shuffle Without Repeat*, *Repeat Track*, *Shuffle and Repeat Track*
- Added a **Reading Status** command that returns the "raw" value of the reading status (the existing **Status** command returns a value translated based on the language configured in Jeedom)
- Added the **Group Status** (indicates whether the device is grouped or not) and **Group Name** commands for devices that are grouped
- Added the **LED On**, **LED Off**, and **LED Status** commands to control the status indicator
- Added a **Play MP3 Radio** command to play an MP3 radio stream directly via a URL (accessible on the internet, for example)
- Added the **Increase Volume** and **Decrease Volume** commands by 1%
- Added a **Volume Transition** command, which is very useful for managing volume level transitions. Three modes are available: *LINEAR*, *ALARM*, *AUTOPLAY*. See the documentation for more information.
- Added the **Loudness Status**, **Loudness On**, and **Loudness Off** commands
- Added the commands **Status Crossfade**, **On Crossfade**, **Off Crossfade**
- Added the **Status Touch Commands**, **On Touch Commands**, and **Off Touch Commands**
- Added the **Balance** (action/slider) and **Balance Status** commands, which manage the balance based on a value ranging from -100 (all the way to the left) to 100 (all the way to the right)
- Added the **Bass** (action/slider) and **Bass Status** commands, which manage bass levels based on a value between -10 and 10
- Added the **Treble** (action/slider) and **Treble Status** commands, which adjust the treble based on a value between -10 and 10
- Added the **Party Mode** command, which lets you group all Sonos devices together
- Added the **Mic Status** command, which indicates whether the microphone is enabled or disabled on Sonos devices equipped with a microphone
- Added a **Battery** info command to battery-powered Sonos devices that displays the battery charge percentage
- Added a **Charging** command to battery-powered Sonos devices that indicates whether charging is in progress or not
- Add a **Next Alarm** info command to each Sonos speaker, displaying the date of the next alarm scheduled on that speaker

# 25/04/2024

- Documentation update
- Removing accents from share names (not supported by the plugin)
- Removal of the dependency on PicoTTS (the plugin uses Jeedom's global TTS engine)
- Added Sonos Beam Gen 2

# 15/01/2024

- Preparing for Jeedom 4.4
- Added Sonos Move 2

# 24/08/2023

- Added Ikea Symfonisk Floor Lamp

# 25/05/2023

- Added Sonos Era

# 18/10/2022

- Update command list for Jeedom v4.3
- Added Sonos Ray

# 22/03/2022

- Support for the new SYMFONISK loudspeaker

# 01/02/2022

- Fixed a bug on the TTS

# 27/01/2022

- V4.2 optimizations

# 14/01/2022

- Added compatibility with the new SYMFONISK speaker

# 27/12/2021

- Added compatibility with the new Sonos One

# 09/10/2021

- Addition of the Sonos Five
- Adding Sonos Roam
- Adding Symfonisk Framework
- Immediate volume update in case of change by Jeedom, thank you @Domochip

# 24/11/2020

- New presentation of the list of objects
- Added the "V4 Compatibility" tag

# 07/08/2020

- Sonos ARC support

# 24/01/2020

- Support for Sonos One S22

# 11/01/2020

- Support for Sonos Move
- Code optimization in case of Sonos not connected

# 16/12/2019

- Bug fix if a sound system cannot be reached

# 21/10/2017

- Improvement in recovery from TTS

# 15/10/2019

- Sonos port support
- Improved dependency installation script

# 07/10/2019

- Improvements to the dependency installation script (may help resolve TTS issues in some cases)

# 23/09/2019

- Optimizations

# 01/09/2019

- Ikea SYMFONISK lamp speaker support

# 12/08/2019

- Support for Ikea SYMFONISK bookshelf speaker

# 23/04/2019

- Support for one gen2 sonos

# 17/01/2019

- Fixed bugs in case the sound systems were added manually

# 15/01/2019

**IMPORTANT: ONLY WORKS WITH PHP 7. CHECK THE JEEDOM STATUS PAGE FOR YOUR VERSION**

- Complete rewrite of the plugin
- Support for the new Sonos API
- Support for Beam and One sound systems
- Fixed numerous bugs
- Global optimizations

**IMPORTANT**

- Compatible PHP7 only
- Some features had to be removed

# 2018

- Added management of sonos favorites
- Support for Sonos One and Playbase
- Tongue correction with picotts
- Adding a "Line Input" command
- Update to the Sonos communication library
- Optimized loading of playlists
- Addition of picotts for local TTS generation
- Fixed the play/pause button when updating the widget.
