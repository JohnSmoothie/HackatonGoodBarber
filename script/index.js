$(document).ready(function(){

    $.ajax({
        type: 'GET',
        url: '../data/sport.json',
        dataType: 'json',
        success: function (data) {
            console.log(data);

            $.each(data.sport,function(i,sport) {
                var option= new Option(sport, sport);
                $(option).html(sport);
                $("#sport").append(option);
            });
        }
    });

});