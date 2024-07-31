# Sonos plugin

O plug-in Sonos permite controlar o Sonos Play 1, 3, 5, Sonos Connect, Sonos Connect AMP, Sonos Playbar, Ikea Symfonisk... , próximo, anterior, volume, escolha de uma playlist…).

# Configuração do plugin

A configuração é muito simples, após baixar o plugin basta ativá-lo, instalar as dependências e iniciar o daemon.
O plugin irá procurar Sonos na sua rede e criar o equipamento automaticamente. Além disso, se houver uma correspondência entre os objetos Jeedom e as salas Sonos, o Jeedom atribuirá automaticamente o Sonos às salas certas.

> **IMPORTANTE**
> Seu equipamento Sonos deve ser acessível diretamente pela máquina que hospeda o Jeedom e deve ser capaz de acessar o Jeedom em troca da porta TCP 1400.

> **DICA**
>
> Durante a descoberta inicial, é altamente recomendável não ter sistemas de som agrupados, caso contrário, você terá erros.

Se você adicionar um Sonos posteriormente, poderá clicar **Sincronizar** na página do equipamento ou reinicie o daemon.

- **Partilha**: Configure aqui o nome do host da máquina (ou seu IP), o nome do compartilhamento (sem caminho, sem '/') e o caminho para a pasta.
- **Nome de usuário de compartilhamento** : nome de usuário para acessar o compartilhamento.
- **Compartilhando senha** : Compartilhando senha.

> **IMPORTANTE**
>
> Mensagens muito longas não podem ser transmitidas no TTS (o limite
> depende do provedor TTS, geralmente cerca de 100 caracteres).

# Configuração do equipamento

A configuração do equipamento Sonos pode ser acessada no menu Plugins e depois em multimídia.

Aqui você encontra toda a configuração do seu equipamento :

- **Nome do Sonos** : nome do seu equipamento Sonos.
- **Objeto pai** : indica o objeto pai ao qual o equipamento pertence.
- **Ativar** : torna seu equipamento ativo.
- **Visivél** : torna visível no painel.

Bem como informações sobre o seu Sonos: *Modelo*, *Lançamentos*, *Número de série*, *Identificador*, *Endereço MAC* E *Endereço de IP*.

# As ordens

Os controles de informações serão atualizados quase em tempo real (normalmente no máximo alguns segundos), mas a imagem do álbum atualmente sendo reproduzido pode demorar um pouco mais para ser exibida no widget durante uma mudança de faixa, isso é perfeitamente normal e independente do plugar: ele deve recuperar a imagem de uma fonte externa (em um Sonos ou na internet) e isso às vezes leva vários segundos (em princípio no máximo cerca de dez segundos).

## Controles de volume e fonte

Esses comandos sempre controlarão o equipamento correspondente, inclusive quando ele estiver em grupo.

- **Volume** : mude o volume *(de 0 a 100)*.
- **Volume de status** : Nível de volume.
- **Silenciar** : Ativar modo mudo.
- **Sem mudo** : Desativar mudo.
- **Status mudo** : indica se estamos no modo mudo ou não.

- **Televisão** : para mudar para a entrada *Televisão* em equipamentos compatíveis
- **Entrada de áudio analógico** : para mudar para'*Entrada de áudio analógico* em equipamentos compatíveis

## Controles de reprodução

Estes comandos irão indicar e controlar a reprodução atual no equipamento ou no grupo se estiver agrupado e isso de forma transparente, você não precisa se preocupar em saber se o equipamento está agrupado ou não para utilizá-los.

- **Status** : status do leitor traduzido para o idioma configurado em Jeedom. Por exemplo: *Leitura*, *Pausa*, *Parou*.
- **Status de leitura** que fornece o valor "bruto" do status de leitura: *JOGANDO*, *PAUSED_PLAYBACK*, *PAROU*; mais adequado para cenários.
- **Leitura** : ler.
- **Pausa** : pausar.
- **Pare** : pare de ler.
- **Anterior** : faixa anterior.
- **Seguinte** : próxima faixa.
- **Status aleatório** : indica se estamos no modo aleatório ou não.
- **Aleatório** : reverter o status do modo aleatório.
- **Repita o status** : indica se estamos no modo de repetição ou não.
- **Repetir** : reverter o status do modo "repetir"".
- **Modo de leitura** dando status e comando **Escolha o modo de leitura** que permite que você escolha entre as seguintes possibilidades: *Normal*, *Repita tudo*, *Aleatório*, *Aleatório sem repetição*, *Repetir música*, *Música aleatória e repetida*. Esta ação equivale a usar os comandos **Repetir** & **Aleatório** para chegar na configuração desejada. No entanto, esta é a única maneira de mudar para o modo “Repetir música”".
- **Reproduzir lista de reprodução** : comando de tipo de mensagem para iniciar uma lista de reprodução, basta colocar o nome da lista no título.
- **Reproduzir Favoritos** :  comando de tipo de mensagem para lançar um favorito, tudo o que você precisa fazer no título é colocar o nome do favorito.
- **Tocar rádio** : comando de tipo de mensagem para lançar uma estação de rádio, tudo o que você precisa fazer é colocar o nome da rádio no título *(Atenção : isso deve estar nas rádios favoritas)*. Não funciona mais nos modelos "S2"".
- **Imagem** : link para a imagem do álbum.
- **Album** : nome do álbum atualmente sendo reproduzido.
- **Artista** : nome do artista atualmente sendo reproduzido.
- **Track** : nome da faixa atualmente sendo reproduzida.
- **Para dizer** : permite ler um texto no Sonos (consulte a parte TTS). No título, você pode definir o volume e, na mensagem, a mensagem a ser lida.

## Comandos para gerenciar grupos

Esses comandos sempre atuam no equipamento correspondente.

- **Status do grupo** : indica se o equipamento está agrupado ou não.
- **Nome do grupo** se o equipamento estiver agrupado, forneça o nome do grupo.
- **Junte-se a um grupo** : permite que você se junte ao grupo de um determinado alto-falante (um Sonos) (para associar 2 Sonos, por exemplo). Você deve inserir o nome da sala do sistema de som para ingressar. Pode ser qualquer membro de um grupo existente, não precisa ser o coordenador do grupo ou um Sonos isolado.
- **Sair do grupo** : permite que você saia do grupo.

# TTS

O TTS (conversão de texto em fala) para o Sonos requer o compartilhamento do Windows (Samba) na rede (imposto pelo Sonos, não há como fazer o contrário)). Então você precisa de um NAS na rede. A configuração é bastante simples você tem que colocar o nome ou o IP do NAS (cuidado para colocar o mesmo que está declarado no Sonos) e o caminho (relativo), o nome de usuário e a senha ( atenção, o usuário deve ter direitos de escrita)

A criação do arquivo de áudio é gerenciada pelo núcleo Jeedom: o idioma será o configurado no Jeedom e o motor TTS utilizado também poderá ser selecionado nas telas de configuração do Jeedom.

Ao usar TTS (comando **Para dizer**), o plugin executará as seguintes ações:

- geração do arquivo de áudio contendo a mensagem com suporte principal Jeedom
- gravando o arquivo no compartilhamento SAMBA
- Força a reprodução no modo “Normal”, sem repetição
- Forçar o modo “ativar som”"
- Alterando o volume para o valor escolhido ao usar o comando
- lendo mensagem
- restaurar o estado do Sonos antes da reprodução (ou seja, o modo de reprodução, mudo ou não, repetir ou não, etc.) e reiniciar o stream se o Sonos estiver reproduzindo

> **IMPORTANTE**
>
> É absolutamente necessário colocar uma senha para que este procedimento funcione.
>
> Um subdiretório também é absolutamente necessário para que o arquivo de voz seja criado corretamente.
>
> Acima de tudo, não deve haver acentos no nome do compartilhamento ou pasta, espaços ou caracteres especiais

**Aqui está um exemplo de configuração (obrigado @masterfion) :**

Lado NAS, aqui está a minha configuração :

- Pasta Jeedom é compartilhada.
- O usuário do Sonos tem acesso de leitura / gravação (necessário para o Jeedom).
- usuário convidado tem acesso somente leitura (necessário para o Sonos).

No lado do plug-in Sonos, a configuração :

- Partilha :
  - Campo 1: 192.168.xxx.aaa
  - Campo 2: Jeedom
  - Campo 3: TTS
- Nome de usuário e senha...​

Lado da Biblioteca Sonos (aplicativo para PC)

- o caminho é : //192.168.xxx.aaa/Jeedom/TTS

# O painel

O plugin Sonos também fornece um painel que reúne todos os seus Sonos. Disponível no menu Início → Sonos Controller :

> **IMPORTANTE**
>
> Para ter o painel você deve tê-lo ativado na configuração do plugin.
