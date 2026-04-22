<x-filament::page>
    
    <div class="space-y-6">
        
        <!-- Stats Overview -->
        @livewire(\App\Filament\Widgets\StatsOverview::class)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Latest Products -->
            @livewire(\App\Filament\Widgets\LatestProducts::class)
            <!-- Latest Orders -->
            @livewire(\App\Filament\Widgets\LatestOrders::class)
        </div>
    </div>

</x-filament::page>