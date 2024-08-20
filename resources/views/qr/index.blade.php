<x-app-layout>
    <div class="mt-4 overflow-hidden" x-data="qrCodeTable()">
        <div class="max-w-7xl mx-auto">
            <h2 class="mb-4 text-gray-600 text-xl px-2">Alle QR codes</h2>
            <div class="mb-6 mx-2 md:mx-0">
                <form action="{{route('qr.index')}}" class="flex">
                    <div class="w-full md:w-3/12 ">
                        <x-text-input id="label" class="block mt-1 w-full" placeholder="Label" type="text" name="label" :value="$request->label ?? ''" autofocus />
                        <x-input-error :messages="$errors->get('label')" class="mt-2" />
                    </div>
                    <div class="w-full mx-2 flex align-baseline">
                        <x-primary-button class="">
                            {{ __('Search') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
            <div class="flex flex-col bg-white overflow-auto shadow-sm sm:rounded-lg">
                <table class="table-auto divide-y divide-gray-200">
                    <thead class="bg-gray-100 text-xs font-medium text-gray-600 uppercase text-center">
                    <tr>
                        <th class="px-6 py-3">
                            <input type="checkbox" x-model="selectAll" @click="toggleAll">
                        </th>
                        <th class="px-6 py-3">Label</th>
                        <th class="px-6 py-3">Qr-code</th>
                        <th class="px-6 py-3">Background</th>
                        <th class="px-6 py-3">Foreground</th>
                        <th class="px-6 py-3">Note</th>
                        <th class="px-6 py-3">Downloaded</th>
                        <th class="px-6 py-3">Business</th>
                        <th class="px-6 py-3">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 text-sm text-gray-600">
                    @foreach($qrCodes as $qrCode)
                        <tr>
                            <td class="px-6 py-4 text-center">
                                <input type="checkbox" x-model="selected" value="{{ $qrCode->label }}">
                            </td>
                            <td class="px-6 py-4 text-center">{{ $qrCode->label }}</td>
                            <td class="px-6 py-4 text-center flex justify-center {{$qrCode->foreground_color == 'white' ?? 'bg-black'}}">
                                <img src="{{ Storage::url('public/qr_codes/' . $qrCode->label . '.png') }}" alt="qr code image" class="h-12 lg:h-16  {{$qrCode->foreground_color == 'white' ? 'bg-black' : ''}}">
                            </td>
                            <td class="px-6 py-4 text-center">{{ ucfirst($qrCode->background_color) }}</td>
                            <td class="px-6 py-4 text-center">{{ ucfirst($qrCode->foreground_color) }}</td>
                            <td class="px-6 py-4 text-center">{{ ucfirst($qrCode->note) }}</td>
                            <td class="px-6 py-4 text-center">
                                <button class="h-6 w-6 rounded-full {{ $qrCode->is_downloaded ? 'bg-green-500' : 'bg-red-500' }}"></button>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span>{{$qrCode->business_name ?? '-'}}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{route('qr.edit', $qrCode->id)}}" class="bg-gray-400 hover:bg-gray-300 py-2 px-5 text-white rounded-md font-bold">update</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 flex justify-between items-center">
                <button @click="downloadSelected" class="bg-blue-500 text-white px-4 py-2 rounded">Download Selected</button>
            </div>
            <div class="mt-4">
                {{ $qrCodes->links() }}
            </div>
        </div>
    </div>

    <script>
        function qrCodeTable() {
            return {
                selectAll: false,
                selected: [],
                toggleAll() {
                    this.selected = !this.selectAll ? @json($qrCodes->pluck('label')) : [];
                },
                async downloadSelected() {
                    this.selected.forEach(label => {
                        const link = document.createElement('a');
                        link.href = `{{ Storage::url('public/qr_codes') }}/${label}.png`;
                        link.setAttribute('download', '');
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    });
                    await this.markAsDownloaded(this.selected);
                },
                async markAsDownloaded(labels) {
                    await fetch('{{ route('qr.update-status') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ labels })
                    });
                    location.reload();
                }
            };
        }
    </script>
</x-app-layout>
