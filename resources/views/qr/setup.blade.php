<x-guest-layout>
    @section('extra_head')
        <script src="https://maps.googleapis.com/maps/api/js?key={{config('app.google_api_public_key')}}=places"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @endsection
    @include('layouts.alerts')
    <div class="mt-6 flex flex-col justify-center">
        <div class="mb-6 flex justify-center">
            <img src="{{ Storage::url('public/qr_codes/' . $qrCode->label . '.png') }}" alt="qr code image" class="h-36 lg:h-64  {{$qrCode->foreground_color == 'white' ? 'bg-black' : ''}}">

        </div>

        <div>
            <!-- open modal -->
            <div>
                <h2 class="text-gray-600 text-xl font-bold">{{__("Stap 1: U Google profile ophalen")}}</h2>
                <button class=" text-gray-800 border border-gray-800 py-1 px-4 rounded hover:bg-gray-800 hover:text-white mb-4" id="openPlaceIdModal">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 inline-block">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                    </svg>
                    <span>Zoeken</span>
                </button>
            </div>
            <h2 class="text-gray-700">{{(__("Uw profiel:"))}}</h2>
            <div class="text-gray-600 border p-2 shadow-sm bg-slate-50">
                
                <p>
                    <span class="">Naam:</span> <span id="placeNameDisplay" class="text-gray-600 font-semibold">{{$qrCode->business_name ?? ''}}<!-- data from placeIdForm here --></span>
                </p>
                <p>
                    <span class="">Address:</span> <span id="placeAddressDisplay" class="text-gray-600 font-semibold">{{$qrCode->address ?? ''}}<!-- data from placeIdForm here --></span>
                </p>
            </div>

            <form method="POST" action="{{ route('qr.activateByCustomer', $qrCode->id) }}" class="w-full my-8" id="updateForm">
                @csrf
                <div class="w-full">
                    <div>
                        <h2 class="text-gray-600 text-xl font-bold mt-8">{{__("Stap 2: Geef uw activatiecode in")}}</h2>
                        <input type="text" id="activation_code" name="activation_code" value="{{old('activation_code')}}" class="rounded border border-gray-300">
                        <x-input-error :messages="$errors->get('activation_code')" class="mt-2" />
                        <p class="text-gray-500 text-sm mt-2">U kan de activatiecode vinden in de email die u ontvangen heeft.</p>
                    </div>
                    <input type="text" id="place_name" name="place_name" class="hidden">
                    <input type="text" id="place_address" name="place_address" class="hidden">
                    <input type="text" id="place_id" name="place_id" class="hidden" />
                    <button type="submit" class="bg-green-500 text-white py-2 px-5 rounded hover:bg-green-400 mt-6">
                        {{ __('Bevestigen') }}
                    </button>
                </div>
            </form>

            <div role="alert" class="mt-3 relative flex w-full p-3 text-sm text-slate-600 rounded-md bg-slate-100">
                <p>Kunt u uw organisatie niet terugvinden met Google Place Finder? Stuur ons gerust een mailtje en we zullen u verder helpen.</p>
            </div>
        </div>
    </div>

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
                                <x-input-label for="place_name" :value="__('Type en kies uit de lijst!')" />
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
</x-guest-layout>
