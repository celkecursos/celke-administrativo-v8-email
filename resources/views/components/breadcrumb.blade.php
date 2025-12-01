@props(['title', 'items' => []])

<div class="content-wrapper">
    <div class="content-header">
        <h2 class="content-title">{{ $title }}</h2>

        <nav class="breadcrumb">
            @foreach($items as $item)
                @if(isset($item['url']))
                    <a href="{{ $item['url'] }}" class="breadcrumb-link">{{ $item['label'] }}</a>
                    <span>/</span>
                @else
                    <span>{{ $item['label'] }}</span>
                @endif
            @endforeach
        </nav>
    </div>
</div>
