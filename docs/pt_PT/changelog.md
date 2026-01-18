# Changelog Sonos controller

>**IMPORTANTE**
>
>Como lembrete, se não houver informações sobre a atualização, isso significa que se trata apenas da atualização da documentação, tradução ou texto

- 
- 

# 26-04-2025

> Attention
> Redesenho significativo do plugin : 
>
> Requer Jeedom 4.4.8
>
> Compatível com Debian 11 e 12!
>
> Veja também [este tópico na comunidade](https://community.jeedom.com/t/erreur-you-cannot-create-a-controller-instance-from-a-speaker-that-is-not-the-coordinator-of-its-group/128862) para mais detalhes

- Reescrita quase total do plugin, o daemon foi completamente reescrito em python (em vez de PHP)
- Compatível com Debian 11 e 12!
- .
- Também é possível pedir para (re)sincronizar equipamentos, favoritos e playlists sem reiniciar o daemon a partir do painel de equipamentos.
- 
- )
- Redesenho do gerenciamento de grupos (comandos antigos serão excluídos e novos serão adicionados, consulte a documentação). É possível entrar ou sair de um grupo, controlar a reprodução do grupo a partir de qualquer dispositivo do grupo sem se preocupar com quem é o controlador. .
- Adaptation sur la fonction Text-to-Speech (TTS), **il sera nécessaire d'adapter la configuration du partage SAMBA**.
- Otimização : .
- Otimizou a exibição da capa atualmente sendo reproduzida
- Otimização na leitura de favoritos
- Ajout de la possibilité de désactiver la tuile pré-configurée : ...

- Adicionando um comando de ação **Televisão** para mudar para a entrada *Televisão* em equipamentos compatíveis
- Adicionando um comando de informações **Modo de leitura** e ação **Escolha o modo de leitura** que permite selecionar o modo de leitura entre as seguintes possibilidades: *Normal*, *Repita tudo*, *Aleatório e repita tudo*, *Aleatório sem repetição*, *Repetir música*, *Música aleatória e repetida*
- Adicionando um pedido **Status de leitura** que fornece o valor "bruto" do estado de leitura (o comando existente **Status** fornece um valor traduzido com base no idioma configurado no Jeedom)
- Adicionando comandos **Status do grupo** (indica se o equipamento está agrupado ou não) e **Nome do grupo** no caso em que o equipamento está agrupado
- Adicionando comandos **O presente**, **Partiu** E **LED de status** para verificar o indicador de status
- Adicionando um pedido **Tocar rádio mp3** reproduzir uma rádio mp3 diretamente através de uma URL (acessível na internet por exemplo)
- Adicionando comandos **Aumenta o volume** E **Diminuir o volume** a partir de 1%
- Adicionando um pedido **Transição de volume** o que é muito útil para gerenciar transições de nível de volume. 3 modos possíveis: *LINEAR*, *ALARME*, *REPRODUÇÃO AUTOMÁTICA*. Consulte a documentação para obter mais informações.
- Adicionando comandos **Status de volume**, **Volume ativado**, **Volume desligado**
- Adicionando comandos **Status de esmaecimento**, **Desaparecer**, **Desaparecer**
- Adicionando comandos **Controles de toque de status**, **Controles de toque ativados**, **Controles de toque desativados**
- Adicionando comandos **Equilíbrio** (ação/cursor) e **Status do saldo** que gerencia o saldo de acordo com um valor entre -100 (extrema esquerda) e 100 (extrema direita)
- Adicionando comandos **Sepulturas** (ação/cursor) e **Situação séria** que gerencia os graves de acordo com um valor entre -10 e 10
- Adicionando comandos **Agudos** (ação/cursor) e **Status triplo** 
- Adicionando o comando **Moda de festa** que permite agrupar todos os Sonos
- Adicionando o comando **Status do microfone** 
- Adicionando um comando de informações **Bateria** em Sonos equipados com bateria mostrando a porcentagem de carga da bateria
- Adicionando um comando de informações **Carregando** 
- Adicionando um comando de informações **Próximo alarme** em cada Sonos informando a data do próximo alarme programado neste alto-falante

# 25/04/2024

- Atualização da documentação
- )
- )
- Adicionado Sonos Beam Gen 2

# 15/01/2024

- Preparando-se para Jeedom 4.4
- Adicionado Sonos Move 2

# 24/08/2023

- Adicionado abajur Ikea Symfonisk

# 25/05/2023

- Adicionado Sonos Era

# 18/10/2022

- Atualizar lista de comandos para Jeedom v4.3
- Adicionado Sonos Ray

# 22/03/2022

- Suporte para o novo alto-falante SYMFONISK

# 01/02/2022

- Corrigido um bug no TTS

# 27/01/2022

- Otimizações da V4.2

# 14/01/2022

- 

# 27/12/2021

- 

# 10/09/2021

- Adição dos Sonos Five
- Adicionando Sonos Roam
- Adicionando Symfonisk Framework
- Atualização de volume imediata em caso de mudança pela Jeedom, obrigado @Domochip

# 24/11/2020

- Nova apresentação da lista de objetos
- Adição da tag "compatibilidade V4"

# 07/08/2020

- Suporte Sonos ARC

# 24/01/2020

- Suporte para Sonos One S22

# 01/11/2020

- Suporte para Sonos Move
- Otimização de código no caso de Sonos não conectado

# 16/12/2019

- Correção de bug se um sistema de som não puder ser alcançado

# 21/10/2017

- Melhoria na recuperação do TTS

# 15/10/2019

- Suporte para porta Sonos
- Script de instalação de dependência aprimorado

# 07/07/2019

- Melhoria do script de instalação de dependências (pode permitir corrigir em alguns casos os problemas de TTS)

# 23/09/2019

- Optimisations

# 01/09/2019

- Suporte de alto-falante para lâmpada Ikea SYMFONISK

# 12/08/2019

- Suporte para alto-falante de estante Ikea SYMFONISK

# 23/04/2019

- Suporte para sonos gen2

# 17/01/2019

- Erros corrigidos no caso de os sistemas de som serem adicionados manualmente

# 15/01/2019

**IMPORTANTE APENAS FUNCIONA COM PHP7, VEJA A PÁGINA DE SAÚDE DA JEEDOM PARA SUA VERSÃO**

- Reescrita completa do plug-in
- Suporte para a nova API Sonos
- Suporte para sistemas de som Beam e One
- 
- Otimizações globais

**IMPORTANTE**

- Apenas PHP7 compatível
- Alguns recursos tiveram que ser removidos

# 2018

- Adicionado gerenciamento dos favoritos dos sonos
- Suporte para Sonos One e Playbase
- Correção da língua com picotts
- Adicionando um comando "entrada de linha""
- 
- Carregamento otimizado de playlists
- Adição de picotts para geração de TTS local
- Correção do botão reproduzir / pausar ao atualizar o widget.
