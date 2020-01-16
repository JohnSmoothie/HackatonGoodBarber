<?php
    function detourage($json, $pathSource, $largeurCible=NULL, $hauteurCible=NULL)
    //json = [$x1, $y1, $x2, $y2]
    {
        $extension = image_type_to_extension(getimagesize($pathSource)[2]);
        try{
            $im = imagecreatefromjpeg($pathSource);
        }
        catch(Exception $e){
            throw new Exception("Error extension : ".$e, 1);
        }
        
        $x1 = PHP_INT_MAX;
        $y1 = PHP_INT_MAX;
        $x2 = 0;
        $y2 = 0;

        $tabArg = array();
        $rectangles = json_decode($json, true);
        //print_r($rectangles);
        foreach ($rectangles as $rectangle){
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

        echo $height;

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
        
        $imCrop = imagecrop($im, ['x' => $x1, 'y' => $y1, 'width' => $width, 'height' => $height]);
  
        imagejpeg($imCrop,  "../crop/".explode(".", basename($pathSource))[0]."_crop.jpg");
        // Libération de la mémoire
        imagedestroy($im);
        imagedestroy($imCrop);
    }
    detourage('[{
            "x1": 450,
            "y1": 180,
            "x2": 560,
            "y2": 450
        }
    ]', "../images/image.jpg", NULL, 100);
?>