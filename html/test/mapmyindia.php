<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MapMyIndia</title>
    <style>
        <?php include 'mapmyindia.css' ?>
    </style>
</head>
<body>
    <script src="https://apis.mapmyindia.com/advancedmaps/v1/your_map_key_here/map_load?v=1.3"></script>
    <div class="top-div">
        <span class="top-div-span1">MapmyIndia Maps API: </span>
        <span class="top-div-span2">Map Marker Example</span>
    </div>
    <div id="result">
        <div class="btn-div"><button id="mapmyindia_sample_marker">Add Default Marker</button></div>
        <div class="btn-div"><button id="mapmyindia_custom_marker">Add Custom Marker</button></div>
        <div class="btn-div"><button id="mapmyindia_multiple_markers">Add Multiple Markers</button></div>
        <div class="btn-div"><button id="mapmyindia_number_on_marker">Add Number on Markers</button></div>
        <div class="btn-div"><button id="mapmyindia_text_on_marker">Add Text to Markers</button></div>
        <div class="btn-div"><button id="mapmyindia_Arrow_marker">Arrow Markers</button></div>
        <div class="btn-div"><button id="mapmyindia_draggable_marker">Make Marker Draggable</button></div>
        <div class="btn-div"><button id="removeMarker">Remove Marker(s)</button></div>

        <div class="msg-cont">
            <ul class="msg-list">
            <li>Double click anywhere on the map to add a marker.</li>
            <li>Click/drag marker to see events.</li>
            </ul>
        </div>
        <div class="event-header">Event Logs</div>
        <div id="event-log"></div>
    </div>
    <div id="map"></div>

    <script>
        <?php include 'mapmyindia.js' ?>
    </script>

</body>
</html>