<div class="content-box-header">
    <h3 class="content-box-title">{{ $title }}</h3>

    <div class="content-box-btn">
        @foreach ($buttons as $button)
            @can($button['permission'] ?? null)
                {{-- BOTÃO DELETE COM FORMULÁRIO --}}
                @if (isset($button['method']) && strtolower($button['method']) === 'delete')
                    <form action="{{ $button['url'] }}" method="POST" class="inline" id="delete-form-{{ $button['id'] }}">
                        @csrf
                        @method('DELETE')

                        <button type="button" onclick="confirmDelete('{{ $button['id'] }}')"
                            class="{{ $button['class'] ?? '' }} flex items-center space-x-1">
                            <x-dynamic-component :component="$button['icon']" class="h-4 w-4" />
                            <span>{{ $button['label'] }}</span>
                        </button>
                    </form>


                    {{-- BOTÕES NORMAIS (LINKS) --}}
                @else
                    <a href="{{ $button['url'] }}" class="{{ $button['class'] ?? '' }} align-icon-btn">
                        <x-dynamic-component :component="$button['icon']" class="h-4 w-4" />

                        <span>{{ $button['label'] }}</span>
                    </a>
                @endif
            @endcan
        @endforeach
    </div>
</div>
