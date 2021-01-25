# Sonos plugin

O plugin Sonos permite que você controle o Sonos Play 1, 3, 5, Sonos Connect, Sonos Connect AMP, Sonos Playbar, Ikea Symfonisk ... Ele permitirá que você veja o estado do Sonos e execute ações ( reproduzir, pausar, próximo, anterior, volume, escolha de uma lista de reprodução).

# Configuração do plugin

A configuração é muito simples, depois de baixar o plugin, você só precisa ativá-lo e pronto. O plugin irá procurar Sonos na sua rede e criar o equipamento automaticamente. Além disso, se houver uma correspondência entre os objetos Jeedom e as salas Sonos, o Jeedom atribuirá automaticamente o Sonos às salas certas.

> **DICA**
>
> Durante a descoberta inicial, é altamente recomendável não ter sistemas de som agrupados, caso contrário, você terá erros.

Se mais tarde você adicionar um Sonos, você pode criar um dispositivo Sonos fornecendo o IP para Jeedom ou clicar em **Encontre equipamentos Sonos**.

-   **Voz** : escolha de voz durante TTS
-   **Partilha** : compartilhar nome e caminho da pasta.
-   **Nome de usuário de compartilhamento** : nome de usuário para acessar o compartilhamento.
-   **Compartilhando senha** : Compartilhando senha.
-   **Descoberta** : descobrir automaticamente os sistemas de som (não funciona em uma instalação do tipo docker em que cada sistema de som deve ser criado manualmente).

> **IMPORTANTE**
>
> Mensagens muito longas não podem ser transmitidas no TTS (o limite
> depende do provedor TTS, geralmente cerca de 100 caracteres).

# Configuração do equipamento

A configuração do equipamento Sonos pode ser acessada no menu Plugins e depois em multimídia.

Aqui você encontra toda a configuração do seu equipamento :

-   **Nome do Sonos** : nome do seu equipamento Sonos.
-   **Objeto pai** : indica o objeto pai ao qual o equipamento pertence.
-   **Ativar** : torna seu equipamento ativo.
-   **Visivél** : torna visível no painel.
-   **Modelo** : o modelo do seu Sonos (só mude se não for o correto).
-   **IP** : o IP do seu Sonos, pode ser útil se o seu Sonos alterar o IP ou se você o substituir.

Abaixo você encontra a lista de pedidos :

-   **Nome** : nome do comando.
-   **Configuração avançada (pequenas rodas dentadas)** : exibe a configuração avançada do comando (método de registro, widget etc.)).
-   **Teste** : permite testar o comando.

Como ordem, você encontrará :

-   **Reproduzir lista de reprodução** : comando de tipo de mensagem para iniciar uma lista de reprodução, basta colocar o nome da lista no título. Você pode colocar ``random`` na mensagem para embaralhar a lista de reprodução antes de jogar.
-   **Reproduzir Favoritos** :  comando de tipo de mensagem para lançar um favorito, tudo o que você precisa fazer no título é colocar o nome do favorito. Você pode colocar ``random`` na mensagem para misturar favoritos antes de ler.
-   **Tocar rádio** : comando de tipo de mensagem para lançar uma estação de rádio, tudo o que você precisa fazer é colocar o nome da rádio no título *(Atenção : isso deve estar nas rádios favoritas)*.
-   **Adicionando um alto-falante** : permite adicionar um alto-falante (um Sonos) ao alto-falante atual (para associar 2 Sonos, por exemplo). Você precisa colocar o nome da sala de sonos para adicionar no título (o campo de mensagem não é usado aqui).
-   **Remover alto-falante** : permite excluir um alto-falante (um Sonos) do alto-falante atual (para dissociar 2 Sonos, por exemplo,). Você precisa colocar o nome da parte do Sonos para excluir no título (o campo de mensagem não é usado aqui).
-   **Status aleatório** : indica se estamos no modo aleatório ou não.
-   **Aleatório** : reverter o status do modo aleatório.
-   **Repita o status** : indica se estamos no modo de repetição ou não.
-   **Repetir** : reverter o status do modo "repetir"".
-   **Imagem** : link para a imagem do álbum.
-   **Album** : nome do álbum atualmente sendo reproduzido.
-   **Artista** : nome do artista atualmente sendo reproduzido.
-   **Track** : nome da faixa atualmente sendo reproduzida.
-   **Silenciar** : mudo.
-   **Anterior** : faixa anterior.
-   **Seguinte** : próxima faixa.
-   **Leitura** : ler.
-   **Pausa** : pausar.
-   **Pare** : pare de ler.
-   **Volume** : mude o volume *(de 0 a 100)*.
-   **Volume de status** : Nível de volume.
-   **Estado** : status (pausa, leitura, transição…).
-   **Para dizer** : permite ler um texto no Sonos (consulte a parte TTS). No título, você pode definir o volume e, na mensagem, a mensagem a ser lida.

> **NOTA**
>
> Para reproduzir listas de reprodução, você pode colocar opções (na caixa de opções). Para iniciar a lista de reprodução em reprodução aleatória, você deve colocar ``random``.

# TTS

O TTS (conversão de texto em fala) para o Sonos requer o compartilhamento do Windows (Samba) na rede (imposto pelo Sonos, não há como fazer o contrário)). Então você precisa de um NAS na rede. A configuração é bastante simples você tem que colocar o nome ou o IP do NAS (cuidado para colocar o mesmo que está declarado no Sonos) e o caminho (relativo), o nome de usuário e a senha ( atenção, o usuário deve ter direitos de escrita)

> **IMPORTANTE**
>
> É absolutamente necessário colocar uma senha para que este procedimento funcione.

> **IMPORTANTE**
>
> Um subdiretório também é absolutamente necessário para que o arquivo de voz seja criado corretamente.

**Aqui está um exemplo de configuração (obrigado @masterfion) :**

Lado NAS, aqui está a minha configuração :

-   Pasta Jeedom é compartilhada.
-   O usuário do Sonos tem acesso de leitura / gravação (necessário para o Jeedom).
-   usuário convidado tem acesso somente leitura (necessário para o Sonos).

Lado do Sonos Plugin, aqui está minha configuração :

-   Partilha :
    -   Campo 1 : 192.168.xxx.aaa
    -   Campo 2 : Jeedom / TTS
-   Nome de Usuário : Sonos e sua senha…

Lado da Biblioteca Sonos (aplicativo para PC)
-   o caminho é : //192.168.xxx.aaa/Jeedom/TTS

> **IMPORTANTE**
>
> É ABSOLUTAMENTE necessário adicionar o compartilhamento de rede na biblioteca do sonos, caso contrário, o Jeedom criará o mp3 tts, mas não pode ser reproduzido pelos Sonos.

> **IMPORTANTE**
>
> O idioma depende do idioma Jeedom e usa picotts por padrão. A partir do jeedom 3.3.X será possível usar o Google TTS para ter uma voz mais bonita.


# O painel

O plugin Sonos também fornece um painel que reúne todos os seus Sonos. Disponível no menu Início → Sonos Controller :

> **IMPORTANTE**
>
> Para ter o painel você deve tê-lo ativado na configuração do plugin.

# FAQ

> **Erro ``No devices in this collection`` ao procurar por equipamento**
>
> Este erro ocorre se a descoberta automática estiver bloqueada (roteador que bloqueia o boradcast, por exemplo,). Não importa, você apenas tem que adicionar seus sistemas de som manualmente, especificando o modelo e o endereço IP.

> **Estou tendo problemas com TTS (looping), os rádios não funcionam, tenho erros, etc...**
>
> a primeira coisa a fazer no caso de um problema é desconectar todos os Sonos, conectá-los novamente e esperar que eles comecem, em seguida, reiniciar o daemon Sonos no Jeedom. Em geral, isso resolve 90% dos problemas.
