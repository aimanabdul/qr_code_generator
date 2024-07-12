<x-guest-layout >
    <div class="mt-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-1">
            <h2 class="mb-4 text-gray-600 text-xl px-2">All Codes </h2>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="table-auto min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100 text-xs font-medium text-gray-600 uppercase text-center">
                        <tr>
                            <th class="px-6 py-3">#</th>
                            <th class="px-6 py-3">Label</th>
                            <th class="px-6 py-3">Qr code</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Download</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 text-sm text-gray-600">
                        @foreach($qrCodes as $qrCode)
                            <tr>
                                <td class="px-6 py-4 text-center">{{ $loop->index + 1 }}</td>
                                <td class="px-6 py-4 text-center">{{ $qrCode->label }}</td>
                                <td class="px-6 py-4 text-center flex justify-center">
                                    <img src="{{ Storage::url('public/qrcodes/' . $qrCode->id . '.svg') }}" alt="qr code image" class="h-16">
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button class="h-6 w-6 rounded-full {{$qrCode->status ? 'bg-green-500' : 'bg-red-500'}}"></button>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('qr.download', ['id' => $qrCode->id, 'format' => 'svg']) }}">SVG</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
