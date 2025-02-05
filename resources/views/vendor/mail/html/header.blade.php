@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="{{ asset('images/logo-transparent.svg') }}" class="logo" alt="Orbus Courriers Sortants">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
