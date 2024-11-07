<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>{{ isset($title) ? $title . ' | E-Store' : 'E-Store' }}</title>
	<link rel="icon" type="image/x-icon" href="/estore.ico">
	@vite(['resources/css/app.css', 'resources/js/app.js'])
	@livewireStyles
</head>
<body class="bg-slate-200 dark:bg-slate-700 flex flex-col min-h-screen">
	@livewire('partials.navbar')

	<main class="flex-grow">
		{{ $slot }}
	</main>

	@livewire('partials.footer')
	@livewireScripts
	<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<x-livewire-alert::scripts />
</body>
</html>
