# Changelog Sonos Controller

>**Wichtig**
>
>Zur Erinnerung: Wenn keine Informationen zum Update vorhanden sind, bedeutet dies, dass es sich nur um die Aktualisierung von Dokumentation, Übersetzung oder Text handelt

> Attention
> Bedeutende Neugestaltung des Plugins: 
>
> Erfordert Jeedom 4.4.8
>
> Debian 11 und 12 kompatibel!
>
> Siehe auch [dieses Thema auf Community](https://community.jeedom.com/t/erreur-you-cannot-create-a-controller-instance-from-a-speaker-that-is-not-the-coordinator-of-its-group/128862) für mehr Details

# 24/01/2025

- Ajout de la possibilité de désactiver la tuile pré-configurée: vous êtes alors libre de configurer de celle-ci comme vous le souhaitez en utilisant les widgets du core ou vos propres widgets, d'afficher ou masquer les commandes de votre choix...

# 13/11/2024

- Hinzufügung einer stündlichen automatischen Synchronisierung, um mögliche Desynchronisierungen zu korrigieren
- Hinzufügen eines Info-Befehls **Nächster Alarm** Auf jedem Sonos wird das Datum des nächsten auf diesem Lautsprecher programmierten Alarms angezeigt

# 29.08.2024

- Die Anzeige des aktuell wiedergegebenen Covers wurde optimiert
- Optimierung der Lesefavoriten

# 18.08.2024

- Bestellung hinzufügen **Mikrofonstatus** Dies zeigt an, ob das Mikrofon bei Sonos, die mit einem Mikrofon ausgestattet sind, aktiviert ist oder nicht
- Hinzufügen eines Info-Befehls **Batterie** Bei Sonos, die mit einem Akku ausgestattet sind, wird der Akkuladestand in Prozent angezeigt
- Hinzufügen eines Info-Befehls **Laden** Bei Sonos, die mit einem Akku ausgestattet sind, wird angezeigt, ob der Ladevorgang läuft oder nicht

# 17.08.2024

- Befehle hinzufügen **Lautstärkestatus**, **Lautstärke an**, **Lautstärke aus**
- Befehle hinzufügen **Fade-Status**, **Einblenden**, **Ausblenden**
- Befehle hinzufügen **Status-Touch-Steuerelemente**, **Touch-Bedienelemente ein**, **Touch-Steuerung ausgeschaltet**
- Befehle hinzufügen **Gleichgewicht** (Aktion/Cursor) und **Kontostand** die den Saldo nach einem Wert zwischen -100 (ganz links) und 100 (ganz rechts) verwaltet)
- Befehle hinzufügen **Gräber** (Aktion/Cursor) und **Ernsthafter Status** die den Bass nach einem Wert zwischen -10 und 10 verwaltet
- Befehle hinzufügen **Verdreifachen** (Aktion/Cursor) und **Dreifacher Status** der die Höhen nach einem Wert zwischen -10 und 10 verwaltet
- Befehl hinzufügen **Partymode** Dadurch können Sie alle Sonos gruppieren

# 08.03.2024

- Bestellung hinzufügen **Spielen Sie MP3-Radio** um ein MP3-Radio direkt über eine URL (z. B. im Internet zugänglich) abzuspielen)
- Befehle hinzufügen **Drehe die Lautstärke hoch** Und **Verringern Sie die Lautstärke** ab 1%
- Bestellung hinzufügen **Lautstärkeübergang** Dies ist sehr nützlich für die Verwaltung von Lautstärkeübergängen. 3 mögliche Modi: *LINEAR*, *ALARM*, *AUTOMATISCHES ABSPIELEN*. Weitere Informationen finden Sie in der Dokumentation.

# 08.02.2024

- Befehle hinzufügen **Das Geschenk**, **LED aus** Und **Status-LED** um die Statusanzeige zu überprüfen
- Daemon-Absturz behoben, wenn kein Sonos erkannt wird
- Verbesserte Debian 10 / Python 3.7-Kompatibilität

# 01.08.2024

- Das Plugin wurde fast komplett neu geschrieben, der Daemon wurde komplett in Python (anstelle von PHP) neu geschrieben)
- Debian 11 und 12 kompatibel! (Wahrscheinlich Debian 10, aber nicht getestet und keine Unterstützung für diese Version möglich)
- Es gibt keine Erkennung mehr, die manuell gestartet werden muss, und es ist nicht mehr notwendig (und auch nicht möglich), Geräte manuell hinzuzufügen. Das Plugin erkennt automatisch Ihre Soundgeräte und erstellt die Geräte entsprechend jedem Start des Daemons. Es ist auch möglich, Geräte, Favoriten und Wiedergabelisten (erneut) zu synchronisieren, ohne den Daemon über das Gerätefenster neu zu starten
- (Fast) Echtzeitaktualisierung der Bestellinformationen (eine Verzögerung von 0.5 Sekunden bis maximal ein paar Sekunden), mehr Cron-Minuten, auch wenn eine Änderung außerhalb von Jeedom vorgenommen wird (z. B. über die Sonos-App))
- Neugestaltung der Gruppenverwaltung (alte Befehle werden gelöscht und neue hinzugefügt, siehe Dokumentation). Es ist möglich, einer Gruppe beizutreten oder sie zu verlassen und die Wiedergabe der Gruppe von jedem Gerät in der Gruppe aus zu steuern, ohne sich Gedanken darüber machen zu müssen, wer der Controller ist. Die Lautstärke wird immer über den Lautsprecher gesteuert.
- Bei der Anpassung an die Text-to-Speech (TTS)-Funktion ist eine Anpassung der SAMBA-Sharing-Konfiguration erforderlich.
- Optimisation: Es kommt zu keinem weiteren Speicherverlust beim Daemon und er verbraucht weniger als zuvor.

- Hinzufügen eines Aktionsbefehls **Fernseher** um zum Eingang zu wechseln *Fernseher* auf kompatiblen Geräten
- Hinzufügen eines Info-Befehls **Lesemodus** und Aktion **Wählen Sie den Lesemodus** Hier können Sie den Lesemodus aus den folgenden Möglichkeiten auswählen: *Normal*, *Wiederhole alles*, *Zufällig und alles wiederholen*, *Zufällig ohne Wiederholung*, *Lied wiederholen*, *Zufälliges und wiederholtes Lied*
- Bestellung hinzufügen **Lesestatus** Dies gibt den „Rohwert“ des Lesestatus (den vorhandenen Befehl) an **Status** Gibt einen übersetzten Wert basierend auf der in Jeedom konfigurierten Sprache an)
- Befehle hinzufügen **Gruppenstatus** (gibt an, ob das Gerät gruppiert ist oder nicht) und **Name der Gruppe** für den Fall, dass die Ausrüstung gruppiert ist

# 25.04.2024

- Aktualisierung der Dokumentation
- Akzente in Freigabenamen bereinigen (vom Plugin nicht unterstützt))
- Entfernen der Abhängigkeit von PicoTTS (das Plugin verwendet die globale TTS-Engine von jeedom))
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

- Kompatibilität mit dem neuen SYMFONISK-Lautsprecher hinzugefügt

# 27.12.2021

- Kompatibilität mit dem neuen Sonos One hinzugefügt

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
- Korrektur der Fehlernummer
- Globale Optimierungen

**Wichtig**

- Nur kompatibles PHP7
- Einige Funktionen mussten entfernt werden

# 2018

- Verwaltung der Sonos-Favoriten hinzugefügt
- Unterstützung für Sonos One und Playbase
- Zungenkorrektur mit Picotts
- Hinzufügen eines Befehls "Zeileneintrag""
- Aktualisierung der Soundkommunikationsbibliothek
- Optimiertes Laden von Wiedergabelisten
- Zugabe von Picotts zur lokalen TTS-Erzeugung
- Korrektur der Wiedergabe- / Pause-Schaltfläche beim Aktualisieren des Widgets
