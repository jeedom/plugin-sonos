# Complemento Sonos

El plugin de Sonos te permite controlar el Sonos Play 1, 3, 5, Sonos Connect, Sonos Connect AMP, Sonos Playbar, Ikea Symfonisk... Te permitirá ver el estado del Sonos y realizar acciones (reproducir, pausar , siguiente, anterior, volumen, elección de una lista de reproducción).

# Configuración del plugin

La configuración es muy sencilla, después de descargar el complemento, solo necesitas activarlo, instalar las dependencias e iniciar el demonio.
El complemento buscará Sonos en su red y creará el equipo automáticamente. Además, si hay una coincidencia entre los objetos Jeedom y las habitaciones de Sonos, Jeedom asignará automáticamente Sonos a las habitaciones correctas.

> **Importante**
> Su equipo Sonos debe ser accesible directamente desde la máquina que aloja Jeedom (transmisión/multidifusión posible en la misma red) y deben poder comunicarse con Jeedom a cambio en el puerto TCP 1400.

Dans le cas où vos enceintes Jeedom ne seraient pas sur le même sous-réseau que Jeedom, vous pouvez configurer celui-ci de préférence sous le format CIDR, par exemple `192.168.1.0/24`. Il devrait également être possible d'entrer directement l'ip de l'une de vos enceintes afin de découvrir les autres à partir de celle-ci mais il est recommandé de configurer le réseau complet. **Attention, ne configurer rien si vous ne maitrisez pas cette partie, testez d'abord la configuration par défaut**

Si luego agrega un Sonos, puede hacer clic en **Sincronizar** en la página del equipo o reinicie el demonio.

- **Compartir**: Configure aquí el nombre de host de la máquina (o su IP), el nombre del recurso compartido (sin la ruta, sin '/') y la ruta a la carpeta.
- **Compartir nombre de usuario**: nombre de usuario para acceder a compartir.
- **Compartir contraseña**: Compartir contraseña.

# Configuración del equipo

Se puede acceder a la configuración del equipo Sonos desde el menú Complementos y luego multimedia.

Aquí encontrarás toda la configuración habitual de tu equipo :

- **Nombre de Sonos**: nombre de su equipo Sonos.
- **Objeto padre**: indica el objeto padre al que pertenece el equipo.
- **Activar**: activa su equipo.
- **Visible**: lo hace visible en el tablero.

Además de información sobre tu Sonos: *Modelo*, *Lanzamientos*, *Número de serie*, *Identificador*, *Dirección MAC* Y *Dirección IP*.

Vous avez aussi la possibilité de désastiver la tuile de l'équipement pré-configurée (option active par défaut) et dans ce cas configurer cette tuile comme vous le souhaitez en utilisant les widgets du core ou vos propres widgets, d'afficher ou masquer les commandes de votre choix...

La tuile pré-configurée ne tient pas compte du statut visible ou non des commandes, ni des options avancées d'affichage; La configuration de celle-ci ne peut pas être modifiée.

# Las órdenes

Los controles de información se actualizarán casi en tiempo real (normalmente unos segundos como máximo), pero la imagen del álbum que se está reproduciendo actualmente puede tardar un poco más en mostrarse en el widget cuando se produce un cambio de pista, esto es perfectamente normal e independiente de la pista. enchufar: debe recuperar la imagen de una fuente externa (en un Sonos o en Internet) y esto a veces lleva varios segundos (en principio, un máximo de diez segundos)).

## Controles y controles de volumen de Sonos

Estos comandos controlarán siempre el equipo correspondiente, incluso cuando esté en grupo.

- **Volumen**: cambiar el volumen *(de 0 a 100)*
- **Volumen de estado**: nivel de volumen (en %)
- **Sube el volumen**: aumenta el volumen en un 1%; Puede ser útil para la integración con otros sistemas o complementos
- **Disminuir el volumen**: disminuye el volumen en un 1%; Puede ser útil para la integración con otros sistemas o complementos
- **Transición de volumen** le permite realizar transiciones de nivel de volumen administradas directamente por el altavoz Sonos, no es el complemento el que se encarga de esto y por lo tanto no bloquea pero los retrasos no son configurables desde que los define Sonos. El tipo de transición y el volumen de destino deben elegirse al ejecutar el comando. Hay 3 modos:
  - *LINEAL*: transición lineal del volumen actual al volumen objetivo (aumentar o disminuir), la velocidad es 1.25 por segundo (una transición *LINEAL* Del 50% al 30% se necesitarán 16s)
  - *ALARMA*: inicializa el volumen a 0, hace una pausa de unos 30 segundos y luego aumenta al volumen solicitado a una velocidad de 2.5 por segundo (una transición *ALARMA* del 0% al 10% tardará 4s)
  - *AUTO-REPRODUCCIÓN*: inicializa el volumen a 0 y aumenta rápidamente al volumen solicitado a una velocidad de 50 por segundo (una transición *AUTO-REPRODUCCIÓN* de 0% a 50% tomará 1s)
- **Mudo**: Activar el modo silencio.
- **Sin silencio**: Desactivar silencio.
- **Estado de silencio**: indica si estamos en modo silencio o no.
- **Balance** (acción/cursor) y **Estado del saldo** que gestiona el equilibrio según un valor entre -100 (extremo izquierdo) y 100 (extremo derecho) para Sonos compatibles
- **Tumbas** (acción/cursor) y **Estado serio** que gestiona los graves según un valor entre -10 y 10
- **Triplicar** (acción/cursor) y **Estado de agudos** que gestiona los agudos según un valor entre -10 y 10
- **Estado de sonoridad**, **Volumen encendido**, **Volumen apagado** controla el volumen

- **TELEVISOR**: para cambiar a la entrada *TELEVISOR* en equipos compatibles
- **Entrada de audio analógico**: para cambiar a'*Entrada de audio analógico* (*En linea*) en equipos compatibles
- **El don** Y **Llevar afuera**: Activa y desactiva el LED, la luz de estado
- **LED de estado**: indica si la luz de estado está encendida o no. Esta información solo se actualiza una vez por minuto en caso de que se modifique fuera de Jeedom
- **Controles táctiles activados** Y **Controles táctiles desactivados** Activa y desactiva los botones físicos o táctiles en Sonos
- **Controles táctiles de estado** indica si los controles táctiles están habilitados o no
- **Estado del micrófono** que indica si el micrófono está activado o no en Sonos equipado con un micrófono
- **Batería** en Sonos equipado con una batería que muestra el porcentaje de carga de la batería
- **Cargando** en Sonos equipado con una batería que indica si la carga está en curso o no

## Controles de reproducción

Estos comandos indicarán y controlarán la reproducción actual en el equipo o en el grupo si está agrupado y esto de forma transparente, no tienes que preocuparte por saber si los equipos están agrupados o no para usarlos.

- **Estado**: estado del lector traducido al idioma configurado en Jeedom. Por ejemplo: *Lectura*, *Pausa*, *Interrumpido*.
- **Estado de lectura** que da el valor "bruto" del estado de lectura: *JUGANDO*, *PAUSAD_REPRODUCCIÓN*, *INTERRUMPIDO*; más adecuado para escenarios.
- **Lectura**: leer.
- **Pausa**: pausa.
- **Detener**: deja de leer.
- **Anterior**: pista anterior.
- **Próximo**: siguiente pista.
- **Estado aleatorio**: indica si estamos en modo aleatorio o no.
- **Al azar**: invertir el estado del modo aleatorio.
- **Repita el estado**: indica si estamos en modo de repetición o no.
- **Repetición**: invertir el estado del modo "repetir"".
- **Estado de desvanecimiento**, **Desvanecerse**, **Desaparecer** controlar y activar o no el *Fundido cruzado*
- **Elige el modo de lectura** le permite elegir entre las siguientes posibilidades:
  - *Normal* (repetir, desactivar aleatoriamente),
  - *Repite todo* (aleatorio apagado),
  - *Aleatorio y repetir todo*,
  - *Aleatorio sin repetición*,
  - *Repetir canción* (aleatorio apagado),
  - *Canción aleatoria y repetida*.

  Recomiendo usar este comando en un escenario en lugar de **Repetición** & **Al azar** para llegar a la configuración deseada incluso si todos actúan sobre los mismos parámetros. Este comando es, sin embargo, la única manera de cambiar al modo *Repetir canción* O *Canción aleatoria y repetida*.
- **Modo de lectura** dando el estado actual que será uno de los valores citados anteriormente.
- **Reproducir lista de reproducción**: comando de tipo de mensaje para iniciar una lista de reproducción, solo ponga el nombre de la lista de reproducción en el título. En un escenario, se mostrará automáticamente una lista de posibilidades cuando empieces a escribir.
- **Jugar favoritos**:  comando de tipo de mensaje para lanzar un favorito, todo lo que tienes que hacer en el título es poner el nombre del favorito. En un escenario, se mostrará automáticamente una lista de posibilidades cuando empieces a escribir.
- **Tocar una radio**: comando de tipo de mensaje para lanzar una estación de radio, todo lo que tiene que hacer es poner el nombre de la radio en el título *(Atención : esto debe estar en las radios favoritas)*. En un escenario, se mostrará automáticamente una lista de posibilidades cuando empieces a escribir. Ya no funciona en los modelos "S2", es normal tener una lista vacía en todos los modelos que usan la aplicación Sonos S2.
- **Reproducir radio mp3**: le permite reproducir una radio mp3 a través de una URL (por ejemplo, desde Internet)). Debes poner un título en el cuadro *Título* y la URL (http(formato s))://...mp3) en el área *Mensaje*.
- **Imagen**: enlace a la imagen del álbum.
- **Álbum**: nombre del álbum actualmente en reproducción.
- **Artista**: nombre del artista actualmente en reproducción.
- **Seguir**: nombre de la pista que se está reproduciendo actualmente.
- **Decir**: permite leer un texto en Sonos (ver parte de TTS). En el título puede configurar el volumen y en el mensaje, el mensaje a leer.

> **Pista**
> Las listas de reproducción y favoritos deben crearse a través de la aplicación Sonos (en el móvil o en la computadora) luego se debe realizar la sincronización para actualizar el equipo y poder usarlo en un escenario.

## Comandos para gestionar grupos

Estos comandos actúan siempre sobre el equipo correspondiente.

- **Estado del grupo**: Indica si el equipo está agrupado o no.
- **Nombre del grupo** si el equipo está agrupado, indique el nombre del grupo.
- **Únete a un grupo**: le permite unirse al grupo del hablante determinado (un Sonos) (para asociar 2 Sonos, por ejemplo)). Debe ingresar el nombre de la sala del sistema de sonido para unirse. Puede ser cualquier miembro de un grupo existente, no tiene que ser el coordinador del grupo ni un Sonos aislado. En un escenario, se mostrará automáticamente una lista de posibilidades cuando empieces a escribir.
- **Abandonar el grupo**: te permite salir del grupo.
- **Moda de fiesta** te permite agrupar todos los Sonos

# TTS

TTS (texto a voz) para Sonos requiere compartir SAMBA en la red (impuesto por Sonos, no hay forma de hacerlo de otra manera)). Por lo tanto, necesita un NAS o equivalente en la red. La configuración es bastante sencilla, debes ingresar el nombre o IP del NAS (cuidado de poner lo mismo que se declara en Sonos) y la ruta a la carpeta que debe contener los archivos de audio así como el nombre del usuario. y contraseña (tenga en cuenta que el usuario debe tener derechos de escritura)

La creación del archivo de audio es gestionada por el núcleo Jeedom: el idioma será el configurado en Jeedom y el motor TTS utilizado también se podrá seleccionar en la configuración de Jeedom.

Cuando se utiliza TTS (comando **Decir**), el complemento realizará las siguientes acciones:

- generación del archivo de audio que contiene el mensaje con soporte central de Jeedom
- escribiendo el archivo en el recurso compartido SAMBA
- forzar la reproducción en modo “Normal”, sin repetición
- forzar el modo “activar silencio” (solo en el equipo, no en todo el grupo))
- Cambiar el volumen al valor elegido al utilizar el comando (solo en el equipo, no en todo el grupo))
- mensaje de lectura
- restaurar el estado de Sonos antes de la reproducción (es decir, el modo de reproducción, silenciar o no, repetir o no, etc.) y reiniciar la transmisión si Sonos estaba reproduciendo

> **Importante**
>
> Es absolutamente necesario poner una contraseña para que este procedimiento funcione.
>
> Un subdirectorio también es absolutamente necesario para que el archivo de voz se cree correctamente.
>
> Sobre todo, no debe haber acentos en el nombre del recurso compartido o carpeta, espacios o caracteres especiales.
>
> Los mensajes demasiado largos no se pueden transmitir en TTS (el límite depende del proveedor de TTS, generalmente alrededor de 100 caracteres)).

## Ejemplo de configuración

Del lado del NAS se debe realizar la siguiente configuración:

- el registro *Jeedom* es compartido y contiene una carpeta *TTS*
- el usuario *Jeedom* tiene acceso de lectura/escritura (necesario para Jeedom).
- el usuario *su hueso* tiene acceso de solo lectura (necesario para Sonos).

En el lado del complemento de Sonos, la configuración :

- Compartir :
  - Campo 1: 192.168.xxx.yyy
  - Campo 2: *Jeedom*
  - Campo 3: *TTS*
- Nombre del usuario (*Jeedom* en el ejemplo) y su contraseña…​

Sonos Library side (aplicación para PC)

- el camino es : //192.168.xxx.yyy/Jeedom/TTS
- el usuario será *su hueso* (en este ejemplo) + contraseña

# El panel

El complemento de Sonos también proporciona un panel que reúne a todos sus Sonos. Disponible desde el menú Inicio → Sonos Controller :

> **Importante**
>
> Para tener el panel debes tenerlo activado en la configuración del plugin.
