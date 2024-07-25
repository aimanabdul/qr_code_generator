{{-- session key = success --}}
@if (session()->has('success'))
    <div x-data="{ show: true }" x-show="show" id="alert-border-3" class="flex items-center p-4 mb-4 text-green-800 border-l-4 border-green-300 bg-green-100" role="alert">
        <i class="fa-solid fa-circle-check fa-xl"></i>
        <div class="ml-3 text-sm font-medium">
            {!! session()->get('success') !!}
        </div>
        <button @click="show = false" type="button" class="ml-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8" aria-label="Close">
            <span class="sr-only">Dismiss</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </button>
    </div>
@endif

{{-- session key = danger --}}
@if (session()->has('danger'))
    <div x-data="{ show: true }" x-show="show" id="alert-border-2" class="flex items-center p-4 mb-4 text-red-800 border-l-4 border-red-300 bg-red-100 " role="alert">
        <i class="fa-solid fa-triangle-exclamation fa-xl"></i>
        <div class="ml-3 text-sm font-medium">
            {!! session()->get('danger') !!}
        </div>
        <button @click="show = false" type="button" class="ml-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8" aria-label="Close">
            <span class="sr-only">Dismiss</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </button>
    </div>
@endif

{{-- session key = info --}}
@if (session()->has('info'))
    <div x-data="{ show: true }" x-show="show" id="alert-border-1" class="flex items-center p-4 mb-4 text-blue-800 border-l-4 border-blue-300 bg-blue-100" role="alert">
        <i class="fa-solid fa-circle-info fa-xl"></i>
        <div class="ml-3 text-sm font-medium">
            {!! session()->get('info') !!}
        </div>
        <button @click="show = false" type="button" class="ml-auto -mx-1.5 -my-1.5 bg-blue-50 text-blue-500 rounded-lg focus:ring-2 focus:ring-blue-400 p-1.5 hover:bg-blue-200 inline-flex items-center justify-center h-8 w-8 " aria-label="Close">
            <span class="sr-only">Dismiss</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </button>
    </div>
@endif
