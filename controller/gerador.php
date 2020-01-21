<?php
    header("Content-Type: application/json");

    /*Fonte dos números a serem gerados*/
    $resultados_um = array(1, 2, 4, 9, 10, 12, 13, 15, 16);
    $resultados_dois = array(18, 23, 27, 49);
    $resultados_tres = array(36, 37, 40, 50, 73, 79);
    $resultados_quatro = array(53, 39, 54, 56, 44);
    $resultados_cinco = array(53, 66, 72, 74, 80);

    /*Criação do JSON com o resultado aleatório*/
    $json_response = array('Gerados' => array(
        'a' => $resultados_um[array_rand($resultados_um)],
        'b' => $resultados_dois[array_rand($resultados_dois)],
        'c' => $resultados_tres[array_rand($resultados_tres)],
        'd' => $resultados_quatro[array_rand($resultados_quatro)],
        'e' => $resultados_cinco[array_rand($resultados_cinco)]
    ));

    $json = json_encode($json_response);
    echo $json;
