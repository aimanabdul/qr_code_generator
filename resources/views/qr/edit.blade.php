<x-app-layout>
    @section('extra_head')
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAArrPWnROsdtg4yGLLNC9npQVwbvEcz88&libraries=places"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @endsection
    <div class="mt-6 mx-4 w-full md:max-w-3xl md:mx-auto">
        <div class="mb-6">
            <img src="{{ Storage::url('public/qr_codes/' . $qrCode->label . '.png') }}" alt="qr code image" class="h-12 lg:h-16  {{$qrCode->foreground_color == 'white' ? 'bg-black' : ''}}">
            <h4 class="text-sm text-gray-600">{{$qrCode->label}}</h4>
        </div>
        <div>
            @if(!$qrCode->business_id)
                <button class="bg-green-500 text-white py-1 px-4 rounded hover:bg-green-400 mb-2" id="openPlaceIdModal">
                    FIND
                </button>
            @endif
            <h2 class="text-gray-700 text-xl font-bold">Business Info</h2>
            <p>
                <span>Naam:</span> <span id="placeNameDisplay">{{$qrCode->business_name ?? ''}}<!-- data from placeIdForm here --></span>
            </p>
            <p>
                <span>Address:</span> <span id="placeAddressDisplay">{{$qrCode->address ?? ''}}<!-- data from placeIdForm here --></span>
            </p>
            <p>
                <span>ID:</span> <span id="placeIdDisplay">{{$qrCode->business_id ?? ''}}<!-- data from placeIdForm here --></span>
            </p>
            <p>
                <span>Review Link:</span> <span id="reviewLinkDisplay">{{$qrCode->forwarding_link ?? ''}}<!-- data from placeIdForm here --></span>
            </p>

            <!-- Modal for placeIdForm -->
            <div id="placeIdModal" class="fixed z-10 inset-0 overflow-hidden hidden">
                <div class="flex items-center justify-center min-h-screen bg-gray-900 bg-opacity-50 overflow-hidden">
                    <div class="bg-white p-6 rounded-lg shadow-lg w-full md:max-w-md overflow-x-auto">
                        <div class="text-right">
                            <button id="closePlaceIdModal" class="text-gray-700 text-xl">&times;</button>
                        </div>
                        <form action="#" method="post" id="placeIdForm">
                            @csrf
                            <div class="mb-6">
                                <div class="mt-4" x-data="autocomplete()">
                                    <x-input-label for="place_name" :value="__('Google Place ID')" />
                                    <x-text-input id="search_place_name" name="search_place_name" class="block mt-1 w-full" placeholder="Type a place name" type="text" required />
                                    <x-input-error :messages="$errors->get('place_id')" class="mt-2" />
                                </div>
                            </div>
                            <button type="submit" class="bg-gray-700 text-white py-2 px-5 rounded">
                                Submit
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @if(!$qrCode->business_id)
            <form method="POST" action="{{ route('qr.update', $qrCode->id) }}" class="w-full" id="updateForm">
                @csrf
                <div class="flex items-center gap-4 mt-4 w-full">
                    <input type="text" id="place_name" name="place_name" class="hidden">
                    <input type="text" id="place_address" name="place_address" class="hidden">
                    <input type="text" id="place_id" name="place_id" class="hidden" />
                    <x-primary-button class="">
                        {{ __('Save') }}
                    </x-primary-button>
                </div>
            </form>
        @endif
    </div>

    @section('extra_scripts')
        <script>
            function autocomplete() {
                const searchPlaceNameInput = $('#search_place_name');
                const placeNameInput = $('#place_name');
                const placeAddressInput = $('#place_address');
                const placeIdInput = $('#place_id');
                const autocomplete = new google.maps.places.Autocomplete(searchPlaceNameInput[0]);

                autocomplete.addListener('place_changed', function() {
                    const place = autocomplete.getPlace();
                    if (place.place_id) {
                        placeIdInput.val(place.place_id);
                        searchPlaceNameInput.val(place.name + ', ' + place.formatted_address);
                        placeNameInput.val(place.name);
                        placeAddressInput.val(place.name + ', ' + place.formatted_address);
                    }
                });
            }

            $(document).ready(function() {
                // Initialize Google Places Autocomplete
                autocomplete();

                // Open modal on button click
                $('#openPlaceIdModal').click(function() {
                    $('#placeIdModal').removeClass('hidden');
                });

                // Close modal on close button click
                $('#closePlaceIdModal').click(function() {
                    $('#placeIdModal').addClass('hidden');
                });

                // Handle form submission
                $('#placeIdForm').submit(function(event) {
                    event.preventDefault();
                    const placeName = $('#place_name').val();
                    const placeAddress = $('#place_address').val();
                    const placeId = $('#place_id').val();

                    $('#placeNameDisplay').text(placeName);
                    $('#placeAddressDisplay').text(placeAddress);
                    $('#placeIdDisplay').text(placeId);
                    $('#reviewLinkDisplay').text(`https://search.google.com/local/writereview?placeid=${placeId}`);

                    $('#placeIdModal').addClass('hidden');
                });
            });
        </script>
    @endsection
</x-app-layout>
