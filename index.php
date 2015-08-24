<?php $conf = json_decode(file_get_contents('private/conf/conf.json'));

try {
    $conf = @json_decode(file_get_contents('private/conf/conf.json'));
    if ($conf == FALSE)
        throw new Exception("Manque le fichier de conf");
} catch (Exception $ex) {
    echo $ex -> getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>Georef-client</title>
		<meta name="description" content="">
		<meta name="author" content="CIMI">

		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<script src="<?php echo $conf -> libs -> ol_js; ?>"></script>
		<link rel="stylesheet" href="<?php echo $conf -> libs -> ol_css; ?>" />

	</head>

	<body>
		<div id="map" class="map"></div>
	</body>
	<script>
        var colors = ['red', 'green', 'blue', 'teal', 'navy', 'lime'];

        var gpxs = [{
            'name' : '2015-08-11T15.gpx',
            'color' : 'red'
        }, {
            'name' : '2015-08-13T13.gpx',
            'color' : 'green'
        }, {
            'name' : '2015-08-14T14.gpx',
            'color' : 'blue'
        }, {
            'name' : '2015-08-15T14.gpx',
            'color' : 'BlueViolet'
        }, {
            'name' : '2015-08-16T16.gpx',
            'color' : 'Brown'
        }, {
            'name' : '2015-08-17T15.gpx',
            'color' : 'CornflowerBlue'
        }, {
            'name' : '2015-08-18T13.gpx',
            'color' : 'DarkOliveGreen'
        }, {
            'name' : '2015-08-20T09.gpx',
            'color' : 'DodgerBlue'
        }, {
            'name' : '2015-08-21T12.gpx',
            'color' : 'LightSalmon'
        }];

        function getRandColor() {
            return colors[Math.round(Math.random() * colors.length)];
        }

        var osm_layer = new ol.layer.Tile({
            // opacity: '0.2',
            source : new ol.source.OSM({})
        });

        var map = new ol.Map({
            layers : [osm_layer],
            target : document.getElementById('map'),
            view : new ol.View({
                center : ol.proj.transform([-3.9060, 47.8530], 'EPSG:4326', 'EPSG:3857'),
                zoom : 19
            })
        });

        var image_hydrant = new ol.style.Circle({
            radius : 7,
            fill : new ol.style.Fill({
                color : 'rgba(255, 0, 0, 0.8)'
            }),
            stroke : new ol.style.Stroke({
                color : 'white',
                width : 2
            })
        });

        var hydrants_geojson = new ol.layer.Vector({
            source : new ol.source.Vector({
                url : 'datas/geojson/20150822_hyrants.geojson',
                format : new ol.format.GeoJSON()
            }),

            style : (function() {
                var stroke = new ol.style.Stroke({
                    color : 'black'
                });
                var textStroke = new ol.style.Stroke({
                    color : '#fff',
                    width : 3
                });
                var textFill = new ol.style.Fill({
                    color : '#000'
                });
                return function(feature, resolution) {
                    return [new ol.style.Style({
                        image : image_hydrant,
                        text : new ol.style.Text({
                            font : '12px Calibri,sans-serif',
                            text : feature.get('ref'),
                            fill : textFill,
                            stroke : textStroke,
                            offsetY : 13,
                            opacity : 0.6
                        })
                    })];
                };
            })()
        });

        // image : image_hydrant

        for (var i = 0; i < gpxs.length; i++) {
            console.log(gpxs[i]);
            var gpx = new ol.layer.Vector({
                opacity : '0.6',
                source : new ol.source.Vector({
                    url : 'datas/gpx/' + gpxs[i].name,
                    format : new ol.format.GPX()
                }),
                style : new ol.style.Style({
                    stroke : new ol.style.Stroke({
                        color : gpxs[i].color,
                        width : 3
                    })
                })

            });
            map.addLayer(gpx);
        }
        map.addLayer(hydrants_geojson);

	</script>

</html>
