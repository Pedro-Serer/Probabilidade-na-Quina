var padrao = "[a-z\s]+$";
var pattern = "[^0-9A-Za-z]";
var proxClickMin = 7;
var proxClickMax = 17;
var maximo = 0;
var bloqueiaMIN = bloqueiaMAX = false;
var innerMAX = document.getElementById('maximo');

innerMAX.innerHTML = "<center>" + maximo + "<center>";
//Gráfico de barras
function BARchart (cinco, quatro, tres, dois, um, zero) {
    var chart1 = c3.generate({
      bindto: '#chart1',
      size: {
          height: 300,
          width: 680
      },
      data: {
          columns: [
              ['bolas sorteadas', zero, um, dois, tres, quatro, cinco],

          ],
          type: 'bar'
      },
      bar: {
          width: {
              ratio: 0.5
          }
      },
      axis: {
          x: {
              type: 'category',
              categories: ['0 acerto', '1 acerto', '2 acertos', '3 acertos', '4 acertos', '5 acertos']
          },
          y: {
           label: {
               text: 'quantidade de acertos',
               position: 'outer-middle'
          }
        }
      }
    });
}

//Gráfico de pizza
function PIEchart (tudo, nada) {
    var chart2 = c3.generate({
      bindto: '#chart2',
      size: {
          height: 300,
          width: 300
      },
      data: {
          columns: [
              ['ganhar algum prêmio', tudo],
              ['perder todo seu dinheiro', nada],
          ],
          type : 'donut',
          onclick: function (d, i) { console.log("onclick", d, i); },
          onmouseover: function (d, i) { console.log("onmouseover", d, i); },
          onmouseout: function (d, i) { console.log("onmouseout", d, i); }
      },
      donut: {
          title: "chances reais"
      }
    });
}

//AJAX
function conectar () {
    var h2 = document.getElementsByClassName('numeros_escolhidos')[0];
    var form = document.getElementById('form');
    var formLoader = document.getElementById('process-loader');
    var divTable = document.getElementById('div-table');
    var http = new XMLHttpRequest();
    var aposta = [];

    //Recebe a resposta
    http.responseType = 'json';
    http.onreadystatechange = function() {

        if (this.readyState != 4) {
            //Ativa o loader
            form.style.display = 'none';
            formLoader.style.display = 'block';

        }  else if (this.readyState == 4 && this.status == 200) {
              var retunedArr = this.response;

              BARchart(
                  retunedArr['Resultado'][['cinco acertos']],
                  retunedArr['Resultado'][['quatro acertos']],
                  retunedArr['Resultado'][['tres acertos']],
                  retunedArr['Resultado'][['dois acertos']],
                  retunedArr['Resultado'][['um acertos']],
                  retunedArr['Resultado'][['zero acertos']]
              );

              PIEchart(
                  retunedArr['Probabilidade']['ganhar algum premio'],
                  retunedArr['Probabilidade']['perder tudo']
              );

              //Desfaz o loader
              form.style.display = 'block';
              formLoader.style.display = 'none';
              divTable.style.display = 'block';

              //Desce a tela 465 pixels
              window.scrollTo(0, 465);

              //Apresenta os números apostados na tela
              if (retunedArr['Premio maximo']) {
                  h2.innerHTML = "<h2 class='w3-text-red'>Alface não dá em árvore mas você ganhou na Quina!</h2>";
              } else {
                  h2.innerHTML = aposta.join('-');
              }
        }
    }

    //Validação dos inputs
    for (var i = 1; i < 6; i++) {
        aposta[i-1] = document.getElementById('bola-' + i).value;

        //Impede deixar vazio!
        if (document.getElementById('bola-' + i).value == "") {
            //Sobe a tela para o pixel 0
            window.scrollTo(0, 0);

            document.getElementById('msg-error').style.display = 'block';
            return;
        }

        //Impede colocar letras e caracteres especiais
        if (document.getElementById('bola-' + i).value.search(padrao) != -1
            || document.getElementById('bola-' + i).value.search(pattern) != -1) {

            document.getElementById('msg-alert').style.display = 'block';
            return;
        } else {
            document.getElementById('msg-alert').style.display = 'none';
        }

        document.getElementById('msg-error').style.display = 'none';
        document.getElementById('bola-' + i).value = null;
    }

    //Faz a conexão com o JSON de probabilidades
    http.open('POST', 'controller/alimenta_graficos.php');
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.send(`aposta=${aposta}`);
}

//Pega dados do gerador
function gerar() {
    var http = new XMLHttpRequest();

    //Recebe a resposta
    http.responseType = 'json';
    http.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var geradosArr = this.response;
            document.getElementById('bola-1').value = geradosArr['Gerados']['a'];
            document.getElementById('bola-2').value = geradosArr['Gerados']['b'];
            document.getElementById('bola-3').value = geradosArr['Gerados']['c'];
            document.getElementById('bola-4').value = geradosArr['Gerados']['d'];
            document.getElementById('bola-5').value = geradosArr['Gerados']['e'];
        }
    }

    http.open('POST', 'controller/gerador.php');
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.send();
}


//Pega dados da tabela
function load(min, max) {
    var h2 = document.getElementsByClassName('numeros_escolhidos')[0];
    var form = document.getElementById('form');
    var formLoader = document.getElementById('init-loader');
    var http = new XMLHttpRequest();
    var limite;

    //TD é o mesmo que <td>, cada número simboliza uma coluna
    var table = [], tr = [], td1 = [], td2 = [],
    td3 = [], td4 = [], td5 = [], td6 = [], td7 = [];

    table = document.getElementById('table');

    //Recebe a resposta
    http.responseType = 'json';
    http.onreadystatechange = function() {
      if (this.readyState != 4) {
        //Ativa o loader
        form.style.display = 'none';
        formLoader.style.display = 'block';

      }  else if (this.readyState == 4 && this.status == 200) {
            var geradosArr = this.response;
            limite = geradosArr['Tamanho'];

            if(maximo > Math.ceil((limite / 10))){
              bloqueiaMAX = true;
            }

            if (maximo == 0) {
              bloqueiaMIN = true;
            }

            //Criação da tabela
            for (var i = min; i < max; i++) {
               tr[i] = table.insertRow(-1);
               td1[i] = tr[i].insertCell(0);
               td2[i] = tr[i].insertCell(0);
               td3[i] = tr[i].insertCell(0);
               td4[i] = tr[i].insertCell(0);
               td5[i] = tr[i].insertCell(0);
               td6[i] = tr[i].insertCell(0);
               td7[i] = tr[i].insertCell(0);
               tr[i].setAttribute("id", i);

               //Append dos valores na tabela
               td1[i].innerHTML = geradosArr['bolas']['bola5'][i];
               td2[i].innerHTML = geradosArr['bolas']['bola4'][i];
               td3[i].innerHTML = geradosArr['bolas']['bola3'][i];
               td4[i].innerHTML = geradosArr['bolas']['bola2'][i];
               td5[i].innerHTML = geradosArr['bolas']['bola1'][i];
               td6[i].innerHTML = geradosArr['bolas']['cdata'][i];
               td7[i].innerHTML = geradosArr['bolas']['conum'][i];
            }

            //desfaz o loader
            form.style.display = 'block';
            formLoader.style.display = 'none';
        }
    }

    http.open('POST', 'controller/alimenta_tabela.php');
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.send();
}

function proximo(){
    var tableLen = document.getElementsByClassName('linha');

    if(maximo == 0) {
      bloqueiaMIN = false;
    }

    if (bloqueiaMAX) {
      return;
    }

    proxClickMin += 10;
    proxClickMax += 10;
    maximo++;

    for (var i = (proxClickMin - 10); i < (proxClickMax - 10); i++) {
      document.getElementById(i).remove();
    }

    innerMAX.innerHTML = "<center>" + maximo + "<center>";
    load(proxClickMin, proxClickMax);
}


function anterior(){
    var tableLen = document.getElementsByClassName('linha');

    if(bloqueiaMAX) {
      bloqueiaMIN = false;
      bloqueiaMAX = false;
    }

    if (bloqueiaMIN) {
      return;
    }

    proxClickMin -= 10;
    proxClickMax -= 10;
    maximo--;

    for (var i = (proxClickMin + 10); i < (proxClickMax + 10); i++) {
      document.getElementById(i).remove();
    }

    innerMAX.innerHTML = "<center>" + maximo + "<center>";
    load(proxClickMin, proxClickMax);
}
