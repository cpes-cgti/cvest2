<tr>
    <td class="header">
            <img src="{{ asset('favicon/android-chrome-192x192.png') }}" style="height: 2em;">
            <a style="color:black;" href="{{ $url }}" style="line-height: 2em; height: 1em;">
                {{ $slot }}
            </a>
        {{-- <a href="{{ $url }}">
            {{ $slot }}
        </a> --}}
    </td>
</tr>
