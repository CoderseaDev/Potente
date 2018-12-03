const users = document.getElementById('users');
if (users) {
    function deleteUser(e) {
        if (confirm('are you sure?')) {
            fetch(`/user/delete/${e.target.getAttribute('data-id')}`, {method: 'DELETE'})
                .then(res => window.location.reload());
        }
    }
}

$( document ).ready( function(){
    $( "#imageUpladForm" ).submit( function( e ) {
        e.preventDefault();
        var data = new FormData();
        var files = $('[type="file"]').get(0).files;
        // Add the uploaded image content to the form data collection
        if (files.length > 0) {
            data.append("image_upload", files[0]);
        }
        // the actual request to your newAction
        $.ajax({
            url: '/saveProfileImage',
            type: 'POST',
            dataType: 'script',
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                var url = JSON.parse(data).url;
                $('#image_upload_file').attr('src', window.location.origin + url);
            }
        });
    } );
});

function uploadImage() {
    $("#imageUpladForm").submit();

}


//GeoChart
google.charts.load('current', {
    'packages':['geochart'],
    'mapsApiKey': 'AIzaSyD-9tSrke72PouQMnMX-a7eZSW0jkFMBWY'
});
google.charts.setOnLoadCallback(drawRegionsMap);

function drawRegionsMap() {
    var data = google.visualization.arrayToDataTable([
        ['Country', 'Popularity'],
        ['Germany', 200],
        ['United States', 300],
        ['Brazil', 400],
        ['Canada', 500],
        ['France', 600],
        ['RU', 700]
    ]);

    var options = {};

    var chart = new google.visualization.GeoChart(document.getElementById('regions_div'));

    chart.draw(data, options);
}


//LineChart
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {
    var data = google.visualization.arrayToDataTable([
        ['Year', 'Sales', 'Expenses'],
        ['2004', 1000, 400],
        ['2005', 1170, 460],
        ['2006', 660, 1120],
        ['2007', 1030, 540]
    ]);

    var options = {
        title: 'Company Performance',
        curveType: 'function',
        legend: {position: 'bottom'}
    };

    var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

    chart.draw(data, options);
}

