# Changelog Sonos Controller

>**WICHTIG**
>
>Zur Erinnerung: Wenn keine Informationen zum Update vorhanden sind, bedeutet dies, dass es sich ausschließlich um eine Aktualisierung der Dokumentation, der Übersetzung oder des Textes handelt.

# 18-05-2026

- Behebung eines kleinen Fehlers beim Befehl **Dire**

# 11-04-2026

- Hinzufügen eines Befehls „Info **Sender**“, der den aktuell wiedergegebenen Radiosender anzeigt (sofern diese Information verfügbar ist)

# 27-01-2026

- Bild für *Ikea Tischlampe* hinzugefügt

# 19-01-2026

- Hinzufügen einer optionalen Konfiguration, um – nur falls erforderlich – das Subnetz (VLAN) anzugeben, in dem sich Ihre Sonos-Lautsprecher befinden, falls dieses vom Subnetz (VLAN) abweicht, in dem sich Jeedom befindet
- Korrekturen für die Meldung „Subscription renewal failed“ und den Verlust der Informationsübermittlung
- Bildkorrekturen

# 26-04-2025

> Aufmerksamkeit
> Umfassende Überarbeitung des Plugins: Ein Großteil des Plugins wurde neu geschrieben, darunter die gesamte Kommunikation mit Sonos (Daemon), und einige Funktionen wurden geändert und funktionieren nicht mehr wie zuvor, insbesondere die Verwaltung von Gruppen;
>
> Erfordert Jeedom 4.4.8
>
> Kompatibel mit Debian 11 und 12!
>
> Siehe auch [dieses Thema auf Community](https://community.jeedom.com/t/erreur-you-cannot-create-a-controller-instance-from-a-speaker-that-is-not-the-coordinator-of-its-group/128862) Weitere Informationen

- Das Plugin wurde fast vollständig überarbeitet; der Daemon wurde komplett in Python (anstelle von PHP) neu geschrieben.
- Kompatibel mit Debian 11 und 12!
- Es muss kein Erkennungsvorgang mehr manuell gestartet werden, und es ist nicht mehr erforderlich (und auch nicht möglich), Geräte manuell hinzuzufügen. Das Plugin erkennt Ihre Sonos-Geräte automatisch und legt bei jedem Start des Daemons die entsprechenden Geräte an.
- Es ist außerdem möglich, über das Geräte-Panel die (Neu-)Synchronisierung von Geräten, Favoriten und Wiedergabelisten anzufordern, ohne den Daemon neu zu starten.
- Stündliche automatische Synchronisierung zur Korrektur eventueller Abweichungen
- Aktualisierung der Steuerungsinformationen in (nahezu) Echtzeit (Verzögerung von 0,5 s bis maximal einigen Sekunden), kein minutenspezifischer Cron-Job mehr, auch wenn eine Änderung außerhalb von Jeedom vorgenommen wird (z. B. über die Sonos-App)
- Überarbeitung der Gruppenverwaltung (die bisherigen Befehle werden entfernt und neue hinzugefügt, siehe Dokumentation). Es ist möglich, einer Gruppe beizutreten oder sie zu verlassen sowie die Wiedergabe der Gruppe von jedem Gerät der Gruppe aus zu steuern, ohne darauf achten zu müssen, welches Gerät als Controller fungiert. Die Lautstärke wird weiterhin pro Lautsprecher geregelt.
- Anpassung der Text-to-Speech-Funktion (TTS): **Die Konfiguration der SAMBA-Freigabe muss angepasst werden**.
- Optimierung: Keine Speicherverluste mehr beim Daemon, und er verbraucht weniger als zuvor.
- Die Anzeige des aktuell wiedergegebenen Covers wurde optimiert
- Optimierung der Lesefavoriten
- Es wurde die Möglichkeit hinzugefügt, die vorkonfigurierte Kachel zu deaktivieren: Sie können diese dann nach Belieben konfigurieren, indem Sie die Widgets des Kerns oder Ihre eigenen Widgets verwenden und die gewünschten Befehle ein- oder ausblenden...

- Hinzufügen eines Befehls „**TV**“, um bei kompatiblen Geräten auf den Eingang *TV* umzuschalten
- Hinzufügen eines Befehls „**Wiedergabemodus**“ und einer Aktion „**Wiedergabemodus auswählen**“, mit der der Wiedergabemodus aus den folgenden Optionen ausgewählt werden kann: *Normal*, *Alles wiederholen*, *Zufällig und alles wiederholen*, *Zufällig ohne Wiederholung*, *Titel wiederholen*, *Zufällig und Titel wiederholen*
- Hinzufügen eines Befehls **Lesestatus**, der den „rohen“ Wert des Lesestatus angibt (der bereits vorhandene Befehl **Status** gibt einen Wert aus, der entsprechend der in Jeedom konfigurierten Sprache übersetzt wurde)
- Hinzufügen der Befehle **Statusgruppe** (gibt an, ob das Gerät einer Gruppe zugeordnet ist oder nicht) und **Gruppenname**, falls das Gerät einer Gruppe zugeordnet ist
- Hinzufügen der Befehle **LED ein**, **LED aus** und **LED-Status** zur Steuerung der Status-LED
- Hinzufügen eines Befehls **MP3-Radio abspielen**, um ein MP3-Radio direkt über eine URL (z. B. im Internet verfügbar) abzuspielen
- Hinzufügen der Befehle **Lautstärke erhöhen** und **Lautstärke verringern** um 1 %
- Es wurde ein Befehl namens **Lautstärkenübergang** hinzugefügt, der für die Steuerung von Lautstärkenübergängen sehr nützlich ist. Es stehen 3 Modi zur Verfügung: *LINEAR*, *ALARM*, *AUTOPLAY*. Weitere Informationen finden Sie in der Dokumentation.
- Hinzufügen der Befehle **Loudness-Status**, **Loudness ein**, **Loudness aus**
- Hinzufügen der Befehle **Status-Überblendung**, **Überblendung ein**, **Überblendung aus**
- Hinzufügen der Befehle **Touch-Befehle „Status“**, **Touch-Befehle „Ein“**, **Touch-Befehle „Aus“**
- Hinzufügen der Befehle **Waage** (Aktion/Schieberegler) und **Waagenstatus**, die die Waage anhand eines Werts zwischen -100 (ganz links) und 100 (ganz rechts) steuern
- Hinzufügen der Befehle **Bässe** (Aktion/Schieberegler) und **Bässe-Status**, die die Bässe anhand eines Werts zwischen -10 und 10 steuern
- Hinzufügen der Befehle **Höhen** (Aktion/Schieberegler) und **Höhenstatus**, die die Höhen anhand eines Werts zwischen -10 und 10 regeln
- Hinzufügung der Steuerung **Party-Modus**, mit der alle Sonos-Geräte zusammengefasst werden können
- Hinzufügen des Befehls **Mikrofonstatus**, der anzeigt, ob das Mikrofon bei Sonos-Geräten mit Mikrofon aktiviert ist oder nicht
- Hinzufügen eines Info-Befehls **Batterie** bei Sonos-Geräten mit Batterie, der den Ladezustand der Batterie in Prozent anzeigt
- Hinzufügen eines Info-Befehls **Ladevorgang** auf Sonos-Geräten mit Batterie, der anzeigt, ob gerade geladen wird oder nicht
- Hinzufügen eines Befehls „**Nächster Alarm**“ auf jedem Sonos-Gerät, der das Datum des nächsten auf diesem Lautsprecher programmierten Alarms angibt

# 25/04/2024

- Aktualisierung der Dokumentation
- Entfernen von Akzenten in Freigabenamen (vom Plugin nicht unterstützt)
- Beseitigung der Abhängigkeit von PicoTTS (das Plugin nutzt die globale TTS-Engine von Jeedom)
- Sonos Beam Gen 2 hinzugefügt

# 15/01/2024

- Vorbereitung auf Jeedom 4.4
- Sonos Move 2 hinzugefügt

# 24/08/2023

- Ikea Symfonisk Stehlampe hinzugefügt

# 25/05/2023

- Sonos-Ära hinzugefügt

# 18/10/2022

- Befehlsliste für Jeedom v4.3 aktualisieren
- Sonos Ray hinzugefügt

# 22/03/2022

- Unterstützung für den neuen SYMFONISK-Lautsprecher

# 01/02/2022

- Fehler im TTS behoben

# 27/01/2022

- V4.2-Optimierungen

# 14/01/2022

- Kompatibilität mit dem neuen SYMFONISK-Lautsprecher hinzugefügt

# 27/12/2021

- Kompatibilität mit dem neuen Sonos One hinzugefügt

# 09/10/2021

- Hinzufügung der Sonos Five
- Hinzufügen von Sonos Roam
- Symfonisk Framework hinzufügen
- Sofortige Volumenaktualisierung bei Änderung durch Jeedom, danke @Domochip

# 24/11/2020

- Neue Darstellung der Objektliste
- Hinzufügen des Tags „V4-Kompatibilität“

# 07/08/2020

- Sonos ARC-Unterstützung

# 24/01/2020

- Unterstützung für Sonos One S22

# 11/01/2020

- Unterstützung für Sonos Move
- Codeoptimierung bei nicht verbundenem Sonos

# 16/12/2019

- Fehlerbehebung, wenn ein Soundsystem nicht erreicht werden kann

# 21/10/2017

- Verbesserung der Erholung von TTS

# 15/10/2019

- Sonos Port-Unterstützung
- Verbessertes Skript zur Installation von Abhängigkeiten

# 07/10/2019

- Verbesserung des Skripts zur Installation der Abhängigkeiten (kann in bestimmten Fällen dazu beitragen, TTS-Probleme zu beheben)

# 23/09/2019

- Optimierungen

# 01/09/2019

- Unterstützung für Ikea SYMFONISK Lampenlautsprecher

# 12/08/2019

- Unterstützung für Ikea SYMFONISK Regallautsprecher

# 23/04/2019

- Unterstützung für ein Gen2-Sonos

# 17/01/2019

- Fehler behoben, falls die Soundsysteme manuell hinzugefügt wurden

# 15/01/2019

**WICHTIG: FUNKTIONIERT NUR MIT PHP7, SIEHE DIE JEEDOM-STATUSSEITE FÜR IHRE VERSION**

- Vollständiges Umschreiben des Plugins
- Unterstützung für die neue Sonos-API
- Unterstützung für Beam- und One-Soundsysteme
- Behebung zahlreicher Fehler
- Globale Optimierungen

**WICHTIG**

- Nur kompatibles PHP7
- Einige Funktionen mussten entfernt werden

# 2018

- Verwaltung der Sonos-Favoriten hinzugefügt
- Unterstützung für Sonos One und Playbase
- Zungenkorrektur mit Picotts
- Hinzufügen eines Befehls „Zeilen-Eingabe“
- Aktualisierung der Kommunikationsbibliothek für Sonos
- Optimiertes Laden von Wiedergabelisten
- Zugabe von Picotts zur lokalen TTS-Erzeugung
- Korrektur der Wiedergabe-/Pause-Schaltfläche beim Aktualisieren des Widgets.
