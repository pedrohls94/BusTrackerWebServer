/*
 *
 */
 // some things to do right when the map is loaded {
    var map;
    var lineId = location.search.split('lineId=')[1];
    var lineStops;

    $.get("/php/request.php?action=findLine&id=" + lineId, function(data, status){
        var str = JSON.parse(data)["Name"];
        if(str != null) {
            if (lineId != null) {
                $.get("/php/request.php?action=fetchStopsByLine&lineId=" + lineId, function(data, status){
                    data = JSON.parse(data);
                    for(busStop in data) {
                        busStop = data[busStop]
                        var str = busStop['Location'];
                        var position = {
                            lat: parseFloat(str.substring(str.lastIndexOf("(")+1,str.lastIndexOf(","))),
                            lng: parseFloat(str.substring(str.lastIndexOf(" ")+1,str.lastIndexOf(")")))
                        };
                        makeMarker(position, 'img/marker0.png', 'stop', null, null);
                    }
                });
            }
        }
    });
// }

/*
 *
 */
function makeBusStopMarker(position) {
    var marker = new google.maps.Marker({
        position: position,
        map: map,
        icon: 'img/marker0.png',
        title: 'stop'
    });
    marker.addListener('click', function() {
        var confirmDelete = confirm("Delete this stop?");
        if (confirmDelete == true) {
            marker.setMap(null);
            $.get(
                "/php/request.php?action=deleteBusStop" +
                "&lineId=" + lineId +
                "&location=" + location, function(data, status){}
            );
        }
    });
}

/*
*
*/
function saveBusStopToBD(lineId, location) {
    var link = "/php/request.php?action=createBusStop" +
    "&lineId=" + lineId +
    "&location=" + location;
    $.get(
        link, function(data, status){}
    );
}

/*
 *
 */
function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: -27.605, lng: -48.590},
        zoom: 11
    });

    var a = document.createElement('a');
    var bannerImage = new Image(100, 200);
    banneImage.src = 'img/banner0.png';
    banneImage.setAttribute("height", "50px");
    banneImage.setAttribute("width", "200px");
    a.appendChild(bannerImage);
    a.title = "Home";
    a.href = "index.html";
    a.index = 1;
    map.controls[google.maps.ControlPosition.TOP_CENTER].push(a);

    google.maps.event.addListener(map, 'click', function(event) {
        $( "#newBusStopDialog" ).dialog({
            buttons: {
                "Create": function() {
                    $( "#newBusStopDialog" ).dialog( "close" );
                    makeBusStopMarker(event.latLng);
                    saveBusStopToBD(lineId, event.latLng);
                },
                Cancel: function() {
                    $( "#newBusStopDialog" ).dialog( "close" );
                }
            },
            close: function() {
                $( "#newBusStopDialog" ).dialog( "close" );
            }
        });
    });
}
