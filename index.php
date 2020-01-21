<?php
    $pathFiles = __DIR__ . '\php-excel-reader-2.21';
    require($pathFiles.'\excel_reader2.php');
    require($pathFiles.'\SpreadsheetReader.php');
    error_reporting('E_NOTICE');

    /**
    * Função que mostra as bolas sorteadas ao longo dos anos
    * @param $pathfiles (string) | diretório para os arquivos da classe
    */

    function bolas_sorteadas ($pathFiles) {
        $excel = new SpreadsheetReader($pathFiles.'\quina.xls');

        $i = 0;
        foreach ($excel as $row)
        {
            $bola1[$i] = $row[2];
            $bola2[$i] = $row[3];
            $bola3[$i] = $row[4];
            $bola4[$i] = $row[5];
            $bola5[$i] = $row[6];
            $i++;
        }

        return [$bola1, $bola2, $bola3, $bola4, $bola5];
    }

    //Função que calcula a quantidade de acertos individuais de cada bola
    function bolas_prob ($bola) {
        $quantidadeAcerto = 0;
        $qtdIndividual = [];
        $numero = 0;

        for ($i=1; $i < 81; $i++)
        {
            for ($j=1; $j < count($bola); $j++)
            {
                if($bola[$j] == $i) {
                    $quantidadeAcerto++;
                }
            }

            $qtdIndividual[$i] = $quantidadeAcerto;
            $resultado = ($quantidadeAcerto - $qtdIndividual[$i-1]);

            //Filtra somente os resultados que sairam mais de 78 vezes
            if($resultado > 78) {
                echo "A bola número $i saiu : " . $resultado . " vezes. <br>";
                $numero++;
            }
        }

        return $numero;
    }

    //Função que mostra os resultados
    function resultados (...$bolas) {
        $i = $total = 0;

        foreach ($bolas as $bola)
        {
            $i++;

            echo "[Bola número {$i}] <br><br>";
                $prob = bolas_prob($bola);
                echo "As chances de cair algum desses valores na bola {$i} são: " . (float) (($prob / 80) * 100) . "%";
                echo "<br><br>";
            $total += $prob;
        }

        echo "As chances de cair algum desses números são: " . (float) (($total / 80) * 100) . "%";
    }

    list($bola1, $bola2, $bola3, $bola4, $bola5) = bolas_sorteadas($pathFiles);
    define('MAX', count($bola1));

    $vetor = array(8, 1, 32, 60, 74); //Vetor com os dados a serem procurados
    $zero   = 0;
    $um     = 0;
    $dois   = 0;
    $tres   = 0;
    $quatro = 0;
    $cinco  = 0;
    //calcula as chances reais de acertar (científica)

    for ($i=0; $i<MAX; $i++) {
        $matriz[$i][0] = $bola1[$i];
        $matriz[$i][1] = $bola2[$i];
        $matriz[$i][2] = $bola3[$i];
        $matriz[$i][3] = $bola4[$i];
        $matriz[$i][4] = $bola5[$i];

        if ($i > 6) {
            $chances = array_diff($vetor, $matriz[$i]);
            switch (count($chances))
            {
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

    //------------------------------------------------------------------------------------------------//

    resultados($bola1, $bola2, $bola3, $bola4, $bola5);

    echo "<br><br>Acertou 5 números {$cinco} vezes.<br>";
    echo "Acertou 4 números {$quatro} vezes.<br>";
    echo "Acertou 3 números {$tres} vezes.<br>";
    echo "Acertou 2 números {$dois} vezes.<br>";
    echo "Acertou 1 número {$um} vezes.<br>";
    echo "Nenhum acerto {$zero} vezes.<br><br>";

    echo "<h3>Resultado geral</h3>";
    printf("[Suas chances de ganhar o prêmio máximo são de :  %.2f%%]<br>", (($cinco / 5162) * 100));
    printf("[Suas chances de ganhar com quatro números são de :  %.2f%%]<br>", (($quatro / 5162) * 100));
    printf("[Suas chances geral de ganhar algum prêmio são de :  %.2f%%]<br>", (($total / 5162) * 100));
    printf("[Suas chances de perder seu dinheiro são de :  %.2f%%]<br>", (($zero / 5162) * 100));
