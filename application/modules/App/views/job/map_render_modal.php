<style>
    #menu {
        background: #fff;
        position: absolute;
        z-index: 1;
        top: 10px;
        right: 10px;
        border-radius: 3px;
        width: 120px;
        border: 1px solid rgba(0, 0, 0, 0.4);
        font-family: 'Open Sans', sans-serif;
    }
    
    #menu a {
        font-size: 13px;
        color: #404040;
        display: block;
        margin: 0;
        padding: 0;
        padding: 10px;
        text-decoration: none;
        border-bottom: 1px solid rgba(0, 0, 0, 0.25);
        text-align: center;
    }
    
    #menu a:last-child {
        border: none;
    }
    
    #menu a:hover {
        background-color: #f8f8f8;
        color: #404040;
    }
    
    #menu a.active {
        background-color: #ffffff;
        color: #000000;
    }
    
    #menu a.active:focus {background: blue;color: #ffffff;}


</style>

  <div class="modal-content">
  <div class="modal-body">
            <div id="map" style="width:870px; height: 600px;">
            <nav id="menu"></nav>
            </div>
            <div class="modal-footer-full-width  modal-footer">
                <button type="button" class="btn btn-danger btn-md btn-rounded" data-dismiss="modal">Close</button>
            </div>  
    </div>
 
<script>
    mapboxgl.accessToken = 'pk.eyJ1IjoiYXZzcmFodWwiLCJhIjoiY2thdW93NzE4M2RvZDJzcDY5MXIycXloZCJ9.R5fqaLXG385y58hNFVbd_A';
    var map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v11',
        center: [<?php echo $longitude?> , <?php echo $latitude?>],
        zoom: 15

    });

    
    //var distanceContainer = document.getElementById('distance');
    var marker = new mapboxgl.Marker({ color: '#800020'})
      .setLngLat([<?php echo $longitude?> , <?php echo $latitude?>])
      .addTo(map);

    var marker = new mapboxgl.Marker({ color: '#0000FF'})
      .setLngLat([<?php echo $store_longitude?> , <?php echo $store_latitude?>])
      .addTo(map);

    //   new mapboxgl.Popup()
    //     .setLngLat([<?php echo $longitude?> , <?php echo $latitude?>])
    //     .setHTML("<h6>Null Island</h6>")
    //     .addTo(map);

    //map.addControl(new mapboxgl.NavigationControl());
    
      map.addControl(
        new MapboxDirections({
        accessToken: mapboxgl.accessToken
        }),
        'top-left'
        );

      
    
    map.on('load', function() {
        map.resize();
        map.addSource('points', {
            'type': 'geojson',
            'data': {
            'type': 'FeatureCollection',
            'features': [
                {
                    // feature for Mapbox DC
                    'type': 'Feature',
                    'geometry': {
                        'type': 'Point',
                        'coordinates': [ <?php echo $longitude?> , <?php echo $latitude?>]
                    },
                    'properties': {
                        'title': 'Taster Location '
                    }
                }, {
                    // feature for Mapbox DC
                    'type': 'Feature',
                    'geometry': {
                        'type': 'Point',
                        'coordinates': [<?php echo $store_longitude?> , <?php echo $store_latitude?>]
                    },
                    'properties': {
                        'title': 'Store Location '
                    }
                }
            ]
            }
        });
        map.addLayer({
            'id': 'points',
            'type': 'symbol',
            'source': 'points',
            'layout': {
                  // get the icon name from the source's "icon" property
                  // concatenate the name to get an icon from the style's sprite sheet
                 // 'icon-image': ['concat', ['get', 'icon'], '-15'],
                  // get the title name from the source's "title" property
                  'text-field': ['get', 'title'],
                  'text-font': ['Arial Unicode MS Bold', 'Arial Unicode MS Bold'],
                  'text-offset': [0, 0.6],
                  'text-size': 10,
                  'text-anchor': 'top'
                }
        });


        map.addSource('museums', {
            type: 'vector',
            url: 'mapbox://mapbox.2opop9hr'
        });
        map.addLayer({
            'id': 'museums',
            'type': 'circle',
            'source': 'museums',
            'layout': {
            // make layer visible by default
            'visibility': 'none'
            },
                'paint': {
                'circle-radius': 8,
                'circle-color': 'rgba(55,148,179,1)'
                },
            'source-layer': 'museum-cusco'
        });
 
    // add source and layer for contours
        map.addSource('contours', {
            type: 'vector',
            url: 'mapbox://mapbox.2opop9hr'
        });
        map.addLayer({
            'id': 'contours',
            'type': 'line',
            'source': 'contours',
            'source-layer': 'contour',
            'layout': {
                // make layer visible by default
                'visibility': 'visible',
                'line-join': 'round',
                'line-cap': 'round'
            },
            'paint': {
                'line-color': '#877b59',
                'line-width': 1
            }
        });


        //Store future event buinding is done here 
        var toggleableLayerIds = ['Store Location', 'Taster Location'];
        var store_id = toggleableLayerIds[0];
        var link = document.createElement('a');
        link.href = '#';
        link.className = 'active';
        link.textContent = store_id;
        link.onclick = function(e) { 
            map.flyTo({ center:  [<?php echo $store_longitude?> , <?php echo $store_latitude?>] });
            link.style.background='#FFFFFF'; 
            link.style.color='#000000'; 
         };
        var layers = document.getElementById('menu');
        layers.appendChild(link);
        //Taster future event buinding is done here 
        var taster_id = toggleableLayerIds[1];
        var link = document.createElement('a');
        link.href = '#';
        link.className = 'active';
        link.textContent = taster_id;
        link.onclick = function(e) { 
            map.flyTo({ center:  [<?php echo $longitude?> , <?php echo $latitude?>] }); 
            link.style.background='#800020';
            link.style.color='#ffffff'; 
        };
        var layers = document.getElementById('menu');
        layers.appendChild(link);
        link.click();
        

     });

</script>
