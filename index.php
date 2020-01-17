<?php
    $pathFiles = __DIR__ . '\php-excel-reader-2.21';
    require ($pathFiles.'\excel_reader2.php');
    require($pathFiles.'\SpreadsheetReader.php');
    error_reporting('E_NOTICE');

    //Função que mostra todas as bolas sorteadas
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

    //Função que mostra as porcentagens gerais
    function resultado_geral ($bola1, $bola2, $bola3, $bola4, $bola5) {
        $num          = 0;
        $zeroAcerto   = 0;
        $umAcerto     = 0;
        $doisAcerto   = 0;
        $tresAcerto   = 0;
        $quatroAcerto = 0;
        $cincoAcerto  = 0;

        for ($i=0; $i<count($bola1); $i++)
        {
            for ($j=0; $j<5; $j++)
            {
                $matriz[$i][0] = $bola1[$i];
                $matriz[$i][1] = $bola2[$i];
                $matriz[$i][2] = $bola3[$i];
                $matriz[$i][3] = $bola4[$i];
                $matriz[$i][4] = $bola5[$i];

                //As bolas escolhidas pelo usuário
                if (($matriz[$i][$j] == 8) ||
                        ($matriz[$i][$j] == 43) ||
                            ($matriz[$i][$j] == 44) ||
                                ($matriz[$i][$j] == 60) ||
                                    ($matriz[$i][$j] == 74))
                {
                    $numQtd[$i]++;
                    $num++;
                }
            }

            switch ($numQtd[$i])
            {
                case 1:
                    $umAcerto++;
                    break;
                case 2:
                    $doisAcerto++;
                    break;
                case 3:
                    $tresAcerto++;
                    break;
                case 4:
                    $quatroAcerto++;
                    break;
                case 5:
                    $cincoAcerto++;
                    break;
                case 0:
                    $zeroAcerto++;
                    break;
            }
        }

        $total = ($doisAcerto + $tresAcerto + $quatroAcerto + $cincoAcerto);

        return [$umAcerto, $doisAcerto, $tresAcerto, $quatroAcerto, $cincoAcerto, $zeroAcerto, $total];
    }

    list($bola1, $bola2, $bola3, $bola4, $bola5) = bolas_sorteadas($pathFiles);

    $vetor = array(2, 32, 31, 53, 18); //Vetor com os dados a serem procurados

    //calcula as chances reais de acertar (científica)

    for ($i=0; $i<count($bola1); $i++) {
        $matriz[$i][0] = $bola1[$i];
        $matriz[$i][1] = $bola2[$i];
        $matriz[$i][2] = $bola3[$i];
        $matriz[$i][3] = $bola4[$i];
        $matriz[$i][4] = $bola5[$i];
    }

    $num = 0;
    for ($i=7; $i < 5162; $i++) {
      $chances = array_diff($vetor, $matriz[$i]);
      if (count($chances) < 3) { //Se conseguiu acertar 3 números em um mesmo jogo passado
          $num++;
      }
    }

    //------------------------------------------------------------------------------------------------//
    //
    // list($um, $dois, $tres, $quatro, $cinco, $zero, $total) = resultado_geral($bola1, $bola2, $bola3, $bola4, $bola5);
    //
    //
    // resultados($bola1, $bola2, $bola3, $bola4, $bola5);
    //
    // echo "<br><br>Acertou 5 números {$cinco} vezes.<br>";
    // echo "Acertou 4 números {$quatro} vezes.<br>";
    // echo "Acertou 3 números {$tres} vezes.<br>";
    // echo "Acertou 2 números {$dois} vezes.<br>";
    // echo "Acertou 1 número {$um} vezes.<br>";
    // echo "Nenhum acerto {$zero} vezes.<br><br>";
    //
    // echo "<h3>Resultado geral</h3>";
    // printf("[Suas chances de ganhar o prêmio máximo são de :  %.2f%%]<br>", (($cinco / 5162) * 100));
    // printf("[Suas chances de ganhar com quatro números são de :  %.2f%%]<br>", (($quatro / 5162) * 100));
    // printf("[Suas chances geral de ganhar algum prêmio são de :  %.2f%%]<br>", (($total / 5162) * 100));
    // printf("[Suas chances de perder seu dinheiro são de :  %.2f%%]<br>", (($zero / 5162) * 100));
    // printf("[Suas chances de acertar mais de 3 números em um mesmo jogo são de :  %.2f%%]<br>", ($num / 5161) * 100);
