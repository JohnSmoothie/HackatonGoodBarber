$(document).ready(function(){

    var valider = $("#valider");

    var imgSquare = $("imgSquare");

    var imgCrop = $("imgCrop");


    valider.click(function () {
        var dict = [];
        
        var nomImage = "../img/sources/object6.jpg";

        var largeur = $("#largeur").val();

        var hauteur = $("#hauteur").val();
        console.log(largeur)
        console.log(hauteur)
        $.ajax({
            type: 'GET',
            url: 'php-opencv-examples/detect_objects_by_dnn_mobilenet.php',
            data: {
                nomImage: nomImage
            },
            success: function (data) {
                console.log(data);
                $.each(data, function (i, rectangle) {
                    var startX = rectangle.startX;
                    var startY = rectangle.startY;
                    var endX = rectangle.endX;
                    var endY = rectangle.endY;
                    //var pourcentage = rectangle.pourcentage;

                    //list = [startX, startY, endX, endY, pourcentage];
                    list = [startX, startY, endX, endY];

                    dict.push(list);

                });
          imgSquare.prepend($('<img>',{id:'imageSquares',src:'img/results/result.png'}));

                console.log(dict);
                $.ajax({
                    type: 'POST',
                    url: 'php/index.php',

                    data: {
                        rectangle: JSON.stringify({dict:dict}),
        
                        path: '/img/results/object6.png',
                        hauteur: hauteur,
                        largeur: largeur,
                    },
                    success: function (data) {
                        console.log('ss');
                    }
                });
                console.log('fin');
            }

        $.ajax({
            type: 'POST',
            url: 'php/index.php',
            data: {
                rectangle: JSON.stringify({dict : dict}),
                path: '/img/results/result.png',
                hauteur: hauteur,
                largeur: largeur,
            },
            success: function (data) {
                imgSquare.prepend($('<img>',{id:'imageCropped',src:'img/crop/image_crop.png'}));
            }
        });


      
    });
});