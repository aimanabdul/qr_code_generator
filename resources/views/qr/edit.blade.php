<x-app-layout>
    @section('extra_head')
        <script src="https://maps.googleapis.com/maps/api/js?key={{ config("app.google_api_public_key") }}=places"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @endsection
    <div class="mt-6 mx-4 w-full md:max-w-3xl md:mx-auto">
        <div class="mb-6">
            <img src="{{ Storage::url('public/qr_codes/' . $qrCode->label . '.png') }}" alt="qr code image" class="h-12 mb-2 lg:h-16  {{$qrCode->foreground_color == 'white' ? 'bg-black' : ''}}">
            <span class="text-sm text-gray-700 p-1 bg-gray-200">{{$qrCode->label}}</span>
        </div>

        
        <div class="flex flex-row gap-4">
            <!-- open modal -->
            <a class="bg-green-500 text-white py-3 px-4 rounded-md hover:bg-green-400 mb-8 hover:cursor-pointer" id="openPlaceIdModal">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 inline-block">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                </svg>
                <span>{{__("Update Google Profile")}}</span> 
            </a>
            <a href="{{route("qr.getActivationCode", $qrCode->id)}}" class="bg-green-500 text-white py-3 px-4 rounded-md hover:bg-green-400 mb-8">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 inline-block">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
                </svg>
                <span>{{__("Generate Activation Code")}}</span> 
            </a>
        </div>
        <h2 class="text-gray-700 text-xl font-bold">{{__("Activation Code")}}</h2>
        <div class="mb-8">  
            @if(!$qrCode->activation_code)
                <p class="text-gray-600">{{__("No activation code generated yet.")}}</p>
            @else
            <p class="text-gray-600">{{$qrCode->activation_code}} <span class="{{$qrCode->activation_code_is_active ? 'bg-green-500': 'bg-red-500'}} p-1 text-white font-medium rounded text-xs">{{$qrCode->activation_code_is_active ? 'active': 'inactive'}}</span></p>
            <p class="text-sm text-gray-500">{{__("Created at: ")}} {{$qrCode->activation_code_created_at}}</p>
            <p class="text-sm text-gray-500">{{__("Expired at: ")}} {{$qrCode->activation_code_expired_at}}</p>
            <p class="text-sm text-gray-500">{{__("Used at: ")}} {{$qrCode->activation_code_used_at ?? '-- Not used yet -- '}}</p>
            @endif
        </div>
        <h2 class="text-gray-700 text-xl font-bold">Business Info</h2>
        <div class="border p-4 bg-stone-50 rounded-lg shadow-sm text-gray-600">
            
            <p>
                <span class="font-bold uppercase">Naam:</span> <span id="placeNameDisplay" class="text-gray-600">{{$qrCode->business_name ?? ''}}<!-- data from placeIdForm here --></span>
            </p>
            <p>
                <span class="font-bold uppercase">Address:</span> <span id="placeAddressDisplay" class="text-gray-600">{{$qrCode->address ?? ''}}<!-- data from placeIdForm here --></span>
            </p>
            <p>
                <span class="font-bold">ID:</span> <span id="placeIdDisplay" class="text-gray-600">{{$qrCode->business_id ?? ''}}<!-- data from placeIdForm here --></span>
            </p>
            <p>
                <span class="font-bold uppercase">Review Link:</span> <span id="reviewLinkDisplay" class="text-gray-600">{{$qrCode->forwarding_link ?? ''}}<!-- data from placeIdForm here --></span>
            </p>
        </div>

        <form method="POST" action="{{ route('qr.update', $qrCode->id) }}" class="w-full " id="updateForm">
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
                                <x-input-label for="place_name" :value="__('Search with google')" />
                                <x-text-input id="search_place_name" name="search_place_name" class="block mt-1 w-full" placeholder="Type a place name" type="text" required />
                                <x-input-error :messages="$errors->get('place_id')" class="mt-2" />
                                <p class="text-sm text-gray-500 px-2">{{__("Please select from the list")}}</p>

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
