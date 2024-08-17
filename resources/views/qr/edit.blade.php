<x-app-layout>
    <div class="max-w-3xl m-auto mt-8 p-4">
        <div class="mb-6">
            <img src="{{ Storage::url('public/qr_codes/' . $qrCode->label . '.png') }}" alt="qr code image" class="h-12 lg:h-16  {{$qrCode->foreground_color == 'white' ? 'bg-black' : ''}}">
        </div>
        <form method="POST" action="{{ route('qr.update', $qrCode->id) }}" class="w-full">
            @csrf
            <div class="w-full">
                <x-input-label for="forwarding_link" :value="__('Forwarding Link')" />
                <x-text-input id="forwarding_link" name="forwarding_link" class="block mt-1 w-full" type="text"  :value="old('forwarding_link', $qrCode->forwarding_link ?? '')" required autofocus />
                <x-input-error :messages="$errors->get('forwarding_link')" class="mt-2" />
            </div>
            <div class="flex items-center mt-4 w-full">
                <x-primary-button class="ms-3">
                    {{ __('Save') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>
