@props([
    'authors' => [],
    'date' => null,
    'time' => null,
])

<div>
    @if (count($authors = array_filter($authors)))
        <div class="mb-1 flex items-center gap-2 text-sm text-ink-800">
            <div class="flex -space-x-2">
                @forelse ($authors as $author)
                    <div
                        class="flex h-8 w-8 items-center justify-center rounded-full border border-line bg-bg-alt text-xs font-medium text-ink-800">
                        {{ strtoupper(substr($author, 0, 1)) }}
                    </div>
                @endforeach
            </div>

            <p class="m-0 font-medium">
                {{ implode(', ', $authors) }}
            </p>
        </div>
    @endif

    @php
        $meta = collect([
            $date,
            $time ? __('content-studio-plugin::content_studio.reading_time', ['minutes' => $time]) : null,
        ])->filter();
    @endphp

    @if ($meta->isNotEmpty())
        <div class="ml-2 flex items-center gap-2 text-sm text-ink-400">
            @foreach ($meta as $item)
                <span class="leading-none">{{ $item }}</span>

                @if (!$loop->last)
                    <span>•</span>
                @endif
            @endforeach
        </div>
    @endif
</div>
