$(document).ready(function(){

    var nomImage = "../img/sources/objects.jpg";

    $.ajax({
        type: 'GET',
        url: 'php-opencv-examples/detect_objects_by_dnn_mobilenet.php',
        data: {
            ImageName: nomImage
        },
        success: function (data) {
            console.log(data);
            data.each(function (rectangle) {
                var startX = rectangle.startX;
                var startY = rectangle.startY;
                var endX = rectangle.endX;
                var endY = rectangle.endY;
                var pourcentage = rectangle.pourcentage;
            });
        }
    });

});