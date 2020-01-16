$(document).ready(function(){

    var nomImage = "objects.jpg";

    console.log("truc");

    $.ajax({
        type: 'GET',
        url: 'php-opencv-examples/detect_objects_by_dnn_mobilenet.php',
        data: {
            ImageName: nomImage
        },
        success: function (data) {
            console.log(data);
        }
    });

});