<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Live Location - Google Maps</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
        }

        .container {
            width: 400px;
            margin: 50px auto;
            padding: 15px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        button {
            width: 100%;
            padding: 12px;
            background: #28a745;
            color: white;
            border: none;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        #map {
            width: 100%;
            height: 250px;
            margin-top: 15px;
            border-radius: 6px;
        }

        #address {
            margin-top: 10px;
            font-size: 14px;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>📍 Live Location (Google Maps)</h2>
        <button onclick="getLocation()">Get My Location</button>

        <div id="map"></div>
        <div id="address"></div>
    </div>

    <script src="script.js"></script>

    <!-- Google Maps API -->
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBFbU1UkuV2HVULSP2rnTwQWYM0xpFvG20">
    </script>
    <script>
        let map, marker;

        function getLocation() {
            if (!navigator.geolocation) {
                alert("Geolocation not supported");
                return;
            }

            navigator.geolocation.getCurrentPosition(
                showPosition,
                error => alert("Location permission denied"),
                { enableHighAccuracy: true }
            );
        }

        function showPosition(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            const userLocation = { lat, lng };

            map = new google.maps.Map(document.getElementById("map"), {
                center: userLocation,
                zoom: 16
            });

            marker = new google.maps.Marker({
                position: userLocation,
                map: map,
                title: "You are here"
            });

            const geocoder = new google.maps.Geocoder();
            geocoder.geocode({ location: userLocation }, (results, status) => {
                if (status === "OK" && results[0]) {
                    document.getElementById("address").innerHTML =
                        "<b>Address:</b><br>" + results[0].formatted_address;
                }
            });
        }

    </script>

</body>

</html>