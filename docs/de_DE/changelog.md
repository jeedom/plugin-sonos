# Changelog Sonos Controller

>**Wichtig**
>
>Zur Erinnerung: Wenn keine Informationen zum Update vorhanden sind, bedeutet dies, dass es sich nur um die Aktualisierung von Dokumentation, Übersetzung oder Text handelt

# 19.01.2026

- Ajout d'une configuration optionnelle pour indiquer, uniquement si nécessaire, le sous-réseau (vlan) sur lequel se trouvent vos enceintes Sonos si celui-ci est différent du sous-réseau (vlan) sur lequel se trouve Jeedom
- Corrections pour le message "Subscription renewal failed" et la perte de remontée des informations
- Bildkorrekturen

# 26-04-2025

> Attention
> Bedeutende Neugestaltung des Plugins : une très grande partie du plugin a été réécrite dont toute la communication avec Sonos (démon) et certaines fonctionnalités ont été modifiées et ne fonctionnent plus comme précédemment, notamment la gestion des groupes ;
>
> Erfordert Jeedom 4.4.8
>
> Debian 11 und 12 kompatibel!
>
> Siehe auch [dieses Thema auf Community](https://community.jeedom.com/t/erreur-you-cannot-create-a-controller-instance-from-a-speaker-that-is-not-the-coordinator-of-its-group/128862) für mehr Details

- Das Plugin wurde fast komplett neu geschrieben, der Daemon wurde komplett in Python (anstelle von PHP) neu geschrieben)
- Debian 11 und 12 kompatibel!
- Il n'y a plus de découverte à lancer manuellement et il n'est plus nécessaire (ni possible) d'ajouter manuellement un équipement, le plugin découvre automatiquement vos appareils Sonos et crée les équipements correspondants à chaque démarrage du démon.
- Es ist auch möglich, Geräte, Favoriten und Wiedergabelisten (erneut) zu synchronisieren, ohne den Daemon über das Gerätefenster neu zu starten.
- Synchro automatique chaque heure pour corriger les désynchronisations éventuelles
- Mise à jour en (quasi) temps réel des commandes infos (un délai de 0,5s à quelques secondes max), plus de cron minute, y compris lorsqu'un changement est effectué hors Jeedom (via l'app Sonos par exemple)
- Neugestaltung der Gruppenverwaltung (alte Befehle werden gelöscht und neue hinzugefügt, siehe Dokumentation). Es ist möglich, einer Gruppe beizutreten oder sie zu verlassen und die Wiedergabe der Gruppe von jedem Gerät in der Gruppe aus zu steuern, ohne sich Gedanken darüber machen zu müssen, wer der Controller ist. Le volume est, lui, toujours contrôlé par enceinte.
- Adaptation sur la fonction Text-to-Speech (TTS), **il sera nécessaire d'adapter la configuration du partage SAMBA**.
- Optimierung : plus de pertes de mémoire sur le démon et il consomme moins qu'auparavant.
- Die Anzeige des aktuell wiedergegebenen Covers wurde optimiert
- Optimierung der Lesefavoriten
- Ajout de la possibilité de désactiver la tuile pré-configurée : vous êtes alors libre de configurer celle-ci comme vous le souhaitez en utilisant les widgets du core ou vos propres widgets, d'afficher ou de masquer les commandes de votre choix...

- Hinzufügen eines Aktionsbefehls **Fernseher** um zum Eingang zu wechseln *Fernseher* auf kompatiblen Geräten
- Hinzufügen eines Info-Befehls **Lesemodus** und Aktion **Wählen Sie den Lesemodus** Hier können Sie den Lesemodus aus den folgenden Möglichkeiten auswählen: *Normal*, *Wiederhole alles*, *Zufällig und alles wiederholen*, *Zufällig ohne Wiederholung*, *Lied wiederholen*, *Zufälliges und wiederholtes Lied*
- Bestellung hinzufügen **Lesestatus** Dies gibt den „Rohwert“ des Lesestatus (den vorhandenen Befehl) an **Status** Gibt einen übersetzten Wert basierend auf der in Jeedom konfigurierten Sprache an)
- Befehle hinzufügen **Gruppenstatus** (gibt an, ob das Gerät gruppiert ist oder nicht) und **Name der Gruppe** für den Fall, dass die Ausrüstung gruppiert ist
- Befehle hinzufügen **Das Geschenk**, **LED aus** Und **Status-LED** um die Statusanzeige zu überprüfen
- Bestellung hinzufügen **Spielen Sie MP3-Radio** um ein MP3-Radio direkt über eine URL (z. B. im Internet zugänglich) abzuspielen)
- Befehle hinzufügen **Drehe die Lautstärke hoch** Und **Verringern Sie die Lautstärke** ab 1%
- Bestellung hinzufügen **Lautstärkeübergang** Dies ist sehr nützlich für die Verwaltung von Lautstärkeübergängen. 3 mögliche Modi: *LINEAR*, *ALARM*, *AUTOMATISCHES ABSPIELEN*. Weitere Informationen finden Sie in der Dokumentation.
- Befehle hinzufügen **Lautstärkestatus**, **Lautstärke an**, **Lautstärke aus**
- Befehle hinzufügen **Fade-Status**, **Einblenden**, **Ausblenden**
- Befehle hinzufügen **Status-Touch-Steuerelemente**, **Touch-Bedienelemente ein**, **Touch-Steuerung ausgeschaltet**
- Befehle hinzufügen **Gleichgewicht** (Aktion/Cursor) und **Kontostand** die den Saldo nach einem Wert zwischen -100 (ganz links) und 100 (ganz rechts) verwaltet)
- Befehle hinzufügen **Gräber** (Aktion/Cursor) und **Ernsthafter Status** die den Bass nach einem Wert zwischen -10 und 10 verwaltet
- Befehle hinzufügen **Verdreifachen** (Aktion/Cursor) und **Dreifacher Status** qui gèrent les aigus selon une valeur comprise entre -10 et 10
- Befehl hinzufügen **Partymode** Dadurch können Sie alle Sonos gruppieren
- Befehl hinzufügen **Mikrofonstatus** qui indique si le micro est activé ou non sur les Sonos équipés d'un micro
- Hinzufügen eines Info-Befehls **Batterie** Bei Sonos, die mit einem Akku ausgestattet sind, wird der Akkuladestand in Prozent angezeigt
- Hinzufügen eines Info-Befehls **Laden** sur les Sonos équipés d'une batterie qui indique si la charge est en cours ou non
- Hinzufügen eines Info-Befehls **Nächster Alarm** Auf jedem Sonos wird das Datum des nächsten auf diesem Lautsprecher programmierten Alarms angezeigt

# 25.04.2024

- Aktualisierung der Dokumentation
- Nettoyage des accents dans les noms de partage (non supportés par le plugin)
- Suppression de la dépendance à PicoTTS (le plugin utilise le moteur global de TTS de Jeedom)
- Sonos Beam Gen 2 hinzugefügt

# 15.01.2024

- Vorbereitung auf Jeedom 4.4
- Sonos Move 2 hinzugefügt

# 24.08.2023

- Ikea Symfonisk Stehlampe hinzugefügt

# 25.05.2023

- Sonos-Ära hinzugefügt

# 18.10.2022

- Befehlsliste für Jeedom v4.3 aktualisieren
- Sonos Ray hinzugefügt

# 22.03.2022

- Unterstützung für den neuen SYMFONISK-Lautsprecher

# 01.02.2022

- Fehler im TTS behoben

# 27.01.2022

- V4.2-Optimierungen

# 14.01.2022

- Ajout de la compatibilité avec la nouvelle enceinte SYMFONISK

# 27.12.2021

- Ajout de la compatibilité avec la nouvelle Sonos One

# 09.10.2021

- Hinzufügung der Sonos Five
- Hinzufügen von Sonos Roam
- Symfonisk Framework hinzufügen
- Sofortige Volumenaktualisierung bei Änderung durch Jeedom, danke @Domochip

# 24/11/2020

- Neue Darstellung der Objektliste
- Hinzufügung des Tags "V4-Kompatibilität"

# 08/07/2020

- Sonos ARC-Unterstützung

# 24.01.2020

- Unterstützung für Sonos One S22

# 01/11/2020

- Unterstützung für Sonos Move
- Codeoptimierung bei nicht verbundenem Sonos

# 16.12.2019

- Fehlerbehebung, wenn ein Soundsystem nicht erreicht werden kann

# 21.10.2017

- Verbesserung der Erholung von TTS

# 15.10.2019

- Sonos Port-Unterstützung
- Verbessertes Skript zur Installation von Abhängigkeiten

# 10/07/2019

- Verbesserung des Abhängigkeitsinstallationsskripts (kann in einigen Fällen die Behebung der Probleme von TTS ermöglichen)

# 2019.09.23

- Optimisations

# 09/01/2019

- Unterstützung für Ikea SYMFONISK Lampenlautsprecher

# 08/12/2019

- Unterstützung für Ikea SYMFONISK Regallautsprecher

# 23.04.2019

- Unterstützung für ein Gen2-Sonos

# 17.01.2019

- Fehler behoben, falls die Soundsysteme manuell hinzugefügt wurden

# 2019.01.15

**WICHTIG NUR MIT PHP7 ARBEITEN, SIEHE JEEDOM GESUNDHEITSSEITE FÜR IHRE VERSION**

- Vollständiges Umschreiben des Plugins
- Unterstützung für die neue Sonos-API
- Unterstützung für Beam- und One-Soundsysteme
- Correction de nombreux bugs
- Globale Optimierungen

**Wichtig**

- Nur kompatibles PHP7
- Einige Funktionen mussten entfernt werden

# 2018

- Verwaltung der Sonos-Favoriten hinzugefügt
- Unterstützung für Sonos One und Playbase
- Zungenkorrektur mit Picotts
- Hinzufügen eines Befehls "Zeileneintrag""
- Mise à jour de la librairie de communication avec les Sonos
- Optimiertes Laden von Wiedergabelisten
- Zugabe von Picotts zur lokalen TTS-Erzeugung
- Korrektur der Wiedergabe- / Pause-Schaltfläche beim Aktualisieren des Widgets.
