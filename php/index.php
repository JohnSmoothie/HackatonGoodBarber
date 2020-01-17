<?php
     //header('Content-Type: image/jpeg');
    //phpinfo();
    $json = $_POST["rectangle"];
    $path = $_POST["path"];
    $hauteur = $_POST["hauteur"];
    $largeur = $_POST["largeur"];

    /*
    out put  d'index
{"dict":[[1868.0592041015625,1431.3135738372803,2956.51407623291,2809.6590728759766]]}object(stdClass)#1 (1) { ["dict"]=> array(1) { [0]=> array(4) { [0]=> float(1868.0592041016) [1]=> float(1431.3135738373) [2]=> float(2956.5140762329) [3]=> float(2809.659072876) } } } Resource id #4____x1 org: 9223372036854775807y1 org: 9223372036854775807width org height org x1 : 3532 y1 : 2524 width 500 height 500../img/crop/object6_crop2.jpeg

    */


    /*
    out put 2
{"dict":[[324.3850326538086,313.5175609588623,655.1875305175781,882.8359222412109],[555.1160430908203,254.24660682678223,721.3845062255859,556.8390655517578],[416.9395446777344,309.0864658355713,706.1710357666016,824.9712753295898],[315.7160949707031,673.2893371582031,571.0444641113281,907.2530364990234]]}
    */
    
    
    echo $json;
    try{
        $json = json_decode($json);
    }
    catch(Exception $e){
        echo $e->getMessage() ;
    }
    
    
     

    function detourage($json, $pathSource, $largeurCible, $hauteurCible)
    //json = [$x1, $y1, $x2, $y2]
    {
        
        
        try{
          
            //faut que l'image soit en png !!
            $im = imagecreatefrompng($pathSource);
            //$im = imagecreatefrompng("../img/results/result.png");
            echo "$im";
        }
        
        catch(Exception $e){
            echo "catch";
            throw new Exception("Error extension : ".$e, 1);
        }
        
        echo "___";
        $x1 = PHP_INT_MAX;
        $y1 = PHP_INT_MAX;
        $x2 = 0;
        $y2 = 0;

        $tabArg = array();
        $rectangles = json_decode($json, true);
        echo $rectangles;
        echo "_";
        //print_r($rectangles);
        foreach ($rectangles as $rectangle){
            echo "_".$rectangle;
            array_push($tabArg, array($rectangle["x1"], $rectangle["y1"], $rectangle["x2"], $rectangle["y2"]));
        }
        
        foreach ($tabArg as $arguments){
            $x1 = min($x1, $arguments[0]);
            $y1 = min($y1, $arguments[1]);
            $x2 = max($x2, $arguments[2]);
            $y2 = max($y2, $arguments[3]);
        }

        $x1 = $x1-($x2-$x1)*0.05;
        $y1 = $y1-($y2-$y1)*0.05;
        $width = 1.05*($x2-$x1);
        $height = 1.05*($y2-$y1);
     

        

        if ($largeurCible != NULL) {
            $width = max($largeurCible, $width);
            if ($largeurCible==$width) {
                $x1 = $x1-intdiv($largeurCible-$width, 2);
            }
        }
        if ($hauteurCible != NULL) {
            $height = max($hauteurCible, $height);
            if ($hauteurCible==$height) {
                $y1 = $y1-intdiv($hauteurCible-$height, 2);
            }
        }


        if($x1+$width>imagesx($im)){
            $x1 = imagesx($im)-$width;
        }
        if ($y1+$height>imagesy($im)) {
            $y1 = imagesy($im)-$height;
        }

        $x1 = max($x1, 0);
        $y1 = max($y1, 0);
        $width = min(imagesx($im), $width);
        $height = min(imagesy($im), $height);
        echo "\n x1 : $x1";
        echo "\n y1 : $y1";
        echo "\n width $width";
        echo "\n height $height";
        $imCrop = imagecrop($im, ['x' => $x1, 'y' => $y1, 'width' => $width, 'height' => $height]);
        $p = "../img/crop/".explode(".", basename($pathSource))[0]."_crop2.jpeg";
        echo $p;
        
       // $b = imagejpeg($imCrop, $p);
       imagepng($im,$p);
        //echo "ret $b";
        // Libération de la mémoire
        imagedestroy($im);
        imagedestroy($imCrop);
    }
    
    detourage($json, $path, $largeur, $hauteur);

?>