# Changelog Sonos controller

>**Important**
>
>As a reminder if there is no information on the update, it means that it only concerns the updating of documentation, translation or text

- 
- 

# 26-04-2025

> Attention
> Significant plugin redesign : 
>
> Requires Jeedom 4.4.8
>
> Debian 11 and 12 compatible!
>
> See also [this topic on community](https://community.jeedom.com/t/erreur-you-cannot-create-a-controller-instance-from-a-speaker-that-is-not-the-coordinator-of-its-group/128862) for more details

- Almost total rewrite of the plugin, the daemon has been completely rewritten in python (instead of PHP)
- Debian 11 and 12 compatible!
- .
- It is also possible to ask to (re)synchronize equipment, favorites and playlists without restarting the daemon from the equipment panel.
- 
- )
- Redesign of group management (old commands will be deleted and new ones added, see documentation). It is possible to join or leave a group, control the playback of the group from any device in the group without worrying about who is the controller. .
- Adaptation sur la fonction Text-to-Speech (TTS), **il sera nécessaire d'adapter la configuration du partage SAMBA**.
- Optimization : .
- Optimized the display of the cover of the current reading
- Optimization on reading favorites
- Ajout de la possibilité de désactiver la tuile pré-configurée : ...

- Adding an action command **TV** to switch to the input *TV* on compatible equipment
- Adding an info command **Reading mode** and action **Choose reading mode** which allows you to select the reading mode from the following possibilities: *Normal*, *Repeat all*, *Random and repeat all*, *Random without repetition*, *Repeat song*, *Random and repeat song*
- Adding an order **Reading status** which gives the "raw" value of the reading state (the existing command **Status** gives a translated value based on the language configured in Jeedom)
- Adding commands **Group status** (indicates whether the equipment is grouped or not) and **Name of the group** in the case where the equipment is grouped
- Adding commands **The gift**, **Led off** And **Status LED** to check the status indicator
- Adding an order **Play mp3 radio** to play an mp3 radio directly via a URL (accessible on the internet for example)
- Adding commands **Turn up the volume** And **Decrease the volume** from 1%
- Adding an order **Volume transition** which is very useful for managing volume level transitions. 3 possible modes: *LINEAR*, *ALARM*, *AUTOPLAY*. See documentation for more information.
- Adding commands **Loudness status**, **Loudness on**, **Loudness off**
- Adding commands **Crossfade status**, **Crossfade on**, **Fade off**
- Adding commands **Touch controls status**, **Touch controls on**, **Touch controls off**
- Adding commands **Balance** (action/cursor) and **Balance status** which manages the balance according to a value between -100 (far left) and 100 (far right))
- Adding commands **Graves** (action/cursor) and **Serious status** which manages the bass according to a value between -10 and 10
- Adding commands **Highs** (action/cursor) and **Acute status** 
- Adding the command **Party mode** which allows you to group all Sonos together
- Adding the command **Mic status** 
- Adding an info command **Battery** on Sonos equipped with a battery indicating the battery charge percentage
- Adding an info command **Loading** 
- Adding an info command **Next alarm** on each Sonos giving the date of the next alarm programmed on this speaker

# 04/25/2024

- Documentation update
- )
- )
- Added Sonos Beam Gen 2

# 01/15/2024

- Preparing for Jeedom 4.4
- Added Sonos Move 2

# 08/24/2023

- Added Ikea Symfonisk Floor Lamp

# 05/25/2023

- Added Sonos Era

# 10/18/2022

- Update command list for Jeedom v4.3
- Added Sonos Ray

# 03/22/2022

- Support for the new SYMFONISK loudspeaker

# 02/01/2022

- Fixed a bug on the TTS

# 01/27/2022

- V4.2 optimizations

# 01/14/2022

- 

# 12/27/2021

- 

# 09/10/2021

- Addition of the Sonos Five
- Adding Sonos Roam
- Adding Symfonisk Framework
- Immediate volume update in case of change by Jeedom, thank you @Domochip

# 24/11/2020

- New presentation of the list of objects
- Addition of the tag "V4 compatibility"

# 08/07/2020

- Sonos ARC support

# 01/24/2020

- Support for Sonos One S22

# 01/11/2020

- Support for Sonos Move
- Code optimization in case of Sonos not connected

# 12/16/2019

- Bug fix if a sound system cannot be reached

# 10/21/2017

- Improvement in recovery from TTS

# 10/15/2019

- Sonos port support
- Improved dependency installation script

# 10/07/2019

- Improvement of the dependency installation script (may allow to correct in some cases the problems of TTS)

# 09/23/2019

- Optimisations

# 09/01/2019

- Ikea SYMFONISK lamp speaker support

# 08/12/2019

- Support for Ikea SYMFONISK bookshelf speaker

# 04/23/2019

- Support for one gen2 sonos

# 01/17/2019

- Fixed bugs in case the sound systems were added manually

# 01/15/2019

**IMPORTANT ONLY WORKS WITH PHP7, SEE JEEDOM HEALTH PAGE FOR YOUR VERSION**

- Complete rewrite of the plugin
- Support for the new Sonos API
- Support for Beam and One sound systems
- 
- Global optimizations

**Important**

- Compatible PHP7 only
- Some features had to be removed

# 2018

- Added management of sonos favorites
- Support for Sonos One and Playbase
- Tongue correction with picotts
- Adding a "line entry" command"
- 
- Optimized loading of playlists
- Addition of picotts for local TTS generation
- Correction of the play / pause button when updating the widget.
