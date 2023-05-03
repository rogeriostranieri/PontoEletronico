<!DOCTYPE html>
	<html>
  <a href="cadastro.html" class="button">Ir para Cadastro</a>

  <head>
    <meta charset="UTF-8">
    <title>Folha Ponto - criado por Rogerio Stranieri</title>
     <!-- vincular o arquivo CSS aqui -->
     <link rel="stylesheet" href="CSS/FolhaPontoCSS.css">
     <link rel="stylesheet" href="CSS/Cookie.css">
  
  
		<body>      
			<h1>Folha de Ponto</h1>
      <h1>
      <p>Empresa: <span id="empresa"></span></p>
      <p>Funcionário: <span id="funcionario"></span></p>
        <label for="hora-padrao">Horario total de trabalho:</label>
        <input type="time" id="hora-padrao" name="hora-padrao" step="1">
        <label for="data-inicio">Início da folha:</label>
        <input type="date" id="data-inicio" name="data-inicio">
        <label for="data-fim">Fim da folha:</label>
        <input type="date" id="data-fim" name="data-fim">

        <p>Semana: <span id="diasTrabalho"></span></p>
        <p>Folga: <span id="diasFolga"></span></p>
      
        <!-- Scripts recuperação do cadastro cookies -->
       <script>
            var Empresa = document.getElementById("empresa");
            var Funcionario = document.getElementById("funcionario");
            
            var nomeempresa = localStorage.getItem("nomeempresa");
            var funcionario = localStorage.getItem("funcionario");         
           
            var horaPadrao = document.getElementById("hora-padrao");
            var dataInicio = document.getElementById("data-inicio");
            var dataFim = document.getElementById("data-fim");
           
            var totalhouras = localStorage.getItem("horatrabalhada");
            var iniciodafolha = localStorage.getItem("iniciodafolha");
            var fimdafolha = localStorage.getItem("fimdafolha");
           
            var diasTrabalho = document.getElementById("diasTrabalho");
            var diasFolga = document.getElementById("diasFolga");
            
            var diasTrabalho = localStorage.getItem("diasTrabalho");
            var diasFolga = localStorage.getItem("diasFolga");

            Empresa.textContent = nomeempresa;
            Funcionario.textContent = funcionario;
            horaPadrao.value = totalhouras;
            dataInicio.value = iniciodafolha;
            dataFim.value = fimdafolha;
            
            diasTrabalho.value = diasTrabalho;
            diasFolga.value = diasFolga;
       </script>

      <!-- CONTINUA --> 
      <button type="button" onclick="AtualizarDatas()">Atualizar</button>
      </h1>
      <h1><button type="button" onclick="calcularTudo()">Calcular Tudo</button>
      <button type="button" onclick="FinalizaFolha()">Finaliza Folha</button><h1>
			
      <table>
			<thead>
				<tr>
        <th style="text-align: center;">Data</th>
        <th style="text-align: center;">Dia da Semana</th>
        <th style="text-align: center;">Feriado</th>
				<th style="text-align: center;">Entrada e Saída 1º Turno</th>
				<th style="text-align: center;">Entrada e Saída 2º Turno</th>
				<th style="text-align: center;">Total de Horas</th>
        <th style="text-align: center;">Horas Extras</th>
        <th style="text-align: center;">Horas Falta</th>
				<th style="text-align: center;">Adicional Noturno</th>
				</tr>
			</thead>
			<tbody>
				<!-- Loop para criar 31 linhas na tabela -->
				<?php for ($i = 1; $i <= 31; $i++) { ?>
				<tr>
        <td><input type="text" name="data<?php echo $i ?>" size="10"></td>
        <td><input type="text" name="dia_da_semana<?php echo $i ?>" size="15"></td>
        <td>
    <select name="menu_suspenso<?php echo $i ?>">
    <option value="trabalhado">Trabalhado</option>
    <option value="meio_periodo">Meio Período</option>
    <option value="descanso_semanal">Descanso Semanal</option>
    <option value="abonado">Abonado</option>
    <option value="feriado">Feriado</option>
    <option value="atestado">Atestado I</option>
    <option value="atestado_pacial">Atestado P</option>
    <option value="ferias">Férias</option>
     </select>
        </td>
				<td style="text-align: center;"><input type="time" name="entrada1_<?php echo $i ?>" required> - <input type="time" name="saida1_<?php echo $i ?>" required></td>
				<td style="text-align: center;"><input type="time" name="entrada2_<?php echo $i ?>" required> - <input type="time" name="saida2_<?php echo $i ?>" required></td>
        <td style="text-align: center;"><input type="text" name="total_de_horas<?php echo $i ?>" size="8" required></td>
        <td style="text-align: center;"><input type="text" name="horas_extra<?php echo $i ?>" size="8" readonly></td>
        <td style="text-align: center;"><input type="text" name="horas_falta<?php echo $i ?>" size="8" readonly></td>
				<td style="text-align: center;"><input type="text" name="adicional_noturno<?php echo $i ?>" size="8" readonly></td>
				</tr>
				<?php } ?>
			</tbody>
			</table>

       <!-- Banner de cookies -->
        <div id="cookie-banner">
            <p>Este site usa cookies para melhorar sua experiência. Ao continuar navegando, você concorda com o uso de cookies.</p>
            <button id="accept-cookies">Aceitar cookies</button>
        </div>
        <!-- Scripts cookies-->
        <script src="js/cookies.js"></script>
		</body>
  </head>


<script> //////////////////////// INICIO CODIGOS  /////////////////////////////

function calcularAdicionalNoturno() {
  // Obter todos os elementos de entrada para o primeiro turno
  let entrada1 = document.querySelectorAll('[name^="entrada1_"]');
  let saida1 = document.querySelectorAll('[name^="saida1_"]');
  let entrada2 = document.querySelectorAll('[name^="entrada2_"]');
  let saida2 = document.querySelectorAll('[name^="saida2_"]');
  let adicionalNoturno = document.querySelectorAll('[name^="adicional_noturno"]');

  // Loop através de cada linha da tabela
  for (let i = 0; i < entrada1.length; i++) {
    // Obter o valor da entrada e saída do primeiro turno
    let entrada1Value = entrada1[i].value;
    let saida1Value = saida1[i].value;
    let entrada2Value = entrada2[i].value;
    let saida2Value = saida2[i].value;

    // Inicializar as variáveis de horas noturnas trabalhadas
    let horasNoturnas1 = 0;
    let horasNoturnas2 = 0;

    // Verificar se a entrada e saída do primeiro turno foram preenchidas
    if (entrada1Value && saida1Value) {
      // Converter a entrada e saída para objetos Date
      let dataEntrada1 = new Date('1970-01-01T' + entrada1Value + 'Z');
      let dataSaida1 = new Date('1970-01-01T' + saida1Value + 'Z');

      // Verificar se a saída é antes da entrada (ou seja, o turno passou da meia-noite)
      if (dataSaida1 < dataEntrada1) {
        // Adicionar um dia à data de saída
        dataSaida1.setDate(dataSaida1.getDate() + 1);
      }

      // Calcular o número de minutos trabalhados no primeiro turno
      let minutosTrabalhados1 = 0;

      // Loop através de cada minuto trabalhado
      for (let dataAtual = new Date(dataEntrada1); dataAtual < dataSaida1; dataAtual.setMinutes(dataAtual.getMinutes() + 1)) {
        // Obter a hora atual
        let horaAtual = dataAtual.getUTCHours();

        // Verificar se a hora atual está dentro do horário noturno
        if (horaAtual >= 22 || horaAtual < 5) {
          // Adicionar um minuto ao contador de minutos trabalhados
          minutosTrabalhados1++;
        }
      }

      // Calcular o número de horas noturnas trabalhadas no primeiro turno
      horasNoturnas1 = minutosTrabalhados1 / 52.5;
    }

    // Verificar se a entrada e saída do segundo turno foram preenchidas
    if (entrada2Value && saida2Value) {
      // Converter a entrada e saída para objetos Date
      let dataEntrada2 = new Date('1970-01-01T' + entrada2Value + 'Z');
      let dataSaida2 = new Date('1970-01-01T' + saida2Value + 'Z');

      // Verificar se a saída é antes da entrada (ou seja, o turno passou da meia-noite)
      if (dataSaida2 < dataEntrada2) {
        // Adicionar um dia à data de saída
        dataSaida2.setDate(dataSaida2.getDate() + 1);
      }

      // Calcular o número de minutos trabalhados no segundo turno
      let minutosTrabalhados2 = 0;

      // Loop através de cada minuto trabalhado
      for (let dataAtual = new Date(dataEntrada2); dataAtual < dataSaida2; dataAtual.setMinutes(dataAtual.getMinutes() + 1)) {
        // Obter a hora atual
        let horaAtual = dataAtual.getUTCHours();

        // Verificar se a hora atual está dentro do horário noturno
        if (horaAtual >= 22 || horaAtual < 5) {
          // Adicionar um minuto ao contador de minutos trabalhados
          minutosTrabalhados2++;
        }
      }

      // Calcular o número de horas noturnas trabalhadas no segundo turno
      horasNoturnas2 = minutosTrabalhados2 / 52.5;
    }

    // Calcular o número total de horas noturnas trabalhadas
    let horasNoturnas = horasNoturnas1 + horasNoturnas2;

    // Converter o número de horas noturnas para o formato de horas:minutos:segundos
    let horas = Math.floor(horasNoturnas);
    let minutos = Math.floor((horasNoturnas - horas) * 60);
    let segundos = Math.round((((horasNoturnas - horas) * 60) - minutos) * 60);

    // Formatar o resultado como uma string no formato hh:mm:ss
    let resultado = horas.toString().padStart(2, '0') + ':' +
                    minutos.toString().padStart(2, '0') + ':' +
                    segundos.toString().padStart(2, '0');

    // Exibir o resultado no campo adicional noturno da linha atual
    adicionalNoturno[i].value = resultado;
  }
}
////////////////////FIM DO ADICIONAL //////////////////////////

//////////Total hora ///////////

function calcularHorasTrabalhadas() {
  // Obtém o número de linhas
  var numLinhas = document.querySelectorAll('[name^="total_de_horas"]').length;

  // Percorre todas as linhas
  for (var i = 1; i <= numLinhas; i++) {
    // Obtém os valores dos campos de entrada e saída da linha atual
    var entrada1 = document.querySelector('[name="entrada1_' + i + '"]').value;
    var saida1 = document.querySelector('[name="saida1_' + i + '"]').value;
    var entrada2 = document.querySelector('[name="entrada2_' + i + '"]').value;
    var saida2 = document.querySelector('[name="saida2_' + i + '"]').value;

    // Inicializa as variáveis de intervalo
    var intervalo1 = 0;
    var intervalo2 = 0;

    // Verifica se os campos "entrada1" e "saida1" não estão vazios
    if (entrada1 && saida1) {
      // Converte os valores para objetos Date
      entrada1 = new Date('1970-01-01T' + entrada1 + 'Z');
      saida1 = new Date('1970-01-01T' + saida1 + 'Z');

      // Verifica se a hora de saída é menor que a hora de entrada
      if (saida1 < entrada1) {
        // Adiciona um dia à hora de saída
        saida1.setDate(saida1.getDate() + 1);
      }

      // Calcula a diferença entre as horas de entrada e saída em milissegundos
      intervalo1 = saida1 - entrada1;
    }

    // Verifica se os campos "entrada2" e "saida2" não estão vazios
    if (entrada2 && saida2) {
      // Converte os valores para objetos Date
      entrada2 = new Date('1970-01-01T' + entrada2 + 'Z');
      saida2 = new Date('1970-01-01T' + saida2 + 'Z');

      // Verifica se a hora de saída é menor que a hora de entrada
      if (saida2 < entrada2) {
        // Adiciona um dia à hora de saída
        saida2.setDate(saida2.getDate() + 1);
      }

      // Calcula a diferença entre as horas de entrada e saída em milissegundos
      intervalo2 = saida2 - entrada2;
    }

    // Converte a diferença em milissegundos para segundos
    var segundosTrabalhados = (intervalo1 + intervalo2) / 1000;

    // Calcula o número de horas, minutos e segundos trabalhados
    var horasTrabalhadas = Math.floor(segundosTrabalhados / 3600);
    segundosTrabalhados %= 3600;
    var minutosTrabalhados = Math.floor(segundosTrabalhados / 60);
    var segundosTrabalhados = segundosTrabalhados % 60;

    // Formata o resultado como uma string no formato "HH:MM:SS"
    var resultado = [
      horasTrabalhadas.toString().padStart(2, '0'),
      minutosTrabalhados.toString().padStart(2, '0'),
      segundosTrabalhados.toString().padStart(2, '0')
    ].join(':');

    // Exibe o resultado no campo "total_de_horas" da linha atual
    document.querySelector('[name="total_de_horas' + i + '"]').value = resultado;
  }
}

////////// FIM CODIGO TOTAL HORAS ////////////////////////
////////// Inicio  HORAS EXTRAS 
function calcularHorasExtras() {
    // Obtém a hora padrão máxima de trabalho
    var horaPadrao = document.getElementById("hora-padrao").value;
    // Converte a hora padrão para minutos
    var horaPadraoMinutos = parseInt(horaPadrao.split(":")[0]) * 60 + parseInt(horaPadrao.split(":")[1]) + parseInt(horaPadrao.split(":")[2]) / 60;
    
    // Obtém todos os elementos com o nome começando com "total_de_horas"
    var totalDeHorasElements = document.querySelectorAll('[name^="total_de_horas"]');
    
    // Itera sobre cada elemento de total de horas
    for (var i = 0; i < totalDeHorasElements.length; i++) {
        // Obtém o valor do elemento atual
        var totalDeHoras = totalDeHorasElements[i].value;
        // Converte o total de horas para minutos
        var totalDeHorasMinutos = parseInt(totalDeHoras.split(":")[0]) * 60 + parseInt(totalDeHoras.split(":")[1]) + parseInt(totalDeHoras.split(":")[2]) / 60;
        
        // Calcula a diferença entre o total de horas e a hora padrão
        var diferencaMinutos = totalDeHorasMinutos - horaPadraoMinutos;
        
        // Se a diferença for positiva, há horas extras
        if (diferencaMinutos > 0) {
            // Converte a diferença em minutos para o formato de hora
            var horas = Math.floor(diferencaMinutos / 60);
            var minutos = Math.floor(diferencaMinutos % 60);
            var segundos = Math.round((diferencaMinutos % 1) * 60);
            var horasExtras = (horas < 10 ? "0" + horas : horas) + ":" + (minutos < 10 ? "0" + minutos : minutos) + ":" + (segundos < 10 ? "0" + segundos : segundos);
            
            // Define o valor do campo de horas extras correspondente
            document.getElementsByName("horas_extra" + (i + 1))[0].value = horasExtras;
        } else {
            // Se não houver horas extras, define o valor do campo como vazio
            document.getElementsByName("horas_extra" + (i + 1))[0].value = "";
        }
    }
}
//////////////////////// FINAL HORAS EXTRA  /////////////////////////////
//////////////////////// Inicio Horas falta
function calcularHorasFaltantes() {
    // Obtém a hora padrão máxima de trabalho
    var horaPadrao = document.getElementById("hora-padrao").value;
    // Converte a hora padrão para minutos
    var horaPadraoMinutos = parseInt(horaPadrao.split(":")[0]) * 60 + parseInt(horaPadrao.split(":")[1]) + parseInt(horaPadrao.split(":")[2]) / 60;
    
    // Obtém todos os elementos com o nome começando com "total_de_horas"
    var totalDeHorasElements = document.querySelectorAll('[name^="total_de_horas"]');
    
    // Itera sobre cada elemento de total de horas
    for (var i = 0; i < totalDeHorasElements.length; i++) {
        // Obtém o valor do elemento atual
        var totalDeHoras = totalDeHorasElements[i].value;
        // Converte o total de horas para minutos
        var totalDeHorasMinutos = parseInt(totalDeHoras.split(":")[0]) * 60 + parseInt(totalDeHoras.split(":")[1]) + parseInt(totalDeHoras.split(":")[2]) / 60;
        
        // Calcula a diferença entre a hora padrão e o total de horas
        var diferencaMinutos = horaPadraoMinutos - totalDeHorasMinutos;
        
        // Se a diferença for positiva, há horas faltantes
        if (diferencaMinutos > 0) {
            // Converte a diferença em minutos para o formato de hora
            var horas = Math.floor(diferencaMinutos / 60);
            var minutos = Math.floor(diferencaMinutos % 60);
            var segundos = Math.round((diferencaMinutos % 1) * 60);
            var horasFaltantes = (horas < 10 ? "0" + horas : horas) + ":" + (minutos < 10 ? "0" + minutos : minutos) + ":" + (segundos < 10 ? "0" + segundos : segundos);
            
            // Define o valor do campo de horas faltantes correspondente
            document.getElementsByName("horas_falta" + (i + 1))[0].value = horasFaltantes;
        } else {
            // Se não houver horas faltantes, define o valor do campo como vazio
            document.getElementsByName("horas_falta" + (i + 1))[0].value = "";
        }
    }
}

//////////////////////// FINAL CODIGOS  /////////////////////////////

function calcularTudo() {
    calcularHorasTrabalhadas();
  calcularHorasExtras();
  calcularHorasFaltantes();
  calcularAdicionalNoturno();
}

////////////////////////// Atualiza datas 
function AtualizarDatas() {
  // Obter as datas selecionadas nos campos data-inicio e data-fim
  var dataInicio = document.getElementById('data-inicio').valueAsDate;
  var dataFim = document.getElementById('data-fim').valueAsDate;

  // Calcular o número de dias entre as datas de início e fim
  var dias = Math.round((dataFim - dataInicio) / (1000 * 60 * 60 * 24)) + 1;

  // Adicionar ou remover linhas da tabela conforme necessário
  var tabela = document.querySelector('table tbody');
  var linhas = tabela.querySelectorAll('tr');
  if (linhas.length < dias) {
    // Adicionar linhas
    for (var i = linhas.length + 1; i <= dias; i++) {
      var tr = document.createElement('tr');
      tr.innerHTML = '<td><input type="text" name="data' + i + '" size="10"></td>' +
                     '<td><input type="text" name="dia_da_semana' + i + '" size="15"></td>' +
                     '<td><select name="menu_suspenso' + i + '">' +
                     '<option value="trabalhado">Trabalhado</option>' +
    '<option value="meio_periodo">Meio Período</option>' +
    '<option value="descanso_semanal">Descanso Semanal</option>' +
    '<option value="abonado">Abonado</option>' +
    '<option value="feriado">Feriado</option>' +
    '<option value="atestado">Atestado I</option>' +
    '<option value="atestado_pacial">Atestado P</option>' +
    '<option value="ferias">Férias</option>' +
                   '</select></td>' +
                     '<td><input type="time" name="entrada1_' + i + '"> - <input type="time" name="saida1_' + i + '"></td>' +
                     '<td><input type="time" name="entrada2_' + i + '"> - <input type="time" name="saida2_' + i + '"></td>' +
                     '<td><input type="text" name="total_de_horas' + i + '" size="8"></td>' +
                     '<td><input type="text" name="horas_extra' + i + '" size="8"></td>' +
                     '<td><input type="text" name="horas_falta' + i + '" size="8"></td>' +
                     '<td><input type="text" name="adicional_noturno' + i + '" size="8"></td>';
      tabela.appendChild(tr);
    }
  } else if (linhas.length > dias) {
    // Remover linhas
    for (var i = linhas.length; i > dias; i--) {
      tabela.removeChild(linhas[i - 1]);
    }
  }

  // Atualizar as datas e dias da semana na tabela
  for (var i = 1; i <= dias; i++) {
    var data = new Date(Date.UTC(dataInicio.getUTCFullYear(), dataInicio.getUTCMonth(), dataInicio.getUTCDate()));
    data.setUTCDate(data.getUTCDate() + (i - 1));
    var dia = data.getUTCDate().toString().padStart(2, '0');
    var mes = (data.getUTCMonth() + 1).toString().padStart(2, '0');
    var ano = data.getUTCFullYear();
    document.querySelector('input[name="data' + i + '"]').value = dia + '/' + mes + '/' + ano;

    var dias_da_semana = ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];
    var dia_da_semana = dias_da_semana[data.getUTCDay()];
    document.querySelector('input[name="dia_da_semana' + i + '"]').value = dia_da_semana;
  }
}
 
//////////////////// recuperar dados do cadastro



/////////////////////////////////// FINALIZACAO //////////////////////////////3
function FinalizaFolha() {
  
}

</script> 
</html>