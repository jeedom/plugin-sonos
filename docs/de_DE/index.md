Mit dem Sonos-Plugin können Sie Sonos Play 1, 3, 5 und Sonos Connect steuern,
Sonos Connect AMP und Sonos Playbar. Hier können Sie den Status anzeigen
Sonos und führen Sie Aktionen auf ihnen aus (spielen, pausieren, weiter,
Zurück, Lautstärke, Auswahl einer Wiedergabeliste…)

# Plugin Konfiguration

Die Konfiguration ist sehr einfach, nach dem Herunterladen des Plugins ist es
Sie aktivieren es einfach und das wars. Das Plugin sucht nach
Sonos in Ihrem Netzwerk und erstellen Sie die Geräte automatisch. Von
Plus, wenn es eine Übereinstimmung zwischen Jeedom-Objekten und Teilen gibt
Sonos, Jeedom weist Sonos automatisch rechts zu
Stücke.

> **Tip**
>
> Während der ersten Entdeckung wird dringend empfohlen, Soundsysteme nicht zu gruppieren, wenn Fehler auftreten

Wenn Sie später einen Sonos hinzufügen, können Sie entweder ein Gerät erstellen
Sonos, indem Sie Jeedom die IP geben oder auf "Suchen nach" klicken
Sonos-Ausrüstung"

-   **Voix** : Wahl der Stimme während der TTS
-   **Partage** : Freigabename und Ordnerpfad
-   **Benutzername für die Freigabe** : Benutzername für
    Zugriffsfreigabe
-   **Passwort teilen** : Passwort teilen
-   **Entdeckung** : erkennt automatisch die Soundsysteme (funktioniert nicht
    bei einer Docker-Installation, bei der Sie von Hand erstellen müssen
    jeder sonos)
-   **Sonos Nebengebäude** : Installieren Sie Sonos-Abhängigkeiten für TTS

> **Important**
>
> Zu lange Nachrichten können nicht in TTS (dem Limit) übertragen werden
> hängt vom TTS-Anbieter ab, normalerweise ca. 100 Zeichen)

# Gerätekonfiguration

Die Konfiguration der Sonos-Geräte ist über das Menü zugänglich
Plugins dann Multimedia

Hier finden Sie die gesamte Konfiguration Ihrer Geräte :

-   **Name der Sonos-Ausrüstung** : Name Ihres Sonos-Geräts
-   **Übergeordnetes Objekt** : gibt das übergeordnete Objekt an, zu dem es gehört
    Ausrüstung
-   **Activer** : macht Ihre Ausrüstung aktiv
-   **Visible** : macht es auf dem Dashboard sichtbar
-   **Modell** : Ihr Sonos-Modell (ändern Sie es nur, wenn
    nicht der richtige)
-   **IP** : Die IP Ihres Sonos kann nützlich sein, wenn sich Ihr Sonos ändert
    von IP oder wenn Sie es ersetzen

Nachfolgend finden Sie die Liste der Bestellungen :

-   **Nom** : Name der Bestellung
-   **Erweiterte Konfiguration (kleine gekerbte Räder)** : permet
    Zeigen Sie die erweiterte Konfiguration des Befehls (Methode) an
    Geschichte, Widget…)
-   **Tester** : Wird zum Testen des Befehls verwendet

Als Bestellung finden Sie :

-   **Playlist abspielen** : Nachrichtentyp Befehl zum Starten
    Eine Wiedergabeliste, geben Sie einfach den Namen in den Titel ein
    die Wiedergabeliste. Sie können "zufällig" in die Nachricht einfügen, um sie zu mischen
    die Wiedergabeliste vor dem Lesen.
-   **Favoriten spielen** :  Nachrichtentyp Befehl zum Starten
    Als Favorit reicht es aus, im Titel den Namen der Favoriten anzugeben. Sie
    kann "zufällig" in die Nachricht einfügen, um Favoriten vor dem Lesen zu mischen.
-   **Spielen Sie ein Radio** : Nachrichtentyp Befehl zum Starten
    ein Radio, nur im Titel den Namen des Radios setzen
    (Seien Sie vorsichtig, dies muss in den Lieblingsradiosendern sein).
-   **Hinzufügen eines Lautsprechers** : ermöglicht das Hinzufügen eines Lautsprechers
    (ein Sonos) an den aktuellen Sprecher (um 2 Sonos zuzuordnen
    zum Beispiel). Sie müssen den Namen des Sonos-Raums eingeben, um ihn hinzuzufügen
    im Titel (das Nachrichtenfeld wird hier nicht verwendet).
-   **Lautsprecher entfernen** : Mit dieser Option können Sie einen Lautsprecher löschen
    (ein Sonos) an den aktuellen Sprecher (um 2 Sonos zu trennen
    zum Beispiel). Sie müssen den Namen des zu löschenden Sonos-Raums eingeben
    im Titel (das Nachrichtenfeld wird hier nicht verwendet).
-   **Zufälliger Status** : zeigt an, ob wir uns im Zufallsmodus befinden oder nicht
-   **Zufällig** : Kehren Sie den Status des Zufallsmodus um
-   **Status wiederholen** : zeigt an, ob wir uns im Wiederholungsmodus befinden oder nicht
-   **Wiederholung** : Kehren Sie den Status des "Wiederholungs" -Modus um"
-   **Image** : Link zum Albumbild
-   **Album** : Name des aktuell wiedergegebenen Albums
-   **Artiste** : Künstlername spielt gerade
-   **Piste** : Name des aktuell wiedergegebenen Titels
-   **Muet** : Geh stumm
-   **Früher** : vorheriger Titel
-   **Suivant** : nächster Track
-   **Lecture** : lesen
-   **Pause** : Pause
-   **Stop** : Hör auf zu lesen
-   **Volume** : Lautstärke ändern (von 0 auf 100)
-   **Statusvolumen** : Lautstärke
-   **Statut** : Status (Pause, Lesen, Übergang…)
-   **Dire** : ermöglicht das Lesen eines Textes auf Sonos (siehe TTS-Teil).
    Im Titel können Sie die Lautstärke einstellen und in der Nachricht die
    Nachricht zu lesen

> **Note**
>
> Um Wiedergabelisten abzuspielen, können Sie Optionen (in die
> Optionsfeld). Um die Wiedergabeliste in zufälliger Wiedergabe zu starten, müssen Sie
> in "zufällig setzen"

# TTS

TTS (Text-to-Speech) für Sonos erfordert das Teilen
Windows (Samba) im Netzwerk (von Sonos auferlegt, keine Möglichkeit dazu
sonst). Sie benötigen also einen NAS im Netzwerk. Die Konfiguration ist
ziemlich einfach muss man den Namen oder die IP des NAS eingeben (sei vorsichtig
Setzen Sie das gleiche wie auf Sonos angegeben) und die Chemain
(relativ), Benutzername und Passwort (Aufmerksamkeit
der Benutzer muss Schreibrechte haben)

> **Important**
>
> Es ist unbedingt erforderlich, ein Passwort einzugeben, damit dies funktioniert

> **Important**
>
> Es ist auch unbedingt ein Unterverzeichnis erforderlich, damit die Sprachdatei
> korrekt erstellt werden.

**Hier ist ein Beispiel für die Konfiguration (danke @masterfion) :.**

NAS-Seite, hier ist meine Konfiguration :

-   Jeedom-Ordner wird freigegeben
-   Der Sonos-Benutzer hat Lese- / Schreibzugriff (erforderlich)
    für Jeedom)
-   Der Gastbenutzer hat nur Lesezugriff (erforderlich für
    Sonos)

Sonos Plugin Seite, hier ist meine Konfiguration :

-   Teilen :
    -   Feld 1 : 192.168.xxx.yyy
    -   Feld 2 : Jeedom / TTS
-   Benutzername : Sonos und sein Passwort…

Sonos Library Seite (PC App)
-   der Weg ist : //192.168.xxx.yyy/Jeedom / TTS

> **Important**
>
> ABSOLUT Netzwerkfreigabe in der Soundbibliothek hinzufügen, andernfalls erstellt Jeedom die MP3-Datei für die tts, kann jedoch nicht vom Sonos abgespielt werden

> **Important**
>
> Die Sprache hängt von der Jeedom-Sprache ab und verwendet standardmäßig Picotts. Ab jeedom 3.3.X Es wird möglich sein, Google TTS zu verwenden, um eine schönere Stimme zu haben


# Das Panel

Das Sonos-Plugin bietet auch ein Panel, das alle Ihre Funktionen zusammenführt
Sonos. Verfügbar über das Home-Menü → Sonos Controller :

> **Important**
>
> Um das Panel zu haben, müssen Sie es in der Plugin-Konfiguration aktivieren

# FAQ

** Fehler "Keine Geräte in dieser Sammlung" bei der Suche nach Geräten **
>
> Dieser Fehler tritt auf, wenn die automatische Erkennung blockiert ist (Router, der beispielsweise den Boradcast blockiert).. Es spielt keine Rolle, dass Sie Ihre Sonos nur von Hand hinzufügen müssen, indem Sie das Modell und die IP angeben.
