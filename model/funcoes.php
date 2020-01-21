<?php
    $pathFiles = __DIR__ . '\php-excel-reader-2.21';
    require($pathFiles.'\excel_reader2.php');
    require($pathFiles.'\SpreadsheetReader.php');
    error_reporting('E_NOTICE');

    /**
    * Função que mostra as bolas sorteadas ao longo dos anos
    * @param string $pathfiles | diretório para os arquivos da classe
    * @return array retorna uma matriz de arrays com a lista das bolas
    * de todos concursos.
    * @author Pedro Serer
    */

    function bolasSorteadas ($pathFiles)
    {
        $excel = new SpreadsheetReader($pathFiles.'\quina.xls');

        $i = 0;
        foreach ($excel as $row) {
            $conum[$i] = $row[0]; //Número do concurso
            $cdata[$i] = $row[1]; //Data do concurso
            $bola1[$i] = $row[2];
            $bola2[$i] = $row[3];
            $bola3[$i] = $row[4];
            $bola4[$i] = $row[5];
            $bola5[$i] = $row[6];
            $i++;
        }

        return [$bola1, $bola2, $bola3, $bola4, $bola5, $conum, $cdata,];
    }

    /*Define a constante MAX, com o tamanho total de concursos durante os anos*/
    list($bola1) = bolasSorteadas($pathFiles);
    define('MAX', count($bola1));


    /**
    * Função que calcula a quantidade de acertos individuais de cada bola
    * usada em conjunto a função resultados a entrada é a bola atual.
    *
    * @param array $bola | Bola atual
    * @return int o número de acertos do usuário na
    * respectiva bola.
    */

    function bolasProb ($bola)
    {
        $quantidadeAcerto = 0;
        $qtdIndividual = [];
        $numero = 0;

        for ($i = 1; $i < 81; $i++) {
            for ($j=1; $j < count($bola); $j++) {
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

    /**
    * Função que retorna o cáculo de probabilidade individual
    * baseada no número de acertos.
    *
    * @param array $bolas | pode ter vários parâmetros
    * @return float a probabilidade de acertos do usuário na
    * respectiva bola.
    */

    function resultados (...$bolas)
    {
        $i = $total = 0;

        foreach ($bolas as $bola) {
            $i++;

            echo "[Bola número {$i}] <br><br>";
                $prob = bolasProb($bola);
                echo "As chances de cair algum desses valores na bola {$i} são: " . (float) (($prob / 80) * 100) . "%";
                echo "<br><br>";
            $total += $prob;
        }

        echo "As chances de cair algum desses números são: " . (float) (($total / 80) * 100) . "%";
    }
