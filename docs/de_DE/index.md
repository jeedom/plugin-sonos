# Sonos Plugin

Mit dem Sonos-Plug-in können Sie Sonos Play 1, 3, 5, Sonos Connect, Sonos Connect AMP, Sonos Playbar, Ikea Symfonisk... steuern. Es ermöglicht Ihnen, den Status von Sonos anzuzeigen und Aktionen auszuführen (Wiedergabe, Pause , nächstes, vorheriges, Lautstärke, Auswahl einer Playlist…).

# Plugin Konfiguration

Die Konfiguration ist sehr einfach, nach dem Herunterladen des Plugins müssen Sie es nur noch aktivieren, die Abhängigkeiten installieren und den Daemon starten.
Das Plugin sucht in Ihrem Netzwerk nach Sonos und erstellt die Geräte automatisch. Wenn zwischen Jeedom-Objekten und Sonos-Räumen eine Übereinstimmung besteht, weist Jeedom Sonos automatisch den richtigen Räumen zu.

> **Wichtig**
> Ihre Sonos-Geräte müssen direkt von der Maschine, auf der Jeedom gehostet wird, erreichbar sein (Broadcast/Multicast im selben Netzwerk möglich) und sie müssen Jeedom im Gegenzug über den TCP-Port 1400 erreichen können.

. . ****

Wenn Sie später einen Sonos hinzufügen, können Sie auf klicken **Synchronisieren** auf der Geräteseite oder starten Sie den Daemon neu.

- **Teilen**: Konfigurieren Sie hier den Hostnamen der Maschine (bzw. deren IP), den Namen der Freigabe (ohne Pfad, ohne „/“) und den Pfad zum Ordner.
- **Teilen Sie den Benutzernamen**: Benutzername für den Zugriff auf die Freigabe.
- **Passwort teilen**: Passwort teilen.

# Gerätekonfiguration

Auf die Konfiguration der Sonos-Geräte kann über das Plugins-Menü und dann über Multimedia zugegriffen werden.

Hier finden Sie die gesamte übliche Konfiguration Ihrer Ausrüstung :

- **Sonos Name**: Name Ihres Sonos-Geräts.
- **Übergeordnetes Objekt**: Gibt das übergeordnete Objekt an, zu dem das Gerät gehört.
- **Aktivieren**: macht Ihre Ausrüstung aktiv.
- **Sichtbar**: macht es auf dem Dashboard sichtbar.

Sowie Informationen zu Ihrem Sonos: *Modell*, *Veröffentlichungen*, *Seriennummer*, *Kennung*, *MAC-Adresse* Und *IP Adresse*.

Vous avez aussi la possibilité de désastiver la tuile de l'équipement pré-configurée (option active par défaut) et dans ce cas configurer cette tuile comme vous le souhaitez en utilisant les widgets du core ou vos propres widgets, d'afficher ou masquer les commandes de votre choix...

La tuile pré-configurée ne tient pas compte du statut visible ou non des commandes, ni des options avancées d'affichage; La configuration de celle-ci ne peut pas être modifiée.

# Die Aufträge

Die Info-Steuerelemente werden nahezu in Echtzeit aktualisiert (normalerweise maximal ein paar Sekunden), aber bei einem Titelwechsel kann es etwas länger dauern, bis das Bild des gerade abgespielten Albums im Widget angezeigt wird. Dies ist völlig normal und unabhängig vom Plugin: Er muss das Bild von einer externen Quelle (auf einem Sonos oder im Internet) abrufen, was manchmal mehrere Sekunden dauert (im Prinzip maximal etwa zehn Sekunden)).

## Sonos-Lautstärkeregler und -Regler

Diese Befehle steuern immer das entsprechende Gerät, auch wenn es sich in einer Gruppe befindet.

- **Lautstärke**: Ändern Sie die Lautstärke *(von 0 bis 100)*
- **Statusvolumen**: Lautstärkepegel (in %)
- **Drehe die Lautstärke hoch**: erhöht die Lautstärke um 1 %; kann für die Integration mit anderen Systemen oder Plugins nützlich sein
- **Verringern Sie die Lautstärke**: verringert die Lautstärke um 1 %; kann für die Integration mit anderen Systemen oder Plugins nützlich sein
- **Lautstärkeübergang** Ermöglicht die Durchführung von Lautstärkeübergängen, die direkt vom Sonos-Lautsprecher verwaltet werden. Dies ist nicht das Plug-in, das sich darum kümmert und daher nicht blockiert, aber die Verzögerungen sind nicht konfigurierbar, da sie von Sonos definiert wurden. Beim Ausführen des Befehls müssen der Übergangstyp und das Zielvolume ausgewählt werden. Es gibt 3 Modi:
  - *LINEAR*: Linearer Übergang vom aktuellen Volumen zum Zielvolumen (Erhöhung oder Verringerung), Geschwindigkeit ist 1.25 pro Sekunde (ein Übergang *LINEAR* Von 50 % auf 30 % dauert es 16 Sekunden)
  - *ALARM*: initialisiert die Lautstärke auf 0, pausiert etwa 30 Sekunden und erhöht dann mit der Geschwindigkeit 2 auf die gewünschte Lautstärke.5 pro Sekunde (ein Übergang *ALARM* Von 0 % auf 10 % dauert es 4 Sekunden)
  - *AUTOMATISCHES ABSPIELEN*: Initialisiert die Lautstärke auf 0 und erhöht sich schnell mit einer Rate von 50 pro Sekunde auf die angeforderte Lautstärke (ein Übergang) *AUTOMATISCHES ABSPIELEN* Von 0 % auf 50 % dauert es 1 Sekunde)
- **Stumm**: Stummschaltung aktivieren.
- **Keine Stummschaltung**: Schalten Sie die Stummschaltung aus.
- **Stummschaltungsstatus**: zeigt an, ob wir uns im Stummmodus befinden oder nicht.
- **Gleichgewicht** (Aktion/Cursor) und **Kontostand** die die Balance nach einem Wert zwischen -100 (ganz links) und 100 (ganz rechts) für kompatible Sonos verwaltet
- **Gräber** (Aktion/Cursor) und **Ernsthafter Status** die den Bass nach einem Wert zwischen -10 und 10 verwaltet
- **Verdreifachen** (Aktion/Cursor) und **Dreifacher Status** der die Höhen nach einem Wert zwischen -10 und 10 verwaltet
- **Lautstärkestatus**, **Lautstärke an**, **Lautstärke aus** steuert die Lautstärke

- **Fernseher**: um zum Eingang zu wechseln *Fernseher* auf kompatiblen Geräten
- **Analoger Audioeingang**: zu wechseln'*Analoger Audioeingang* (*Line-in*) auf kompatiblen Geräten
- **Das Geschenk** Und **LED aus**: Aktiviert und deaktiviert die LED, das Statuslicht
- **Status-LED**: Zeigt an, ob die Statusleuchte leuchtet oder nicht. Diese Informationen werden nur einmal pro Minute aktualisiert, falls sie außerhalb von Jeedom geändert werden
- **Touch-Bedienelemente ein** Und **Touch-Steuerung ausgeschaltet** Aktiviert und deaktiviert physische oder Touch-Tasten auf Sonos
- **Status-Touch-Steuerelemente** Zeigt an, ob die Touch-Steuerung aktiviert ist oder nicht
- **Mikrofonstatus** Dies zeigt an, ob das Mikrofon bei Sonos, die mit einem Mikrofon ausgestattet sind, aktiviert ist oder nicht
- **Batterie** Bei Sonos, die mit einem Akku ausgestattet sind, wird der Akkuladestand in Prozent angezeigt
- **Laden** Bei Sonos, die mit einem Akku ausgestattet sind, wird angezeigt, ob der Ladevorgang läuft oder nicht

## Wiedergabesteuerung

Diese Befehle zeigen und steuern die aktuelle Wiedergabe auf dem Gerät oder in der Gruppe, wenn diese gruppiert ist, und zwar auf transparente Weise. Sie müssen sich keine Gedanken darüber machen, ob das Gerät gruppiert ist oder ob Sie sie nicht verwenden sollen.

- **Status**: Leserstatus in die unter Jeedom konfigurierte Sprache übersetzt. Zum Beispiel: *Lesen*, *Pause*, *Gestoppt*.
- **Lesestatus** Dies gibt den „Rohwert“ des Lesestatus an: *SPIELEN*, *PAUSED_PLAYBACK*, *GESTOPPT*; besser geeignet für Szenarien.
- **Lesen**: lesen.
- **Pause**: Pause.
- **STOP**: Hör auf zu lesen.
- **Früher**: vorheriger Titel.
- **Folgende**: nächster Track.
- **Zufälliger Status**: zeigt an, ob wir uns im Zufallsmodus befinden oder nicht.
- **Zufällig**: Kehren Sie den Status des Zufallsmodus um.
- **Status wiederholen**: zeigt an, ob wir uns im Wiederholungsmodus befinden oder nicht.
- **Wiederholung**: Kehren Sie den Status des "Wiederholungs" -Modus um".
- **Fade-Status**, **Einblenden**, **Ausblenden** zu steuern und zu aktivieren oder nicht *Überblendung*
- **Wählen Sie den Lesemodus** ermöglicht Ihnen die Auswahl aus den folgenden Möglichkeiten:
  - *Normal* (Wiederholung aus, Zufall aus),
  - *Wiederhole alles* (zufällig aus),
  - *Zufällig und alles wiederholen*,
  - *Zufällig ohne Wiederholung*,
  - *Lied wiederholen* (zufällig aus),
  - *Zufälliges und wiederholtes Lied*.

  Ich empfehle, diesen Befehl stattdessen in einem Szenario zu verwenden **Wiederholung** & **Zufällig** um zur gewünschten Konfiguration zu gelangen, auch wenn alle auf die gleichen Parameter einwirken. Dieser Befehl ist jedoch die einzige Möglichkeit, in den Modus zu wechseln *Lied wiederholen* Oder *Zufälliges und wiederholtes Lied*.
- **Lesemodus** Angabe des aktuellen Zustands, der einer der oben genannten Werte sein wird.
- **Playlist abspielen**: Befehl zum Nachrichtentyp, um eine Wiedergabeliste zu starten. Geben Sie einfach den Namen der Wiedergabeliste in den Titel ein. In einem Szenario wird automatisch eine Liste mit Möglichkeiten angezeigt, wenn Sie mit der Eingabe beginnen.
- **Favoriten spielen**:  Befehl zum Nachrichtentyp, um einen Favoriten zu starten. Im Titel müssen Sie lediglich den Namen des Favoriten eingeben. In einem Szenario wird automatisch eine Liste mit Möglichkeiten angezeigt, wenn Sie mit der Eingabe beginnen.
- **Spielen Sie ein Radio**: Wenn Sie einen Befehl zum Starten eines Radiosenders eingeben, müssen Sie lediglich den Namen des Radios in den Titel einfügen *(Achtung : Dies muss in den Lieblingsradios sein)*. In einem Szenario wird automatisch eine Liste mit Möglichkeiten angezeigt, wenn Sie mit der Eingabe beginnen. Funktioniert nicht mehr bei „S2“-Modellen, es ist normal, dass bei allen Modellen, die die Sonos S2-App verwenden, eine leere Liste vorhanden ist.
- **Spielen Sie MP3-Radio**: ermöglicht das Abspielen eines MP3-Radios über eine URL (z. B. aus dem Internet)). Sie müssen einen Titel in das Feld eingeben *Titel* und die URL (http(s)-Format))://...mp3) in der Gegend *Nachricht*.
- **Bild**: Link zum Albumbild.
- **Album**: Name des aktuell wiedergegebenen Albums.
- **Künstler**: Künstlername spielt gerade.
- **Verfolgen**: Name des aktuell wiedergegebenen Titels.
- **Zu sagen**: ermöglicht das Lesen eines Textes auf Sonos (siehe TTS-Teil). Im Titel können Sie die Lautstärke und in der Nachricht die zu lesende Nachricht einstellen.

> **Hinweis**
> Wiedergabelisten und Favoriten müssen über die Sonos-App (auf dem Handy oder Computer) erstellt werden. Anschließend muss eine Synchronisierung durchgeführt werden, um die Geräte zu aktualisieren und in einem Szenario verwenden zu können.

## Befehle zum Verwalten von Gruppen

Diese Befehle wirken sich immer auf das entsprechende Gerät aus.

- **Gruppenstatus**: Gibt an, ob das Gerät gruppiert ist oder nicht.
- **Name der Gruppe** Wenn das Gerät gruppiert ist, geben Sie den Namen der Gruppe an.
- **Einer Gruppe beitreten**: ermöglicht es Ihnen, der Gruppe des angegebenen Lautsprechers (einem Sonos) beizutreten (um beispielsweise zwei Sonos zuzuordnen)). Sie müssen den Namen des Soundsystem-Raums eingeben, dem Sie beitreten möchten. Dies kann ein beliebiges Mitglied einer bestehenden Gruppe sein, es muss nicht der Gruppenkoordinator oder ein isolierter Sonos sein. In einem Szenario wird automatisch eine Liste mit Möglichkeiten angezeigt, wenn Sie mit der Eingabe beginnen.
- **Die Gruppe verlassen**: ermöglicht es Ihnen, die Gruppe zu verlassen.
- **Partymode** ermöglicht es Ihnen, alle Sonos zusammenzufassen

# TTS

TTS (Text-to-Speech) zu Sonos erfordert die SAMBA-Freigabe im Netzwerk (wird von Sonos vorgeschrieben, es gibt keine andere Möglichkeit)). Sie benötigen daher ein NAS oder ein gleichwertiges Gerät im Netzwerk. Die Konfiguration ist ganz einfach: Sie müssen den Namen oder die IP-Adresse des NAS eingeben (achten Sie darauf, dasselbe wie bei Sonos anzugeben) und den Pfad zu dem Ordner, der die Audiodateien enthalten muss, sowie den Namen des Benutzers und Passwort (beachten Sie, dass der Benutzer über Schreibrechte verfügen muss)

Die Erstellung der Audiodatei wird vom Jeedom-Kern verwaltet: Die Sprache ist die in Jeedom konfigurierte und die verwendete TTS-Engine kann auch in der Jeedom-Konfiguration ausgewählt werden.

Bei Verwendung von TTS (Befehl **Zu sagen**), Das Plugin führt die folgenden Aktionen aus:

- Generierung der Audiodatei, die die Nachricht enthält, mit Jeedom-Kernunterstützung
- Schreiben der Datei auf die SAMBA-Freigabe
- erzwingt die Wiedergabe im „Normal“-Modus ohne Wiederholung
- „Stummschaltung aufheben“-Modus erzwingen (nur für das Gerät, nicht für die gesamte Gruppe))
- Ändern der Lautstärke auf den gewählten Wert bei Verwendung des Befehls (nur für das Gerät, nicht für die gesamte Gruppe))
- Nachricht lesen
- Wiederherstellen des Zustands des Sonos vor der Wiedergabe (d. h. des Wiedergabemodus, stumm oder nicht, wiederholen oder nicht usw.) und Neustarten des Streams, wenn der Sonos gerade abgespielt hat

> **Wichtig**
>
> Es ist unbedingt erforderlich, ein Passwort einzugeben, damit dieses Verfahren funktioniert.
>
> Ein Unterverzeichnis ist auch unbedingt erforderlich, damit die Sprachdatei korrekt erstellt wird.
>
> Vor allem dürfen im Namen der Freigabe oder des Ordners keine Akzente, Leerzeichen oder Sonderzeichen enthalten sein.
>
> Zu lange Nachrichten können im TTS nicht übertragen werden (Grenze abhängig vom TTS-Anbieter, in der Regel ca. 100 Zeichen)).

## Konfigurationsbeispiel

Auf der NAS-Seite muss die folgende Konfiguration durchgeführt werden:

- der Ordner *Jeedom* ist freigegeben und enthält einen Ordner *TTS*
- der Benutzer *Jeedom* hat Lese-/Schreibzugriff (notwendig für Jeedom)).
- der Benutzer *sein Knochen* hat nur Lesezugriff (notwendig für Sonos)).

Auf der Sonos-Plugin-Seite ist die config :

- Teilen :
  - Feld 1: 192.168.xxx.yyy
  - Feld 2: *Jeedom*
  - Feld 3: *TTS*
- Nutzername (*Jeedom* im Beispiel) und sein Passwort…​

Sonos Library Seite (PC App)

- der Weg ist : //192.168.xxx.yyy/Jeedom/TTS
- der Benutzer wird sein *sein Knochen* (in diesem Beispiel) + Passwort

# Das Panel

Das Sonos-Plugin bietet auch ein Bedienfeld, in dem alle Ihre Sonos zusammengefasst sind. Verfügbar über das Home-Menü → Sonos Controller :

> **Wichtig**
>
> Um das Panel zu haben, müssen Sie es in der Plugin-Konfiguration aktiviert haben.
