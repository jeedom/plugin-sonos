# Sonos Plugin

Mit dem Sonos-Plugin können Sie die Sonos Play 1, 3, 5, Sonos Connect, Sonos Connect AMP, Sonos Playbar, Ikea Symfonisk usw. steuern. Damit können Sie den Status der Sonos-Geräte einsehen und verschiedene Aktionen ausführen (Wiedergabe, Pause, nächster Titel, vorheriger Titel, Lautstärke, Auswahl einer Wiedergabeliste usw.).

# Plugin Konfiguration

Die Konfiguration ist ganz einfach: Nachdem Sie das Plugin heruntergeladen haben, müssen Sie es nur noch aktivieren, die Abhängigkeiten installieren und den Daemon starten.
Das Plugin sucht nach Sonos-Geräten in Ihrem Netzwerk und legt die Geräte automatisch an. Wenn zudem eine Übereinstimmung zwischen den Jeedom-Objekten und den Sonos-Räumen besteht, ordnet Jeedom die Sonos-Geräte automatisch den richtigen Räumen zu.

> **Wichtig**
> Ihre Sonos-Geräte müssen direkt von dem Rechner erreichbar sein, auf dem Jeedom läuft (Broadcast/Multicast im selben Netzwerk möglich), und sie müssen im Gegenzug Jeedom über den TCP-Port 1400 erreichen können.

Falls sich Ihre Jeedom-Lautsprecher nicht im selben Subnetz wie Jeedom befinden, können Sie dieses vorzugsweise im CIDR-Format konfigurieren, zum Beispiel `192.168.1.0/24`. Es sollte auch möglich sein, die IP-Adresse eines Ihrer Lautsprecher direkt einzugeben, um von diesem aus die anderen zu erkennen, es wird jedoch empfohlen, das gesamte Netzwerk zu konfigurieren. **Achtung: Nehmen Sie keine Konfigurationen vor, wenn Sie sich in diesem Bereich nicht auskennen; testen Sie zunächst die Standardkonfiguration.**

Wenn Sie später ein Sonos-Gerät hinzufügen, können Sie auf der Geräteseite auf **Synchronisieren** klicken oder den Daemon neu starten.

- **Freigabe**: Konfigurieren Sie hier den Hostnamen des Rechners (oder dessen IP-Adresse), den Namen der Freigabe (ohne Pfad, ohne „/“) und den Pfad zum Ordner.
- **Benutzername für die Freigabe**: Benutzername für den Zugriff auf die Freigabe.
- **Freigabepasswort**: Passwort für die Freigabe.

# Gerätekonfiguration

Die Konfiguration der Sonos-Geräte ist über das Menü „Plugins“ und anschließend „Multimedia“ zugänglich.

Hier finden Sie alle üblichen Einstellungsmöglichkeiten für Ihre Geräte:

- **Sonos-Name**: Name Ihres Sonos-Geräts.
- **Übergeordnetes Objekt**: Gibt das übergeordnete Objekt an, zu dem das Gerät gehört.
- **Aktivieren**: Damit können Sie Ihre Geräte aktivieren.
- **Sichtbar**: Macht es auf dem Dashboard sichtbar.

Sowie Informationen zu Ihrem Sonos-Gerät: *Modell*, *Versionen*, *Seriennummer*, *ID*, *MAC-Adresse* und *IP-Adresse*.

Sie haben außerdem die Möglichkeit, die vorkonfigurierte Geräte-Kachel zu deaktivieren (Option standardmäßig aktiviert) und in diesem Fall diese Kachel nach Ihren Wünschen zu konfigurieren, indem Sie die Widgets des Core oder Ihre eigenen Widgets verwenden und die Befehle Ihrer Wahl ein- oder ausblenden...

Die vorkonfigurierte Kachel berücksichtigt weder den Sichtbarkeitsstatus der Befehle noch die erweiterten Anzeigeoptionen; ihre Konfiguration kann nicht geändert werden.

# Die Aufträge

Die Informationsanzeigen werden nahezu in Echtzeit aktualisiert (normalerweise mit einer Verzögerung von maximal einigen Sekunden), doch die Anzeige des Covers des aktuell wiedergegebenen Albums kann bei einem Titelwechsel etwas länger dauern, bis es im Widget erscheint. Dies ist völlig normal und hat nichts mit dem Plugin zu tun: Es muss das Bild von einer externen Quelle (auf einem Sonos-Gerät oder aus dem Internet) abrufen, was manchmal mehrere Sekunden dauern kann (in der Regel maximal etwa zehn Sekunden).

## Sonos-Lautstärkeregler und -Regler

Diese Befehle steuern stets das entsprechende Gerät, auch wenn dieses einer Gruppe angehört.

- **Lautstärke**: Lautstärke ändern *(von 0 bis 100)*
- **Lautstärkestatus**: Lautstärkepegel (in %)
- **Lautstärke erhöhen**: Erhöht die Lautstärke um 1 %; kann für die Integration mit anderen Systemen oder Plugins nützlich sein
- **Lautstärke verringern**: Verringert die Lautstärke um 1 %; kann für die Integration mit anderen Systemen oder Plugins nützlich sein
- **Lautstärkenübergang** ermöglicht Lautstärkenübergänge, die direkt vom Sonos-Lautsprecher verwaltet werden. Das Plugin übernimmt diese Aufgabe nicht, sodass es zu keinen Verzögerungen kommt. Die Übergangszeiten sind jedoch nicht konfigurierbar, da sie von Sonos festgelegt werden. Die Art des Übergangs und die Ziellautstärke müssen bei der Ausführung des Befehls ausgewählt werden. Es gibt 3 Modi:
  - *LINEAR*: Linearer Übergang von der aktuellen Lautstärke zur Ziellautstärke (Ansteigen oder Abfallen), die Geschwindigkeit beträgt 1,25 pro Sekunde (ein *LINEAR*-Übergang von 50 % auf 30 % dauert 16 Sekunden)
  - *ALARM*: Setzt die Lautstärke auf 0, hält etwa 30 Sekunden lang an und erhöht sie anschließend mit einer Geschwindigkeit von 2,5 pro Sekunde auf die gewünschte Lautstärke (ein *ALARM*-Übergang von 0 % auf 10 % dauert 4 Sekunden)
  - *AUTOPLAY*: Setzt die Lautstärke auf 0 und erhöht sie schnell auf die gewünschte Lautstärke mit einer Geschwindigkeit von 50 pro Sekunde (ein *AUTOPLAY*-Übergang von 0 % auf 50 % dauert 1 s)
- **Stumm**: Schaltet den Stummschaltungsmodus ein.
- **Stummschaltung aufheben**: Deaktiviert die Stummschaltung.
- **Stummschaltungsstatus**: Zeigt an, ob sich das Gerät im Stummschaltungsmodus befindet oder nicht.
- **Balance** (Aktion/Schieberegler) und **Balance-Status**, der die Balance für kompatible Sonos-Geräte anhand eines Werts zwischen -100 (ganz links) und 100 (ganz rechts) regelt
- **Bässe** (Aktion/Schieberegler) und **Bassstatus**, der die Bässe anhand eines Werts zwischen -10 und 10 regelt
- **Höhen** (Aktion/Schieberegler) und **Höhenstatus**, der die Höhen anhand eines Werts zwischen -10 und 10 regelt
- **Loudness-Status**, **Loudness ein**, **Loudness aus** – steuert die Lautstärke

- **TV**: Um bei kompatiblen Geräten auf den Eingang *TV* umzuschalten
- **Analoger Audioeingang**: Zum Umschalten auf den *analogen Audioeingang* (*Line-in*) bei kompatiblen Geräten
- **LED ein** und **LED aus**: Schaltet die LED, die Statusanzeige, ein bzw. aus
- **Status-LED**: Zeigt an, ob die Status-LED leuchtet oder nicht. Diese Information wird nur einmal pro Minute aktualisiert, sofern sie außerhalb von Jeedom geändert wird.
- **Touch-Bedienelemente ein** und **Touch-Bedienelemente aus** Aktiviert und deaktiviert die physischen oder Touch-Bedienelemente am Sonos
- **Status der Touch-Bedienelemente** gibt an, ob die Touch-Bedienelemente aktiviert sind oder nicht
- **Mikrofonstatus**, der anzeigt, ob das Mikrofon bei Sonos-Geräten mit Mikrofon aktiviert ist oder nicht
- **Batterie** bei Sonos-Geräten mit Batterie, die den Ladezustand der Batterie in Prozent anzeigt
- **Ladevorgang** bei Sonos-Geräten mit Batterie, bei denen angezeigt wird, ob gerade geladen wird oder nicht

## Wiedergabesteuerung

Diese Befehle zeigen die aktuell auf dem Gerät oder der Gruppe (sofern diese gruppiert ist) laufende Wiedergabe an und steuern sie – und zwar auf transparente Weise. Sie müssen sich keine Gedanken darüber machen, ob das Gerät gruppiert ist oder nicht, um diese Befehle zu verwenden.

- **Status**: Status des Players, übersetzt in die unter Jeedom konfigurierte Sprache. Zum Beispiel: *Wiedergabe*, *Pause*, *Gestoppt*.
- **Wiedergabestatus**, der den „Rohwert“ des Wiedergabestatus angibt: *PLAYING*, *PAUSED_PLAYBACK*, *STOPPED*; eignet sich besser für Szenarien.
- **Wiedergabe**: In den Wiedergabemodus wechseln.
- **Pause**: Anhalten.
- **Stopp**: Wiedergabe anhalten.
- **Zurück**: Vorheriger Titel.
- **Weiter**: nächster Titel.
- **Zufallsmodus**: Zeigt an, ob sich das Gerät im Zufallsmodus befindet oder nicht.
- **Zufallsmodus**: Schaltet den Zufallsmodus ein oder aus.
- **Status wiederholen**: Gibt an, ob sich das System im Wiederholungsmodus befindet oder nicht.
- **Wiederholen**: Schaltet den Status des Modus „Wiederholen“ um.
- **Überblendstatus**, **Überblendung ein**, **Überblendung aus** zum Steuern und Aktivieren bzw. Deaktivieren der *Überblendung*
- Mit **„Wiedergabemodus auswählen“** können Sie zwischen folgenden Optionen wählen:
  - *Normal* (Wiederholung aus, Zufallswiedergabe aus),
  - *Alles wiederholen* (Zufallswiedergabe aus),
  - *Zufällig und alles wiederholen*,
  - *Zufällig ohne Wiederholung*,
  - *Titel wiederholen* (Zufallswiedergabe aus),
  - *Zufällige Wiedergabe und Titel wiederholen*.

Ich empfehle, diesen Befehl in einem Szenario anstelle von **Wiederholen** und **Zufällig** zu verwenden, um die gewünschte Konfiguration zu erreichen, auch wenn alle Befehle auf dieselben Parameter wirken. Dieser Befehl ist jedoch die einzige Möglichkeit, in den Modus *Titel wiederholen* oder *Zufällig und Titel wiederholen* zu wechseln.
- **Lesemodus**, der den aktuellen Status angibt, der einer der oben genannten Werte sein wird.
- **Playlist abspielen**: Ein Befehl vom Typ „Nachricht“, mit dem eine Playlist gestartet werden kann. Geben Sie dazu einfach den Namen der Playlist in die Betreffzeile ein. In einem Szenario wird automatisch eine Liste mit möglichen Optionen angezeigt, sobald Sie mit der Eingabe beginnen.
- **Favoriten aufrufen**:  Eine Befehlsart, mit der Sie einen Favoriten starten können. Geben Sie dazu einfach den Namen des Favoriten in die Betreffzeile ein. In einem Szenario wird automatisch eine Liste mit Möglichkeiten angezeigt, sobald Sie mit der Eingabe beginnen.
- **Radio abspielen**: Ein Befehl vom Typ „Nachricht“, mit dem Sie ein Radio starten können. Geben Sie dazu einfach den Namen des Radios in den Titel ein *(ACHTUNG: Das Radio muss zu den Favoriten gehören)*. In einem Szenario wird automatisch eine Liste mit Möglichkeiten angezeigt, sobald Sie mit der Eingabe beginnen. Funktioniert nicht mehr auf den „S2“-Modellen; es ist normal, dass bei allen Modellen, die die Sonos S2-App verwenden, eine leere Liste angezeigt wird.
- **MP3-Radio abspielen**: Ermöglicht die Wiedergabe eines MP3-Radios über eine URL (z. B. aus dem Internet). Sie müssen einen Titel in das Feld *Titel* und die URL (im Format http(s)://...mp3) in das Feld *Nachricht* eingeben.
- **Bild**: Link zum Bild im Album.
- **Album**: Name des gerade abgespielten Albums.
- **Interpret**: Name des gerade abgespielten Interpreten.
- **Titel**: Name des gerade abgespielten Titels.
- **Dire**: Ermöglicht das Vorlesen eines Textes über Sonos (siehe Abschnitt „TTS“). Im Titel können Sie die Lautstärke festlegen und in der Nachricht den vorzulesenden Text eingeben.

> **Hinweis**
> Playlists und Favoriten müssen über die Sonos-App (auf dem Handy oder am Computer) erstellt werden. Anschließend muss eine Synchronisierung durchgeführt werden, um die Geräte zu aktualisieren und sie in einem Szenario nutzen zu können.

## Befehle zum Verwalten von Gruppen

Diese Befehle wirken sich immer auf das entsprechende Gerät aus.

- **Gruppenstatus**: Gibt an, ob das Gerät einer Gruppe zugeordnet ist oder nicht.
- **Gruppenname**: Wenn das Gerät einer Gruppe zugeordnet ist, wird hier der Name der Gruppe angegeben.
- **Einer Gruppe beitreten**: Ermöglicht es, der Gruppe des angegebenen Lautsprechers (eines Sonos-Geräts) beizutreten (um beispielsweise zwei Sonos-Geräte miteinander zu verbinden). Geben Sie den Raumnamen des Sonos-Geräts ein, dem Sie beitreten möchten. Dies kann jedes beliebige Mitglied einer bestehenden Gruppe sein; es muss nicht unbedingt der Gruppenkoordinator oder ein einzelnes Sonos-Gerät sein. In einem Szenario wird automatisch eine Liste mit Möglichkeiten angezeigt, sobald Sie mit der Eingabe beginnen.
- **Gruppe verlassen**: Hiermit können Sie die Gruppe verlassen.
- Mit dem **Party-Modus** lassen sich alle Sonos-Geräte zu einer Gruppe zusammenfassen

# TTS

Für die TTS-Funktion (Text-to-Speech) auf Sonos ist eine SAMBA-Freigabe im Netzwerk erforderlich (von Sonos vorgeschrieben, es gibt keine Alternative). Sie benötigen daher ein NAS oder ein ähnliches Gerät im Netzwerk. Die Konfiguration ist recht einfach: Sie müssen den Namen oder die IP-Adresse des NAS eingeben (achten Sie darauf, genau das anzugeben, was bei Sonos hinterlegt ist) sowie den Pfad zu dem Ordner, der die Audiodateien enthalten soll, und den Benutzernamen sowie das Passwort (Achtung: Der Benutzer muss über Schreibrechte verfügen).

Die Erstellung der Audiodatei wird vom Jeedom-Core verwaltet: Als Sprache wird die in Jeedom konfigurierte Sprache verwendet, und die verwendete TTS-Engine kann ebenfalls in der Jeedom-Konfiguration ausgewählt werden.

Bei Verwendung von TTS (Befehl **Dire**) führt das Plugin folgende Aktionen aus:

- Generierung der Audiodatei, die die Nachricht enthält, mit Jeedom-Kernunterstützung
- Schreiben der Datei auf die SAMBA-Freigabe
- erzwingt die Wiedergabe im „Normal“-Modus ohne Wiederholung
- Modus „Nicht stumm“ erzwingen (nur für das Gerät, nicht für die gesamte Gruppe)
- Anpassung der Lautstärke auf den bei Verwendung des Befehls gewählten Wert (nur für das jeweilige Gerät, nicht für die gesamte Gruppe)
- Nachricht lesen
- Wiederherstellen des Zustands des Sonos vor der Wiedergabe (d. h. des Wiedergabemodus, stumm oder nicht, wiederholen oder nicht usw.) und Neustarten des Streams, wenn der Sonos gerade abgespielt hat

> **WICHTIG**
>
> Damit dieser Vorgang funktioniert, muss unbedingt ein Passwort festgelegt werden.
>
> Außerdem ist unbedingt ein Unterverzeichnis erforderlich, damit die Sprachdatei korrekt erstellt wird.
>
> Der Name der Freigabe oder des Ordners darf auf keinen Fall Akzente, Leerzeichen oder Sonderzeichen enthalten.
>
> Zu lange Nachrichten können nicht per TTS übertragen werden (die Obergrenze hängt vom TTS-Anbieter ab, in der Regel liegen sie bei etwa 100 Zeichen).

## Konfigurationsbeispiel

Was das NAS betrifft, muss folgende Konfiguration vorgenommen werden:

- Der Ordner *Jeedom* ist freigegeben und enthält einen Ordner *TTS*
- Der Benutzer *jeedom* verfügt über Lese-/Schreibzugriff (erforderlich für Jeedom).
- Der Benutzer *sonos* hat Lesezugriff (erforderlich für Sonos).

Was das Sonos-Plugin betrifft, so sieht die Konfiguration wie folgt aus:

- Teilen:
  - Feld 1: 192.168.xxx.yyy
  - Feld 2: *Jeedom*
  - Feld 3: *TTS*
- Benutzername (*jeedom* im Beispiel) und Passwort…​

Sonos-Bibliothek (PC-App)

- Der Pfad lautet: //192.168.xxx.yyy/Jeedom/TTS
- Der Benutzer ist *sonos* (in diesem Beispiel) + Passwort

# Das Panel

Das Sonos-Plugin stellt außerdem ein Bedienfeld zur Verfügung, in dem alle Ihre Sonos-Geräte zusammengefasst sind. Erreichbar über das Menü „Startseite“ → „Sonos Controller“:

> **WICHTIG**
>
> Um das Panel nutzen zu können, muss es in den Plugin-Einstellungen aktiviert werden.
