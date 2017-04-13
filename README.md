# PhpTestBed v0.2.0

A técnica do teste de mesa ajuda programadores de qualquer nível a compreender
melhor a lógica empregada ao verificar o fluxo e mudanças de estados de varíavel
no decorrer da execução de um programa.

Esta biblioteca pretende realizar leitura de scripts PHP a fim de executar o
teste de mesa informando ao usuário cada passo que será tomado pelo processador
de script, do início até a conclusão da execução do script.

Ainda estamos em desenvolvimento inicial, por isso a quantidade de recursos da
linguagem que são suportados são poucos.

## Exemplo

Script de teste:
```php
<?php

for($i = 1; $i <= 2; $i++) {
    echo $i + 1;
}
```

Saída:
```
Script iniciado
Linha 3: --- Entrou no laço FOR ---
Linha 3: A variável $i recebe o valor 1
Linha 3: A condição da estrutura retorna o valor true resultante da operação ($i <= 2) onde $i = 1
Linha 4: Imprime na tela o valor 2 resultante da operação ($i + 1) onde $i = 1
Linha 3: A variável $i tem seu valor incrementado para 2
Linha 3: A condição da estrutura retorna o valor true resultante da operação ($i <= 2) onde $i = 2
Linha 4: Imprime na tela o valor 3 resultante da operação ($i + 1) onde $i = 2
Linha 3: A variável $i tem seu valor incrementado para 3
Linha 3: A condição da estrutura retorna o valor false resultante da operação ($i <= 2) onde $i = 3
Linha 5: --- Saiu do laço FOR ---
Script finalizado
```

## Comandos suportados

<table>
    <tr>
        <td><b>Comando</b></td>
        <td><b>Incluso na versão</b></td>
    </tr>
	<tr>
    	<td colspan="2">
    		<b>Estruturas condicionais</b>
    	</td>
    </tr>
	<tr>
    	<td>If-Else</td>
    	<td>v0.1.0</td>
	</tr>
        <tr>
    	<td>Switch-Case-Default</td>
    	<td>v0.2.0</td>
	</tr>
  	<tr>
    <td colspan="2"><b>Laços de repetição</b></td>
  	</tr>
    <tr>
    <td>For</td>
    <td>v0.1.0</td>
    </tr>
    <tr>    
    <td>Foreach</td>
    <td>v0.2.0</td>
    </tr>
    <tr>
    <td>While</td>
    <td>v0.1.0</td>
    </tr>
    <tr>
    <td>Do-While</td>
    <td>v0.1.0</td>
    </tr>
    <tr>
    <td colspan="2"><b>Operações aritméticas</b></td>
  	</tr>
    <tr>
    <td>Soma (`+`)</td>
    <td>v0.1.0</td>
    </tr>
    <tr>
    <td>Subtração (`-`)</td>
    <td>v0.1.0</td>
    </tr>
    <tr>
    <td>Multiplicação (`*`)</td>
    <td>v0.1.0</td>
    </tr>
    <tr>
    <td>Divisão (`/`)</td>
    <td>v0.1.0</td>
    </tr>
    <tr>
    <td>Resto da divisão (`%`)</td>
    <td>v0.1.0</td>
    </tr>
    <tr>
    <td>Deslocamento binário (`&lt;&lt;` e `&gt;&gt;`)</td>
    <td>v0.1.0</td>
    </tr>
    <tr>
    <td>Incrementação (`++`)</td>
    <td>v0.1.0 (posterior)<br>v0.2.0(anterior)</td>
    </tr>
    <tr>
    <td>Decrementação (`--`)</td>
    <td>v0.2.0</td>
    </tr>
    <tr>
    <td colspan="2"><b>Operações lógicas</b></td>
    </tr>
    <tr>
    <td>Booleano E (`&amp;&amp;`)</td>
    <td>v0.1.0</td>
    </tr>
    <tr>
    <td>Booleano OU (`||`)</td>
    <td>v0.1.0</td>
    </tr>
    <tr>
    <td>Bitwise E (`&amp;`)</td>
    <td>v0.1.0</td>
    </tr>
    <tr>
    <td>Bitwise OU (`|`)</td>
    <td>v0.1.0</td>
    </tr>
    <tr>
    <td>Bitwise OU EXCLUSIVO (`^`)</td>
    <td>v0.1.0</td>
    </tr>
    <tr>
    <td colspan="2"><b>Operações relacionais</b></td>
    </tr>
    <tr>	
    <td>Igual (`==`)</td>
    <td>v0.1.0</td>
    </tr>
    <tr>	
    <td>Idêntico (`===`)</td>
    <td>v0.1.0</td>
    </tr>
    <tr>	
    <td>Diferente (`!=`)</td>
    <td>v0.1.0</td>
    </tr>
    <tr>	
    <td>Não idêntico (`!==`)</td>
    <td>v0.1.0</td>
    </tr>
    <tr>	
    <td>Maior (`&gt;`)</td>
    <td>v0.1.0</td>
    </tr>
    <tr>	
    <td>Maior ou igual (`&gt;=`)</td>
    <td>v0.1.0</td>
    </tr>
    <tr>	
    <td>Menor (`&lt;`)</td>
    <td>v0.1.0</td>
    </tr>
    <tr>	
    <td>Menor ou igual (`&lt;=`)</td>
    <td>v0.1.0</td>
    </tr>
    <tr>	
    <td>Nave espacial (`&lt;=&gt;`)</td>
    <td>v0.1.0</td>
    </tr>	
    <tr>
    <td colspan="2"><b>Outros</b></td>
    </tr>
    <tr>	
    <td>Echo</td>
    <td>v0.1.0</td>
    </tr>
    <tr>
    <td>Atribuição (`=`)</td>
    <td>v0.1.0</td>
    </tr>
    <tr>
    <td>Concatenação (`.`)</td>
    <td>v0.1.0</td>
    </tr>
    <tr>
    <td>Global</td>
    <td>v0.2.0</td>
    </tr>
    <tr>
    <td>Parada (`break`)</td>
    <td>v0.2.0</td>
    </tr>
    <tr>
    <td>Constantes</td>
    <td>v0.2.0 (`const`)<br>em breve (`define`)</td>
    </tr>
    <tr>
    <td>Vetores e Matrizes (`array()` e `[]`)</td>
    <td>v0.2.0</td>
    </tr>
    <tr>
    <td>Try-Catch-Finally</td>
    <td>v0.2.0</td>
    </tr>
    <tr>
    <td>Throw</td>
    <td>v0.2.0</td>
    </tr>
    </table>


## Contribua!

Ajude-nos a fazer esse software dar certo. Veja em milestones que funções essa
biblioteca ainda precisa implementar. Ou então revise o que já existe para
continuar melhorando a qualidade e desempenho.

[![Licença Creative Commons](https://i.creativecommons.org/l/by/4.0/88x31.png)](blob/master/LICENSE.md)
Este trabalho está licenciado com uma Licença [Creative Commons - Atribuição  4.0 Internacional](blob/master/LICENSE.md)