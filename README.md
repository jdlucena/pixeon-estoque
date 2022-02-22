# pixeon-estoque
 Exibe o estoque atual e outros indicadores

Esse sistema tem como objetivo auxiliar os responsáveis pelo estoque de seus respectivos setores, exibindo de forma direta todos os materiais cadastrados em seus respectivos grupos.

O sistema utiliza PHP, JavaScript e T-SQL, conectado ao banco de dados local do sistema Smart da [PIXEON](https://www.pixeon.com/).

![tela](assets/images/tela.png)

### Configuração

É necessário alterar o arquivo `conn/dp.php` com as configurações de acesso ao seu banco SQL Server.

### Colunas

| Coluna | Descrição |
| --- | --- |
| Código | Código do material. |
| Descrição | Descrição do material. |
| Curva | Curva ABC, usado para classificar conforme o seu grau de importância. |
| Estoque |	Quantidade atual em estoque. |
| CMM |	Consumo médio mensal nos últimos 90 dias. |
| 7D | Consumo nos últimos 7 dias. |
| Status | Exibe 3 níveis de consumo intenso nos últimos dias. |
| Suficiência |	Quantidade de dias para o estoque acabar. |
| Meses | Consumo dos últimos 3 meses separadamente. |
| AF | Consulta AF autorizada com informações referente ao pedido. |

## Status

O emoji 🔥 tem como objetivo sinalizar ao usuário que está havendo um consumo intenso recente, onde:

 - 🔥 - consumo intenso
 - 🔥🔥 - consumo muito intenso
 - 🔥🔥🔥 - consumo bastante intenso

## ENJOY IT!