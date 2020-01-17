<?php
header("Content-Type:application/json");
use CV\Scalar;
use function CV\{imread, cvtColor};

$image = $_GET["nomImage"];

//var_dump($image);
//$categories = explode("\n", file_get_contents('models/ssd_mobilenet_v1_coco/classes.txt'));
$categories = explode("\n", file_get_contents('models/ssdlite_mobilenet_v2_coco/classes.txt'));

//$src = imread("../img/sources/" . $image); // opencv loads image to matrix with BGR order

//$src = imread("../img/sources/objects.jpg");
$src = imread($image);

//var_export($src);

$blob = \CV\DNN\blobFromImage($src, 1, new \CV\Size(300,300), new Scalar(127.5, 127.5, 127.5), true, false); // convert image to 4 dimensions matrix
//var_export($blob);

//$net = \CV\DNN\readNetFromTensorflow('models/ssd_mobilenet_v2_coco/frozen_inference_graph.pb', 'models/ssd_mobilenet_v2_coco/ssd_mobilenet_v2_coco.pbtxt');
//$net = \CV\DNN\readNetFromTensorflow('models/ssd_mobilenet_v1_coco/frozen_inference_graph.pb', 'models/ssd_mobilenet_v1_coco/ssd_mobilenet_v1_coco.pbtxt');
$net = \CV\DNN\readNetFromTensorflow('models/ssdlite_mobilenet_v2_coco/frozen_inference_graph.pb', 'models/ssdlite_mobilenet_v2_coco/ssdlite_mobilenet_v2_coco.pbtxt');
$net->setInput($blob, "");

$r = $net->forward();
//var_export($r);

$data = array();
$coord = array();

$rectangles = [];
for ($i = 0; $i < $r->shape[2]; $i++) {
    $classId = $r->atIdx([0,0,$i,1]);
    $confidence = intval($r->atIdx([0,0,$i,2]) * 100);
    if ($classId && $confidence > 20) {
        $startX = $r->atIdx([0,0,$i,3]) * $src->cols;
        $startY = $r->atIdx([0,0,$i,4]) * $src->rows;
        $endX = $r->atIdx([0,0,$i,5]) * $src->cols;
        $endY = $r->atIdx([0,0,$i,6]) * $src->rows;
       
        $coord["startX"] = $startX;
        $coord["startY"] = $startY;
        $coord["endX"] = $endX;
        $coord["endY"] = $endY ;       
       
        array_push($data, $coord);
        $scalar = new Scalar(0, 0, 255);
        \CV\rectangle($src, $startX, $startY, $endX, $endY, $scalar, 2);
       
        $text = "{$categories[$classId]} $confidence%";
        \CV\rectangle($src, $startX, $startY + 10, $startX + 20 * strlen($text), $startY - 30, new Scalar(255,255,255), -2);
        \CV\putText($src, "{$categories[$classId]} $confidence%", new \CV\Point($startX, $startY - 2), 0, 1.5, new Scalar(), 2);
    }
    
}


$jsondata = json_encode($data);

echo $jsondata;

\CV\imwrite("../img/results/result.png", $src);
//echo "<img src='../img/results/_detect_objects_by_dnn_mobilenet.png'>";
