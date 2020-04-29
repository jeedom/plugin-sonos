,
. 
,


# Plugin Konfiguration

Die Konfiguration ist sehr einfach, nach dem Herunterladen des Plugins ist es
Sie aktivieren es einfach und das wars. 



.

> **Tip**
>
> 



"

-   **Voix** : 
-   **Partage** : 
-   **Benutzername für die Freigabe** : 
    
-   **Passwort teilen** : Passwort teilen
-   **Entdeckung** : 
    
    
-   **** : 

> **Important**
>
> 
> 

# Gerätekonfiguration




Hier finden Sie die gesamte Konfiguration Ihrer Geräte :

-   **** : 
-   **Übergeordnetes Objekt** : gibt das übergeordnete Objekt an, zu dem es gehört
    Ausrüstung
-   **Activer** : macht Ihre Ausrüstung aktiv
-   **Visible** : macht es auf dem Dashboard sichtbar
-   **Modell** : 
    
-   **IP** : 
    

Nachfolgend finden Sie die Liste der Bestellungen :

-   **Nom** : Name der Bestellung
-   **Erweiterte Konfiguration (kleine gekerbte Räder)** : erlaubt
    
    Geschichte, Widget…)
-   **Tester** : Wird zum Testen des Befehls verwendet

 :

-   **Playlist abspielen** : 
    
    . 
    .
-   **Favoriten spielen** :  
    
    .
-   **Spielen Sie ein Radio** : 
    
    .
-   **** : 
    
    zum Beispiel). 
    .
-   **** : 
    
    zum Beispiel). 
    .
-   **** : 
-   **Zufällig** : 
-   **** : 
-   **Wiederholung** : "
-   **Image** : 
-   **Album** : 
-   **Artiste** : 
-   **Piste** : 
-   **Muet** : 
-   **Früher** : 
-   **Suivant** : 
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

# Faq

** Fehler "Keine Geräte in dieser Sammlung" bei der Suche nach Geräten **
>
> Dieser Fehler tritt auf, wenn die automatische Erkennung blockiert ist (Router, der beispielsweise den Boradcast blockiert).. Es spielt keine Rolle, dass Sie Ihre Sonos nur von Hand hinzufügen müssen, indem Sie das Modell und die IP angeben.
