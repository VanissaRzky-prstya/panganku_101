<div class="bg-white">
    <div class="flex items-center gap-5 px-4 py-3 bg-white rounded-full shadow-sm border border-gray-400 overflow-x-auto">
        <a href="{{ route('beranda') }}" class="text-gray-600 hover:text-green-500 transition whitespace-nowrap text-sm md:text-base">Beranda</a>
        @foreach ($breadcrumbs as $breadcrumb)
            <span class="text-green-600 text-xl leading-none">›</span>
            @if ($loop->last)
                <span class="text-green-500 whitespace-nowrap text-sm font-bold md:text-base">{{ $breadcrumb['label'] }}</span>
            @else
                @if (!empty($breadcrumb['url']))
                    <a href="{{ $breadcrumb['url'] }}" class="text-gray-600 hover:text-green-500 transition whitespace-nowrap text-sm md:text-base">
                        {{ $breadcrumb['label'] }}
                    </a>
                @else
                    <span class="text-gray-600 whitespace-nowrap text-sm md:text-base">{{ $breadcrumb['label'] }}</span>
                @endif
            @endif
        @endforeach
    </div>
</div>