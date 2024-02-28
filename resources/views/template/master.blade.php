<!DOCTYPE html>

<html>

	<head>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		
		<title>Routing</title>

		<!-- PWA -->
		@laravelPWA

		<!-- Vue -->
		@vite(['resources/css/app.css','resources/scss/app.scss'])

		<!-- Vue Template -->
		<link rel="stylesheet" href="{{url('template/css/chunk-4218871c.4a0230ff.css')}}">
		<link rel="stylesheet" href="{{url('template/css/chunk-4c8b6c66.0e433876.css')}}">
		<link rel="stylesheet" href="{{url('template/css/chunk-vendors.0d89a5bc.css')}}">
		<link rel="stylesheet" href="{{url('template/css/app.0625c153.css')}}">
		<link rel="stylesheet" href="{{url('template/css/chunk-4218871c.4a0230ff.css')}}">

		<!-- Leaflet -->
		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin="" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css"/>

		<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
		<link rel="stylesheet" href="{{url('Leaflet.markercluster-1.4.1/dist/MarkerCluster.css')}}" />
		<link rel="stylesheet" href="{{url('Leaflet.markercluster-1.4.1/dist/MarkerCluster.Default.css')}}" />
		<link rel="stylesheet" href="https://unpkg.com/leaflet-editable@1.2.0/dist/leaflet.editable.css" />

		<!-- Font Awesome -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.css"></link>
	
		<!-- Animate -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

		<!-- Custom CSS -->
		<link rel="stylesheet" href="{{url('css/login.css')}}">
		<link rel="stylesheet" href="{{url('css/custom.css')}}">
		<link rel="stylesheet" href="{{url('css/route.css')}}">

		<link rel="stylesheet" href="{{url('css/header.css')}}">

		<link rel="stylesheet" href="{{url('css/redefinition.css')}}">
		<link rel="stylesheet" href="{{url('css/datatables.css')}}">

		<link rel="stylesheet" href="{{url('css/responsiveness.css')}}">

		<!--  -->

		<!-- PWA Test -->
		<link rel="manifest" href="/manifest.json">

		<!-- Chrome for Android theme color -->
		<meta name="theme-color" content="#000000">

		<!-- Add to homescreen for Chrome on Android -->
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="application-name" content="PWA">
		<link rel="icon" sizes="512x512" href="/images/icons/icon-512x512.png">

		<!-- Add to homescreen for Safari on iOS -->
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="apple-mobile-web-app-title" content="PWA">
		<link rel="apple-touch-icon" href="/images/icons/icon-512x512.png">

		<link href="/images/icons/splash-640x1136.png" 		media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)" 	rel="apple-touch-startup-image" />
		<link href="/images/icons/splash-750x1334.png" 		media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)" 	rel="apple-touch-startup-image" />
		<link href="/images/icons/splash-1242x2208.png" 	media="(device-width: 621px) and (device-height: 1104px) and (-webkit-device-pixel-ratio: 3)" 	rel="apple-touch-startup-image" />
		<link href="/images/icons/splash-1125x2436.png" 	media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3)" 	rel="apple-touch-startup-image" />
		<link href="/images/icons/splash-828x1792.png" 		media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2)" 	rel="apple-touch-startup-image" />
		<link href="/images/icons/splash-1242x2688.png" 	media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3)" 	rel="apple-touch-startup-image" />
		<link href="/images/icons/splash-1536x2048.png" 	media="(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2)" 	rel="apple-touch-startup-image" />
		<link href="/images/icons/splash-1668x2224.png" 	media="(device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2)" 	rel="apple-touch-startup-image" />
		<link href="/images/icons/splash-1668x2388.png" 	media="(device-width: 834px) and (device-height: 1194px) and (-webkit-device-pixel-ratio: 2)" 	rel="apple-touch-startup-image" />
		<link href="/images/icons/splash-2048x2732.png" 	media="(device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2)" 	rel="apple-touch-startup-image" />

		<!--  -->

		<!-- Tile for Win8 -->
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage" content="/images/icons/icon-512x512.png">
	</head>

	<body>

		<div id="app">
			<main-app></main-app>
		</div>

	</body>

	<!-- Vue Routing -->
	<script src="https://unpkg.com/vue@3"></script>
	<script src="https://unpkg.com/vue-router@4"></script>

	<!-- Leaflet -->
	<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js">		</script>
	<script src="https://rawgit.com/hayeswise/Leaflet.PointInPolygon/master/wise-leaflet-pip.js">	</script>

	<script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js">	</script>
	<!-- <script src="https://unpkg.com/leaflet-editable@1.2.0/dist/leaflet.editable.js"></script> -->

	<script src='//api.tiles.mapbox.com/mapbox.js/plugins/leaflet-omnivore/v0.3.1/leaflet-omnivore.min.js'></script>

	<script src="{{url('Leaflet.markercluster-1.4.1/dist/leaflet.markercluster-src.js')}}">			</script>
	<script src="{{url('Leaflet.markercluster-1.4.1/src/MarkerCluster.js')}}">						</script>

	<!--  -->

	<!-- Template -->
	<!-- <script src="{{url('template/js/chunk-2d0b9062.97448cc8.js')}}">	</script>
	<script src="{{url('template/js/chunk-2d0ba2e9.eafd91ad.js')}}">	</script>
	<script src="{{url('template/js/chunk-2d0c18ec.6a1ea22e.js')}}">	</script>
	<script src="{{url('template/js/chunk-2d0ccf69.8e9fb9e3.js')}}">	</script>
	<script src="{{url('template/js/chunk-2d0e2534.696069ec.js')}}">	</script>
	<script src="{{url('template/js/chunk-2d20febb.edbe74cd.js')}}">	</script>
	<script src="{{url('template/js/chunk-2d217b0a.1c1d01ec.js')}}">	</script>
	<script src="{{url('template/js/chunk-2d21e985.34450ac3.js')}}">	</script>
	<script src="{{url('template/js/chunk-2d22db47.8944bbc1.js')}}">	</script>
	<script src="{{url('template/js/chunk-355d107d.c505912a.js')}}">	</script>
	<script src="{{url('template/js/chunk-4218871c.b9ca3326.js') }}">	</script>
	<script src="{{url('template/js/chunk-4c8b6c66.fc58459b.js')}}">	</script>
	<script src="{{url('template/js/app.3c7ebefc.js') }}">				</script> -->

	<!-- Datatable -->
	<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script> -->
	<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script> -->

	<!-- Excel     -->
	<script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>

	<!-- Customer -->
	<!-- <script src="{{url('js/responsive.js')}}"></script> -->

	@vite('resources/js/app.js')

</html>