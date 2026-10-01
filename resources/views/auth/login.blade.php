<x-ifb.guest titulo="Entrar">
    <div class="text-center mb-10">
        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 bg-gradient-to-br from-ifb-primary to-ifb-accent">
            <span class="font-mono text-xl font-bold text-white">IF</span>
        </div>
        <h1 class="text-2xl font-bold tracking-tight mb-1">Bem-vindo ao IFBANK</h1>
        <p class="text-sm text-ifb-dim">Acesse sua conta com segurança</p>
    </div>

    @if (session('status'))
        <div class="mb-4 text-sm text-ifb-success">{{ session('status') }}</div>
    @endif

    <x-ifb.card>
        <form method="POST" action="{{ route('login') }}" class="space-y-4" x-data="{ mostrar: false }">
            @csrf

            <x-ifb.input name="email" type="email" label="E-mail" placeholder="seu@email.com" required autofocus autocomplete="username" />

            <div class="flex flex-col gap-1.5">
                <label for="password" class="text-xs font-medium text-ifb-dim">Senha</label>
                <div class="relative">
                    <input id="password" name="password" type="password" x-bind:type="mostrar ? 'text' : 'password'"
                        placeholder="••••••••" required autocomplete="current-password"
                        class="w-full px-3 py-2.5 pr-10 rounded-xl text-sm bg-ifb-secondary border border-ifb-line text-ifb-text placeholder:text-ifb-dim focus:border-ifb-primary focus:ring-2 focus:ring-ifb-primary/20">
                    <button type="button" x-on:click="mostrar = !mostrar" class="absolute right-3 top-1/2 -translate-y-1/2 text-ifb-dim">
                        <span x-show="!mostrar"><x-ifb.icon name="eye" /></span>
                        <span x-show="mostrar" x-cloak><x-ifb.icon name="eye-off" /></span>
                    </button>
                </div>
                @error('password')
                    <p class="text-xs text-ifb-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <label for="remember_me" class="inline-flex items-center gap-2 text-xs text-ifb-dim cursor-pointer">
                    <input id="remember_me" type="checkbox" name="remember" class="rounded bg-ifb-secondary border-ifb-line text-ifb-primary focus:ring-ifb-primary/30">
                    Manter conectado
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs text-ifb-accent hover:underline">Esqueci minha senha</a>
                @endif
            </div>

            <button type="submit" class="w-full py-3 rounded-xl font-semibold text-white transition-all hover:opacity-90 bg-gradient-to-br from-ifb-primary to-[#6B11DF]">
                Entrar
            </button>
        </form>
    </x-ifb.card>
</x-ifb.guest>