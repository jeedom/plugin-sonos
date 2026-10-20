# Registro de cambios del controlador de Sonos

>**IMPORTANTE**
>
>Recuerda que, si no hay información sobre la actualización, es porque esta se refiere únicamente a la actualización de la documentación, la traducción o el texto.

# 18-05-2026

- Se ha corregido un error menor en el comando **Dire**

# 11-04-2026

- Se ha añadido un comando de información **Estación** que indica la emisora de radio que se está reproduciendo (si la información está disponible)

# 27-01-2026

- Se ha añadido la imagen de *Lámpara de mesa de Ikea*

# 19-01-2026

- Se ha añadido una configuración opcional para indicar, solo si es necesario, la subred (VLAN) en la que se encuentran tus altavoces Sonos, en caso de que sea diferente de la subred (VLAN) en la que se encuentra Jeedom.
- Correcciones para el mensaje «Error al renovar la suscripción» y la pérdida de la transmisión de información
- Correcciones de imagen

# 26-04-2025

> Atención
> Reestructuración importante del complemento: se ha reescrito una gran parte del complemento, incluida toda la comunicación con Sonos (demonio), y se han modificado algunas funciones, que ya no funcionan como antes, en particular la gestión de grupos;
>
> Requiere Jeedom 4.4.8
>
> ¡Compatible con Debian 11 y 12!
>
> Véase también [este tema en la comunidad](https://community.jeedom.com/t/erreur-you-cannot-create-a-controller-instance-from-a-speaker-that-is-not-the-coordinator-of-its-group/128862) Para más información

- Reescritura casi total del complemento; el demonio se ha reescrito por completo en Python (en lugar de PHP).
- ¡Compatible con Debian 11 y 12!
- Ya no es necesario iniciar el proceso de detección manualmente y tampoco es necesario (ni posible) añadir dispositivos manualmente, ya que el complemento detecta automáticamente tus dispositivos Sonos y crea los dispositivos correspondientes cada vez que se inicia el demonio.
- También es posible solicitar la (re)sincronización de los dispositivos, favoritos y listas de reproducción sin necesidad de reiniciar el demonio desde el panel de dispositivos.
- Sincronización automática cada hora para corregir posibles desincronizaciones
- Actualización (casi) en tiempo real de los comandos e información (con un retraso de entre 0,5 s y unos segundos como máximo), sin necesidad de tareas programadas por minutos, incluso cuando se realiza un cambio fuera de Jeedom (por ejemplo, a través de la aplicación de Sonos).
- Rediseño de la gestión de grupos (se eliminarán los antiguos comandos y se añadirán otros nuevos; consulta la documentación). Es posible unirse a un grupo o abandonarlo, así como controlar la reproducción del grupo desde cualquier dispositivo del grupo sin tener que preocuparse de quién es el controlador. El volumen, por su parte, se controla siempre por altavoz.
- Adaptación de la función de conversión de texto a voz (TTS): **será necesario ajustar la configuración del uso compartido SAMBA**.
- Optimización: ya no hay pérdidas de memoria en el demonio y consume menos que antes.
- Se optimizó la visualización de la portada que se está reproduciendo actualmente
- Optimización en la lectura de favoritos
- Se ha añadido la posibilidad de desactivar el mosaico preconfigurado: así podrás configurarlo como quieras utilizando los widgets del núcleo o tus propios widgets, mostrar u ocultar los controles que elijas...

- Se ha añadido un comando de acción **TV** para cambiar a la entrada *TV* en los dispositivos compatibles
- Se ha añadido un comando de información **Modo de reproducción** y una acción **Elegir modo de reproducción** que permite seleccionar el modo de reproducción entre las siguientes opciones: *Normal*, *Repetir todo*, *Aleatorio y repetir todo*, *Aleatorio sin repetición*, *Repetir la canción*, *Aleatorio y repetir la canción*
- Se ha añadido un comando **Estado de lectura** que proporciona el valor «en bruto» del estado de lectura (el comando existente **Estado** proporciona un valor traducido según el idioma configurado en Jeedom).
- Se han añadido los controles **Estado del grupo** (indica si el equipo está agrupado o no) y **Nombre del grupo** en caso de que el equipo esté agrupado
- Se han añadido los comandos **Led on**, **Led off** y **Led statut** para controlar el indicador de estado
- Se ha añadido un comando **Reproducir radio MP3** para reproducir una emisora de radio MP3 directamente a través de una URL (accesible en Internet, por ejemplo).
- Se han añadido los comandos **Subir el volumen** y **Bajar el volumen** en un 1 %.
- Se ha añadido un comando **Transición de volumen** que resulta muy útil para gestionar las transiciones de nivel de volumen. Hay tres modos disponibles: *LINEAL*, *ALARMA* y *REPRODUCCIÓN AUTOMÁTICA*. Consulta la documentación para obtener más información.
- Se han añadido los comandos **Loudness estado**, **Loudness activado** y **Loudness desactivado**
- Se han añadido los comandos **Fundido encadenado de estado**, **Fundido encadenado activado** y **Fundido encadenado desactivado**
- Se han añadido los controles **Controles táctiles de estado**, **Controles táctiles de encendido** y **Controles táctiles de apagado**
- Se han añadido los controles **Balance** (acción/cursor) y **Balance estado**, que gestionan el equilibrio según un valor comprendido entre -100 (extremo izquierdo) y 100 (extremo derecho).
- Se han añadido los controles **Graves** (acción/cursor) y **Estado de graves**, que gestionan los graves según un valor comprendido entre -10 y 10.
- Se han añadido los controles **Agudos** (acción/deslizador) y **Estado de agudos**, que regulan los agudos según un valor comprendido entre -10 y 10.
- Se ha añadido el comando **Modo fiesta**, que permite agrupar todos los dispositivos Sonos
- Se ha añadido el comando **Estado del micrófono**, que indica si el micrófono está activado o no en los dispositivos Sonos equipados con micrófono
- Se ha añadido un comando de información **Batería** en los dispositivos Sonos equipados con batería, que indica el porcentaje de carga de la batería
- Se ha añadido un comando de información **Carga** en los dispositivos Sonos con batería que indica si se está cargando o no
- Se ha añadido un comando de información **Próxima alarma** en cada dispositivo Sonos que indica la fecha de la próxima alarma programada en ese altavoz

# 25/04/2024

- Actualización de documentación
- Eliminación de acentos en los nombres de recursos compartidos (no compatibles con el complemento)
- Se ha eliminado la dependencia de PicoTTS (el complemento utiliza el motor global de TTS de Jeedom)
- Se agregó Sonos Beam Gen 2

# 15/01/2024

- Preparándose para Jeedom 4.4
- Se agregó Sonos Move 2

# 24/08/2023

- Lámpara de pie Symfonisk de Ikea añadida

# 25/05/2023

- Se agregó la era de Sonos

# 18/10/2022

- Lista de comandos de actualización para Jeedom v4.3
- Añadido Sonos Ray

# 22/03/2022

- Soporte para el nuevo altavoz SYMFONISK

# 01/02/2022

- Se corrigió un error en el TTS

# 27/01/2022

- Optimizaciones V4.2

# 14/01/2022

- Se ha añadido la compatibilidad con el nuevo altavoz SYMFONISK

# 27/12/2021

- Se ha añadido la compatibilidad con el nuevo Sonos One

# 09/10/2021

- Adición de Sonos Five
- Agregar Sonos Roam
- Añadiendo Symfonisk Framework
- Actualización de volumen inmediata en caso de cambio por parte de Jeedom, gracias @Domochip

# 24/11/2020

- Nueva presentación de la lista de objetos
- Se ha añadido la etiqueta «Compatibilidad con la versión 4»

# 07/08/2020

- Compatibilidad con Sonos ARC

# 24/01/2020

- Soporte para Sonos One S22

# 11/01/2020

- Soporte para Sonos Move
- Optimización de código en caso de que Sonos no esté conectado

# 16/12/2019

- Corrección de errores si no se puede alcanzar un sistema de sonido

# 21/10/2017

- Mejora en la recuperación de TTS

# 15/10/2019

- Soporte de puerto de Sonos
- Script de instalación de dependencia mejorado

# 07/10/2019

- Mejora del script de instalación de dependencias (podría permitir corregir, en algunos casos, los problemas de TTS)

# 23/09/2019

- Optimizaciones

# 01/09/2019

- Soporte de altavoz de lámpara Ikea SYMFONISK

# 12/08/2019

- Soporte para altavoz de estantería Ikea SYMFONISK

# 23/04/2019

- Soporte para un sonos gen2

# 17/01/2019

- Se corrigieron errores en caso de que los sistemas de sonido se agregaran manualmente

# 15/01/2019

**IMPORTANTE: SOLO FUNCIONA CON PHP7. CONSULTA LA PÁGINA DE ESTADO DE JEEDOM PARA CONOCER TU VERSIÓN**

- Completa reescritura del complemento
- Soporte para la nueva API de Sonos
- Soporte para sistemas de sonido Beam y One
- Se han corregido numerosos errores
- Optimizaciones globales

**IMPORTANTE**

- Solo PHP7 compatible
- Algunas características tuvieron que ser eliminadas

# 2018

- Administración agregada de favoritos de sonos
- Soporte para Sonos One y Playbase
- Corrección de lengua con picotts
- Incorporación de un comando «Entrada de línea»
- Actualización de la biblioteca de comunicación con Sonos
- Carga optimizada de listas de reproducción
- Adición de picotts para la generación local de TTS
- Se ha corregido el botón de reproducción/pausa al actualizar el widget.
