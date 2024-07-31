# Sonos Plugin

Mit dem Sonos-Plug-in können Sie Sonos Play 1, 3, 5, Sonos Connect, Sonos Connect AMP, Sonos Playbar, Ikea Symfonisk... steuern. Es ermöglicht Ihnen, den Status von Sonos anzuzeigen und Aktionen auszuführen (Wiedergabe, Pause , nächstes, vorheriges, Lautstärke, Auswahl einer Playlist…).

# Plugin Konfiguration

Die Konfiguration ist sehr einfach, nach dem Herunterladen des Plugins müssen Sie es nur noch aktivieren, die Abhängigkeiten installieren und den Daemon starten.
Das Plugin sucht in Ihrem Netzwerk nach Sonos und erstellt die Geräte automatisch. Wenn zwischen Jeedom-Objekten und Sonos-Räumen eine Übereinstimmung besteht, weist Jeedom Sonos automatisch den richtigen Räumen zu.

> **Wichtig**
> Ihre Sonos-Geräte müssen direkt von der Maschine erreichbar sein, auf der Jeedom gehostet wird, und sie müssen Jeedom im Gegenzug über den TCP-Port 1400 erreichen können.

> **TIPP**
>
> Bei der ersten Ermittlung wird dringend empfohlen, keine Soundsysteme zu gruppieren, da sonst Fehler auftreten.

Wenn Sie später einen Sonos hinzufügen, können Sie auf klicken **Synchronisieren** auf der Geräteseite oder starten Sie den Daemon neu.

- **Teilen**: Konfigurieren Sie hier den Hostnamen der Maschine (bzw. deren IP), den Namen der Freigabe (ohne Pfad, ohne „/“) und den Pfad zum Ordner.
- **Teilen Sie den Benutzernamen** : Benutzername für den Zugriff auf die Freigabe.
- **Passwort teilen** : Passwort teilen.

> **Wichtig**
>
> Zu lange Nachrichten können nicht in TTS (dem Limit) übertragen werden
> hängt vom TTS-Anbieter ab, normalerweise ca. 100 Zeichen).

# Gerätekonfiguration

Auf die Konfiguration der Sonos-Geräte kann über das Plugins-Menü und dann über Multimedia zugegriffen werden.

Hier finden Sie die gesamte Konfiguration Ihrer Geräte :

- **Sonos Name** : Name Ihres Sonos-Geräts.
- **Übergeordnetes Objekt** : Gibt das übergeordnete Objekt an, zu dem das Gerät gehört.
- **Aktivieren** : macht Ihre Ausrüstung aktiv.
- **Sichtbar** : macht es auf dem Dashboard sichtbar.

Sowie Informationen zu Ihrem Sonos: *Modell*, *Veröffentlichungen*, *Seriennummer*, *Kennung*, *MAC-Adresse* Und *IP Adresse*.

# Die Aufträge

Die Info-Steuerelemente werden nahezu in Echtzeit aktualisiert (normalerweise maximal ein paar Sekunden), aber bei einem Titelwechsel kann es etwas länger dauern, bis das Bild des gerade abgespielten Albums im Widget angezeigt wird. Dies ist völlig normal und unabhängig vom Plugin: Er muss das Bild von einer externen Quelle (auf einem Sonos oder im Internet) abrufen, was manchmal mehrere Sekunden dauert (im Prinzip maximal etwa zehn Sekunden)).

## Lautstärke- und Quellensteuerung

Diese Befehle steuern immer das entsprechende Gerät, auch wenn es sich in einer Gruppe befindet.

- **Lautstärke** : Ändern Sie die Lautstärke *(von 0 bis 100)*.
- **Statusvolumen** : Lautstärke.
- **Stumm** : Stummschaltung aktivieren.
- **Keine Stummschaltung** : Schalten Sie die Stummschaltung aus.
- **Stummschaltungsstatus** : zeigt an, ob wir uns im Stummmodus befinden oder nicht.

- **Fernseher** : um zum Eingang zu wechseln *Fernseher* auf kompatiblen Geräten
- **Analoger Audioeingang** : zu wechseln'*Analoger Audioeingang* auf kompatiblen Geräten

## Wiedergabesteuerung

Diese Befehle zeigen und steuern die aktuelle Wiedergabe auf dem Gerät oder in der Gruppe, wenn diese gruppiert ist, und zwar auf transparente Weise. Sie müssen sich keine Gedanken darüber machen, ob das Gerät gruppiert ist oder ob Sie sie nicht verwenden sollen.

- **Status** : Leserstatus in die unter Jeedom konfigurierte Sprache übersetzt. Zum Beispiel: *Lesen*, *Pause*, *Gestoppt*.
- **Lesestatus** Dies gibt den „Rohwert“ des Lesestatus an: *SPIELEN*, *PAUSED_PLAYBACK*, *GESTOPPT*; besser geeignet für Szenarien.
- **Lesen** : lesen.
- **Pause** : Pause.
- **STOP** : Hör auf zu lesen.
- **Früher** : vorheriger Titel.
- **Folgende** : nächster Track.
- **Zufälliger Status** : zeigt an, ob wir uns im Zufallsmodus befinden oder nicht.
- **Zufällig** : Kehren Sie den Status des Zufallsmodus um.
- **Status wiederholen** : zeigt an, ob wir uns im Wiederholungsmodus befinden oder nicht.
- **Wiederholung** : Kehren Sie den Status des "Wiederholungs" -Modus um".
- **Lesemodus** Status und Befehl geben **Wählen Sie den Lesemodus** wodurch Sie aus den folgenden Möglichkeiten wählen können: *Normal*, *Wiederhole alles*, *Zufällig*, *Zufällig ohne Wiederholung*, *Lied wiederholen*, *Zufälliges und wiederholtes Lied*. Diese Aktion entspricht der Verwendung der Befehle **Wiederholung** & **Zufällig** um in die gewünschte Konfiguration zu gelangen. Allerdings ist dies die einzige Möglichkeit, in den Modus „Song wiederholen“ zu wechseln".
- **Playlist abspielen** : Befehl zum Nachrichtentyp, um eine Wiedergabeliste zu starten. Geben Sie einfach den Namen der Wiedergabeliste in den Titel ein.
- **Favoriten spielen** :  Befehl zum Nachrichtentyp, um einen Favoriten zu starten. Im Titel müssen Sie lediglich den Namen des Favoriten eingeben.
- **Spielen Sie ein Radio** : Wenn Sie einen Befehl zum Starten eines Radiosenders eingeben, müssen Sie lediglich den Namen des Radios in den Titel einfügen *(Achtung : Dies muss in den Lieblingsradios sein)*. Funktioniert nicht mehr bei „S2“-Modellen".
- **Bild** : Link zum Albumbild.
- **Album** : Name des aktuell wiedergegebenen Albums.
- **Künstler** : Künstlername spielt gerade.
- **Verfolgen** : Name des aktuell wiedergegebenen Titels.
- **Zu sagen** : ermöglicht das Lesen eines Textes auf Sonos (siehe TTS-Teil). Im Titel können Sie die Lautstärke und in der Nachricht die zu lesende Nachricht einstellen.

## Befehle zum Verwalten von Gruppen

Diese Befehle wirken sich immer auf das entsprechende Gerät aus.

- **Gruppenstatus** : Gibt an, ob das Gerät gruppiert ist oder nicht.
- **Name der Gruppe** Wenn das Gerät gruppiert ist, geben Sie den Namen der Gruppe an.
- **Einer Gruppe beitreten** : ermöglicht es Ihnen, der Gruppe des angegebenen Lautsprechers (einem Sonos) beizutreten (um beispielsweise zwei Sonos zuzuordnen)). Sie müssen den Namen des Soundsystem-Raums eingeben, dem Sie beitreten möchten. Dies kann ein beliebiges Mitglied einer bestehenden Gruppe sein, es muss nicht der Gruppenkoordinator oder ein isolierter Sonos sein.
- **Die Gruppe verlassen** : ermöglicht es Ihnen, die Gruppe zu verlassen.

# TTS

TTS (Text-to-Speech) für Sonos erfordert die Freigabe von Windows (Samba) im Netzwerk (von Sonos vorgeschrieben, keine andere Möglichkeit)). Sie benötigen also einen NAS im Netzwerk. Die Konfiguration ist recht einfach. Sie müssen den Namen oder die IP des NAS (achten Sie darauf, dass Sie das Gleiche wie auf Sonos deklarieren) sowie den Pfad (relativ), den Benutzernamen und das Passwort ( Aufmerksamkeit der Benutzer muss Schreibrechte haben)

Die Erstellung der Audiodatei wird vom Jeedom-Kern verwaltet: Die Sprache ist die in Jeedom konfigurierte und die verwendete TTS-Engine kann auch in den Jeedom-Konfigurationsbildschirmen ausgewählt werden.

Bei Verwendung von TTS (Befehl **Zu sagen**), Das Plugin führt die folgenden Aktionen aus:

- Generierung der Audiodatei, die die Nachricht enthält, mit Jeedom-Kernunterstützung
- Schreiben der Datei auf die SAMBA-Freigabe
- Erzwingt die Wiedergabe im „Normal“-Modus ohne Wiederholung
- Erzwingen Sie den Modus „Stummschaltung aufheben“"
- Ändern der Lautstärke auf den gewählten Wert, wenn Sie den Befehl verwenden
- Nachricht lesen
- Wiederherstellen des Zustands des Sonos vor der Wiedergabe (d. h. des Wiedergabemodus, stumm oder nicht, wiederholen oder nicht usw.) und Neustarten des Streams, wenn der Sonos gerade abgespielt hat

> **Wichtig**
>
> Es ist unbedingt erforderlich, ein Passwort einzugeben, damit dieses Verfahren funktioniert.
>
> Ein Unterverzeichnis ist auch unbedingt erforderlich, damit die Sprachdatei korrekt erstellt wird.
>
> Vor allem dürfen im Namen der Freigabe oder des Ordners keine Akzente, Leerzeichen oder Sonderzeichen enthalten sein

**Hier ist ein Beispiel für die Konfiguration (danke @masterfion) :**

NAS-Seite, hier ist meine Konfiguration :

- Jeedom-Ordner wird freigegeben.
- Der Sonos-Benutzer hat Lese- / Schreibzugriff (erforderlich für Jeedom)).
- Der Gastbenutzer hat nur Lesezugriff (für Sonos erforderlich)).

Auf der Sonos-Plugin-Seite ist die config :

- Teilen :
  - Feld 1: 192.168.xxx.yyy
  - Feld 2: Jeedom
  - Feld 3: TTS
- Benutzername und Passwort...​

Sonos Library Seite (PC App)

- der Weg ist : //192.168.xxx.yyy/Jeedom/TTS

# Das Panel

Das Sonos-Plugin bietet auch ein Bedienfeld, in dem alle Ihre Sonos zusammengefasst sind. Verfügbar über das Home-Menü → Sonos Controller :

> **Wichtig**
>
> Um das Panel zu haben, müssen Sie es in der Plugin-Konfiguration aktiviert haben.
