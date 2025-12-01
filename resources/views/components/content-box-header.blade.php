<div class="content-box-header">
    <h3 class="content-box-title">{{ $title }}</h3>

    <div class="content-box-btn">
        @foreach($buttons as $button)
            @can($button['permission'] ?? null)
                <a 
                    href="{{ $button['url'] }}" 
                    class="{{ $button['class'] ?? '' }} align-icon-btn"
                >
                    <x-dynamic-component 
                        :component="$button['icon']" 
                        class="h-4 w-4" 
                    />

                    <span>{{ $button['label'] }}</span>
                </a>
            @endcan
        @endforeach
    </div>
</div>
