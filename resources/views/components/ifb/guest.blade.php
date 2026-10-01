@props(['titulo' => null])
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $titulo ? $titulo.' · ' : '' }}IFBank</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <style>[x-cloak]{display:none !important}</style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-ifb bg-ifb-bg text-ifb-text antialiased min-h-screen flex flex-col">
    <header class="px-8 py-5 flex items-center gap-3 border-b border-ifb-line">
        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-ifb-primary">
            <span class="font-mono text-xs font-bold text-white">IF</span>
        </div>
        <span class="text-xl font-bold tracking-tight">IFBANK</span>
    </header>

    <div class="flex flex-1 items-center justify-center p-6">
        <div class="w-full max-w-md">
            {{ $slot }}
        </div>
    </div>
</body>
</html>