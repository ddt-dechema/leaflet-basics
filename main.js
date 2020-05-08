var map = L.map('map').setView([50.110924, 8.682127], 3);

		L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
			maxZoom: 14,
			attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
				'<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
				'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
			id: 'mapbox/light-v9',
			tileSize: 512,
			zoomOffset: -1
		}).addTo(map);

		var baseballIcon = L.icon({
			iconUrl: 'baseball-marker.png',
			iconSize: [32, 37],
			iconAnchor: [16, 37],
			popupAnchor: [0, -28]
		});

		function onEachFeature(feature, layer) {
			var popupContent = "<p>I started out as a GeoJSON " +
					feature.geometry.type + ", but now I'm a Leaflet vector!</p>";

			if (feature.properties && feature.properties.popupContent) {
				popupContent += feature.properties.popupContent;
			}

			layer.bindPopup(popupContent);
		}

		L.geoJson([bicycleRental, campus], {

			style: function (feature) {
				return feature.properties && feature.properties.style;
			},

			onEachFeature: onEachFeature,

			pointToLayer: function (feature, latlng) {
				return L.circleMarker(latlng, {
					radius: 8,
					fillColor: "#ff7800",
					color: "#000",
					weight: 1,
					opacity: 1,
					fillOpacity: 0.8
				});
			}
		}).addTo(map);

		L.geoJson(freeBus, {

			filter: function (feature, layer) {
				if (feature.properties) {
					// If the property "underConstruction" exists and is true, return false (don't render features under construction)
					return feature.properties.underConstruction !== undefined ? !feature.properties.underConstruction : true;
				}
				return false;
			},

			onEachFeature: onEachFeature
		}).addTo(map);

		var coorsLayer = L.geoJson(coorsField, {

			pointToLayer: function (feature, latlng) {
				return L.marker(latlng, {icon: baseballIcon});
			},

			onEachFeature: onEachFeature
		}).addTo(map);


		var CO2_global_panes = new L.GeoJSON.AJAX(["geojson.php"], {
		// onEachFeature: function(feature, layer) {
		// 	layer.bindPopup('<h2>' + feature.properties.country_name_en +
		// 			' (' + feature.properties.iso_a3 + ')</h2><p>' + feature.properties.MTonnes + ' MTonnes CO<sub>2</sub>/year'),
		// 		layer.bindTooltip('<h2>' + feature.properties.country_name_en +
		// 			' (' + feature.properties.iso_a3 + ')</h2><p>' + feature.properties.MTonnes + ' MTonnes CO<sub>2</sub>/year')
		// 	}}).addTo(map);
		style: style,
		onEachFeature: function(feature, layer) {
			layer.bindPopup('<h2>' + feature.properties.country_name_en +
				' (' + feature.properties.iso_a3 + ')</h2><p>' + feature.properties.MTonnes + ' MTonnes CO<sub>2</sub>/year'),
			layer.bindTooltip('<h2>' + feature.properties.country_name_en +
				' (' + feature.properties.iso_a3 + ')</h2><p>' + feature.properties.MTonnes + ' MTonnes CO<sub>2</sub>/year')
				// mouseover: highlightFeature,
				// // mouseout: resetHighlight,
				// //click: zoomToFeature
				// });
				layer.on('mouseover', highlightFeature);
			layer.on('mouseout', function() {
				CO2_global_panes.resetStyle(this);
			});
		}
		}).addTo(map)
		;
		
		function style(feature) {
			return {
				fillColor: getColor(feature.properties.MTonnes),
				weight: 2,
				opacity: 1,
				color: 'white',
				dashArray: '3',
				fillOpacity: 0.7
			};
		}
		function getColor(d) {
			return d > 1000 ? '#800026' :
				   d > 500  ? '#BD0026' :
				   d > 200  ? '#E31A1C' :
				   d > 100  ? '#FC4E2A' :
				   d > 50   ? '#FD8D3C' :
				   d > 20   ? '#FEB24C' :
				   d > 10   ? '#FED976' :
							  '#FFEDA0';
		}
		function highlightFeature(e) {
			var layer = e.target;
		
			layer.setStyle({
				weight: 5,
				color: '#666',
				dashArray: '',
				fillOpacity: 0.7
			});
		
			if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) {
				layer.bringToFront();
			}
		}



		$(document).ready(function () {
			$(document).on('submit', '#geofenceform', function() {
				window.parent.$('#geofencemodal').modal('hide');
			});
		});