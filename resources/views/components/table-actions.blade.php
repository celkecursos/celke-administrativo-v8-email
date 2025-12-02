@props(['actions' => []])

<div class="table-actions-align">
    @foreach ($actions as $action)
        @can($action['can'])
            {{-- LINK --}}
            @if ($action['type'] === 'link')
                <a href="{{ $action['url'] }}" class="{{ $action['class'] }}">
                    <x-dynamic-component :component="'lucide-' . Str::kebab(str_replace('lucide-', '', $action['icon']))" class="size-5" />
                    <span>{{ $action['label'] }}</span>
                </a>
            @endif

            {{-- DELETE --}}
            @if ($action['type'] === 'delete')
                <form id="delete-form-{{ $action['id'] }}"
                      action="{{ $action['url'] }}"
                      method="POST"
                      class="inline">
                    @csrf
                    @method('DELETE')

                    <button type="button"
                            onclick="confirmDelete({{ $action['id'] }})"
                            class="{{ $action['class'] }}">
                        <x-dynamic-component :component="'lucide-' . Str::kebab(str_replace('lucide-', '', $action['icon']))" class="size-5" />
                        <span>{{ $action['label'] }}</span>
                    </button>
                </form>
            @endif
        @endcan
    @endforeach
</div>
