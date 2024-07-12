<x-guest-layout>
    <div class="max-w-2xl m-auto my-8">
        <form method="POST" action="{{ route('qr.store') }}">
            @csrf
            <div>
                <x-input-label for="email" :value="__('Quantity')" />
                <x-text-input id="quantity" class="block mt-1 w-full" type="number" name="quantity" :value="old('quantity')"  min="1" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <div class="flex items-center justify-center mt-4">
                <x-primary-button class="ms-3">
                    {{ __('Generate') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
