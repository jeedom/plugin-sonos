# Sonos Plugin

Mit dem Sonos-Plug-in können Sie Sonos Play 1, 3, 5, Sonos Connect, Sonos Connect AMP, Sonos Playbar, Ikea Symfonisk... steuern. Es ermöglicht Ihnen, den Status von Sonos anzuzeigen und Aktionen auszuführen (Wiedergabe, Pause , nächstes, vorheriges, Lautstärke, Auswahl einer Playlist…).

> **Wichtig**
>
> Das Plugin verwaltet Gruppen sehr schlecht (oder gar nicht), daher ist es ratsam, die Gruppierung überhaupt nicht mit dem Plugin zu verwenden

# Plugin Konfiguration

Die Konfiguration ist sehr einfach. Nach dem Herunterladen des Plugins müssen Sie es nur noch aktivieren und fertig. Das Plugin sucht in Ihrem Netzwerk nach Sonos und erstellt die Geräte automatisch. Wenn zwischen Jeedom-Objekten und Sonos-Räumen eine Übereinstimmung besteht, weist Jeedom Sonos automatisch den richtigen Räumen zu.

> **TIPP**
>
> Bei der ersten Ermittlung wird dringend empfohlen, keine Soundsysteme zu gruppieren, da sonst Fehler auftreten.

Wenn Sie später einen Sonos hinzufügen, können Sie entweder ein Sonos-Gerät erstellen, indem Sie Jeedom die IP-Adresse geben, oder auf klicken **Finden Sie Sonos-Geräte**.

-   **Stimme** : Wahl der Stimme während der TTS
-   **Teilen** : Freigabename und Ordnerpfad.
-   **Teilen Sie den Benutzernamen** : Benutzername für den Zugriff auf die Freigabe.
-   **Passwort teilen** : Passwort teilen.
-   **Entdeckung** : Erkennen Sie automatisch die Soundsysteme (funktioniert nicht bei einer Docker-Installation, bei der jedes Soundsystem von Hand erstellt werden muss).

> **Wichtig**
>
> Zu lange Nachrichten können nicht in TTS (dem Limit) übertragen werden
> hängt vom TTS-Anbieter ab, normalerweise ca. 100 Zeichen).

# Gerätekonfiguration

Auf die Konfiguration der Sonos-Geräte kann über das Plugins-Menü und dann über Multimedia zugegriffen werden.

Hier finden Sie die gesamte Konfiguration Ihrer Geräte :

-   **Sonos Name** : Name Ihres Sonos-Geräts.
-   **Übergeordnetes Objekt** : Gibt das übergeordnete Objekt an, zu dem das Gerät gehört.
-   **Aktivieren** : macht Ihre Ausrüstung aktiv.
-   **Sichtbar** : macht es auf dem Dashboard sichtbar.
-   **Modell** : das Modell Ihres Sonos (nur ändern, wenn es nicht das richtige ist).
-   **IP** : Die IP Ihres Sonos kann nützlich sein, wenn Ihr Sonos die IP ändert oder wenn Sie sie ersetzen.

Nachfolgend finden Sie die Liste der Bestellungen :

-   **Name** : Name der Bestellung.
-   **Erweiterte Konfiguration (kleine gekerbte Räder)** : Zeigt die erweiterte Konfiguration des Befehls an (Protokollierungsmethode, Widget usw.)).
-   **Test** : Wird zum Testen des Befehls verwendet.

Als Bestellung finden Sie :

-   **Playlist abspielen** : Befehl zum Nachrichtentyp, um eine Wiedergabeliste zu starten. Geben Sie einfach den Namen der Wiedergabeliste in den Titel ein. Du kannst Geben ``random`` in der Nachricht, um die Wiedergabeliste vor dem Spielen zu mischen.
-   **Favoriten spielen** :  Befehl zum Nachrichtentyp, um einen Favoriten zu starten. Im Titel müssen Sie lediglich den Namen des Favoriten eingeben. Du kannst Geben ``random`` in der Nachricht, um Favoriten vor dem Lesen zu mischen.
-   **Spielen Sie ein Radio** : Wenn Sie einen Befehl zum Starten eines Radiosenders eingeben, müssen Sie lediglich den Namen des Radios in den Titel einfügen *(Achtung : Dies muss in den Lieblingsradios sein)*.
-   **Hinzufügen eines Lautsprechers** : Ermöglicht das Hinzufügen eines Lautsprechers (eines Sonos) zum aktuellen Lautsprecher (um beispielsweise 2 Sonos zuzuordnen). Sie müssen den Namen des Sonos-Raums in den Titel einfügen (das Nachrichtenfeld wird hier nicht verwendet).
-   **Lautsprecher entfernen** : Mit dieser Option können Sie einen Lautsprecher (einen Sonos) aus dem aktuellen Lautsprecher löschen (um beispielsweise 2 Sonos zu trennen). Sie müssen den Namen des zu löschenden Sonos-Teils in den Titel einfügen (das Nachrichtenfeld wird hier nicht verwendet).
-   **Zufälliger Status** : zeigt an, ob wir uns im Zufallsmodus befinden oder nicht.
-   **Zufällig** : Kehren Sie den Status des Zufallsmodus um.
-   **Status wiederholen** : zeigt an, ob wir uns im Wiederholungsmodus befinden oder nicht.
-   **Wiederholung** : Kehren Sie den Status des "Wiederholungs" -Modus um".
-   **Bild** : Link zum Albumbild.
-   **Album** : Name des aktuell wiedergegebenen Albums.
-   **Künstler** : Künstlername spielt gerade.
-   **Verfolgen** : Name des aktuell wiedergegebenen Titels.
-   **Stumm** : Geh stumm.
-   **Früher** : vorheriger Titel.
-   **Folgende** : nächster Track.
-   **Lesen** : lesen.
-   **Pause** : Pause.
-   **STOP** : Hör auf zu lesen.
-   **Lautstärke** : Ändern Sie die Lautstärke *(von 0 bis 100)*.
-   **Statusvolumen** : Lautstärke.
-   **Status** : Status (Pause, Lesen, Übergang…).
-   **Zu sagen** : ermöglicht das Lesen eines Textes auf Sonos (siehe TTS-Teil). Im Titel können Sie die Lautstärke und in der Nachricht die zu lesende Nachricht einstellen.

> **Notiz**
>
> Zum Abspielen von Wiedergabelisten können Sie Optionen (im Optionsfeld) einfügen). Um die Wiedergabeliste im zufälligen Spiel zu starten, müssen Sie sie eingeben ``random``.

# TTS

TTS (Text-to-Speech) für Sonos erfordert die Freigabe von Windows (Samba) im Netzwerk (von Sonos vorgeschrieben, keine andere Möglichkeit)). Sie benötigen also einen NAS im Netzwerk. Die Konfiguration ist recht einfach. Sie müssen den Namen oder die IP des NAS (achten Sie darauf, dass Sie das Gleiche wie auf Sonos deklarieren) sowie den Pfad (relativ), den Benutzernamen und das Passwort ( Aufmerksamkeit der Benutzer muss Schreibrechte haben)

> **Wichtig**
>
> Es ist unbedingt erforderlich, ein Passwort einzugeben, damit dieses Verfahren funktioniert.

> **Wichtig**
>
> Ein Unterverzeichnis ist auch unbedingt erforderlich, damit die Sprachdatei korrekt erstellt wird.

> **Wichtig**
>
> Vor allem dürfen im Namen der Freigabe oder des Ordners keine Akzente, keine Leerzeichen oder Sonderzeichen enthalten sein

**Hier ist ein Beispiel für die Konfiguration (danke @masterfion) :**

NAS-Seite, hier ist meine Konfiguration :

-   Jeedom-Ordner wird freigegeben.
-   Der Sonos-Benutzer hat Lese- / Schreibzugriff (erforderlich für Jeedom)).
-   Der Gastbenutzer hat nur Lesezugriff (für Sonos erforderlich)).

Sonos Plugin Seite, hier ist meine Konfiguration :

-   Teilen :
    -   Feld 1 : 192.168.xxx.yyy
    -   Feld 2 : Jeedom / TTS
-   Benutzername : Sonos und sein Passwort…

Sonos Library Seite (PC App)
-   der Weg ist : //192.168.xxx.yyy/Jeedom/TTS

> **Wichtig**
>
> Es ist ABSOLUT notwendig, die Netzwerkfreigabe in der Sonos-Bibliothek hinzuzufügen, andernfalls erstellt Jeedom die MP3-Datei der tts, kann jedoch nicht von Sonos abgespielt werden.

> **Wichtig**
>
> Die Sprache hängt von der Jeedom-Sprache ab und verwendet standardmäßig Picotts. Ab jeedom 3.3.X Es wird möglich sein, Google TTS zu verwenden, um eine schönere Stimme zu haben.


# Das Panel

Das Sonos-Plugin bietet auch ein Bedienfeld, in dem alle Ihre Sonos zusammengefasst sind. Verfügbar über das Home-Menü → Sonos Controller :

> **Wichtig**
>
> Um das Panel zu haben, müssen Sie es in der Plugin-Konfiguration aktiviert haben.

# FAQ

> **Fehler ``No devices in this collection`` bei der Suche nach Ausrüstung**
>
> Dieser Fehler tritt auf, wenn die automatische Erkennung blockiert ist (z. B. Router, der den Boradcast blockiert)). Es spielt keine Rolle, Sie müssen nur Ihre Soundsysteme von Hand hinzufügen und das Modell und die IP-Adresse angeben.

> **Ich habe Probleme mit TTS (Looping), die Funkgeräte funktionieren nicht, ich habe Fehler usw...**
>
> Das erste, was Sie im Falle eines Problems tun müssen, ist, alle Sonos abzuziehen, sie wieder anzuschließen und auf den Start zu warten. Starten Sie dann den Sonos-Daemon in Jeedom neu. Im Allgemeinen löst dies 90% der Probleme.
