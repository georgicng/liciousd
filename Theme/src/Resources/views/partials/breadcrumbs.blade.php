@unless ($breadcrumbs->isEmpty())
    <span class="font-Poppins text-[14px] leading-[1.3] font-medium text-[#7a7a7a] capitalize max-[991px]:mt-[4px]">
            @foreach ($breadcrumbs as $breadcrumb)
                @if (
                    $breadcrumb->url
                    && ! $loop->last
                )
                        <a href="{{ $breadcrumb->url }}" class="text-[#64b496]">
                            {{ $breadcrumb->title }}
                        </a> -
                @else
                        {{ $breadcrumb->title }}
                @endif
            @endforeach
    </span>
@endunless
