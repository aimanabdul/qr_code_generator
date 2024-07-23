<x-guest-layout>
    <div class="max-w-2xl m-auto mt-8 p-4">
        <form method="POST" action="{{ route('qr.store') }}">
            @csrf
            <div>
                <x-input-label for="quantity" :value="__('Quantity')" />
                <x-text-input id="quantity" class="block mt-1 w-full" type="number" name="quantity" :value="old('quantity')" min="1" required autofocus />
                <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
            </div>
            <div class="mt-4">
                <x-input-label for="foreground_color" :value="__('Foreground Color')" />
                <select id="foreground_color" name="foreground_color" class="block mt-1 w-full">
                    <option value="black" {{ old('foreground_color') == 'black' ? 'selected' : '' }}>Black</option>
                    <option value="white" {{ old('foreground_color') == 'white' ? 'selected' : '' }}>White</option>
                </select>
                <x-input-error :messages="$errors->get('foreground_color')" class="mt-2" />
            </div>
            <div class="mt-4">
                <x-input-label for="background_color" :value="__('Background Color')" />
                <select id="background_color" name="background_color" class="block mt-1 w-full">
                    <option value="white" {{ old('background_color') == 'white' ? 'selected' : '' }}>White</option>
                    <option value="transparent" {{ old('background_color') == 'transparent' ? 'selected' : '' }}>Transparent</option>
                </select>
                <x-input-error :messages="$errors->get('background_color')" class="mt-2" />
            </div>
            <div class="flex items-center justify-center mt-4">
                <x-primary-button class="ms-3">
                    {{ __('Generate') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
