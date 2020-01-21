<?php
    header("Content-Type: application/json");
    # Aqui ficarão os dados de controle, ou seja, os valores que estão aqui
    # serão retornados para "quina.html(view) via AJAX e JSON"


    /*Inicialização de arquivos (model)*/
    $pathFiles = __DIR__ . '\php-excel-reader-2.21';
    require('..\model\funcoes.php');

    /*Inicialização das funções*/
    list($bola1, $bola2, $bola3, $bola4, $bola5) = bolasSorteadas($pathFiles);

    /*processa os dados digitados pelo usuário, transforma a string em um array*/
    $vetor = str_replace(",", null, $_POST['aposta']);
    $vetor = str_split($vetor, 2);

    $zero   = 0;
    $um     = 0;
    $dois   = 0;
    $tres   = 0;
    $quatro = 0;
    $cinco  = 0;

    //calcula as chances reais de acertar
    for ($i = 0; $i < MAX; $i++) {
        $matriz[$i][0] = $bola1[$i];
        $matriz[$i][1] = $bola2[$i];
        $matriz[$i][2] = $bola3[$i];
        $matriz[$i][3] = $bola4[$i];
        $matriz[$i][4] = $bola5[$i];

        if ($i > 6) {
            $chances = array_diff($vetor, $matriz[$i]);
            switch (count($chances)) {
                case 5:
                  $zero++;
                  break;
                case 4:
                    $um++;
                    break;
                case 3:
                    $dois++;
                    break;
                case 2:
                    $tres++;
                    break;
                case 1:
                    $quatro++;
                    break;
                case 0:
                    $cinco++;
                    break;
            }
        }
    }
    $total = $dois + $tres + $quatro + $cinco;


    //Verifica se saiu algum prêmio máximo (quina)
    $cinco >= 1 ? $maximo = true : $maximo = false;

    //Criação do JSON
    $json_response = array('Resultado' => array(
        'cinco acertos'  => $cinco,
        'quatro acertos' => $quatro,
        'tres acertos'   => $tres,
        'dois acertos'   => $dois,
        'um acertos'     => $um,
        'zero acertos'   => $zero,
    ),
        'Probabilidade'  => array(
        'maximo premio'           => (($cinco / MAX) * 100),
        'acertar quatro numeros'  => (($quatro / MAX) * 100),
        'ganhar algum premio'     => (($total / MAX) * 100),
        'perder tudo'             => (($zero / MAX) * 100)
    ),
        'Premio maximo' => $maximo
    );

    $json = json_encode($json_response);
    echo $json;
