@props(['title', 'subtitle' => null, 'actions' => null])
<div class="flex items-start justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">{{ $title }}</h1>
        @if($subtitle)
        <p class="text-sm text-gray-500 mt-0.5">{{ $subtitle }}</p>
        @endif
    </div>
    @if($actions)
    <div class="flex items-center gap-2">
        {{ $actions }}
    </div>
    @endif
</div>
