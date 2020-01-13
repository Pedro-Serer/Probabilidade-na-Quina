<?php
    $pathFiles = __DIR__ . '\php-excel-reader-2.21';
    require ($pathFiles.'\excel_reader2.php');
    require($pathFiles.'\SpreadsheetReader.php');

    error_reporting('E_NOTICE');
    $excel = new SpreadsheetReader($pathFiles.'\quina.xls');
    $num = $bol = 0;
    $val = [];
    $per = [];
    $jbol = 1;

    for ($j=2; $j<7; $j++){
        echo "[ Bola número " . $jbol++ . " ]<br><br>";

        for ($i=1; $i<81; $i++)
        {
        //Lê os dados do excel

            foreach ($excel as $Row)
            {
                if ($Row[$j] == $i) {
                    $num++;
                }
            }

        //Calcula o número de vezes que certa bola saiu
            $val[$i] = $num;
            $resultado = ($num - $val[$i-1]);

            if($resultado > 78 && $resultado < 120) {
                echo "A bola número $i saiu : " . $resultado . " vezes. <br>";
                $per[$jbol] = $bol++;
                $probabilidade[$i] = $i;
            }
        }

        //Calcula a probabilidade de cada bola
        $parcial = $bol - $per[$jbol-1];

        if ($jbol > 2) {
            echo "As chances de cair algum desses números na bola " . ($jbol-1) ." são de: " . (float) ((($parcial-1) / 80) * 100). "%" ;
        } else {
            echo "As chances de cair algum desses números na bola " . ($jbol-1) ."  são de: " . (float) ((($parcial) / 80) * 100). "%" ;
        }

        echo "<br><br>";
    }

    //Calcula a probabilidade no geral
    echo "<br>As chances de cair algum desses números são de: " . (float) (($bol / 80) * 100). "%";
