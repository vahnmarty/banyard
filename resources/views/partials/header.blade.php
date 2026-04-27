<div class="bg-white border-b shadow-sm">
    <div class="max-w-6xl mx-auto px-6 md:px-8">
        <header class="flex justify-between py-6 items-center">
            <a href="{{ url('/') }}" class="font-bold text-xl md:text-2xl">Banyard Pickleball</a>

            <div class="flex gap-x-4 xl:gap-x-6  items-center">
                <a href="{{ url('/') }}" class="text-neutral-600 hover:text-neutral-900 hover:underline">Home</a>
                <a href="{{ url('/schedule') }}" class="text-neutral-600 hover:text-neutral-900 hover:underline">Schedule</a>
                <x-filament::button tag="a" href="{{ route('book') }}">Book Now</x-filament::button>
            </div>
        </header>
    </div>

</div>
