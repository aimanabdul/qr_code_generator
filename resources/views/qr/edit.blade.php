<x-guest-layout>
    <div class="max-w-2xl m-auto mt-8 p-4">
        <div class="flex justify-center">
            <img src="{{ Storage::url('public/qr_codes/' . $qrCode->label . '.png') }}" alt="qr code image" class="h-12 lg:h-16  {{$qrCode->foreground_color == 'white' ? 'bg-black' : ''}}">
        </div>
        <form method="POST" action="{{ route('qr.update', $qrCode->id) }}">
            @csrf
            <div>
                <x-input-label for="forwarding_link" :value="__('Forwarding Link')" />
                <x-text-input id="forwarding_link" name="forwarding_link" class="block mt-1 w-full" type="text"  :value="old('forwarding_link', $qrCode->forwarding_link ?? '')" required autofocus />
                <x-input-error :messages="$errors->get('forwarding_link')" class="mt-2" />
            </div>
            <div class="flex items-center justify-center mt-4">
                <x-primary-button class="ms-3">
                    {{ __('Generate') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
