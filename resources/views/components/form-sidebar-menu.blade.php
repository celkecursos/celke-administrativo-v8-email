<div class="form-group-menu-container">
    <div class="sidebar-card">
        <div class="sidebar-card-header">
            <h3 class="sidebar-card-title">Configurações</h3>
        </div>

        <nav class="sidebar-nav">
            @foreach ($items as $item)
                @can($item['permission'] ?? null)
                    <a 
                        href="{{ $item['url'] }}" 
                        class="sidebar-nav-item {{ ($item['active'] ?? false) ? 'active' : '' }}"
                    >
                        {{-- Ícone dinâmico (Blade Lucide) --}}
                        @if (!empty($item['icon']))
                            <x-dynamic-component :component="$item['icon']" class="icon-btn" />
                        @endif

                        <span>{{ $item['label'] }}</span>
                    </a>
                @endcan
            @endforeach
        </nav>
    </div>
</div>
