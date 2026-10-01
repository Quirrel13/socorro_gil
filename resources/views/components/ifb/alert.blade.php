@if (session('success'))
    <div class="flex items-center gap-3 p-4 rounded-xl mb-6 text-sm font-medium bg-ifb-success-soft border border-ifb-success-line text-ifb-success">
        <x-ifb.icon name="check" />
        <span>{{ session('success') }}</span>
    </div>
@endif

@error('regra_negocio')
    <div class="flex items-center gap-3 p-4 rounded-xl mb-6 text-sm font-medium bg-ifb-danger-soft border border-ifb-danger-line text-ifb-danger">
        <x-ifb.icon name="alert" />
        <span>{{ $message }}</span>
    </div>
@enderror