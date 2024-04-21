$(document).ready(function(){
    $(".horizontal-chart-demo").hBarChart();
    
    $.ajax({
        url: "https://api.openweathermap.org/data/2.5/weather?lat=14.4791&lon=120.8970&appid=f88bb901cd2915e2d9b8e9c78123c46e",
        type: 'get',
        success: function (data) {
            console.log(data);
            
            var temp = data.main.temp - 273.15;
            var icon = data.weather[0].icon;
            var weather = data.weather[0].main;

            $('#imgWeather').attr('src',`http://openweathermap.org/img/w/${icon}.png`);
            $("#spTemp").text(`${temp.toFixed(2)}`);
            $("#spWeather").text(weather);
        }
    });
});