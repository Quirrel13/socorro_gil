@props(['value', 'signed' => false, 'color' => false])

@php
    $numero = (float) $value;
    $texto = 'R$ '.number_format(abs($numero), 2, ',', '.');

    if ($numero < 0) {
        $texto = '-'.$texto;
    } elseif ($signed && $numero > 0) {
        $texto = '+'.$texto;
    }

    $cor = $color ? ($numero < 0 ? 'text-ifb-danger' : 'text-ifb-success') : '';
@endphp
<span {{ $attributes->merge(['class' => $cor]) }}>{{ $texto }}</span>