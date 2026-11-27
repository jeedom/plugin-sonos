# Complemento Sonos

El complemento de Sonos permite controlar los dispositivos Sonos Play 1, 3, 5, Sonos Connect, Sonos Connect AMP, Sonos Playbar, Ikea Symfonisk... Te permitirá ver el estado de los dispositivos Sonos y realizar acciones (reproducir, pausar, canción siguiente, canción anterior, ajustar el volumen, seleccionar una lista de reproducción…).

# Configuración del plugin

La configuración es muy sencilla: tras descargar el complemento, solo tienes que activarlo, instalar los requisitos previos e iniciar el demonio.
El complemento buscará los dispositivos Sonos en tu red y creará los equipos automáticamente. Además, si hay una correspondencia entre los objetos de Jeedom y las habitaciones de Sonos, Jeedom asignará automáticamente los dispositivos Sonos a las habitaciones correctas.

> **Importante**
> Tus dispositivos Sonos deben ser accesibles directamente desde el servidor que aloja Jeedom (es posible el broadcast o el multicast en la misma red) y deben poder conectarse a Jeedom a través del puerto TCP 1400.

En caso de que tus altavoces Jeedom no estén en la misma subred que Jeedom, puedes configurarlo preferiblemente en formato CIDR, por ejemplo: `192.168.1.0/24`. También debería ser posible introducir directamente la dirección IP de uno de tus altavoces para detectar los demás a partir de él, pero se recomienda configurar toda la red. **Atención: no configures nada si no dominas este tema; prueba primero la configuración por defecto**

Si más adelante añades un Sonos, puedes hacer clic en **Sincronizar** en la página de dispositivos o reiniciar el demonio.

- **Recurso compartido**: Configura aquí el nombre de host del equipo (o su dirección IP), el nombre del recurso compartido (sin la ruta, sin «/») y la ruta a la carpeta.
- **Nombre de usuario del recurso compartido**: nombre de usuario para acceder al recurso compartido.
- **Contraseña de uso compartido**: contraseña de uso compartido.

# Configuración del equipo

Se puede acceder a la configuración de los dispositivos Sonos desde el menú «Plugins» y, a continuación, «Multimedia».

Aquí encontrarás toda la configuración habitual de tu equipo:

- **Nombre del Sonos**: nombre de tu dispositivo Sonos.
- **Objeto principal**: indica el objeto principal al que pertenece el equipo.
- **Activar**: permite poner en marcha tu equipo.
- **Visible**: lo muestra en el panel de control.

Además de información sobre tu Sonos: *Modelo*, *Versiones*, *Número de serie*, *ID*, *Dirección MAC* y *Dirección IP*.

También tienes la posibilidad de desactivar el mosaico del equipo preconfigurado (opción activa por defecto) y, en ese caso, configurar dicho mosaico como desees utilizando los widgets del núcleo o tus propios widgets, mostrar u ocultar los controles que elijas...

El mosaico preconfigurado no tiene en cuenta si los controles están visibles o no, ni las opciones avanzadas de visualización; su configuración no se puede modificar.

# Las órdenes

Los datos de control se actualizarán casi en tiempo real (normalmente con un retraso de unos segundos como máximo), pero la imagen del álbum que se está reproduciendo puede tardar un poco más en aparecer en el widget al cambiar de pista; esto es totalmente normal y no tiene nada que ver con el complemento: tiene que recuperar la imagen de una fuente externa (en un Sonos o en Internet) y eso a veces lleva varios segundos (en principio, como máximo unos diez segundos).

## Controles y controles de volumen de Sonos

Estos comandos siempre controlarán el equipo correspondiente, incluso cuando este forme parte de un grupo.

- **Volumen**: ajustar el volumen *(de 0 a 100)*
- **Volumen actual**: nivel de volumen (en %)
- **Aumentar el volumen**: aumenta el volumen un 1 %; puede resultar útil para la integración con otros sistemas o complementos
- **Bajar el volumen**: baja el volumen un 1 %; puede resultar útil para la integración con otros sistemas o complementos
- **Transición de volumen** permite realizar transiciones de nivel de volumen gestionadas directamente por el altavoz Sonos; no es el complemento el que se encarga de ello, por lo que no supone ningún bloqueo, pero los tiempos de espera no son configurables, ya que los define Sonos. El tipo de transición y el volumen de destino deben seleccionarse al ejecutar el comando. Existen tres modos:
  - *LINEAL*: transición lineal desde el volumen actual hasta el volumen de destino (aumento o disminución), a una velocidad de 1,25 por segundo (una transición *LINEAL* del 50 % al 30 % tardará 16 s)
  - *ALARM*: inicializa el volumen en 0, hace una pausa de unos 30 segundos y, a continuación, lo aumenta hasta el volumen solicitado a una velocidad de 2,5 por segundo (una transición *ALARM* del 0 % al 10 % tardará 4 s)
  - *AUTOPLAY*: inicializa el volumen en 0 y lo aumenta rápidamente hasta el volumen solicitado a una velocidad de 50 por segundo (una transición *AUTOPLAY* del 0 % al 50 % tardará 1 s)
- **Silencio**: Activa el modo silencio.
- **No silenciado**: Desactiva el modo silencioso.
- **Estado de silencio**: indica si se está en modo silencio o no.
- **Balance** (acción/deslizador) y **Balance de estado**, que controla el balance según un valor comprendido entre -100 (extremo izquierdo) y 100 (extremo derecho) para los dispositivos Sonos compatibles
- **Graves** (acción/cursor) y **Estado de graves**, que gestiona los graves según un valor comprendido entre -10 y 10
- **Agudos** (acción/cursor) y **Estado de agudos**, que controla los agudos según un valor comprendido entre -10 y 10
- **Estado de Loudness**, **Loudness activado**, **Loudness desactivado**: controla el volumen

- **TV**: para cambiar a la entrada *TV* en los dispositivos compatibles
- **Entrada de audio analógica**: para cambiar a la *Entrada de audio analógica* (*Line-in*) en los dispositivos compatibles
- **LED encendido** y **LED apagado**: Activa y desactiva el LED, el indicador de estado
- **Estado del LED**: indica si el indicador de estado está encendido o no. Esta información solo se actualiza una vez por minuto en caso de que se modifique fuera de Jeedom.
- **Controles táctiles activados** y **Controles táctiles desactivados**: activa y desactiva los botones físicos o táctiles del Sonos
- **Estado de los controles táctiles** indica si los controles táctiles están activados o no
- **Estado del micrófono**, que indica si el micrófono está activado o no en los dispositivos Sonos equipados con micrófono
- **Batería** en los dispositivos Sonos equipados con batería, que indica el porcentaje de carga de la batería
- **Carga** en los dispositivos Sonos con batería, indicando si se está cargando o no

## Controles de reproducción

Estos comandos mostrarán y controlarán la reproducción actual en el dispositivo o en el grupo, si este está agrupado, y lo harán de forma transparente; no tienes que preocuparte por si el dispositivo está agrupado o no para utilizarlos.

- **Estado**: estado del reproductor traducido al idioma configurado en Jeedom. Por ejemplo: *Reproducción*, *Pausa*, *Detener*.
- **Estado de reproducción**, que proporciona el valor «bruto» del estado de reproducción: *PLAYING*, *PAUSED_PLAYBACK*, *STOPPED*; más adecuado para los escenarios.
- **Lectura**: pasar al modo de lectura.
- **Pausa**: poner en pausa.
- **Stop**: detener la reproducción.
- **Anterior**: pista anterior.
- **Siguiente**: pista siguiente.
- **Estado aleatorio**: indica si se está en modo aleatorio o no.
- **Aleatorio**: invierte el estado del modo aleatorio.
- **Repetir estado**: indica si se está en modo de repetición o no.
- **Repetir**: invierte el estado del modo «Repetir».
- **Fundido encadenado: estado**, **Fundido encadenado: activado**, **Fundido encadenado: desactivado** para controlar y activar o desactivar el *fundido encadenado*
- **Seleccionar modo de reproducción** permite elegir entre las siguientes opciones:
  - *Normal* (repetición desactivada, aleatoriedad desactivada),
  - *Repetir todo* (aleatorio desactivado),
  - *Aleatorio y repetir todo*,
  - *Aleatorio sin repeticiones*,
  - *Repetir la canción* (aleatorio desactivado),
  - *Reproducir aleatoriamente y repetir la canción*.

Recomiendo utilizar este comando en un escenario en lugar de **Repetir** y **Aleatorio** para conseguir la configuración deseada, aunque todos actúen sobre los mismos parámetros. Sin embargo, este comando es la única forma de pasar al modo *Repetir la canción* o *Aleatorio y repetir la canción*.
- **Modo de lectura** que indica el estado actual, que será uno de los valores mencionados anteriormente.
- **Reproducir lista de reproducción**: comando de tipo mensaje que permite iniciar una lista de reproducción; basta con introducir el nombre de la lista de reproducción en el título. En un escenario, se mostrará automáticamente una lista de opciones en cuanto empieces a escribir.
- **Ejecutar favoritos**: comando de tipo mensaje que permite ejecutar un favorito; basta con introducir el nombre del favorito en el título. En un escenario, se mostrará automáticamente una lista de opciones en cuanto empieces a escribir.
- **Reproducir una emisora de radio**: comando de tipo mensaje que permite poner una emisora de radio; basta con introducir el nombre de la emisora en el título *(ATENCIÓN: esta debe estar entre las emisoras favoritas)*. En un escenario, se mostrará automáticamente una lista de opciones cuando empieces a escribir. Ya no funciona en los modelos «S2»; es normal que la lista esté vacía en todos los modelos que utilicen la aplicación Sonos S2.
- **Reproducir una radio en formato MP3**: permite reproducir una radio en formato MP3 a través de una URL (por ejemplo, desde Internet). Debes introducir un título en el campo *Título* y la URL (formato http(s)://...mp3) en el campo *Mensaje*.
- **Imagen**: enlace a la imagen del álbum.
- **Álbum**: nombre del álbum que se está reproduciendo.
- **Artista**: nombre del artista que se está reproduciendo.
- **Pista**: nombre de la pista que se está reproduciendo.
- **Dire**: permite leer un texto en el Sonos (véase la sección TTS). En el título puedes indicar el volumen y, en el mensaje, el texto que se va a leer.

> **Sugerencia**
> Las listas de reproducción y los favoritos deben crearse a través de la aplicación de Sonos (en el móvil o en el ordenador); a continuación, hay que realizar una sincronización para actualizar los dispositivos y poder utilizarlos en un escenario.

## Comandos para gestionar grupos

Estos comandos siempre actúan sobre el equipo correspondiente.

- **Estado del grupo**: indica si el equipo está agrupado o no.
- **Nombre del grupo**: en caso de que el equipo esté agrupado, indica el nombre del grupo.
- **Unirse a un grupo**: permite unirse al grupo de un altavoz (un Sonos) concreto (por ejemplo, para emparejar dos Sonos). Hay que introducir el nombre de la habitación del Sonos al que te quieres unir. Puede ser cualquier miembro de un grupo ya existente; no tiene por qué ser necesariamente el coordinador del grupo ni un Sonos independiente. En algunos casos, se mostrará automáticamente una lista de opciones en cuanto empieces a escribir.
- **Salir del grupo**: permite salir del grupo.
- **Modo fiesta** permite agrupar todos los dispositivos Sonos

# TTS

Para que el TTS (text-to-speech) funcione con Sonos, es necesario tener un recurso compartido SAMBA en la red (es un requisito de Sonos, no hay otra forma de hacerlo). Por lo tanto, necesitas un NAS o equivalente en la red. La configuración es bastante sencilla: hay que introducir el nombre o la IP del NAS (atención: asegúrate de introducir exactamente lo mismo que figura en Sonos), la ruta a la carpeta que debe contener los archivos de audio, así como el nombre de usuario y la contraseña (atención: el usuario debe tener permisos de escritura).

La creación del archivo de audio la gestiona el núcleo de Jeedom: el idioma será el configurado en Jeedom y el motor TTS utilizado también se puede seleccionar en la configuración de Jeedom.

Al utilizar el TTS (comando **Dire**), el complemento llevará a cabo las siguientes acciones:

- generación del archivo de audio que contiene el mensaje con soporte central de Jeedom
- escribiendo el archivo en el recurso compartido SAMBA
- forzar la reproducción en modo “Normal”, sin repetición
- forzar el modo «no silenciado» (solo en el equipo, no en todo el grupo)
- Ajuste del volumen al valor seleccionado al utilizar el mando (solo en el equipo, no en todo el grupo)
- mensaje de lectura
- restaurar el estado de Sonos antes de la reproducción (es decir, el modo de reproducción, silenciar o no, repetir o no, etc.) y reiniciar la transmisión si Sonos estaba reproduciendo

> **IMPORTANTE**
>
> Es imprescindible introducir una contraseña para que este procedimiento funcione.
>
> También es imprescindible que haya un subdirectorio para que el archivo de voz se cree correctamente.
>
> Es muy importante que el nombre del recurso compartido o de la carpeta no contenga acentos, espacios ni caracteres especiales.
>
> Los mensajes demasiado largos no se pueden transmitir mediante TTS (el límite depende del proveedor de TTS; por lo general, ronda los 100 caracteres).

## Ejemplo de configuración

En cuanto al NAS, hay que realizar la siguiente configuración:

- La carpeta *Jeedom* está compartida y contiene una carpeta *TTS*
- El usuario *jeedom* tiene acceso de lectura y escritura (necesario para Jeedom).
- El usuario *sonos* tiene acceso de solo lectura (necesario para los dispositivos Sonos).

En cuanto al plugin de Sonos, la configuración:

- Compartir:
  - Campo 1: 192.168.xxx.yyy
  - Campo 2: *Jeedom*
  - Campo 3: *TTS*
- Nombre de usuario (*jeedom* en el ejemplo) y su contraseña…​

En la biblioteca de Sonos (aplicación para PC)

- la ruta es: //192.168.xxx.yyy/Jeedom/TTS
- El usuario será *sonos* (en este ejemplo) + contraseña

# El panel

El complemento de Sonos también ofrece un panel que reúne todos tus dispositivos Sonos. Disponible desde el menú Inicio → Sonos Controller:

> **IMPORTANTE**
>
> Para poder acceder al panel, es necesario haberlo activado en la configuración del complemento.
