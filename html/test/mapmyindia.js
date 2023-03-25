var map = null;
var marker = [];
var latitudeArr = [28.549948, 28.552232, 28.551748, 28.551738, 28.548602, 28.554603, 28.545639, 28.544339, 28.553196, 28.545842];
var longitudeArr = [77.268241, 77.268941, 77.269022, 77.270164, 77.271546, 77.268305, 77.26480, 77.26424, 77.265407, 77.264195];
//var pth = window.location.href; /*get path of image folder*/
var full_path = "http://www.mapmyindia.com/api/advanced-maps/doc/sample";
window.onload = function() {
  map = new MapmyIndia.Map('map', {
    center: [28.549948, 77.268241],
    zoomControl: true,
    hybrid: true
  });
  /***
   1. Create a MapmyIndia Map by simply calling new MapmyIndia.Map() and passing it a html div id, all other parameters are optional...
   2. All leaflet mapping functions can be called simply by using "L" object.
   3. In future, MapmyIndia may extend the customised/forked Leaflet object to enhance mapping functionality for developers, 
   which will be clearly documented in the MapmyIndia API documentation section.
   ***/

  /***map-events****/
  map.on("dblclick", function(e) {
    var title = "Text marker sample!";
    marker.push(addMarker(e.latlng, "", title));
  });
};

function addMarker(position, icon, title, draggable) {
  /* position must be instance of L.LatLng that replaces current WGS position of this object. Will always return current WGS position.*/
  var event_div = document.getElementById("event-log");
  if (icon == '') {
    var mk = new L.Marker(position, {
      draggable: draggable,
      title: title
    }); /*marker with a default icon and optional param draggable, title */
    mk.bindPopup(title);
  } else {
    var mk = new L.Marker(position, {
      icon: icon,
      draggable: draggable,
      title: title
    }); /*marker with a custom icon */
    mk.bindPopup(title);
  }
  map.addLayer(mk); /*add the marker to the map*/
  /* marker events:*/
  mk.on("click", function(e) {
    event_div.innerHTML = "Marker clicked<br>" + event_div.innerHTML;
  });

  return mk;
}

function mapmyindia_fit_markers_into_bound() {
  var maxlat = Math.max.apply(null, latitudeArr);
  var maxlon = Math.max.apply(null, longitudeArr);
  var minlat = Math.min.apply(null, latitudeArr);
  var minlon = Math.min.apply(null, longitudeArr);
  var southWest = L.latLng(maxlat, maxlon); /*south-west WGS location object*/
  var northEast = L.latLng(minlat, minlon); /*north-east WGS location object*/
  var bounds = L.latLngBounds(southWest, northEast); /*This class represents bounds on the Earth sphere, defined by south-west and north-east corners*/
  map.fitBounds(bounds); /*Sets the center map position and level so that all markers is the area of the map that is displayed in the map area*/
}

$("#removeMarker").click(function() {
  mapmyindia_removeMarker();
});

/*function to remove  markers from map*/

function mapmyindia_removeMarker() {
  var markerlength = marker.length;
  if (markerlength > 0) {
    for (var i = 0; i < markerlength; i++) {
      map.removeLayer(marker[i]); /* deletion of marker object from the map */
    }
  }
  delete marker;
  marker = [];
  document.getElementById("event-log").innerHTML = "";
}
/*function to add default marker*/
$("#mapmyindia_sample_marker").click(function(){
    mapmyindia_removeMarker(); /*Remove marker if exists on map*/
    var postion = new L.LatLng(28.5628, 77.6856); /*The WGS location object*/
    var title = "Sample marker!";
    var mk = addMarker(postion, "", title, false); /*call the add marker function with the position and title*/
    marker.push(mk);
    map.setView(mk.getLatLng(), 8); /*function that modifies both center map position and zoom level.*/
  });

  /*function to add custom userdefined marker at a given path*/
  $("#mapmyindia_custom_marker").click(function(){
    mapmyindia_removeMarker();
    var icon = L.icon({
      iconUrl: full_path + '/images/MarkerIcon.png',
      iconRetinaUrl: full_path + '/images/MarkerIcon.png',
      iconSize: [30, 30],
      popupAnchor: [-3, -15]
    });
    var postion = new L.LatLng(28.5628, 77.6856); /*WGS location object*/
    var mk = addMarker(postion, icon, "Custom marker sample!", false);
    marker.push(mk);
    map.setView(mk.getLatLng());
  });

$("#mapmyindia_multiple_markers").click(function() {
  mapmyindia_removeMarker();
  for (var i = 0; i < latitudeArr.length; i++) {
    var postion = new L.LatLng(latitudeArr[i], longitudeArr[i]); /*WGS location object*/
    marker.push(addMarker(postion, '', "Multiple markersample !"));
  }
  mapmyindia_fit_markers_into_bound();
});

/*function to make number appear on marker*/
$("#mapmyindia_number_on_marker").click(function() {
  mapmyindia_removeMarker();
  for (var i = 0; i < latitudeArr.length; i++) {
    var title = "Number marker Sample!";
    var icon = L.divIcon({
      className: 'my-div-icon',
      html: "<img class='map_marker'  src=" + "'https://maps.mapmyindia.com/images/2.png'>" + '<span class="my-div-span">' + (i + 1) + '</span>',
      iconSize: [10, 10],
      popupAnchor: [12, -10]
    }); /*function that creates a div over a icon and display content on the div*/
    var postion = new L.LatLng(latitudeArr[i], longitudeArr[i]); /*WGS location object*/
    marker.push(addMarker(postion, icon, title));
  }
  mapmyindia_fit_markers_into_bound();
});

/*function to make text appear on marker*/
$("#mapmyindia_text_on_marker").click(function() {
  mapmyindia_removeMarker();
  for (var i = 0; i < latitudeArr.length; i++) {
    var title = "Text marker sample!";
    var icon = L.divIcon({
      className: 'my-div-icon',
      html: "<img class='map_marker' src=" + "'https://maps.mapmyindia.com/images/2.png'>" + '<span class="my-div-span">' + 'M' + '</span>',
      //html: "<img style='position:relative;width:34px;height:48px' src=" + "'https://maps.mapmyindia.com/images/2.png'>" + '<span style="position: absolute;left:1.5em;right: 1em;top:0.9em;bottom:3em; font-size:9px;font-weight:bold; width: 4px; color:black;" class="my-div-span">' + 'M' + '</span>',
      iconSize: [10, 10],
      popupAnchor: [12, -10]
    }); /*function that creates a div over a icon and display content on the div*/
    var postion = new L.LatLng(latitudeArr[i], longitudeArr[i]); /*WGS location object*/
    marker.push(addMarker(postion, icon, title));
  }
  mapmyindia_fit_markers_into_bound();
});

//*function to add custom userdefined marker say a arrow at a given angle*/
$("#mapmyindia_Arrow_marker").click(function() {
  mapmyindia_removeMarker();
  var angle = 45;
  var icon = L.icon({
    iconUrl: full_path + '/images/arrow.png',
    iconRetinaUrl: full_path + '/images/MarkerIcon.png',
    iconSize: [30, 30],
    popupAnchor: [-3, -15]
  });
  var m = L.marker(new L.LatLng(28.551738, 77.269022), {
    icon: icon,
    draggable: true,
    rotationAngle: angle
  }).addTo(map);
  marker.push(m);
  map.setView(m.getLatLng()); /*get the wgs locaton from marker and set the location into center*/
  var event_div = document.getElementById("event-log");
  event_div.innerHTML = "Arrow marker at an angle:" + angle;

});

/*function to make  marker draggable*/
$("#mapmyindia_draggable_marker").click(function() {
  mapmyindia_removeMarker();
  var postion = new L.LatLng(28.5628, 77.6856); /*WGS location object*/
  var mk = addMarker(postion, '', "Draggable marker sample", true); /*call the add marker function*/
  var event_div = document.getElementById("event-log");
  event_div.innerHTML = "Draggable Marker created, drag the marker to the new position.";
  /* following events can be assigned handler (for every instance of draggable marker(s))*/
  mk.on("dragstart", function(e) {
    event_div.innerHTML = "Marker drag start<br>" + event_div.innerHTML;
  });
  mk.on("dragend", function(e) {
    var pt = e.target._latlng; /*event returns lat lng of dragged position*/
    mk.setLatLng(pt); /*set marker position to dragged position*/
    event_div.innerHTML = "Draggable:</br> lat:" + pt.lat + "</br>lng:" + pt.lng + "</br>";
  });
  marker.push(mk);
  map.setView(mk.getLatLng()); /*get the wgs locaton from marker and set the location into center*/
});






/* var map = null;
var poly2 = null;
var poly = [];
var visbility = false;
var polyline1, polyline2;
var event_div = document.getElementById("event-log"); //log element
var visible = document.getElementById("visible"); //show hide polyline button

window.onload = function() {
  var center = new L.LatLng(28.549948, 77.268241);
  map = new MapmyIndia.Map('map', {
    center: center,
    editable: true,
    zoomControl: true,
    hybrid: true
  });
  **map initialization with tiles of MapmyIndia with MapmyIndia.Map**
  **
   1. Create a MapmyIndia Map by simply calling new MapmyIndia.Map() and passing it a html div id, all other parameters are optional...
   2. All leaflet mapping functions can be called simply by using "L" object.
   3. In future, MapmyIndia may extend the customised/forked Leaflet object to enhance mapping functionality for developers, 
   which will be clearly documented in the MapmyIndia API documentation section.
   **

  array of wgs points
  var pts1 = [
    new L.LatLng(center.lat - 150 / 10000, center.lng - 150 / 10000),
    new L.LatLng(center.lat + 0 / 10000, center.lng - 50 / 10000),
    new L.LatLng(center.lat + 50 / 10000, center.lng - 100 / 10000),
    new L.LatLng(center.lat + 70 / 10000, center.lng + 50 / 10000),
    new L.LatLng(center.lat - 70 / 10000, center.lng + 100 / 10000)
  ];

  array of wgs points
  var pts2 = [
    new L.LatLng(center.lat - 150 / 10000, center.lng - 450 / 10000),
    new L.LatLng(center.lat + 0 / 10000, center.lng - 350 / 10000),
    new L.LatLng(center.lat + 50 / 10000, center.lng - 500 / 10000),
    new L.LatLng(center.lat + 70 / 10000, center.lng - 250 / 10000),
    new L.LatLng(center.lat - 70 / 10000, center.lng - 200 / 10000),
    new L.LatLng(center.lat - 70 / 10000, center.lng + 100 / 10000)
  ];

  parameters of polyline
  var poly1param = {
    weight: 4,
    opacity: 0.5
  };
  var poly1 = new L.Polyline(pts1, poly1param); polyline with given points and default color is created
  map.addLayer(poly1);

  var poly2param = {
    color: "red",
    weight: 4,
    opacity: 0.5
  };

  poly2 = new L.Polyline(pts2, poly2param); polyline with given points and red color is created
  map.addLayer(poly2);

  ** zoom the map to the polyline*
  map.fitBounds(poly2.getBounds());

  //extend the L.Control  to Edit control to draw editable polyline
  L.EditControl = L.Control.extend({
    options: {
      position: 'topleft',
      callback: null,
      kind: '',
      html: ''
    },
    onAdd: function(map) {
      var container = L.DomUtil.create('div', 'leaflet-control leaflet-bar'),
        link = L.DomUtil.create('a', '', container);

      link.href = '#';
      link.title = 'Create a new ' + this.options.kind;
      link.innerHTML = this.options.html;
      L.DomEvent.on(link, 'click', L.DomEvent.stop)
        .on(link, 'click', function() {
          window.LAYER = this.options.callback.call(map.editTools);
        }, this);

      return container;
    }

  });
  //at a control polyline to control
  L.NewLineControl = L.EditControl.extend({
    options: {
      position: 'topleft',
      callback: map.editTools.startPolyline,
      kind: 'line',
      html: 'Line'
    }
  });
  // add the control to map
  map.addControl(new L.NewLineControl());

  var deleteShape = function(e) {

    if ((e.originalEvent.ctrlKey || e.originalEvent.metaKey) && this.editEnabled()) {
      this.editor.deleteShapeAt(e.latlng);
    }

  };
  map.on('layeradd', function(e) {
    if (e.layer instanceof L.Path) //delete shape
      e.layer.on('click', L.DomEvent.stop).on('click', deleteShape, e.layer);
  });
};

$("#map_create_polyline").click(function() {
  event_div.innerHTML = "";
  var center = new L.LatLng(28.549948, 77.268241);
  var pts1 = [
    new L.LatLng(center.lat - 500 / 10000, center.lng - 400 / 10000),
    new L.LatLng(center.lat + 500 / 10000, center.lng - 400 / 10000),
    new L.LatLng(center.lat + 500 / 10000, center.lng - 400 / 10000),
    new L.LatLng(center.lat + 500 / 10000, center.lng - 400 / 10000),
    new L.LatLng(center.lat - 500 / 10000, center.lng - 400 / 10000),
    new L.LatLng(center.lat - 700 / 10000, center.lng + 400 / 10000)
  ]; array of wgs points
  var pts2 = [
    new L.LatLng(center.lat - 200 / 10000, center.lng - 200 / 10000),
    new L.LatLng(center.lat + 0 / 10000, center.lng - 400 / 10000),
    new L.LatLng(center.lat + 250 / 10000, center.lng - 125 / 10000),
    new L.LatLng(center.lat + 250 / 10000, center.lng - 125 / 10000),
    new L.LatLng(center.lat - 250 / 10000, center.lng - 125 / 10000),
    new L.LatLng(center.lat - 250 / 10000, center.lng + 125 / 10000)
  ]; array of wgs points

  var poly1param = {
    color: 'blue',
    weight: 4,
    opacity: 0.5
  };
  var poly2param = {
    color: 'yellow',
    weight: 4,
    opacity: 0.5
  };

  removePolyline();

  polyline1 = new L.Polyline(pts1, poly1param); polyline with given points and blue color is created
  map.addLayer(polyline1); add polyline to map

  polyline2 = new L.Polyline(pts2, poly2param); polyline with given points and yellow color is created
  map.addLayer(polyline2);

  map.fitBounds(polyline1.getBounds());

  poly.push(polyline1);
  poly.push(polyline2);
  visbility = true;
  visible.innerHTML = "Hide Polylines";
});
$("#map_get_bounds").click(function() {
  var bounds = poly2.getBounds();
  event_div.innerHTML = "";
  event_div.innerHTML = "Current bound: </br> north east lat:" + bounds.getNorthEast().lat +
    "</br> north east lng:" + bounds.getNorthEast().lng + "</br>south west lat:" +
    bounds.getSouthWest().lat + "</br>south west lng:" + bounds.getSouthWest().lng + "</br>" + event_div.innerHTML;
});
$("#map_set_unvisible_polylines").click(function() {
  var polylength = poly.length;
  if (polylength > 0) {
    visible.innerHTML = visbility ? "Show Polylines" : "Hide Polylines";
    If false, this polyline is not drawn in DOM. if true polyline is drawn in DOM
    if (visbility) {
      for (var i = 0; i < polylength; i++) {
        map.removeLayer(poly[i]);
      }
    } else {
      for (var i = 0; i < polylength; i++) {
        map.addLayer(poly[i])
      }
    }
  } else {
    event_div.innerHTML = "";
    event_div.innerHTML = "Please add polylines first by clicking \"Add Polylines\" button";
  }
  visbility = visbility ? false : true;
});
var removePolyline = function() {
  var polylength = poly.length;
  if (polylength > 0) {
    for (var i = 0; i < polylength; i++) {
      map.removeLayer(poly[i]);
    }
    poly = new Array();
  }
}
 */
 
 
 
var map = null;
var poly2 = null;
var poly = [];
var visbility = false;
var polyline1, polyline2;
var event_div = document.getElementById("event-log"); //log element
var visible = document.getElementById("visible"); //show hide polyline button

window.onload = function () {
    var center = new L.LatLng(28.549948, 77.268241);
    map = new MapmyIndia.Map('map', {
        center: center,
        editable: true,
        zoomControl: true,
        hybrid: true
    });
    /***map initialization with tiles of MapmyIndia with MapmyIndia.Map***/
    /***
     1. Create a MapmyIndia Map by simply calling new MapmyIndia.Map() and passing it a html div id, all other parameters are optional...
     2. All leaflet mapping functions can be called simply by using "L" object.
     3. In future, MapmyIndia may extend the customised/forked Leaflet object to enhance mapping functionality for developers, 
     which will be clearly documented in the MapmyIndia API documentation section.
     ***/

    /*array of wgs points*/
    var pts1 = [
        new L.LatLng(center.lat - 150 / 10000, center.lng - 150 / 10000),
        new L.LatLng(center.lat + 0 / 10000, center.lng - 50 / 10000),
        new L.LatLng(center.lat + 50 / 10000, center.lng - 100 / 10000),
        new L.LatLng(center.lat + 70 / 10000, center.lng + 50 / 10000),
        new L.LatLng(center.lat - 70 / 10000, center.lng + 100 / 10000)
    ];

    /*array of wgs points*/
    var pts2 = [
        new L.LatLng(center.lat - 150 / 10000, center.lng - 450 / 10000),
        new L.LatLng(center.lat + 0 / 10000, center.lng - 350 / 10000),
        new L.LatLng(center.lat + 50 / 10000, center.lng - 500 / 10000),
        new L.LatLng(center.lat + 70 / 10000, center.lng - 250 / 10000),
        new L.LatLng(center.lat - 70 / 10000, center.lng - 200 / 10000),
        new L.LatLng(center.lat - 70 / 10000, center.lng + 100 / 10000)
    ];

    /*parameters of polyline*/
    var poly1param = {
        weight: 4,
        opacity: 0.5
    };
    var poly1 = new L.Polyline(pts1, poly1param); /*polyline with given points and default color is created*/
    map.addLayer(poly1);

    var poly2param = {
        color: "red",
        weight: 4,
        opacity: 0.5
    };

    poly2 = new L.Polyline(pts2, poly2param); /*polyline with given points and red color is created*/
    map.addLayer(poly2);

    /*** zoom the map to the polyline**/
    map.fitBounds(poly2.getBounds());

    //extend the L.Control  to Edit control to draw editable polyline
    L.EditControl = L.Control.extend({
        options: {
            position: 'topleft',
            callback: null,
            kind: '',
            html: ''
        },
        onAdd: function (map) {
            var container = L.DomUtil.create('div', 'leaflet-control leaflet-bar'),
                link = L.DomUtil.create('a', '', container);

            link.href = '#';
            link.title = 'Create a new ' + this.options.kind;
            link.innerHTML = this.options.html;
            L.DomEvent.on(link, 'click', L.DomEvent.stop)
                .on(link, 'click', function () {
                    window.LAYER = this.options.callback.call(map.editTools);
                }, this);

            return container;
        }

    });
    //at a control polyline to control
    L.NewLineControl = L.EditControl.extend({
        options: {
            position: 'topleft',
            callback: map.editTools.startPolyline,
            kind: 'line',
            html: 'Line'
        }
    });
    // add the control to map
    map.addControl(new L.NewLineControl());

    var deleteShape = function (e) {

        if ((e.originalEvent.ctrlKey || e.originalEvent.metaKey) && this.editEnabled()) {
            this.editor.deleteShapeAt(e.latlng);
        }

    };
    map.on('layeradd', function (e) {
        if (e.layer instanceof L.Path) //delete shape
            e.layer.on('click', L.DomEvent.stop).on('click', deleteShape, e.layer);
    });
};

$("#map_create_polyline").click(function () {
    event_div.innerHTML = "";
    var center = new L.LatLng(28.549948, 77.268241);
    var pts1 = [
        new L.LatLng(center.lat - 500 / 10000, center.lng - 400 / 10000),
        new L.LatLng(center.lat + 500 / 10000, center.lng - 400 / 10000),
        new L.LatLng(center.lat + 500 / 10000, center.lng - 400 / 10000),
        new L.LatLng(center.lat + 500 / 10000, center.lng - 400 / 10000),
        new L.LatLng(center.lat - 500 / 10000, center.lng - 400 / 10000),
        new L.LatLng(center.lat - 700 / 10000, center.lng + 400 / 10000)
    ]; /*array of wgs points*/
    var pts2 = [
        new L.LatLng(center.lat - 200 / 10000, center.lng - 200 / 10000),
        new L.LatLng(center.lat + 0 / 10000, center.lng - 400 / 10000),
        new L.LatLng(center.lat + 250 / 10000, center.lng - 125 / 10000),
        new L.LatLng(center.lat + 250 / 10000, center.lng - 125 / 10000),
        new L.LatLng(center.lat - 250 / 10000, center.lng - 125 / 10000),
        new L.LatLng(center.lat - 250 / 10000, center.lng + 125 / 10000)
    ]; /*array of wgs points*/

    var poly1param = {
        color: 'blue',
        weight: 4,
        opacity: 0.5
    };
    var poly2param = {
        color: 'yellow',
        weight: 4,
        opacity: 0.5
    };

    removePolyline();

    polyline1 = new L.Polyline(pts1, poly1param); /*polyline with given points and blue color is created*/
    map.addLayer(polyline1); /*add polyline to map*/

    polyline2 = new L.Polyline(pts2, poly2param); /*polyline with given points and yellow color is created*/
    map.addLayer(polyline2);

    map.fitBounds(polyline1.getBounds());

    poly.push(polyline1);
    poly.push(polyline2);
    visbility = true;
    visible.innerHTML = "Hide Polylines";
});
$("#map_get_bounds").click(function () {
    var bounds = poly2.getBounds();
    event_div.innerHTML = "";
    event_div.innerHTML = "Current bound: </br> north east lat:" + bounds.getNorthEast().lat +
        "</br> north east lng:" + bounds.getNorthEast().lng + "</br>south west lat:" +
        bounds.getSouthWest().lat + "</br>south west lng:" + bounds.getSouthWest().lng + "</br>" + event_div.innerHTML;
});
$(".map_set_unvisible_polylines").click(function () {
    var polylength = poly.length;
    if (polylength > 0) {
        visible.innerHTML = visbility ? "Show Polylines" : "Hide Polylines";
        /* If false, this polyline is not drawn in DOM. if true polyline is drawn in DOM */
        if (visbility) {
            for (var i = 0; i < polylength; i++) {
                map.removeLayer(poly[i]);
            }
        } else {
            for (var i = 0; i < polylength; i++) {
                map.addLayer(poly[i])
            }
        }
    } else {
        event_div.innerHTML = "";
        event_div.innerHTML = "Please add polylines first by clicking \"Add Polylines\" button";
    }
    visbility = visbility ? false : true;
});
var removePolyline = function () {
    console.log(poly)
    var polylength = poly.length;
    if (polylength > 0) {
        for (var i = 0; i < polylength; i++) {
            map.removeLayer(poly[i]);
        }
        poly = new Array();
    }
}