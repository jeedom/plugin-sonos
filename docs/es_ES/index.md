# Complemento Sonos

El plugin de Sonos te permite controlar el Sonos Play 1, 3, 5, Sonos Connect, Sonos Connect AMP, Sonos Playbar, Ikea Symfonisk... Te permitirá ver el estado del Sonos y realizar acciones (reproducir, pausar , siguiente, anterior, volumen, elección de una lista de reproducción).

> **Importante**
>
> El complemento administra muy mal los grupos (o no lo hace en absoluto), por lo que es recomendable no usar la agrupación en absoluto con el complemento

# Configuración del plugin

La configuración es muy simple, después de descargar el complemento, solo necesita activarlo y listo. El complemento buscará Sonos en su red y creará el equipo automáticamente. Además, si hay una coincidencia entre los objetos Jeedom y las habitaciones de Sonos, Jeedom asignará automáticamente Sonos a las habitaciones correctas.

> **CONSEJO**
>
> Durante el descubrimiento inicial, se recomienda encarecidamente no tener sistemas de sonido agrupados, de lo contrario tendrá errores.

Si luego agrega un Sonos, puede crear un dispositivo Sonos dando la IP a Jeedom o haciendo clic en **Encuentra equipos Sonos**.

-   **Voz** : elección de voz durante TTS
-   **Compartir** : compartir nombre y ruta de carpeta.
-   **Compartir nombre de usuario** : nombre de usuario para acceder a compartir.
-   **Compartir contraseña** : Compartir contraseña.
-   **Descubrimiento** : descubra automáticamente los sistemas de sonido (no funciona en una instalación de tipo acoplable donde cada sistema de sonido debe crearse a mano).

> **Importante**
>
> Los mensajes que son demasiado largos no se pueden transmitir en TTS (el límite
> depende del proveedor de TTS, generalmente alrededor de 100 caracteres).

# Configuración del equipo

Se puede acceder a la configuración del equipo Sonos desde el menú Complementos y luego multimedia.

Aquí encontrarás toda la configuración de tu equipo :

-   **Nombre de Sonos** : nombre de su equipo Sonos.
-   **Objeto padre** : indica el objeto padre al que pertenece el equipo.
-   **Activar** : activa su equipo.
-   **Visible** : lo hace visible en el tablero.
-   **Modelo** : El modelo de su Sonos (solo cambie si no es el correcto).
-   **IP** : la IP de su Sonos, puede ser útil si su Sonos cambia de IP o si la reemplaza.

A continuación encontrará la lista de pedidos :

-   **Nombre** : Nombre de la orden.
-   **Configuración avanzada (ruedas pequeñas con muescas)** : muestra la configuración avanzada del comando (método de registro, widget, etc.)).
-   **Probar** : Se usa para probar el comando.

Como orden encontrarás :

-   **Reproducir lista de reproducción** : comando de tipo de mensaje para iniciar una lista de reproducción, solo ponga el nombre de la lista de reproducción en el título. Puedes poner ``random`` en el mensaje para reproducir aleatoriamente la lista de reproducción antes de reproducirla.
-   **Jugar favoritos** :  comando de tipo de mensaje para lanzar un favorito, todo lo que tienes que hacer en el título es poner el nombre del favorito. Puedes poner ``random`` en mensaje para mezclar favoritos antes de leer.
-   **Tocar una radio** : comando de tipo de mensaje para lanzar una estación de radio, todo lo que tiene que hacer es poner el nombre de la radio en el título *(Atención : esto debe estar en las radios favoritas)*.
-   **Agregar un altavoz** : permite agregar un altavoz (un Sonos) al altavoz actual (para asociar 2 Sonos por ejemplo). Debe agregar el nombre de la sala de sonos para agregar en el título (el campo del mensaje no se usa aquí).
-   **Retire el altavoz** : le permite eliminar un altavoz (un Sonos) del altavoz actual (para disociar 2 Sonos, por ejemplo). Debe poner el nombre de la parte de Sonos para eliminar en el título (el campo del mensaje no se usa aquí).
-   **Estado aleatorio** : indica si estamos en modo aleatorio o no.
-   **Al azar** : invertir el estado del modo aleatorio.
-   **Repita el estado** : indica si estamos en modo de repetición o no.
-   **Repetición** : invertir el estado del modo "repetir"".
-   **Imagen** : enlace a la imagen del álbum.
-   **Álbum** : nombre del álbum actualmente en reproducción.
-   **Artista** : nombre del artista actualmente en reproducción.
-   **Seguir** : nombre de la pista que se está reproduciendo actualmente.
-   **Mudo** : callarse.
-   **Anterior** : pista anterior.
-   **Próximo** : siguiente pista.
-   **Lectura** : leer.
-   **Pausa** : pausa.
-   **Detener** : deja de leer.
-   **Volumen** : cambiar el volumen *(de 0 a 100)*.
-   **Volumen de estado** : Nivel de volumen.
-   **Estado** : estado (pausa, lectura, transición).
-   **Decir** : permite leer un texto en Sonos (ver parte de TTS). En el título puede configurar el volumen y en el mensaje, el mensaje a leer.

> **Nota**
>
> Para reproducir listas de reproducción, puede poner opciones (en el cuadro de opciones). Para iniciar la lista de reproducción en reproducción aleatoria, debe ingresar ``random``.

# TTS

TTS (texto a voz) para Sonos requiere que Windows (Samba) se comparta en la red (impuesto por Sonos, no hay forma de hacerlo de otra manera)). Entonces necesita un NAS en la red. La configuración es bastante sencilla hay que poner el nombre o la IP del NAS (cuidado con poner lo mismo que se declara en Sonos) y la ruta (relativa), el usuario y la contraseña ( atención el usuario debe tener derechos de escritura)

> **Importante**
>
> Es absolutamente necesario poner una contraseña para que este procedimiento funcione.

> **Importante**
>
> Un subdirectorio también es absolutamente necesario para que el archivo de voz se cree correctamente.

**Aquí hay un ejemplo de configuración (gracias @masterfion) :**

Lado NAS, aquí está mi configuración :

-   La carpeta Jeedom es compartida.
-   El usuario de Sonos tiene acceso de lectura / escritura (necesario para Jeedom).
-   el usuario invitado tiene acceso de solo lectura (requerido para Sonos).

Lado del complemento de Sonos, aquí está mi configuración :

-   Compartir :
    -   Campo 1 : 192.168.xxx.yyy
    -   Campo 2 : Jeedom / TTS
-   Nombre de usuario : Sonos y su contraseña

Sonos Library side (aplicación para PC)
-   el camino es : //192.168.xxx.yyy/Jeedom/TTS

> **Importante**
>
> Es ABSOLUTAMENTE necesario agregar el recurso compartido de red en la biblioteca de Sonos; de lo contrario, Jeedom creará el mp3 de los tts, pero el Sonos no podrá reproducirlo.

> **Importante**
>
> El idioma depende del idioma de Jeedom y usa picotts por defecto. A partir de la libertad 3.3.X será posible usar Google TTS para tener una voz más bonita.


# El panel

El complemento de Sonos también proporciona un panel que reúne a todos sus Sonos. Disponible desde el menú Inicio → Sonos Controller :

> **Importante**
>
> Para tener el panel debes tenerlo activado en la configuración del plugin.

# FAQ

> **Error ``No devices in this collection`` al buscar equipo**
>
> Este error se produce si el descubrimiento automático está bloqueado (por ejemplo, el enrutador que bloquea el boradcast)). No importa, solo debes agregar tus sistemas de sonido a mano, especificando el modelo y la dirección IP.

> **Tengo problemas con TTS (bucle), las radios no funcionan, tengo errores, etc...**
>
> Lo primero que debe hacer en caso de un problema es desconectar todos los Sonos, volverlos a conectar y esperar a que se inicien, luego reiniciar el demonio de Sonos en Jeedom. En general, esto resuelve el 90% de los problemas.
