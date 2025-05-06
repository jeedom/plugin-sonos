# Sonos plugin

O plug-in Sonos permite controlar o Sonos Play 1, 3, 5, Sonos Connect, Sonos Connect AMP, Sonos Playbar, Ikea Symfonisk... , próximo, anterior, volume, escolha de uma playlist…).

# Configuração do plugin

A configuração é muito simples, após baixar o plugin basta ativá-lo, instalar as dependências e iniciar o daemon.
O plugin irá procurar Sonos na sua rede e criar o equipamento automaticamente. Além disso, se houver uma correspondência entre os objetos Jeedom e as salas Sonos, o Jeedom atribuirá automaticamente o Sonos às salas certas.

> **IMPORTANTE**
> Seu equipamento Sonos deve ser acessível diretamente pela máquina que hospeda o Jeedom (transmissão / multicast possível na mesma rede) e deve ser capaz de acessar o Jeedom em troca da porta TCP 1400.

Dans le cas où vos enceintes Jeedom ne seraient pas sur le même sous-réseau que Jeedom, vous pouvez configurer celui-ci de préférence sous le format CIDR, par exemple `192.168.1.0/24`. Il devrait également être possible d'entrer directement l'ip de l'une de vos enceintes afin de découvrir les autres à partir de celle-ci mais il est recommandé de configurer le réseau complet. **Attention, ne configurer rien si vous ne maitrisez pas cette partie, testez d'abord la configuration par défaut**

Se você adicionar um Sonos posteriormente, poderá clicar **Sincronizar** na página do equipamento ou reinicie o daemon.

- **Partilha**: Configure aqui o nome do host da máquina (ou seu IP), o nome do compartilhamento (sem caminho, sem '/') e o caminho para a pasta.
- **Nome de usuário de compartilhamento**: nome de usuário para acessar o compartilhamento.
- **Compartilhando senha**: Compartilhando senha.

# Configuração do equipamento

A configuração do equipamento Sonos pode ser acessada no menu Plugins e depois em multimídia.

Aqui você encontra toda a configuração usual do seu equipamento :

- **Nome do Sonos**: nome do seu equipamento Sonos.
- **Objeto pai**: indica o objeto pai ao qual o equipamento pertence.
- **Ativar**: torna seu equipamento ativo.
- **Visivél**: torna visível no painel.

Bem como informações sobre o seu Sonos: *Modelo*, *Lançamentos*, *Número de série*, *Identificador*, *Endereço MAC* E *Endereço de IP*.

Vous avez aussi la possibilité de désastiver la tuile de l'équipement pré-configurée (option active par défaut) et dans ce cas configurer cette tuile comme vous le souhaitez en utilisant les widgets du core ou vos propres widgets, d'afficher ou masquer les commandes de votre choix...

La tuile pré-configurée ne tient pas compte du statut visible ou non des commandes, ni des options avancées d'affichage; La configuration de celle-ci ne peut pas être modifiée.

# As ordens

Os controles de informações serão atualizados quase em tempo real (normalmente no máximo alguns segundos), mas a imagem do álbum atualmente sendo reproduzido pode demorar um pouco mais para ser exibida no widget durante uma mudança de faixa, isso é perfeitamente normal e independente do plugar: ele deve recuperar a imagem de uma fonte externa (em um Sonos ou na internet) e isso às vezes leva vários segundos (em princípio no máximo cerca de dez segundos).

## Controles e controles de volume do Sonos

Esses comandos sempre controlarão o equipamento correspondente, inclusive quando ele estiver em grupo.

- **Volume**: mude o volume *(de 0 a 100)*
- **Volume de status**: nível de volume (em %)
- **Aumenta o volume**: aumenta o volume em 1%; pode ser útil para integração com outros sistemas ou plugins
- **Diminuir o volume**: diminui o volume em 1%; pode ser útil para integração com outros sistemas ou plugins
- **Transição de volume** permite realizar transições de nível de volume gerenciadas diretamente pelo alto-falante Sonos, não é o plugin que cuida disso e portanto não está bloqueando mas os atrasos não são configuráveis desde definidos pela Sonos. O tipo de transição e o volume alvo devem ser escolhidos ao executar o comando. Existem 3 modos:
  - *LINEAR*: transição linear do volume atual para o volume alvo (aumentar ou diminuir), a velocidade é 1.25 por segundo (uma transição *LINEAR* de 50% a 30% levará 16s)
  - *ALARME*: inicializa o volume em 0, pausa por cerca de 30 segundos e depois aumenta até o volume solicitado a uma velocidade de 2.5 por segundo (uma transição *ALARME* de 0% a 10% levará 4s)
  - *REPRODUÇÃO AUTOMÁTICA*: inicializa o volume para 0 e aumenta rapidamente para o volume solicitado a uma taxa de 50 por segundo (uma transição *REPRODUÇÃO AUTOMÁTICA* de 0% a 50% levará 1s)
- **Silenciar**: Ativar modo mudo.
- **Sem mudo**: Desativar mudo.
- **Status mudo**: indica se estamos no modo mudo ou não.
- **Equilíbrio** (ação/cursor) e **Status do saldo** que gerencia o equilíbrio de acordo com um valor entre -100 (extrema esquerda) e 100 (extrema direita) para Sonos compatíveis
- **Sepulturas** (ação/cursor) e **Situação séria** que gerencia os graves de acordo com um valor entre -10 e 10
- **Agudos** (ação/cursor) e **Status triplo** que gerencia os agudos de acordo com um valor entre -10 e 10
- **Status de volume**, **Volume ativado**, **Volume desligado** controla o volume

- **Televisão**: para mudar para a entrada *Televisão* em equipamentos compatíveis
- **Entrada de áudio analógico**: para mudar para'*Entrada de áudio analógico* (*Entrada de linha*) em equipamentos compatíveis
- **O presente** E **Partiu**: Ativa e desativa o LED, a luz de status
- **LED de status**: indica se a luz de status está acesa ou não. Esta informação só é atualizada uma vez por minuto caso seja modificada fora do Jeedom
- **Controles de toque ativados** E **Controles de toque desativados** Ativa e desativa botões físicos ou de toque no Sonos
- **Controles de toque de status** indica se os controles de toque estão ativados ou não
- **Status do microfone** que indica se o microfone está ativado ou não em Sonos equipados com microfone
- **Bateria** em Sonos equipados com bateria mostrando a porcentagem de carga da bateria
- **Carregando** em Sonos equipados com bateria indicando se o carregamento está ou não em andamento

## Controles de reprodução

Estes comandos irão indicar e controlar a reprodução atual no equipamento ou no grupo se estiver agrupado e isso de forma transparente, você não precisa se preocupar em saber se o equipamento está agrupado ou não para utilizá-los.

- **Estado**: status do leitor traduzido para o idioma configurado em Jeedom. Por exemplo: *Leitura*, *Pausa*, *Parou*.
- **Status de leitura** que fornece o valor "bruto" do status de leitura: *JOGANDO*, *PAUSED_PLAYBACK*, *PAROU*; mais adequado para cenários.
- **Leitura**: ler.
- **Pausa**: pausar.
- **Pare**: pare de ler.
- **Anterior**: faixa anterior.
- **Seguinte**: próxima faixa.
- **Status aleatório**: indica se estamos no modo aleatório ou não.
- **Aleatório**: reverter o status do modo aleatório.
- **Repita o status**: indica se estamos no modo de repetição ou não.
- **Repetir**: reverter o status do modo "repetir"".
- **Status de esmaecimento**, **Desaparecer**, **Desaparecer** controlar e ativar ou não o *Crossfade*
- **Escolha o modo de leitura** permite que você escolha entre as seguintes possibilidades:
  - *Normal* (repetir, aleatório),
  - *Repita tudo* (aleatório),
  - *Aleatório e repita tudo*,
  - *Aleatório sem repetição*,
  - *Repetir música* (aleatório),
  - *Música aleatória e repetida*.

  Eu recomendo usar este comando em um cenário em vez de **Repetir** & **Aleatório** para chegar à configuração desejada mesmo que todos atuem nos mesmos parâmetros. Este comando é, no entanto, a única maneira de mudar para o modo *Repetir música* Ou *Música aleatória e repetida*.
- **Modo de leitura** dando o estado atual que será um dos valores citados acima.
- **Reproduzir lista de reprodução**: comando de tipo de mensagem para iniciar uma lista de reprodução, basta colocar o nome da lista no título. Em um cenário, uma lista de possibilidades será exibida automaticamente quando você começar a digitar.
- **Reproduzir Favoritos**:  comando de tipo de mensagem para lançar um favorito, tudo o que você precisa fazer no título é colocar o nome do favorito. Em um cenário, uma lista de possibilidades será exibida automaticamente quando você começar a digitar.
- **Tocar rádio**: comando de tipo de mensagem para lançar uma estação de rádio, tudo o que você precisa fazer é colocar o nome da rádio no título *(Atenção : isso deve estar nas rádios favoritas)*. Em um cenário, uma lista de possibilidades será exibida automaticamente quando você começar a digitar. Não funciona mais nos modelos "S2", é normal ter uma lista vazia em todos os modelos usando o aplicativo Sonos S2.
- **Tocar rádio mp3**: permite-lhe reproduzir uma rádio mp3 através de um URL (por exemplo da internet). Você deve colocar um título na caixa *Título* e o URL (formato http(s))://...mp3) na área *Mensagem*.
- **Imagem**: link para a imagem do álbum.
- **Album**: nome do álbum atualmente sendo reproduzido.
- **Artista**: nome do artista atualmente sendo reproduzido.
- **Track**: nome da faixa atualmente sendo reproduzida.
- **Para dizer**: permite ler um texto no Sonos (consulte a parte TTS). No título, você pode definir o volume e, na mensagem, a mensagem a ser lida.

> **Dica**
> As listas de reprodução e os favoritos devem ser criados através do aplicativo Sonos (no celular ou computador) e depois a sincronização deve ser feita para atualizar o equipamento e poder utilizá-lo em um cenário.

## Comandos para gerenciar grupos

Esses comandos sempre atuam no equipamento correspondente.

- **Status do grupo**: indica se o equipamento está agrupado ou não.
- **Nome do grupo** se o equipamento estiver agrupado, forneça o nome do grupo.
- **Junte-se a um grupo**: permite que você se junte ao grupo de um determinado alto-falante (um Sonos) (para associar 2 Sonos, por exemplo). Você deve inserir o nome da sala do sistema de som para ingressar. Pode ser qualquer membro de um grupo existente, não precisa ser o coordenador do grupo ou um Sonos isolado. Em um cenário, uma lista de possibilidades será exibida automaticamente quando você começar a digitar.
- **Sair do grupo**: permite que você saia do grupo.
- **Moda de festa** permite agrupar todos os Sonos

# TTS

TTS (texto para fala) para Sonos requer compartilhamento SAMBA na rede (imposto pela Sonos, não há como fazer de outra forma). Portanto, você precisa de um NAS ou equivalente na rede. A configuração é bastante simples, você deve inserir o nome ou IP do NAS (cuidado para colocar o mesmo que está declarado no Sonos) e o caminho para a pasta que deve conter os arquivos de áudio bem como o nome do usuário e senha (observe que o usuário deve ter direitos de gravação)

A criação do arquivo de áudio é gerenciada pelo núcleo Jeedom: o idioma será aquele configurado no Jeedom e o motor TTS utilizado também pode ser selecionado na configuração do Jeedom.

Ao usar TTS (comando **Para dizer**), o plugin executará as seguintes ações:

- geração do arquivo de áudio contendo a mensagem com suporte principal Jeedom
- gravando o arquivo no compartilhamento SAMBA
- forçar a reprodução no modo “Normal”, sem repetição
- forçar o modo “ativar som” (apenas no equipamento, não em todo o grupo)
- Alterar o volume para o valor escolhido ao utilizar o comando (apenas no equipamento, não em todo o grupo)
- lendo mensagem
- restaurar o estado do Sonos antes da reprodução (ou seja, o modo de reprodução, mudo ou não, repetir ou não, etc.) e reiniciar o stream se o Sonos estiver reproduzindo

> **IMPORTANTE**
>
> É absolutamente necessário colocar uma senha para que este procedimento funcione.
>
> Um subdiretório também é absolutamente necessário para que o arquivo de voz seja criado corretamente.
>
> Acima de tudo, não deve haver acentos no nome do compartilhamento ou pasta, espaços ou caracteres especiais.
>
> Mensagens muito longas não podem ser transmitidas em TTS (o limite depende do provedor de TTS, geralmente em torno de 100 caracteres).

## Exemplo de configuração

No lado NAS, a seguinte configuração deve ser realizada:

- a pasta *Jeedom* é compartilhado e contém uma pasta *TTS*
- o usuário *Jeedom* tem acesso de leitura/gravação (necessário para Jeedom).
- o usuário *seu osso* tem acesso somente leitura (necessário para Sonos).

No lado do plug-in Sonos, a configuração :

- Partilha :
  - Campo 1: 192.168.xxx.aaa
  - Campo 2: *Jeedom*
  - Campo 3: *TTS*
- Nome de usuário (*Jeedom* no exemplo) e sua senha…​

Lado da Biblioteca Sonos (aplicativo para PC)

- o caminho é : //192.168.xxx.aaa/Jeedom/TTS
- o usuário será *seu osso* (neste exemplo) + senha

# O painel

O plugin Sonos também fornece um painel que reúne todos os seus Sonos. Disponível no menu Início → Sonos Controller :

> **IMPORTANTE**
>
> Para ter o painel você deve tê-lo ativado na configuração do plugin.
