@props([
    'color' => 'emerald',
    'photoPath' => null,
    'fallbackInitial' => 'U',
    'title' => '',
    'subtitle' => '',
])

<div class="bg-{{ $color }}-600 px-8 py-10 text-white relative">
    <div class="absolute inset-0 bg-{{ $color }}-700 opacity-20 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]">
    </div>

    <div class="relative z-10 flex flex-col md:flex-row items-center gap-6">
        <!-- Alerts -->
        @if (session()->has('success'))
            <div class="absolute -top-6 right-0 bg-green-500 text-white px-4 py-2 rounded shadow-md text-sm animate-pulse">
                {{ session('success') }}
            </div>
        @endif
        @error('photo')
            <div class="absolute -top-6 right-0 bg-red-500 text-white px-4 py-2 rounded shadow-md text-sm animate-pulse">
                {{ $message }}
            </div>
        @enderror

        <div class="w-32 h-32 bg-white rounded-full p-1 shadow-lg shrink-0 relative group">
            <div class="w-full h-full bg-{{ $color }}-100 rounded-full flex items-center justify-center text-4xl font-bold text-{{ $color }}-700 overflow-hidden relative">
                @if ($photoPath)
                    <img src="{{ Storage::url($photoPath) }}" alt="{{ $title }}" class="w-full h-full object-cover">
                @else
                    {{ $fallbackInitial }}
                @endif

                <!-- Loading State -->
                <div wire:loading wire:target="photo" class="absolute inset-0 bg-black/50 flex items-center justify-center text-white z-20">
                    <svg class="animate-spin h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>

            <!-- Upload Overlay -->
            <label class="absolute inset-1 bg-black/50 text-white rounded-full flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 cursor-pointer transition-opacity z-10">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span class="text-[10px] font-semibold mt-1">ছবি বদলান</span>
                <input type="file" wire:model="photo" class="hidden" accept="image/*">
            </label>
        </div>
        <div class="text-center md:text-left">
            <h3 class="text-3xl font-bold mb-1">{{ $title }}</h3>
            @if($subtitle)
                <p class="text-{{ $color }}-100 text-lg mb-3">{{ $subtitle }}</p>
            @endif
            <div class="flex flex-wrap justify-center md:justify-start gap-2">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
