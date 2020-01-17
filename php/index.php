<?php
     //header('Content-Type: image/jpeg');
    //phpinfo();
   /* $json = $_POST["rectangle"];
    $path = $_POST["path"];
    $path = "..".$path;
    $hauteur = $_POST["hauteur"];
    $largeur = $_POST["largeur"];
*/
    /*   
    try{
        $json = json_decode($json);
    }
    catch(Exception $e){
        echo $e->getMessage() ;
    }
    */
    
     

    function detourage($json, $pathSource, $largeurCible, $hauteurCible)
    //json = [$x1, $y1, $x2, $y2]
    {
        
        
        try{
          
            //faut que l'image soit en png !!
            //$im = imagecreatefrompng($pathSource);
            $im = imagecreatefrompng($pathSource);
            //echo "$im";
        }
        
        catch(Exception $e){
            
            echo "catch";
            throw new Exception("Error extension : png attendu -> ".$e, 1);
        }
       
        //echo "___";
        $x1 = PHP_INT_MAX;
        $y1 = PHP_INT_MAX;
        $x2 = 0;
        $y2 = 0;

        $tabArg = array();
        $json = str_replace("'", '"', $json);
        //echo $json;
        $rectangles = json_decode($json);
        //$temp = json_decode('{"dict":[[1868.0592041015625,1431.3135738372803,2956.51407623291,2809.6590728759766]]}');
        //print_r($temp);
        //print_r($rectangles);
        //echo " ";
        //echo "_";


        foreach ($rectangles as $rectangle){
            foreach ($rectangle as $rec){
                //print_r($rec);
                //echo "\n";
                array_push($tabArg, array($rec["0"], $rec["1"], $rec["2"], $rec["3"]));
            }
        }

        print_r($tabArg);
        
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
       imagepng($imCrop,$p);
        //echo "ret $b";
        // Libération de la mémoire
        imagedestroy($im);
        imagedestroy($imCrop);
    }

    
    
    //detourage($json, $path, $largeur, $hauteur);
    detourage('{"dict":[[2451.168972015381,1181.9971132278442,3466.7012672424316,2323.7029237747192],[2611.9561500549316,1342.7636232376099,3086.913242340088,2143.1909952163696],[2734.6430168151855,1278.3356065750122,3400.8985862731934,2100.757487297058],[1152.781831741333,740.3443279266357,3128.3105850219727,2959.332618713379],[2061.0399627685547,1065.4829578399658,3531.100685119629,2775.742115020752]]}

    ', "../img/results/result3.png", 500, 500);
     /*
    out put  d'index
    {$JSON "dict":[[1868.0592041015625,1431.3135738372803,2956.51407623291,2809.6590728759766]]}

    $IM object(stdClass)#1 (1) 
    
    { ["dict"]=> array(1) { [0]=> array(4) { [0]=> float(1868.0592041016) [1]=> float(1431.3135738373) [2]=> float(2956.5140762329) [3]=> float(2809.659072876) } } } 
    Resource id #4____x1 org: 9223372036854775807y1 org: 9223372036854775807
width org height org x1 : 3532 y1 : 2524 width 500 height 500../img/crop/object6_crop2.jpeg

    */

?>