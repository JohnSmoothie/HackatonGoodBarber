$(document).ready(function(){

    var valider = $("");

    valider.click(function () {
        var dict = {};

        var nomImage = "../img/sources/objects.jpg";


        $.ajax({
            type: 'GET',
            url: 'php-opencv-examples/detect_objects_by_dnn_mobilenet.php',
            data: {
                ImageName: nomImage
            },
            success: function (data) {
                console.log(data);
                $.each(data, function (i, rectangle) {
                    var startX = rectangle.startX;
                    var startY = rectangle.startY;
                    var endX = rectangle.endX;
                    var endY = rectangle.endY;
                    var pourcentage = rectangle.pourcentage;

                    list = [startX, startY, endX, endY, pourcentage];

                    dict.add(list);

                });

                console.log(dict);
            }
        });


        $.ajax({
            type: 'POST',
            url: 'php/index.php',
            data: {
                rectangle: JSON.stringify({dict : dict}),

            },
            success: function (data) {

            }
        });

    });

});