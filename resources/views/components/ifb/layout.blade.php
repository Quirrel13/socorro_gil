@props(['titulo' => null])

@php
    $usuario = auth()->user();
    $pendentes = $pendentes ?? 0;

    $rotulosRole = ['gerente_geral' => 'Gerente Geral', 'gerente_conta' => 'Gerente de Conta', 'cliente' => 'Cliente'];
    $roleNome = $usuario->role?->name;
    $variantRole = $roleNome === 'gerente_geral' ? 'purple' : ($roleNome === 'gerente_conta' ? 'warning' : 'default');

    $menu = [];

    if ($usuario->can('viewAny', \App\Models\Conta::class)) {
        $menu[] = ['rota' => 'conta.index', 'ativo' => 'conta.*', 'rotulo' => 'Meus Clientes', 'icone' => 'managers', 'badge' => 0];
    }
    if ($usuario->can('viewAny', \App\Models\User::class)) {
        $menu[] = ['rota' => 'users.index', 'ativo' => 'users.*', 'rotulo' => 'Gerentes', 'icone' => 'managers', 'badge' => 0];
    }
    if ($usuario->can('viewAny', \App\Models\Solicitacao::class)) {
        $menu[] = ['rota' => 'solicitacao.index', 'ativo' => 'solicitacao.*', 'rotulo' => 'Solicitações', 'icone' => 'alert', 'badge' => $pendentes];
    }
    if ($usuario->can('viewAny', \OwenIt\Auditing\Models\Audit::class)) {
        $menu[] = ['rota' => 'auditoria.index', 'ativo' => 'auditoria.*', 'rotulo' => 'Logs de Auditoria', 'icone' => 'log', 'badge' => 0];
    }
@endphp
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
<body class="font-ifb bg-ifb-bg text-ifb-text antialiased min-h-screen">

    {{-- Cabeçalho --}}
    <header class="sticky top-0 z-50 px-6 h-14 flex items-center justify-between bg-ifb-bg/90 backdrop-blur-md border-b border-ifb-line">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <div class="w-7 h-7 rounded-lg flex items-center justify-center bg-ifb-primary">
                <span class="font-mono text-xs font-bold text-white">IF</span>
            </div>
            <span class="font-bold tracking-tight text-base">IFBANK</span>
        </a>

        <div class="flex items-center gap-3">
            @if ($pendentes > 0)
                <a href="{{ route('solicitacao.index') }}" class="flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs bg-ifb-warning-soft text-ifb-warning border border-ifb-warning-line">
                    <x-ifb.icon name="alert" :size="16" />
                    <span>{{ $pendentes }} pendente{{ $pendentes > 1 ? 's' : '' }}</span>
                </a>
            @endif

            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5">
                <x-ifb.avatar :name="$usuario->name" tone="solid" box="w-7 h-7 rounded-full text-xs" />
                <div class="hidden sm:block">
                    <div class="flex items-center gap-2 text-sm font-medium leading-none mb-0.5">
                        <span>{{ $usuario->name }}</span>
                        <x-ifb.badge :variant="$variantRole">{{ $rotulosRole[$roleNome] ?? '—' }}</x-ifb.badge>
                    </div>
                    <div class="text-[11px] font-mono leading-none text-ifb-dim">{{ $usuario->email }}</div>
                </div>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-xs px-3 py-1.5 rounded-lg transition-all hover:bg-white/5 text-ifb-dim border border-ifb-line">
                    Sair
                </button>
            </form>
        </div>
    </header>

    <div class="flex">
        {{-- Sidebar (desktop) --}}
        <aside class="hidden md:flex flex-col w-56 shrink-0 py-6 px-3 sticky top-14 h-[calc(100vh-3.5rem)] border-r border-ifb-line">
            @foreach ($menu as $item)
                @php $ativo = request()->routeIs($item['ativo']); @endphp
                <a href="{{ route($item['rota']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium mb-1 transition-all {{ $ativo ? 'bg-ifb-primary-soft text-ifb-accent' : 'text-ifb-dim hover:bg-white/5' }}">
                    <x-ifb.icon :name="$item['icone']" />
                    {{ $item['rotulo'] }}
                    @if ($item['badge'] > 0)
                        <span class="ml-auto w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-bold bg-ifb-warning text-black">{{ $item['badge'] }}</span>
                    @endif
                </a>
            @endforeach
        </aside>

        {{-- Conteúdo --}}
        <main class="flex-1 min-w-0 p-6 pb-24 md:pb-6">
            <div class="max-w-4xl">
                <x-ifb.alert />
                {{ $slot }}
            </div>
        </main>
    </div>

    {{-- Navegação inferior (mobile) --}}
    <nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 flex border-t border-ifb-line bg-ifb-card">
        @foreach ($menu as $item)
            @php $ativo = request()->routeIs($item['ativo']); @endphp
            <a href="{{ route($item['rota']) }}"
               class="flex-1 flex flex-col items-center gap-1 py-3 text-[10px] {{ $ativo ? 'text-ifb-accent' : 'text-ifb-dim' }}">
                <x-ifb.icon :name="$item['icone']" :size="22" />
                {{ $item['rotulo'] }}
            </a>
        @endforeach
    </nav>
</body>
</html>