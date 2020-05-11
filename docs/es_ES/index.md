# Complemento Sonos

El complemento de Sonos le permite controlar Sonos Play 1, 3, 5, Sonos Connect, Sonos Connect AMP, Sonos Playbar, Ikea Symfonisk ... Le permitirá ver el estado de Sonos y realizar acciones en él. (reproducir, pausar, siguiente, anterior, volumen, elección de una lista de reproducción ...)

# Configuración del plugin

La configuración es muy simple, después de descargar el complemento, solo necesita activarlo y listo. El complemento buscará Sonos en su red y creará el equipo automáticamente. Además, si hay una coincidencia entre los objetos Jeedom y las habitaciones de Sonos, Jeedom asignará automáticamente Sonos a las habitaciones correctas..

> **Punta**
>
> Durante el descubrimiento inicial, se recomienda no agrupar los sistemas de sonido so pena de tener errores

Si luego agrega un Sonos, puede crear un dispositivo Sonos dándole a Jeedom la IP o hacer clic en "Buscar dispositivos Sonos"

-   **Voz** : elección de voz durante TTS
-   **Compartir** : compartir nombre y ruta de carpeta
-   **Nombre de usuario para compartir** : nombre de usuario para acceder a compartir
-   **Compartir contraseña** : Compartir contraseña
-   **Descubrimiento** : descubra automáticamente los sistemas de sonido (no funciona en una instalación de tipo acoplable donde cada sistema de sonido debe crearse a mano)
-   **Dependencia de Sonos** : instalar dependencias de sonos para TTS

> **Importante**
>
> Los mensajes que son demasiado largos no se pueden transmitir en TTS (el límite
> depende del proveedor de TTS, generalmente alrededor de 100 caracteres)

# Configuración del equipo

Se puede acceder a la configuración del equipo Sonos desde el menú Complementos y luego multimedia

Aquí encontrarás toda la configuración de tu equipo :

-   **Nombre del equipo de Sonos** : nombre de su equipo Sonos
-   **Objeto padre** : indica el objeto padre al que pertenece el equipo
-   **Activar** : activa su equipo
-   **Visible** : lo hace visible en el tablero
-   **Modelo** : El modelo de su Sonos (solo cambie si no es el correcto)
-   **IP** : la IP de su Sonos, puede ser útil si su Sonos cambia de IP o si la reemplaza

A continuación encontrará la lista de pedidos. :

-   **Nombre** : Nombre de la orden
-   **Configuración avanzada (ruedas con muescas pequeñas)** : muestra la configuración avanzada del comando (método de registro, widget, etc.)
-   **Probar** : Se usa para probar el comando

Como orden encontrarás :

-   **Reproducir lista de reproducción** : comando de tipo de mensaje para iniciar una lista de reproducción, solo ponga el nombre de la lista de reproducción en el título. Puede poner "al azar" en el mensaje para mezclar la lista de reproducción antes de jugar.
-   **Jugar favoritos** :  comando del tipo de mensaje que permite iniciar un favorito, basta con que el título ponga el nombre de los favoritos. Puede poner "al azar" en el mensaje para mezclar favoritos antes de leer.
-   **Tocar una radio** : comando de tipo de mensaje para iniciar una radio, solo en el título ponga el nombre de la radio (TENGA CUIDADO esto debe estar en las radios favoritas).
-   **Agregar un altavoz** : permite agregar un altavoz (un Sonos) al altavoz actual (para asociar 2 Sonos, por ejemplo). Debe agregar el nombre de la sala de sonos para agregar en el título (el campo del mensaje no se usa aquí).
-   **Retire el altavoz** : le permite eliminar un altavoz (un Sonos) del altavoz actual (para disociar 2 Sonos, por ejemplo). Debe poner el nombre de la parte de Sonos para eliminar en el título (el campo del mensaje no se usa aquí).
-   **Estado aleatorio** : indica si estamos en modo aleatorio o no
-   **Al azar** : invertir el estado del modo aleatorio
-   **Repita el estado** : indica si estamos en modo de repetición o no
-   **Repetición** : invertir el estado del modo "repetir""
-   **Imagen** : enlace a la imagen del álbum
-   **Álbum** : nombre del álbum actualmente en reproducción
-   **Artista** : nombre del artista actualmente en reproducción
-   **Seguir** : nombre de la pista que se está reproduciendo actualmente
-   **Mudo** : callarse
-   **Anterior** : pista anterior
-   **Próximo** : siguiente pista
-   **Lectura** : leer
-   **Pausa** : pausa
-   **Detener** : deja de leer
-   **Volumen** : cambiar el volumen (de 0 a 100)
-   **Volumen de estado** : Nivel de volumen
-   **Estado** : estado (pausa, lectura, transición ...)
-   **Decir** : permite leer un texto en Sonos (ver parte de TTS). En el título puede configurar el volumen y en el mensaje, el mensaje a leer

> **Nota**
>
> Para reproducir listas de reproducción, puede poner opciones (en el cuadro de opciones). Para comenzar la lista de reproducción en una lectura aleatoria, debes poner "aleatorio"

# TTS

TTS (texto a voz) para Sonos requiere que Windows (Samba) se comparta en la red (impuesto por Sonos, no hay forma de hacerlo de otra manera). Entonces necesita un NAS en la red. La configuración es bastante simple, debe poner el nombre o la ip del NAS (tenga cuidado de poner lo mismo que lo que se declara en Sonos) y la ruta (relativa), el nombre de usuario y la contraseña ( atención, el usuario debe tener derechos de escritura)

> **Importante**
>
> Es absolutamente necesario poner una contraseña para que esto funcione

> **Importante**
>
> Un subdirectorio también es absolutamente necesario para que el archivo de voz se cree correctamente.

**Aquí hay un ejemplo de configuración (gracias @masterfion) :.**

Lado NAS, aquí está mi configuración :

-   La carpeta Jeedom es compartida
-   El usuario de Sonos tiene acceso de lectura / escritura (necesario para Jeedom)
-   el usuario invitado tiene acceso de solo lectura (requerido para Sonos)

Lado del complemento de Sonos, aquí está mi configuración :

-   Compartir :
    -   Campo 1 : 192.168.xxx.yyy
    -   Campo 2 : Jeedom / TTS
-   Nombre de usuario : Sonos y su contraseña ...

Sonos Library Side (aplicación para PC)
-   el camino es : //192.168.xxx.yyy/Jeedom/TTS

> **Importante**
>
> ABSOLUTAMENTE agregue el uso compartido de red en la biblioteca de sonidos, de lo contrario Jeedom creará el mp3 de tts pero Sonos no puede reproducirlo

> **Importante**
>
> El idioma depende del idioma de Jeedom y usa picotts por defecto. A partir de la libertad 3.3.X será posible usar Google TTS para tener una voz más bonita


# El panel

El complemento de Sonos también proporciona un panel que reúne a todos sus Sonos. Disponible desde el menú Inicio → Sonos Controller :

> **Importante**
>
> Para tener el panel debes activarlo en la configuración del complemento

# Preguntas frecuentes

**Error "No hay dispositivos en esta colección" al buscar equipo**
>
> Este error se produce si el descubrimiento automático está bloqueado (por ejemplo, el enrutador que bloquea el boradcast). No importa, solo tendrá que agregar sus sonos a mano especificando el modelo y la IP.
