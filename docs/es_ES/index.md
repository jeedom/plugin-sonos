El complemento de Sonos le permite controlar Sonos Play 1, 3, 5, Sonos Connect,
Sonos Connect AMP y Sonos Playbar. Te permitirá ver el estado
Sonos y realiza acciones sobre ellos (reproducir, pausar, siguiente,
anterior, volumen, elección de una lista de reproducción ...)

# Configuración del plugin

La configuración es muy simple, después de descargar el complemento,
simplemente lo activas y eso es todo. El complemento buscará
Sonos en tu red y crea el equipo automáticamente. De
Además, si hay una coincidencia entre los objetos y las partes de Jeedom
Sonos, Jeedom asignará automáticamente Sonos a la derecha
monedas.

> **Tip**
>
> Durante el descubrimiento inicial, se recomienda no agrupar los sistemas de sonido so pena de tener errores

Si luego agrega un Sonos, puede crear un dispositivo
Sonos dándole la IP a Jeedom o haga clic en "Buscar
Equipo de Sonos"

-   **Voix** : elección de voz durante TTS
-   **Partage** : compartir nombre y ruta de carpeta
-   **Nombre de usuario para compartir** : nombre de usuario para
    acceso compartido
-   **Compartir contraseña** : Compartir contraseña
-   **Descubrimiento** : descubre automáticamente los sistemas de sonido (no funciona
    en una instalación de tipo acoplable donde debe crear a mano
    cada sonos)
-   **Dependencia de Sonos** : instalar dependencias de sonos para TTS

> **Important**
>
> Los mensajes que son demasiado largos no se pueden transmitir en TTS (el límite
> depende del proveedor de TTS, generalmente alrededor de 100 caracteres)

# Configuración del equipo

Se puede acceder a la configuración del equipo de Sonos desde el menú
Complementos luego multimedia

Aquí encontrarás toda la configuración de tu equipo :

-   **Nombre del equipo de Sonos** : nombre de su equipo Sonos
-   **Objeto padre** : indica el objeto padre al que pertenece
    equipo
-   **Activer** : activa su equipo
-   **Visible** : lo hace visible en el tablero
-   **Modelo** : su modelo de Sonos (no cambie a menos que
    no el correcto)
-   **IP** : la IP de su Sonos, puede ser útil si su Sonos cambia
    de IP o si lo reemplaza

A continuación encontrará la lista de pedidos. :

-   **Nom** : Nombre de la orden
-   **Configuración avanzada (ruedas con muescas pequeñas)** : permet
    muestra la configuración avanzada del comando (método
    historia, widget ...)
-   **Tester** : Se usa para probar el comando

Como orden encontrarás :

-   **Reproducir lista de reproducción** : comando de tipo de mensaje para iniciar
    una lista de reproducción, solo pon el nombre en el título
    la lista de reproducción. Puede poner "al azar" en el mensaje para mezclar
    la lista de reproducción antes de leer.
-   **Jugar favoritos** :  comando de tipo de mensaje para iniciar
    favoritos, es suficiente en el título para poner el nombre de los favoritos. Vosotras
    puede poner "al azar" en el mensaje para mezclar favoritos antes de leer.
-   **Tocar una radio** : comando de tipo de mensaje para iniciar
    una radio, solo en el título pon el nombre de la radio
    (Tenga cuidado, esto debe estar en las estaciones de radio favoritas).
-   **Agregar un altavoz** : permite agregar un altavoz
    (un Sonos) al orador actual (para asociar 2 Sonos
    por ejemplo). Tienes que poner el nombre de la sala de sonos para agregar
    en el título (el campo del mensaje no se usa aquí).
-   **Retire el altavoz** : le permite eliminar un altavoz
    (un Sonos) al hablante actual (para disociar 2 Sonos
    por ejemplo). Tienes que poner el nombre de la sala Sonos para borrar
    en el título (el campo del mensaje no se usa aquí).
-   **Estado aleatorio** : indica si estamos en modo aleatorio o no
-   **Al azar** : invertir el estado del modo aleatorio
-   **Repita el estado** : indica si estamos en modo de repetición o no
-   **Repetición** : invertir el estado del modo "repetir""
-   **Image** : enlace a la imagen del álbum
-   **Album** : nombre del álbum actualmente en reproducción
-   **Artiste** : nombre del artista actualmente en reproducción
-   **Piste** : nombre de la pista que se está reproduciendo actualmente
-   **Muet** : callarse
-   **Anterior** : pista anterior
-   **Suivant** : siguiente pista
-   **Lecture** : leer
-   **Pause** : pausa
-   **Stop** : deja de leer
-   **Volume** : cambiar el volumen (de 0 a 100)
-   **Volumen de estado** : Nivel de volumen
-   **Statut** : estado (pausa, lectura, transición ...)
-   **Dire** : permite leer un texto en Sonos (ver parte de TTS).
    En el título puede establecer el volumen y en el mensaje, el
    mensaje para leer

> **Note**
>
> Para reproducir listas de reproducción, puede poner opciones (en el
> cuadro de opciones). Para iniciar la lista de reproducción en reproducción aleatoria, debe
> poner en "al azar"

# TTS

TTS (texto a voz) para Sonos requiere compartir
Windows (Samba) en la red (impuesto por Sonos, no hay forma de hacerlo
de lo contrario). Entonces necesita un NAS en la red. La configuración es
bastante simple, tiene que poner el nombre o la ip del NAS (tenga cuidado
poner lo mismo que se indica en Sonos) y el chemain
(relativo), nombre de usuario y contraseña (atención
el usuario debe tener derechos de escritura)

> **Important**
>
> Es absolutamente necesario poner una contraseña para que esto funcione

> **Important**
>
> También es absolutamente necesario un subdirectorio para que el archivo de voz
> ser creado correctamente.

**Aquí hay un ejemplo de configuración (gracias @masterfion) :.**

Lado NAS, aquí está mi configuración :

-   La carpeta Jeedom es compartida
-   El usuario de Sonos tiene acceso de lectura / escritura (requerido
    para Jeedom)
-   el usuario invitado tiene acceso de solo lectura (requerido para
    Sonos)

Lado del complemento de Sonos, aquí está mi configuración :

-   Compartir :
    -   Campo 1 : 192.168.xxx.yyy
    -   Campo 2 : Jeedom / TTS
-   Nombre de usuario : Sonos y su contraseña ...

Sonos Library Side (aplicación para PC)
-   el camino es : //192.168.xxx.yyy/Jeedom / TTS

> **Important**
>
> ABSOLUTAMENTE agregue el uso compartido de red en la biblioteca de sonidos, de lo contrario Jeedom creará el mp3 de tts pero Sonos no puede reproducirlo

> **Important**
>
> El idioma depende del idioma de Jeedom y usa picotts por defecto. A partir de la libertad 3.3.X será posible usar Google TTS para tener una voz más bonita


# El panel

El complemento de Sonos también proporciona un panel que reúne todos sus
Sonos Disponible desde el menú Inicio → Sonos Controller :

> **Important**
>
> Para tener el panel debes activarlo en la configuración del complemento

# FAQ

** Error "No hay dispositivos en esta colección" al buscar equipo **
>
> Este error se produce si el descubrimiento automático está bloqueado (por ejemplo, el enrutador que bloquea el boradcast). No importa, solo tendrá que agregar sus sonos a mano especificando el modelo y la IP.
