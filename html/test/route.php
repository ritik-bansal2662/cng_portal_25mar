<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

<head>
    <title>MapmyIndia Maps API: Route Example</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <link rel="icon" href="https://www.mapmyindia.com/images/favicon.ico" type="image/x-icon">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type="text/css">
        /*map css **/
        body,
        html {
            height: 100%;
            font-family: Verdana, sans-serif, Arial;
            color: #555;
            margin: 0;
            font-size: 12px;
            padding: 0;
            background: #fafafa
        }

        a,
        img {
            outline: none;
            border: none;
            color: #047CC8;
            text-decoration: none
        }

        a:hover {
            text-decoration: underline
        }

        #map-container {
            position: absolute;
            left: 312px;
            top: 46px;
            right: 2px;
            bottom: 2px;
            border: 1px solid #cccccc;
        }

        #menu {
            position: absolute;
            left: 2px;
            top: 46px;
            width: 306px;
            bottom: 2px;
            border: 1px solid #cccccc;
            background-color: #FAFAFA;
            overflow-x: hidden;
            overflow-y: auto;
        }

        .textbox {
            width: 229px;
            margin: 0 10px 5px 0px;
            padding: 5px;
            border: 1px solid #ddd;
            color: #555
        }

        select {
            color: #555;
            border: 1px solid #ddd;
        }

        ::-webkit-input-placeholder {
            /* Chrome/Opera/Safari */
            color: #ddd;
        }

        ::-moz-placeholder {
            /* Firefox 19+ */
            color: #ddd;
        }

        :-ms-input-placeholder {
            /* IE 10+ */
            color: #ddd;
        }

        :-moz-placeholder {
            /* Firefox 18- */
            color: #ddd;
        }

        /*route icons*/
        .leaflet-routing-icon {
            background-image: url('route_advices.svg');
            -webkit-background-size: 455px 20px;
            background-size: 455px 20px;
            background-repeat: no-repeat;
            margin: 0;
            content: '';
            display: inline-block;
            vertical-align: top;
            width: 20px;
            height: 20px;
        }

        .leaflet-routing-alt-minimized .leaflet-routing-icon {
            background-image: url('https://apis.mapmyindia.com/map_v3/osrm.directions.icons.color.svg');
        }

        .leaflet-routing-icon.lanes.invalid {
            filter: invert(50%);
        }

        .leaflet-routing-icon-continue {
            background-position: 2px 0px;
        }

        .leaflet-routing-icon-sharp-right {
            background-position: -24px 0px;
        }

        .leaflet-routing-icon-turn-right {
            background-position: -50px 0px;
        }

        .leaflet-routing-icon-bear-right {
            background-position: -74px 0px;
        }

        .leaflet-routing-icon-u-turn {
            background-position: -101px 0px;
        }

        .leaflet-routing-icon-end {
            background-position: -429px 0px;
        }

        .leaflet-routing-icon-sharp-left {
            background-position: -127px 0px;
        }

        .leaflet-routing-icon-turn-left {
            background-position: -150px 0px;
        }

        .leaflet-routing-icon-bear-left {
            background-position: -175px 0px;
        }

        .leaflet-routing-icon-depart {
            background-position: -202px 0px;
        }

        .leaflet-routing-icon-enter-roundabout {
            background-position: -227px 0px;
        }

        .leaflet-routing-icon-arrive {
            background-position: -253px 0px;
        }

        .leaflet-routing-icon-via {
            background-position: -278px 0px;
        }

        .leaflet-routing-icon-fork {
            background-position: -305px 0px;
        }

        .leaflet-routing-icon-ramp-right {
            background-position: -331px 0px;
        }

        .leaflet-routing-icon-ramp-left {
            background-position: -352px 0px;
        }

        .leaflet-routing-icon-merge-left {
            background-position: -376px 0px;
        }

        .leaflet-routing-icon-merge-right {
            background-position: -403px 0px;
        }

        @media screen and (max-width:700px) {
            #menu {
                top: 55%;
                width: 99%
            }

            #map-container {
                top: 52px;
                left: 1px;
                bottom: 45%;
            }
        }
    </style>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>



    <!--put your map api javascript url with key here-->
    <script src="https://apis.mapmyindia.com/advancedmaps/v1/fac149b818d7ba75db4aeee2b5e9f70b/map_load?v=1.3"></script>
    <script>

        var alternate_route = null;
        var poly = [];
        var advice_direct_route;
        var direct_route_info;
        var polyList = [];
        var start_end_markersList = new Array();
        var advicemarkersList = [];
        var via_points = "";
        var alternatives_o;
        var map;
        var pathArrdir = [];
        var pth = window.location.href;/*get path of image folder*/
        var full_path = pth.replace(pth.substr(pth.lastIndexOf('/') + 1), '');

        /*put your REST API URL with key here**/
        //var route_api_url='http://apis.mapmyindia.com/advancedmaps/v1/rest_key_here/route?';
        var route_api_url = 'https://apis.mapmyindia.com/advancedmaps/v1/fac149b818d7ba75db4aeee2b5e9f70b/';
        window.onload = function () {

            try { map = new MapmyIndia.Map('map-container', { zoomControl: true, hybrid: true, traffic: true, search: true }); } catch (e) { alert('Please check map key'); }
            /*1.create a MapmyIndia Map by simply calling new MapmyIndia.Map() and passsing it at the minimum div object, all others are optional...
          2.all leaflet mapping functions can be called simply on the L object
          3.MapmyIndia may extend and in future modify the customised/forked Leaflet object to enhance mapping functionality for developers, which will be clearly documented in the MapmyIndia API documentation section.*/


            get_route_result();/***call route api***********/
        };

        function get_route_result() {
            remove_start_end_markersList();
            remove_advice_marker();
            remove_polyList();
            /*  for (poly1 in polyList){
               map.removeLayer(poly1);
              }*/
            poly = [];
            pathArrdir = [];
            polyList = [];
            var via_arr = '';
            var start_points = document.getElementById('start').value;/***get start points**/
            var destination_points = document.getElementById('destination').value;/**get destination points**/
            via_points = document.getElementById('via').value;/**get via points**/
            if (via_points) {
                var v = via_points.split(';');
                for (var i = 0; i < v.length; i++) {
                    var v_ar = v[i].split(',');
                    via_arr += ";" + v_ar[1] + "," + v_ar[0];
                }

            }
            var advices_o = document.getElementById('advices_o').value;/**get advices option**/
            // var traffic = document.getElementById('traffic').value;/**get advices option**/
            alternatives_o = document.getElementById('alternatives_o').value;/**get alternatives option**/

            var eta = ""; try { eta = document.getElementById('eta').value; } catch (e) { }
            var rtype = document.getElementById('rtype').value;
            var rtype_payload = "&rtype=" + rtype;
            var start_points_array = start_points.split(",");
            var destination_points_array = destination_points.split(",");

            //alert(param_arr);
            var api_name = $("#api_name").val(); var api_call = api_name;
            var profile = $("#Profile").val(); api_call += "/" + profile + "/";
            if ((profile == 'biking' || profile == 'trucking' || profile == 'walking') && api_name != 'route_adv') {
                map.removeLayer(leaflet_polyline);
                document.getElementById('info').innerHTML = "<font color='red'><b>Routing with this profile is restricted to route_adv only.</b></font>";
                return false;
            }
            var regiondv = document.getElementById('region_dv').style;
            var rtypedv = document.getElementById('rtype_dv').style;
            if (api_name == 'route_adv') {
                regiondv.visibility = 'visible'; var rgn = $('#region').val();
                rtypedv.visibility = 'visible';
            } else {
                regiondv.visibility = 'hidden'; var rgn = 'ind';
                rtypedv.visibility = 'hidden'; rtype_payload = "";

            }
            var route_api_url_with_param = route_api_url + api_call + start_points_array[1] + "," + start_points_array[0] + via_arr + ";" + destination_points_array[1] + "," + destination_points_array[0] + "?alternatives=" + alternatives_o + rtype_payload + "&geometries=polyline&overview=" + (eta == 1 ? "simplified" : "full") + "&exclude=" + $('#avoids').val() + "&steps=" + advices_o + "&region=" + rgn;
            //alert(route_api_url_with_param);
            show_markers("start", start_points_array);/*********show start points marker********/
            show_markers("destination", destination_points_array); /*********show destination points marker********/

            mapmyindia_fit_markers_into_bound(start_points_array, destination_points_array);

            document.getElementById('direct_advices').style.display = "inline-block";
            //  document.getElementById('direct_advices').innerHTML = "<font color='red'>loading..</font>";
            document.getElementById('alternatives_advices').innerHTML = "";

            if (poly['direct'] in polyList) {
                polyList.pop(poly['direct']);
                map.removeLayer(poly['direct']);
            }
            if (poly['alternate'] in polyList) {
                polyList.pop(poly['alternate']);
                map.removeLayer(poly['alternate']);
            }
            getUrlResult(route_api_url_with_param);
        }

        // window.onload =get_route_result();
        function getUrlResult(api_url) {
            if (leaflet_polyline) map.removeLayer(leaflet_polyline);

            $.ajax({
                type: "POST",
                dataType: 'text',
                url: "get-map-response.php",
                async: false,
                data: {
                    url: JSON.stringify(api_url),
                },
                success: function (result) {
                    console.log('result', result);
                    var resdata = JSON.parse(result);
                    console.log('res data',resdata)
                    console.log(typeof resdata)
                    route_api_result(resdata);
                    // console.log('data',resdata.status)
                    // if (resdata.data.indexOf("503 Service Temporarily") >= 1) { alert("UAT Server not working"); return false; }
                    if (resdata.status == 'success') {
                        var jsondata = JSON.parse(resdata.data);
                        route_api_result(jsondata);


                    }
                    else {
                        var error_response = "No Response from API Server. kindly check the keys or request server url";
                        $("#info").html(error_response);
                        // document.getElementById('result').innerHTML = error_response + '</ul></div>';/***put response result in div****/
                    }
                }
            });
        }
        var icon_d = L.icon({ iconUrl: "https://maps.mapmyindia.com/images/3.png", iconSize: [36, 51], iconAnchor: [15, 45], popupAnchor: [0, -36] });
        var leaflet_group = [], leaflet_polyline = '', advise_arr = '', adv_marker = '';

        function route_api_result(data) {

            // advise_arr = instructions(data);
            advise_arr = data
            console.log('advise arr',advise_arr['routes'])

            var table = "<table><tr>", advise_dv = "", leaflet_group = [];
            for (i = 0; i < data.routes.length; i++) {
                geometry = decode(data.routes[i].geometry);

                var color = "#016f99"; if (i > 0) color = "#a9b6c4";
                var dash_arr = ''; if ($("#Profile").val() == 'walking') dash_arr = "1, 10"
                leaflet_group[i] = new L.Polyline(geometry, { weight: 7, opacity: 1, dashArray: dash_arr, color: color, smoothFactor: 1 });
                var duration = data.routes[i].duration;
                var hours = Math.floor(duration / 3600); duration %= 3600;
                var minutes = Math.floor(duration / 60);
                var seconds = data.routes[i].duration % 60;
                var total_time = (hours >= 1 ? hours + " hrs " : '') + (minutes >= 1 ? minutes + " min" : '') + (seconds ? " " + Math.round(seconds) + " Sec." : "");


                var distance_meters = data.routes[i].distance;
                var advice_meters = (distance_meters >= 1000 ? (distance_meters / 1000).toFixed(1) + " km " : distance_meters + " mts ");
                //table+="<td class='route_num' id='route_num_"+i+"'>Route "+(i+1)+"<br>"+total_time+"<br>"+advice_meters+"</td>";

                advise_dv += "<br><div id='adv_route_" + i + "' ><b>Route " + (i + 1) + "<br> ETA:" + total_time + " Dis:" + advice_meters + "</b><br><table width='100%'>";
                for (var j = 0; j < advise_arr['routes'][i].length; j++) {
                    var txt = advise_arr['routes'][i][j].text;
                    var icon = advise_arr['routes'][i][j].icon_class;
                    advise_dv += "<tr onmouseover=\"if(adv_marker) map.removeLayer(adv_marker);adv_marker= new L.Marker(new L.LatLng(" + advise_arr['routes'][i][j].lat + "," + advise_arr['routes'][i][j].lng + "), {icon: icon_d,draggable:false});map.addLayer(adv_marker);\" onmouseout=\"map.removeLayer(adv_marker);\"><td><span class='leaflet-routing-icon " + icon + "'></span></td><td style='padding:5px;border-top: 1px solid #e9e9e9;'>" + txt + "</td></tr>"
                }
                advise_dv += "</tr></table>";
            }

            if (!leaflet_group) { $("#info").html("NO data"); }
            else {
                leaflet_polyline = new L.featureGroup(leaflet_group); map.addLayer(leaflet_polyline);
                leaflet_group[0].bringToFront();
                table += "</tr><tr><td colspan='" + data.routes.length + "'>" + advise_dv + "</td></tr></table>"
                document.getElementById('info').innerHTML = table;
            }

            return false;
            if (data.trips.duration != 0) {
                var alternate_route1_text = "";
                var alternate_route2_text = "";
                var direct_route = 'Route';
                alternate_route = data.alternatives;
                document.getElementById("alternate").style.display = "none";
                if (typeof alternate_route[0] != 'undefined') /***get first alternative route***/ {
                    var duration1 = alternate_route[0].duration;/**time in seconds*************/
                    var hours1 = Math.floor(duration1 / 3600);
                    duration1 %= 3600;
                    var minutes1 = Math.floor(duration1 / 60);
                    var total_time1 = (hours1 >= 1 ? hours1 + " hrs " : '') + (minutes1 >= 1 ? minutes1 + " min" : '');
                    var length1 = (alternate_route[0].length) / 1000;
                    alternate_route1_text = '<td ><div style="padding:5px 5px 5px 15px;color:#000;border-left:1px solid #ddd;cursor:pointer" onclick="document.getElementById(\'direct_advices\').style.display=\'none\';document.getElementById(\'alternatives_advices\').style.display=\'inline-block\';alternative_route(0)"><span style="font-size:13px;padding:2px 0 20px 0;color:#222">Route 2</span><br><span style="font-size:11px;line-height:16px;color:#555">' + total_time1 + '<br>' + length1.toFixed(1) + ' km</div></td>';
                    direct_route = 'Route 1';
                }
                if (typeof alternate_route[1] != 'undefined') /***get second alternative route***/ {
                    var duration2 = alternate_route[1].duration;/**time in seconds*************/
                    var hours2 = Math.floor(duration2 / 3600);
                    duration2 %= 3600;
                    var minutes2 = Math.floor(duration2 / 60);
                    var total_time2 = (hours2 >= 1 ? hours2 + " hrs " : '') + (minutes2 >= 1 ? minutes2 + " min" : '');
                    var length2 = (alternate_route[1].length) / 1000;
                    alternate_route2_text = '<td ><div style="padding:5px 5px 5px 15px;color:#000;border-left:1px solid #ddd;cursor:pointer" onclick="document.getElementById(\'direct_advices\').style.display=\'none\';document.getElementById(\'alternatives_advices\').style.display=\'inline-block\';alternative_route(1)"><span style="font-size:13px;padding:2px 0 20px 0;color:#222">Route 3</span><br><span style="font-size:11px;line-height:16px;color:#555">' + total_time2 + '<br>' + length2.toFixed(1) + ' km</div></td>';

                }
                /***check & display alternative route option*****/
                var way = data.trips[0];
                var way1 = data.trips[1];
                if (via_points == "") {
                    var trips = data.trips;
                    var duration = way.duration;/**time in seconds*************/
                    var hours = Math.floor(duration / 3600);
                    duration %= 3600;
                    var minutes = Math.floor(duration / 60);
                    var total_time = (hours >= 1 ? hours + " hrs " : '') + (minutes >= 1 ? minutes + " min" : '');
                    var length = (way.length) / 1000;

                    var pts = decode_path(way.pts);
                    var advices = way.advices; /****advice & display **************/
                }
                else {
                    /*******if via points is provided use trip[0] & trip[1] also************/
                    var duration = way.duration + way1.duration;/**time in seconds*************/
                    var hours = Math.floor(duration / 3600);
                    duration %= 3600;
                    var minutes = Math.floor(duration / 60);
                    var total_time = (hours >= 1 ? hours + " hrs " : '') + (minutes >= 1 ? minutes + " min" : '');
                    var length = (way.length + way1.length) / 1000;
                    var pts = decode_path(way.pts).concat(decode_path(way1.pts));/****points trip[0] & trip[1] to display **************/
                    var advices = way.advices.concat(way1.advices); /****advice trip[0] & trip[1] to display **************/
                }



                /***********display advices***********/
                direct_route_info = '<table width="100%" ><tr><td ><div style="padding:5px;cursor:pointer;background:#f7f7f7" onclick="document.getElementById(\'direct_advices\').style.display=\'inline-block\';document.getElementById(\'alternatives_advices\').style.display=\'none\';map.removeLayer(poly[\'alternate\']);draw_polyline(\'direct\', pathArrdir);"><span style="font-size:13px;padding:2px 0 20px 0;color:#222">' + direct_route + '</span><br><span style="font-size:11px;line-height:16px">' + total_time + '<br>' + length.toFixed(1) + ' km</span></div></td>' + alternate_route1_text + alternate_route2_text + '</tr></table><table width="100%"><tr><td  style="background:#ddd;border:1px solid #ddd;cursor:pointer" align="center" onclick="show_route_details(1,\'pre\',\'pre\',\'pre\',\'pre\')">< Prev.</td><td width="50%"></td><td align="center"  style="background:#ddd;border:1px solid #ddd;cursor:pointer" onclick="show_route_details(1,\'next\',\'next\',\'next\',\'next\')">Next ></td></tr></table>';


                document.getElementById('info').innerHTML = direct_route_info;
                advice_direct_route = '<!--span style="font-size:13px;padding-left:5px">' + direct_route + '</span--><table width="100%" align="center">';
                var num_rec = 1;
                var distance;
                var go = ""; var num = 0;
                advices.forEach(function (advice) {
                    var icon = advice.icon_id;
                    var meters = advice.meters;
                    var distance_meters = meters - distance;
                    distance = meters;

                    var advice_meters = (distance_meters >= 1000 ? (distance_meters / 1000).toFixed(1) + " km " : distance_meters + " mts ")
                    var text = advice.text;
                    if (meters != 0) {
                        go = "<br>Go " + advice_meters;
                        advice_direct_route += go + '</td></tr>';
                    }
                    var advice_pt = advice.pt;

                    advice_direct_route += '<tr id="1_' + num + '"onclick="show_route_details(1,' + advice_pt.lat + ',' + advice_pt.lng + ',\'' + text + go + '\',' + (num++) + ')" style="cursor:pointer;"><td valign="top" style="padding:5px 0px 5px 0px"><img src="https://api.mapmyindia.com/images/step_' + icon + '.png" width="30px"></td><td style="padding:5px;border-top: 1px solid #e9e9e9;">' + text;
                });
                /***********display path***********/
                var pathArr = [];
                pts.forEach(function (pt) {
                    pathArrdir.push(new L.LatLng(pt[0], pt[1]));
                });
                document.getElementById('direct_advices').innerHTML = advice_direct_route + "</table>";
                draw_polyline("direct", pathArrdir);/***********draw polyline***/
            }
            else {
                document.getElementById('info').innerHTML = "";
                document.getElementById('direct_advices').innerHTML = "Invalid points";
                remove_start_end_markersList();/***remove if any existing marker***/
            }

        }
        function decode(encoded) {
            var points = [], index = 0, len = encoded.length, lat = 0, lng = 0;
            while (index < len) {
                var b, shift = 0, result = 0;
                do {

                    b = encoded.charAt(index++).charCodeAt(0) - 63;
                    result |= (b & 0x1f) << shift;
                    shift += 5;
                } while (b >= 0x20);
                var dlat = ((result & 1) != 0 ? ~(result >> 1) : (result >> 1));
                lat += dlat; shift = 0; result = 0;
                do {
                    b = encoded.charAt(index++).charCodeAt(0) - 63;
                    result |= (b & 0x1f) << shift; shift += 5;
                } while (b >= 0x20);
                var dlng = ((result & 1) != 0 ? ~(result >> 1) : (result >> 1)); lng += dlng; points.push([lat / 1E5, lng / 1E5])
            }
            return points
        }
        function instructions(data) {
            var _0xd813 = ['sharp-right', 'uturn', 'u-turn', 'sharp\x20left', 'sharp-left', 'left', 'turn-left', 'slight\x20left', 'bear-left', 'round', 'bearing_after', 'northeast', 'east', 'southeast', 'south', 'southwest', 'west', 'northwest', 'Head\x20', 'use\x20lane', 'Continue\x20', '\x20onto\x20', 'Enter\x20the\x20roundabout', 'exit', '\x20and\x20take\x20the\x20', '\x20exit', '\x20turn\x20', 'turn', 'Take\x20the\x20ramp\x20on\x20the\x20', 'replace', 'slight', 'merge', 'toUpperCase', 'slice', 'slightly', 'indexOf', 'sharp', 'Take\x20a\x20', 'Keep\x20', '\x20on\x20', 'Intermediate\x20', 'destination', 'charAt', 'push', 'location', 'distance', 'leaflet-routing-icon-', 'routes', 'length', 'steps', 'name', 'maneuver', 'type', 'modifier', 'new\x20name', 'continue', 'depart', 'arrive', 'reached', 'roundabout', 'rotary', 'fork', 'on\x20ramp', 'off\x20ramp', 'end\x20of\x20road', 'head', 'waypointreached', 'via', 'destination\x20reached', 'straight', 'slight\x20right', 'bear-right', 'right', 'turn-right', 'sharp\x20right'];
            (function (_0x5eae1b, _0x235dd8) {
                var _0x486139 = function (_0x212f42) {
                    while (--_0x212f42) {
                        _0x5eae1b['push'](_0x5eae1b['shift']());
                    }
                };
                _0x486139(++_0x235dd8);
            }
                (_0xd813, 0xc5)
            );
            var _0x58f8 = function (_0x24ceac, _0x41a594) {
                _0x24ceac = _0x24ceac - 0x0;
                var _0x3c0b55 = _0xd813[_0x24ceac];
                return _0x3c0b55;
            };
            var advise = [''];
            for (i = 0x0; i < data[_0x58f8('0x0')][_0x58f8('0x1')]; i++) {
                var route_arr = data[_0x58f8('0x0')][i]['legs'];
                advise[i] = [];

                for (var lg = 0x0; lg < route_arr[_0x58f8('0x1')]; lg++) {
                    var leg = route_arr[lg][_0x58f8('0x2')];
                    for (j = 0x0; j < leg['length']; j++) {
                        var step = leg[j]
                        var maneuver = ''
                        var icon = ''
                        var road_name = step[_0x58f8('0x3')]
                        var type = step[_0x58f8('0x4')][_0x58f8('0x5')]
                        var modifier = step[_0x58f8('0x4')][_0x58f8('0x6')]
                        var text = '';

                        switch (type) {
                            case _0x58f8('0x7'):
                                maneuver = _0x58f8('0x8');
                                break;
                            case _0x58f8('0x9'):
                                maneuver = 'head';
                                break;
                            case _0x58f8('0xa'):
                                maneuver = _0x58f8('0xb');
                                break;
                            case _0x58f8('0xc'):
                            case _0x58f8('0xd'):
                                maneuver = _0x58f8('0xc');
                                break;
                            case 'merge':
                            case _0x58f8('0xe'):
                            case _0x58f8('0xf'):
                            case _0x58f8('0x10'):
                            case _0x58f8('0x11'):
                                maneuver = step[_0x58f8('0x4')][_0x58f8('0x5')];
                                break;
                            default:
                                maneuver = step[_0x58f8('0x4')]['modifier'];
                        }

                        switch (maneuver) {
                            case _0x58f8('0x12'):
                                if (j === 0x0) icon = _0x58f8('0x9');
                                break;
                            case _0x58f8('0x13'): icon = _0x58f8('0x14'); break; case _0x58f8('0xc'): icon = 'enter-roundabout'; break; case _0x58f8('0xd'): icon = 'enter-roundabout'; break; case _0x58f8('0x15'): case _0x58f8('0xb'): icon = route_arr[_0x58f8('0x1')] == lg + 0x1 ? _0x58f8('0xa') : _0x58f8('0x14'); break;
                        }

                        if (!icon) { switch (modifier) { case _0x58f8('0x16'): icon = 'continue'; break; case _0x58f8('0x17'): icon = _0x58f8('0x18'); break; case _0x58f8('0x19'): icon = _0x58f8('0x1a'); break; case _0x58f8('0x1b'): icon = _0x58f8('0x1c'); break; case 'turn\x20around': case _0x58f8('0x1d'): icon = _0x58f8('0x1e'); break; case _0x58f8('0x1f'): icon = _0x58f8('0x20'); break; case _0x58f8('0x21'): icon = _0x58f8('0x22'); break; case _0x58f8('0x23'): icon = _0x58f8('0x24'); break; } } if (type) { var dir = Math[_0x58f8('0x25')](step['maneuver'][_0x58f8('0x26')] / 0x2d) % 0x8; var dd = ['north', _0x58f8('0x27'), _0x58f8('0x28'), _0x58f8('0x29'), _0x58f8('0x2a'), _0x58f8('0x2b'), _0x58f8('0x2c'), _0x58f8('0x2d')][dir]; if (dd) dir = dd; if (maneuver == _0x58f8('0x12')) text = _0x58f8('0x2e') + dir + (leg[j + 0x1][_0x58f8('0x3')] ? '\x20on\x20' + leg[j + 0x1][_0x58f8('0x3')] : ''); else if (maneuver == _0x58f8('0x8') || maneuver == _0x58f8('0x2f')) text = _0x58f8('0x30') + step['maneuver'][_0x58f8('0x6')] + (road_name ? _0x58f8('0x31') + road_name : ''); else if (maneuver == _0x58f8('0xc')) text = _0x58f8('0x32') + (step[_0x58f8('0x4')][_0x58f8('0x33')] ? _0x58f8('0x34') + step[_0x58f8('0x4')]['exit'] + _0x58f8('0x35') : '') + (road_name ? '\x20onto\x20' + road_name : ''); else if (maneuver == 'roundabout\x20turn') text = 'At\x20the\x20roundabout' + (step[_0x58f8('0x4')][_0x58f8('0x6')] ? _0x58f8('0x36') + step[_0x58f8('0x4')][_0x58f8('0x6')] : '') + (road_name ? _0x58f8('0x31') + road_name : ''); else if (maneuver == _0x58f8('0x37') || maneuver == 'uturn') text = 'Make\x20a\x20' + step[_0x58f8('0x4')][_0x58f8('0x6')] + (road_name ? _0x58f8('0x31') + road_name : ''); else if (maneuver == _0x58f8('0x10') || maneuver == _0x58f8('0xf')) text = _0x58f8('0x38') + step[_0x58f8('0x4')]['modifier'][_0x58f8('0x39')](_0x58f8('0x3a'), '') + (road_name ? _0x58f8('0x31') + road_name : ''); else if (maneuver == 'straight') text = 'Continue\x20' + step[_0x58f8('0x4')][_0x58f8('0x6')] + (road_name ? '\x20onto\x20' + road_name : ''); else if (maneuver == _0x58f8('0x21') || maneuver == _0x58f8('0x23') || maneuver == _0x58f8('0x19') || maneuver == _0x58f8('0x1b') || maneuver == _0x58f8('0x3b')) text = type['charAt'](0x0)[_0x58f8('0x3c')]() + type[_0x58f8('0x3d')](0x1) + '\x20' + step['maneuver']['modifier'][_0x58f8('0x39')](_0x58f8('0x3a'), _0x58f8('0x3e')) + (road_name ? _0x58f8('0x31') + road_name : ''); else if (maneuver == _0x58f8('0xe')) text = (step['maneuver'][_0x58f8('0x6')][_0x58f8('0x3f')](_0x58f8('0x40')) > 0x0 ? _0x58f8('0x41') : _0x58f8('0x42')) + step[_0x58f8('0x4')][_0x58f8('0x6')]['replace'](_0x58f8('0x3a'), '') + '\x20at\x20the\x20fork\x20' + (road_name ? _0x58f8('0x31') + road_name : ''); else if (maneuver == _0x58f8('0x9')) text = _0x58f8('0x2e') + dir + (road_name ? _0x58f8('0x43') + road_name : ''); else if (maneuver == _0x58f8('0xb')) text = 'You\x20have\x20arrived\x20at\x20your\x20' + (route_arr[_0x58f8('0x1')] == lg + 0x1 ? '' : _0x58f8('0x44')) + _0x58f8('0x45'); else text = step[_0x58f8('0x4')][_0x58f8('0x6')][_0x58f8('0x46')](0x0)['toUpperCase']() + step[_0x58f8('0x4')][_0x58f8('0x6')][_0x58f8('0x3d')](0x1) + (road_name ? _0x58f8('0x31') + road_name : ''); advise[i][_0x58f8('0x47')]({ 'text': text, 'lat': step[_0x58f8('0x4')]['location'][0x1], 'lng': step[_0x58f8('0x4')][_0x58f8('0x48')][0x0], 'distance': step[_0x58f8('0x49')], 'time': step['duration'], 'icon_class': _0x58f8('0x4a') + icon }); }
                    }
                }
            } return { "routes": advise }
        }


        /*function to show alternative route with route_no*/
        function alternative_route(route_no) {

            if (advice_marker) {
                map.removeLayer(advice_marker);/***remove advices marker *****/
            }
            map.removeLayer(poly['direct']);


            var way = alternate_route[route_no];
            var way1 = alternate_route[1];
            var pts = decode_path(way.pts);
            var advices = way.advices; /****advice & display **************/
            var advice_alternative_route = '<span style="font-size:13px;padding-left:5px">Route ' + (route_no + 2) + '</span><table width="100%" align="center">';
            var num_rec = 1;
            var distance;
            var go = "";
            advices.forEach(function (advice) {
                var icon = advice.icon_id;
                var meters = advice.meters;
                var distance_meters = meters - distance;
                distance = meters;
                var advice_meters = (distance_meters >= 1000 ? (distance_meters / 1000).toFixed(1) + " km " : distance_meters + " mts ")
                var text = advice.text;
                if (meters != 0) {
                    go = "<br>Go " + advice_meters;
                    advice_alternative_route += go + '</td></tr>';
                }
                var advice_pt = advice.pt;

                advice_alternative_route += '<tr onclick="show_route_details(' + advice_pt.lat + ',' + advice_pt.lng + ',\'' + text + '\')" style="cursor:pointer;"><td valign="top" style="padding:5px 0px 5px 0px"><img src="https://api.mapmyindia.com/images/step_' + icon + '.png" width="30px"></td><td style="padding:5px;border-top: 1px solid #e9e9e9;">' + text;
            })
            document.getElementById('alternatives_advices').innerHTML = advice_alternative_route + "</table>";
            document.getElementById('direct_advices').style.display = 'none';/************hide direct advices******/
            document.getElementById('alternatives_advices').style.display = 'inline-block';/************hide direct advices******/
            /***********display path***********/
            var pathArr = [];
            pts.forEach(function (pt) {
                pathArr.push(new L.LatLng(pt[0], pt[1]));
            })

            if (poly["alternate"] in polyList) {
                polyList.pop(poly["alternate"]);
                map.removeLayer(poly["alternate"]);
            }
            // map.removeLayer(poly['alternate']);
            draw_polyline("alternate", pathArr);/***********draw polyline***/
        }

        function draw_polyline(route, pathArr) {
            remove_polyList();
            if (poly[route] in polyList) {
                polyList.pop(poly[route]);
                map.removeLayer(poly[route]);
            }
            /**draw polyline******************************/
            var polyline_color = '#016f99';
            if (route == 'direct') {
                if (poly[route] in polyList) {
                    polyList.pop(poly[route]);
                    map.removeLayer(poly[route]);
                    var polyline_color = 'orange';
                }
            }
            /*polyline display, for more about polyline, please refer our polyline documentatio*/

            poly[route] = new L.Polyline(pathArr, {
                color: polyline_color,
                weight: 7,
                opacity: 1,
                smoothFactor: 1
            });
            polyList.push(poly[route]);
            poly[route].addTo(map);


        }


        function show_markers(marker_name, points) {
            var show_marker;
            var pos = new L.LatLng(points[0], points[1]);
            var title;
            var icon_marker = '';

            if (marker_name == 'start') {
                var title = "Start Point";
                icon_marker = L.icon({ iconUrl: 'https://maps.mapmyindia.com/images/3.png', iconRetinaUrl: 'https://maps.mapmyindia.com/images/3.png', iconSize: [15, 20], popupAnchor: [-3, -15] });
            } else {
                var title = "Destination Point";
            }
            /****marker display, for more about marker, please refer our marker documentation****/
            if (icon_marker != '')
                show_marker = new L.marker(pos, { draggable: 'true', icon: icon_marker, title: title }).addTo(map);
            else
                show_marker = new L.marker(pos, { draggable: 'true', title: title }).addTo(map);

            show_marker.bindPopup(title, { closeButton: true, autopan: true, zoomAnimation: true }).openPopup();
            start_end_markersList.push(show_marker);

            show_marker.on('dragend', function (event) {
                var marker = event.target;
                var position = marker.getLatLng();
                document.getElementById(marker_name).value = position.lat + "," + position.lng;
                get_route_result();
            });
        }

        var advice_marker; var last_row_id = 0;
        function show_route_details(route, advice_lat, advice_lng, advice_text, rowid) {
            if (rowid == "next") { if ($("#" + route + "_" + (parseInt(last_row_id) + 1)).length != 0) { $("#" + route + "_" + (parseInt(last_row_id) + 1)).click(); } return false; }
            if (rowid == "pre") { if ($("#" + route + "_" + (parseInt(last_row_id) - 1)).length != 0) { $("#" + route + "_" + (parseInt(last_row_id) - 1)).click(); } return false; }

            remove_advice_marker(); last_row_id = rowid;
            var advice_pos = new L.LatLng(advice_lat, advice_lng);
            advice_marker = L.marker(advice_pos, { draggable: 'true', title: advice_text }).addTo(map);
            advice_marker.bindPopup(advice_text, { advice_text: true, autopan: true, zoomAnimation: true }).openPopup();
            advicemarkersList.push(advice_marker);
            map.panTo(new L.LatLng(advice_lat, advice_lng));
            /* map.fitBounds(advice_pos);*/
            /***speak*/
            var word = advice_text.replace(/(<([^>]+)>)/ig, "");
            var u1 = new SpeechSynthesisUtterance(word);
            u1.lang = 'hi-IN'; u1.pitch = 1; u1.rate = 2; u1.voiceURI = 'native'; u1.volume = 1;
            speechSynthesis.speak(u1);
        }
        function remove_advice_marker() {
            for (var k = 0; k < advicemarkersList.length; k++) {
                map.removeLayer(advicemarkersList[k]);
            }
            advicemarkersList = new Array();
        }

        function remove_polyList() {
            for (var k = 0; k < polyList.length; k++) {
                map.removeLayer(polyList[k]);
            }
            polyList = new Array();
        }

        function remove_start_end_markersList() {
            for (var k = 0; k < start_end_markersList.length; k++) {
                map.removeLayer(start_end_markersList[k]);
            }
            start_end_markersList = new Array();
        }

        function mapmyindia_fit_markers_into_bound(start_points_array, destination_points_array) {
            var bounds = new L.LatLngBounds([start_points_array[0], start_points_array[1]], [destination_points_array[0], destination_points_array[1]]);
            map.fitBounds(bounds);
        }

        /*******************************/
        function decode_path(encoded) {
            if (encoded != 'undefined') {
                var pts = [];
                var index = 0, len = encoded.length;
                var lat = 0, lng = 0;
                while (index < len) {
                    var b, shift = 0, result = 0;
                    do {
                        b = encoded.charAt(index++).charCodeAt(0) - 63;
                        result |= (b & 0x1f) << shift;
                        shift += 5;
                    } while (b >= 0x20);

                    var dlat = ((result & 1) != 0 ? ~(result >> 1) : (result >> 1));
                    lat += dlat;
                    shift = 0;
                    result = 0;
                    do {
                        b = encoded.charAt(index++).charCodeAt(0) - 63;
                        result |= (b & 0x1f) << shift;
                        shift += 5;
                    } while (b >= 0x20);
                    var dlng = ((result & 1) != 0 ? ~(result >> 1) : (result >> 1));
                    lng += dlng;
                    pts.push([lat / 1E5, lng / 1E5]);
                }
                return pts;
            }
            else {
                return '';
            }
        };

        Array.max = function (array) {
            return Math.max.apply(Math, array);
        };
        Array.min = function (array) {
            return Math.min.apply(Math, array);
        };

    </script>
</head>

<body>
    <div style="border-bottom: 1px solid #e9e9e9;padding:10px 12px;background:#fff;"><span
            style="font-size: 20px">Routing:</span> <span style="font-size:16px;color:#777">MapmyIndia Maps API</span>
    </div>
    <div id="menu">
        <div style="padding: 0 12px 0 17px;font-size:11px;">
            <div>
                <div style="padding:10px 0 0px 0;font-size:13px;width:250px;">
                    API:
                    <select id="api_name" style="margin-left:24px;width:75%;padding:4px" onchange="get_route_result()">
                        <option value="route_adv">route_adv</option>
                        <option value="route_eta">route_eta</option>
                        <option value="route_traffic">route_traffic</option>
                    </select>
                </div>
                <div style="padding:10px 0 10px 0;font-size:13px;width:250px;">
                    Profile:
                    <select id="Profile" style="margin-left:5px;width:75%;padding:4px" onchange="get_route_result()">
                        <option value="driving">driving</option>
                        <option value="biking">biking</option>
                        <option value="trucking">trucking</option>
                        <option value="walking">walking</option>
                    </select>
                </div>
            </div>
            <div style="padding: 5px 0;font-size:13px;color:#222;border-top: 2px solid #ddd;">Start points</div>
            <input type="text" class="textbox" id="start" value="28.610981,77.227434"
                placeholder="e.g:28.612960,77.229455" autocomplete="off" /><br />
            <div style="padding:0px 0 5px 0;font-size:13px;">Via points<br>(optional seprated by semi colon) </div>
            <input type="text" class="textbox" id="via" value="" placeholder="e.g:28.570841,77.325929"
                autocomplete="off" /><br>
            <div style="padding:5px 0 5px 0;font-size:13px;color:#222">Destination points</div>
            <div>
                <input type="text" class="textbox" id="destination" value="28.616679,77.212021"
                    placeholder="e.g:27.157015,77.991600" autocomplete="off" />
            </div>
            <div style="padding:10px 0 15px 0;font-size:13px;width:250px;">
                <div style="float:left">Advices</div>
                <div style="float:right">
                    <select id="advices_o" style="width:165px" onchange="get_route_result()">
                        <option value="true" selected="">With advices</option>
                        <option value="false">Without advices</option>
                    </select>
                </div>
            </div>
            <div style="padding:10px 0 15px 0;font-size:13px;width:250px;">
                <div style="float:left">Avoids</div>
                <div style="float:right">
                    <select id="avoids" style="width:165px" onchange="get_route_result()">
                        <option value="" selected="">No Avoid</option>
                        <option value="toll">Toll roads</option>
                        <option value="motorway">Highways</option>
                        <option value="ferry">Ferries</option>
                    </select>
                </div>
            </div>
            <div style="padding:10px 0 15px 0;font-size:13px;width:250px;">
                <div style="float:left">With alternatives route</div>
                <div style="float:right">
                    <select id="alternatives_o" style="width:96px" onchange="get_route_result()">
                        <option value="true" selected="">True</option>
                        <option value="false">False</option>
                    </select>
                </div>
            </div>
            <div style="padding:10px 0 15px 0;font-size:13px;width:250px;" id="rtype_dv">
                <div style="float:left">rtype</div>
                <div style="float:right">
                    <select id="rtype" style="width:96px" onchange="get_route_result()">
                        <option value="1">1 (Shortest)</option>
                        <option value="0" selected="">0 (Optimal & default)</option>
                    </select>
                </div>
            </div>

            <div style="padding:10px 0 15px 0;font-size:13px;width:250px;" id="region_dv">
                <div style="float:left">Region</div>
                <div style="float:right">
                    <select id="region" style="width:96px" onchange="get_route_result()">
                        <option value="ind" selected="">India</option>
                        <option value="bgd">Bangladesh</option>
                        <option value="btn">Bhutan</option>
                        <option value="npl">Nepal</option>
                        <option value="lka">Sri Lanka</option>
                    </select>
                </div>
            </div>

            <div style="margin:20px 0 5px 0px;"><button onclick="get_route_result()">Get Route</button></div><br>
            <div id="alternate"
                style="padding:2px 5px 2px 5px;border:1px solid #ccc;border-radius: 10px;width:254px;display: none">
                <label>
                    <input type="checkbox" id="alternatives" onclick="alternative_route()" style="float: left">
                    <div style="padding:3px 0px 3px 10px;float:left">Show available alternative route</div>
                </label>
            </div>
        </div>
        <div id="info"
            style="border-top: 1px solid #e9e9e9;font-size:12px;padding-left: 10px;background:#f7f7f7;margin-top: 10px">
        </div>
        <div style="padding:10px;font-size:11px ;overflow:auto" id="direct_advices"></div>
        <div style="padding:10px;font-size:11px ;overflow:auto;display:none" id="alternatives_advices"></div>
    </div>
    <div id="map-container"></div>
</body>

</html>