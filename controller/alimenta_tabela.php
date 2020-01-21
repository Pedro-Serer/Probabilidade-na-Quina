<?php
    header("Content-Type: application/json");

    /*Inicialização de arquivos (model)*/
    $pathFiles = __DIR__ . '\php-excel-reader-2.21';
    require('..\model\funcoes.php');

    /*Inicialização das funções*/
    list($bola1, $bola2, $bola3, $bola4, $bola5, $conum, $cdata) = bolasSorteadas($pathFiles);

    //Criação do JSON de alimentação da tabela
    $json_response = array('bolas' => array(
        'conum' => $conum,
        'cdata' => $cdata,
        'bola1' => $bola1,
        'bola2' => $bola2,
        'bola3' => $bola3,
        'bola4' => $bola4,
        'bola5' => $bola5
    ),
      'Tamanho' => MAX
    );

    $json = json_encode($json_response);
    echo $json;
