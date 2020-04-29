O plugin Sonos permite controlar o Sonos Play 1, 3, 5, Sonos Connect,
Sonos Connect AMP e Sonos Playbar. Isso permitirá que você veja o status
Sonos e executar ações neles (reproduzir, pausar, próximo,
volume anterior, escolha de uma lista de reprodução ...)

# Configuração do plugin

A configuração é muito simples, depois de baixar o plugin, ele
você acabou de ativá-lo e é isso. O plugin procurará por
Sonos na sua rede e crie o equipamento automaticamente. A partir de
além disso, se houver uma correspondência entre objetos e peças Jeedom
Sonos, o Jeedom atribuirá automaticamente o Sonos à direita
peças.

> **Tip**
>
> Durante a descoberta inicial, é altamente recomendável não agrupar os sistemas de som sob pena de ter erros

Se você adicionar mais tarde um Sonos, poderá criar um dispositivo
Sonos, fornecendo o IP para Jeedom ou clique em "Search for
Equipamento Sonos"

-   **Voix** : escolha de voz durante TTS
-   **Partage** : compartilhar nome e caminho da pasta
-   **Nome de usuário para compartilhamento** : nome de usuário para
    compartilhamento de acesso
-   **Compartilhando senha** : Compartilhando senha
-   **Descoberta** : descobrir automaticamente os sistemas de som (não funciona
    em uma instalação do tipo docker em que você deve criar manualmente
    cada sonos)
-   **Dependência Sonos** : instalar dependências de sonos para TTS

> **Important**
>
> Mensagens muito longas não podem ser transmitidas no TTS (o limite
> depende do provedor TTS, geralmente com cerca de 100 caracteres)

# Configuração do equipamento

A configuração do equipamento Sonos pode ser acessada no menu
Plugins e multimídia

Aqui você encontra toda a configuração do seu equipamento :

-   **Nome do equipamento Sonos** : nome do seu equipamento Sonos
-   **Objeto pai** : indica o objeto pai ao qual pertence
    o equipamento
-   **Activer** : torna seu equipamento ativo
-   **Visible** : torna visível no painel
-   **Modelo** : seu modelo Sonos (não mude a menos que
    não é o caminho certo)
-   **IP** : o IP do seu Sonos, pode ser útil se o seu Sonos mudar
    de IP ou se você substituí-lo

Abaixo você encontra a lista de pedidos :

-   **Nom** : nome do comando
-   **Configuração avançada (pequenas rodas dentadas)** : permite
    exibir a configuração avançada do comando (método
    história, widget ...)
-   **Tester** : permite testar o comando

Como ordem, você encontrará :

-   **Reproduzir lista de reprodução** : comando de tipo de mensagem a ser iniciado
    uma lista de reprodução, basta colocar o nome no título
    a lista de reprodução. Você pode colocar "aleatório" na mensagem para misturar
    a lista de reprodução antes de ler.
-   **Reproduzir Favoritos** :  comando de tipo de mensagem a ser iniciado
    um favorito, basta no título colocar o nome dos favoritos. Você
    pode colocar "aleatório" na mensagem para misturar favoritos antes de ler.
-   **Tocar rádio** : comando de tipo de mensagem a ser iniciado
    um rádio, apenas no título coloque o nome do rádio
    (CUIDADO, este deve estar nas estações de rádio favoritas).
-   **Adicionando um alto-falante** : permite adicionar um alto-falante
    (um Sonos) ao orador atual (para associar 2 Sonos
    por exemplo). Você deve colocar o nome da sala de sonos para adicionar
    no título (o campo da mensagem não é usado aqui).
-   **Remover alto-falante** : permite excluir um alto-falante
    (a Sonos) ao orador atual (para dissociar 2 Sonos
    por exemplo). Você deve colocar o nome da sala Sonos para excluir
    no título (o campo da mensagem não é usado aqui).
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
-   **Dire** : permite ler um texto no Sonos (consulte a parte TTS).
    No título, você pode definir o volume e, na mensagem, o
    mensagem para ler

> **Note**
>
> Para reproduzir listas de reprodução, você pode colocar opções (no
> caixa de opção). Para iniciar a lista de reprodução em reprodução aleatória, você deve
> colocar "aleatoriamente"

# TTS

O TTS (conversão de texto em fala) para o Sonos requer compartilhamento
Windows (Samba) na rede (imposto pelo Sonos, não há como fazer
caso contrário). Então você precisa de um NAS na rede. A configuração é
bem simples você tem que colocar o nome ou o ip do NAS (tenha cuidado
coloque o mesmo que o indicado no Sonos) e o domínio principal
(relativo), nome de usuário e senha (atenção
o usuário deve ter direitos de gravação)

> **Important**
>
> É absolutamente necessário colocar uma senha para que isso funcione

> **Important**
>
> Também é absolutamente necessário um subdiretório para que o arquivo de voz
> ser criado corretamente.

**Aqui está um exemplo de configuração (obrigado @masterfion) :.**

Lado NAS, aqui está a minha configuração :

-   Pasta Jeedom é compartilhada
-   O usuário do Sonos tem acesso de leitura / gravação (necessário
    para Jeedom)
-   o usuário convidado tenha acesso somente leitura (necessário para
    Sonos)

Lado do Sonos Plugin, aqui está minha configuração :

-   Partilha :
    -   Campo 1 : 192.168.xxx.aaa
    -   Campo 2 : Jeedom / TTS
-   Nomee de Usuário : Sonos e sua senha…

Lado da Biblioteca Sonos (aplicativo para PC)
-   o caminho é : //192.168.xxx.aaa/Jeedom / TTS

> **Important**
>
> É absolutamente necessário adicionar o compartilhamento de rede à biblioteca de sons, caso contrário, o Jeedom criará o mp3 para o tts, mas ele não poderá ser reproduzido pelo Sonos.

> **Important**
>
> O idioma depende do idioma Jeedom e usa picotts por padrão. A partir do jeedom 3.3.X será possível usar o Google TTS para ter uma voz mais bonita


# O painel

O plugin Sonos também fornece um painel que reúne todos os seus
Sonos. Disponível no menu Início → Sonos Controller :

> **Important**
>
> Para ter o painel, você precisa ativá-lo na configuração do plugin

# Faq

** Erro "Nenhum dispositivo nesta coleção" ao procurar equipamento **
>
> Este erro ocorre se a descoberta automática estiver bloqueada (roteador que bloqueia o boradcast, por exemplo). Não importa que você tenha que adicionar seus sonos manualmente, especificando o modelo e o IP.
