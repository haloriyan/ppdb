<div class="flex items-center gap-4">
    @foreach ($items as $i => $item)
        <a href="{{ $item[0] }}" class="text-xs {{ $i != count($items) - 1 ? 'text-slate-400 underline' : 'text-primary'}}">
            {{ $item[1] }}
        </a>
        @if ($i != count($items) - 1)
            <ion-icon name="chevron-forward-outline"></ion-icon>
        @endif
    @endforeach
</div>