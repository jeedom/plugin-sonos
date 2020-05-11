# Sonos plugin

O plugin Sonos permite controlar o Sonos Play 1, 3, 5, Sonos Connect, Sonos Connect AMP, Sonos Playbar, Ikea Symfonisk ... Permite visualizar o estado do Sonos e executar ações nele. (reproduzir, pausar, próximo, anterior, volume, escolha de uma lista de reprodução ...)

# Configuração do plugin

A configuração é muito simples, depois de baixar o plugin, você só precisa ativá-lo e pronto. O plugin irá procurar Sonos na sua rede e criar o equipamento automaticamente. Além disso, se houver uma correspondência entre os objetos Jeedom e as salas Sonos, o Jeedom atribuirá automaticamente o Sonos às salas certas.

> **Tip**
>
> Durante a descoberta inicial, é altamente recomendável não agrupar os sistemas de som sob pena de ter erros

Se você adicionar um Sonos posteriormente, poderá criar um dispositivo Sonos fornecendo o IP a Jeedom ou clique em "Procurar dispositivos Sonos"

-   **Voix** : escolha de voz durante TTS
-   **Partage** : compartilhar nome e caminho da pasta
-   **Nome de usuário para compartilhamento** : nome de usuário para acessar o compartilhamento
-   **Compartilhando senha** : Compartilhando senha
-   **Descoberta** : descobrir automaticamente os sistemas de som (não funciona em uma instalação do tipo docker em que cada sistema de som deve ser criado manualmente)
-   **Dependência Sonos** : instalar dependências de sonos para TTS

> **Important**
>
> Mensagens muito longas não podem ser transmitidas no TTS (o limite
> depende do provedor TTS, geralmente com cerca de 100 caracteres)

# Configuração do equipamento

A configuração do equipamento Sonos pode ser acessada no menu Plugins e depois em multimídia

Aqui você encontra toda a configuração do seu equipamento :

-   **Nome do equipamento Sonos** : nome do seu equipamento Sonos
-   **Objeto pai** : indica o objeto pai ao qual o equipamento pertence
-   **Activer** : torna seu equipamento ativo
-   **Visible** : torna visível no painel
-   **Modelo** : o modelo do seu Sonos (só mude se não for o correto)
-   **IP** : o IP do seu Sonos, pode ser útil se o seu Sonos alterar o IP ou se você o substituir

Abaixo você encontra a lista de pedidos :

-   **Nom** : nome do comando
-   **Configuração avançada (pequenas rodas dentadas)** : exibe a configuração avançada do comando (método de registro, widget etc.)
-   **Tester** : permite testar o comando

Como ordem, você encontrará :

-   **Reproduzir lista de reprodução** : comando de tipo de mensagem para iniciar uma lista de reprodução, basta colocar o nome da lista no título. Você pode colocar "aleatório" na mensagem para misturar a lista de reprodução antes de reproduzir.
-   **Reproduzir Favoritos** :  comando de tipo de mensagem para iniciar um marcador, basta colocar o nome do marcador no título. Você pode colocar "aleatório" na mensagem para misturar favoritos antes de ler.
-   **Tocar rádio** : comando de tipo de mensagem para iniciar um rádio, apenas no título, coloque o nome do rádio (CUIDADO, este deve estar nos rádios favoritos).
-   **Adicionando um alto-falante** : permite adicionar um alto-falante (um Sonos) ao alto-falante atual (para associar 2 Sonos, por exemplo). Você deve colocar o nome da sala de sonos para adicionar no título (o campo de mensagem não é usado aqui).
-   **Remover alto-falante** : permite excluir um alto-falante (um Sonos) do atual (para dissociar 2 Sonos, por exemplo). Você deve colocar o nome da parte do Sonos para excluir no título (o campo de mensagem não é usado aqui).
-   **Status aleatório** : indica se estamos no modo aleatório ou não
-   **Aleatório** : reverter o status do modo aleatório
-   **Repita o status** : indica se estamos no modo de repetição ou não
-   **Repetir** : reverter o status do modo "repetir""
-   **Image** : link para a imagem do álbum
-   **Album** : nome do álbum atualmente sendo reproduzido
-   **Artiste** : nome do artista atualmente sendo reproduzido
-   **Piste** : nome da faixa atualmente sendo reproduzida
-   **Muet** : mudo
-   **Anterior** : faixa anterior
-   **Suivant** : próxima faixa
-   **Lecture** : ler
-   **Pause** : pausar
-   **Stop** : pare de ler
-   **Volume** : alterar o volume (de 0 a 100)
-   **Volume de status** : Nível de volume
-   **Statut** : status (pausa, leitura, transição ...)
-   **Dire** : permite ler um texto no Sonos (consulte a parte TTS). No título, você pode definir o volume e, na mensagem, a mensagem a ser lida

> **Note**
>
> Para reproduzir listas de reprodução, você pode colocar opções (na caixa de opções). Para iniciar a lista de reprodução em leitura aleatória, você deve colocar ""

# TTS

O TTS (conversão de texto em fala) para o Sonos requer o compartilhamento do Windows (Samba) na rede (imposto pelo Sonos, não há como fazer o contrário). Então você precisa de um NAS na rede. A configuração é bastante simples: você deve colocar o nome ou o ip do NAS (tenha o cuidado de colocar o mesmo que o declarado no Sonos) e o caminho (relativo), o nome do usuário e a senha ( atenção que o usuário deve ter direitos de gravação)

> **Important**
>
> É absolutamente necessário colocar uma senha para que isso funcione

> **Important**
>
> Um subdiretório também é absolutamente necessário para que o arquivo de voz seja criado corretamente.

**Aqui está um exemplo de configuração (obrigado @masterfion) :.**

Lado NAS, aqui está a minha configuração :

-   Pasta Jeedom é compartilhada
-   O usuário do Sonos tem acesso de leitura / gravação (necessário para o Jeedom)
-   o usuário convidado possui acesso somente leitura (necessário para o Sonos)

Lado do Sonos Plugin, aqui está minha configuração :

-   Partilha:
    -   Campo 1 : 192.168.xxx.aaa
    -   Campo 2 : Jeedom / TTS
-   Nome de Usuário : Sonos e sua senha…

Lado da Biblioteca Sonos (aplicativo para PC)
-   o caminho é : //192.168.xxx.aaa/Jeedom/TTS

> **Important**
>
> É absolutamente necessário adicionar o compartilhamento de rede à biblioteca de sons, caso contrário, o Jeedom criará o mp3 para o tts, mas ele não poderá ser reproduzido pelo Sonos

> **Important**
>
> O idioma depende do idioma Jeedom e usa picotts por padrão. A partir do jeedom 3.3.X será possível usar o Google TTS para ter uma voz mais bonita


# O painel

O plugin Sonos também fornece um painel que reúne todos os seus Sonos. Disponível no menu Início → Sonos Controller :

> **Important**
>
> Para ter o painel, você precisa ativá-lo na configuração do plugin

# FAQ

**Erro "Nenhum dispositivo nesta coleção" ao procurar equipamento**
>
> Este erro ocorre se a descoberta automática estiver bloqueada (roteador que bloqueia o boradcast, por exemplo). Não importa que você tenha que adicionar seus sonos manualmente, especificando o modelo e o IP.
