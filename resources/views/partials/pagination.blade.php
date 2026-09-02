@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span
                    class="relative inline-flex items-center rounded-lg border border-line bg-surface px-4 py-2 text-sm font-medium leading-5 text-ink-400">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="relative inline-flex items-center rounded-lg border border-line bg-surface px-4 py-2 text-sm font-medium leading-5 text-ink-800 transition hover:bg-bg-alt hover:text-amber-600 focus:outline-none focus:ring ring-amber">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="relative ml-3 inline-flex items-center rounded-lg border border-line bg-surface px-4 py-2 text-sm font-medium leading-5 text-ink-800 transition hover:bg-bg-alt hover:text-amber-600 focus:outline-none focus:ring ring-amber">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span
                    class="relative ml-3 inline-flex items-center rounded-lg border border-line bg-surface px-4 py-2 text-sm font-medium leading-5 text-ink-400">
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm leading-5 text-ink-600">
                    {!! __('Showing') !!}
                    @if ($paginator->firstItem())
                        <span class="font-medium">{{ $paginator->firstItem() }}</span>
                        {!! __('to') !!}
                        <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    {!! __('of') !!}
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex rounded-lg shadow-sm">
                    @if ($paginator->onFirstPage())
                        <span
                            class="relative inline-flex items-center rounded-l-lg border border-line bg-surface px-2 py-2 text-sm font-medium leading-5 text-ink-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M12.707 14.707a1 1 0 01-1.414 0L6.586 10l4.707-4.707a1 1 0 111.414 1.414L9.414 10l3.293 3.293a1 1 0 010 1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}"
                            class="relative inline-flex items-center rounded-l-lg border border-line bg-surface px-2 py-2 text-sm font-medium leading-5 text-ink-600 transition hover:bg-bg-alt hover:text-amber-600 focus:outline-none focus:ring ring-amber">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M12.707 14.707a1 1 0 01-1.414 0L6.586 10l4.707-4.707a1 1 0 111.414 1.414L9.414 10l3.293 3.293a1 1 0 010 1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif

                    @foreach ($elements as $element)
                        @if (is_string($element))
                            <span
                                class="relative -ml-px inline-flex items-center border border-line bg-surface px-4 py-2 text-sm font-medium leading-5 text-ink-600">
                                {{ $element }}
                            </span>
                        @endif

                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span
                                            class="relative -ml-px inline-flex items-center border border-amber bg-amber px-4 py-2 text-sm font-semibold leading-5 text-amber-ink">
                                            {{ $page }}
                                        </span>
                                    </span>
                                @else
                                    <a href="{{ $url }}"
                                        class="relative -ml-px inline-flex items-center border border-line bg-surface px-4 py-2 text-sm font-medium leading-5 text-ink-800 transition hover:bg-bg-alt hover:text-amber-600 focus:outline-none focus:ring ring-amber">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}"
                            class="relative -ml-px inline-flex items-center rounded-r-lg border border-line bg-surface px-2 py-2 text-sm font-medium leading-5 text-ink-600 transition hover:bg-bg-alt hover:text-amber-600 focus:outline-none focus:ring ring-amber">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 5.293a1 1 0 011.414 0L13.414 10l-4.707 4.707a1 1 0 11-1.414-1.414L10.586 10 7.293 6.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                    @else
                        <span
                            class="relative -ml-px inline-flex items-center rounded-r-lg border border-line bg-surface px-2 py-2 text-sm font-medium leading-5 text-ink-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 5.293a1 1 0 011.414 0L13.414 10l-4.707 4.707a1 1 0 11-1.414-1.414L10.586 10 7.293 6.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
