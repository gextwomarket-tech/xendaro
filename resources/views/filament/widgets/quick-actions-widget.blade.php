@php
    $quickActions = $this->getQuickActions();
    $colors = [
        'blue' => 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800 hover:bg-blue-100 dark:hover:bg-blue-900/30',
        'green' => 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800 hover:bg-green-100 dark:hover:bg-green-900/30',
        'purple' => 'bg-purple-50 dark:bg-purple-900/20 border-purple-200 dark:border-purple-800 hover:bg-purple-100 dark:hover:bg-purple-900/30',
        'orange' => 'bg-orange-50 dark:bg-orange-900/20 border-orange-200 dark:border-orange-800 hover:bg-orange-100 dark:hover:bg-orange-900/30',
        'emerald' => 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-200 dark:border-emerald-800 hover:bg-emerald-100 dark:hover:bg-emerald-900/30',
        'red' => 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800 hover:bg-red-100 dark:hover:bg-red-900/30',
    ];
    
    $iconColors = [
        'blue' => 'text-blue-600 dark:text-blue-400',
        'green' => 'text-green-600 dark:text-green-400',
        'purple' => 'text-purple-600 dark:text-purple-400',
        'orange' => 'text-orange-600 dark:text-orange-400',
        'emerald' => 'text-emerald-600 dark:text-emerald-400',
        'red' => 'text-red-600 dark:text-red-400',
    ];
@endphp

<x-filament::section>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($quickActions as $action)
            <a href="{{ $action['url'] }}" 
               class="flex items-center gap-4 p-4 border rounded-lg transition {{ $colors[$action['color']] }}">
                <div class="flex-shrink-0">
                    @svg('heroicon-o-' . $action['icon'], 'w-8 h-8 ' . $iconColors[$action['color']])
                </div>
                <div class="flex-grow">
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $action['label'] }}</p>
                </div>
                <div class="flex-shrink-0">
                    @svg('heroicon-o-arrow-right', 'w-5 h-5 text-gray-400 dark:text-gray-600')
                </div>
            </a>
        @endforeach
    </div>
</x-filament::section>
