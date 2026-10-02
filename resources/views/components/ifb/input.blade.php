@props(['name', 'label' => null, 'type' => 'text', 'value' => null, 'prefix' => null, 'hint' => null, 'bag' => 'default'])

<div class="flex flex-col gap-1.5">
    @if ($label)
        <label for="{{ $name }}" class="text-xs font-medium text-ifb-dim">{{ $label }}</label>
    @endif

    <div class="relative">
        @if ($prefix)
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm font-mono text-ifb-dim">{{ $prefix }}</span>
        @endif

        <input
            id="{{ $name }}"
            name="{{ $name }}"
            type="{{ $type }}"
            value="{{ $type === 'password' ? '' : old($name, $value) }}"
            {{ $attributes->merge(['class' => 'w-full py-2.5 rounded-xl text-sm bg-ifb-secondary border border-ifb-line text-ifb-text placeholder:text-ifb-dim focus:border-ifb-primary focus:ring-2 focus:ring-ifb-primary/20 disabled:opacity-50 [color-scheme:dark] '.($prefix ? 'pl-9 pr-3 font-mono' : 'px-3')]) }}
        >
    </div>

    @if ($hint)
        <p class="text-xs text-ifb-dim">{{ $hint }}</p>
    @endif

    @error($name, $bag)
        <p class="text-xs text-ifb-danger">{{ $message }}</p>
    @enderror
</div>