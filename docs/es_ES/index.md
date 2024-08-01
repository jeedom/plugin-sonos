# Complemento Sonos

El plugin de Sonos te permite controlar el Sonos Play 1, 3, 5, Sonos Connect, Sonos Connect AMP, Sonos Playbar, Ikea Symfonisk... Te permitirá ver el estado del Sonos y realizar acciones (reproducir, pausar , siguiente, anterior, volumen, elección de una lista de reproducción).

# Configuración del plugin

La configuración es muy sencilla, después de descargar el complemento, solo necesitas activarlo, instalar las dependencias e iniciar el demonio.
El complemento buscará Sonos en su red y creará el equipo automáticamente. Además, si hay una coincidencia entre los objetos Jeedom y las habitaciones de Sonos, Jeedom asignará automáticamente Sonos a las habitaciones correctas.

> **Importante**
> La máquina que aloja Jeedom debe poder acceder directamente a su equipo Sonos y, a su vez, debe poder comunicarse con Jeedom a través del puerto TCP 1400.

> **CONSEJO**
>
> Durante el descubrimiento inicial, se recomienda encarecidamente no tener sistemas de sonido agrupados, de lo contrario tendrá errores.

Si luego agrega un Sonos, puede hacer clic en **Sincronizar** en la página del equipo o reinicie el demonio.

- **Compartir**: Configure aquí el nombre de host de la máquina (o su IP), el nombre del recurso compartido (sin la ruta, sin '/') y la ruta a la carpeta.
- **Compartir nombre de usuario** : nombre de usuario para acceder a compartir.
- **Compartir contraseña** : Compartir contraseña.

> **Importante**
>
> Los mensajes que son demasiado largos no se pueden transmitir en TTS (el límite
> depende del proveedor de TTS, generalmente alrededor de 100 caracteres).

# Configuración del equipo

Se puede acceder a la configuración del equipo Sonos desde el menú Complementos y luego multimedia.

Aquí encontrarás toda la configuración de tu equipo :

- **Nombre de Sonos** : nombre de su equipo Sonos.
- **Objeto padre** : indica el objeto padre al que pertenece el equipo.
- **Activar** : activa su equipo.
- **Visible** : lo hace visible en el tablero.

Además de información sobre tu Sonos: *Modelo*, *Lanzamientos*, *Número de serie*, *Identificador*, *Dirección MAC* Y *Dirección IP*.

# Las órdenes

Los controles de información se actualizarán casi en tiempo real (normalmente unos segundos como máximo), pero la imagen del álbum que se está reproduciendo actualmente puede tardar un poco más en mostrarse en el widget cuando se produce un cambio de pista, esto es perfectamente normal e independiente de la pista. enchufar: debe recuperar la imagen de una fuente externa (en un Sonos o en Internet) y esto a veces lleva varios segundos (en principio, un máximo de diez segundos)).

## Controles de volumen y fuente

Estos comandos controlarán siempre el equipo correspondiente, incluso cuando esté en grupo.

- **Volumen** : cambiar el volumen *(de 0 a 100)*.
- **Volumen de estado** : Nivel de volumen.
- **Mudo** : Activar el modo silencio.
- **Sin silencio** : Desactivar silencio.
- **Estado de silencio** : indica si estamos en modo silencio o no.

- **TELEVISOR** : para cambiar a la entrada *TELEVISOR* en equipos compatibles
- **Entrada de audio analógico** : para cambiar a'*Entrada de audio analógico* en equipos compatibles

## Controles de reproducción

Estos comandos indicarán y controlarán la reproducción actual en el equipo o en el grupo si está agrupado y esto de forma transparente, no tienes que preocuparte por saber si los equipos están agrupados o no para usarlos.

- **Estado** : estado del lector traducido al idioma configurado en Jeedom. Por ejemplo: *Lectura*, *Pausa*, *Interrumpido*.
- **Estado de lectura** que da el valor "bruto" del estado de lectura: *JUGANDO*, *PAUSAD_REPRODUCCIÓN*, *INTERRUMPIDO*; más adecuado para escenarios.
- **Lectura** : leer.
- **Pausa** : pausa.
- **Detener** : deja de leer.
- **Anterior** : pista anterior.
- **Próximo** : siguiente pista.
- **Estado aleatorio** : indica si estamos en modo aleatorio o no.
- **Aleatorio** : invertir el estado del modo aleatorio.
- **Repita el estado** : indica si estamos en modo de repetición o no.
- **Repetición** : invertir el estado del modo "repetir"".
- **Modo de lectura** dando estatus y mando **Elige el modo de lectura** que le permite elegir entre las siguientes posibilidades: *Normal*, *Repite todo*, *Aleatorio*, *Aleatorio sin repetición*, *Repetir canción*, *Canción aleatoria y repetida*. Esta acción equivale a utilizar los comandos **Repetición** & **Aleatorio** para llegar a la configuración deseada. Sin embargo, esta es la única manera de cambiar al modo "Repetir canción"".
- **Reproducir lista de reproducción** : comando de tipo de mensaje para iniciar una lista de reproducción, solo ponga el nombre de la lista de reproducción en el título.
- **Jugar favoritos** :  comando de tipo de mensaje para lanzar un favorito, todo lo que tienes que hacer en el título es poner el nombre del favorito.
- **Tocar una radio** : comando de tipo de mensaje para lanzar una estación de radio, todo lo que tiene que hacer es poner el nombre de la radio en el título *(Atención : esto debe estar en las radios favoritas)*. Ya no funciona en los modelos "S2"".
- **Imagen** : enlace a la imagen del álbum.
- **Álbum** : nombre del álbum actualmente en reproducción.
- **Artista** : nombre del artista actualmente en reproducción.
- **Seguir** : nombre de la pista que se está reproduciendo actualmente.
- **Decir** : permite leer un texto en Sonos (ver parte de TTS). En el título puede configurar el volumen y en el mensaje, el mensaje a leer.

## Comandos para gestionar grupos

Estos comandos actúan siempre sobre el equipo correspondiente.

- **Estado del grupo** : Indica si el equipo está agrupado o no.
- **Nombre del grupo** si el equipo está agrupado, indique el nombre del grupo.
- **Únete a un grupo** : le permite unirse al grupo del hablante determinado (un Sonos) (para asociar 2 Sonos, por ejemplo)). Debe ingresar el nombre de la sala del sistema de sonido para unirse. Puede ser cualquier miembro de un grupo existente, no tiene que ser el coordinador del grupo ni un Sonos aislado.
- **Abandonar el grupo** : te permite salir del grupo.

# TTS

TTS (texto a voz) para Sonos requiere que Windows (Samba) se comparta en la red (impuesto por Sonos, no hay forma de hacerlo de otra manera)). Entonces necesita un NAS en la red. La configuración es bastante sencilla hay que poner el nombre o la IP del NAS (cuidado con poner lo mismo que se declara en Sonos) y la ruta (relativa), el usuario y la contraseña ( atención el usuario debe tener derechos de escritura)

La creación del archivo de audio es gestionada por el núcleo Jeedom: el idioma será el configurado en Jeedom y el motor TTS utilizado también se podrá seleccionar en las pantallas de configuración de Jeedom.

Cuando se utiliza TTS (comando **Decir**), el complemento realizará las siguientes acciones:

- generación del archivo de audio que contiene el mensaje con soporte central de Jeedom
- escribiendo el archivo en el recurso compartido SAMBA
- Fuerza la reproducción en modo “Normal”, sin repetir
- Forzar el modo "activar silencio""
- Cambiar el volumen al valor elegido al usar el comando
- mensaje de lectura
- restaurar el estado de Sonos antes de la reproducción (es decir, el modo de reproducción, silenciar o no, repetir o no, etc.) y reiniciar la transmisión si Sonos estaba reproduciendo

> **Importante**
>
> Es absolutamente necesario poner una contraseña para que este procedimiento funcione.
>
> Un subdirectorio también es absolutamente necesario para que el archivo de voz se cree correctamente.
>
> Sobre todo, no debe haber acentos en el nombre del recurso compartido o carpeta, espacios o caracteres especiales

**Aquí hay un ejemplo de configuración (gracias @masterfion) :**

Lado NAS, aquí está mi configuración :

- La carpeta Jeedom es compartida.
- El usuario de Sonos tiene acceso de lectura / escritura (necesario para Jeedom).
- el usuario invitado tiene acceso de solo lectura (requerido para Sonos).

En el lado del complemento de Sonos, la configuración :

- Compartir :
  - Campo 1: 192.168.xxx.yyy
  - Campo 2: Jeedom
  - Campo 3: TTS
- Nombre de usuario y contraseña...​

Sonos Library side (aplicación para PC)

- el camino es : //192.168.xxx.yyy/Jeedom/TTS

# El panel

El complemento de Sonos también proporciona un panel que reúne a todos sus Sonos. Disponible desde el menú Inicio → Sonos Controller :

> **Importante**
>
> Para tener el panel debes tenerlo activado en la configuración del plugin.
