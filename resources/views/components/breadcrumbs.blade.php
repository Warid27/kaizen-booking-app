{{-- resources/views/components/breadcrumbs.blade.php --}}
@props(['links' => []])

<nav class="flex mb-6" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        @foreach($links as $index => $link)
            <li class="inline-flex items-center">
                @if($index > 0)
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                @endif
                
                @if($loop->last)
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $link['label'] }}</span>
                @else
                    <a href="{{ $link['href'] }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        @if($index === 0)
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                        @endif
                        {{ $link['label'] }}
                    </a>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
