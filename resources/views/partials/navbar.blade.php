<nav class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-50">

    <div class="flex items-center justify-between h-16 px-6">

        {{-- Logo --}}
        <div class="flex items-center gap-3">

            <img src="{{ asset('images/logoBitpps.png') }}"
                 alt="PSPP"
                 class="w-10 h-10">

            <div>
                <h1 class="text-lg font-bold text-gray-800">
                    PSPP Platform
                </h1>

                <p class="text-xs text-gray-500">
                    Phrae Sangha Provincial Platform
                </p>
            </div>

        </div>

        {{-- Right Menu --}}
        <div class="flex items-center gap-6">

            {{-- Notifications --}}
            <button
                class="relative text-gray-600 hover:text-blue-600 transition">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-6 h-6"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 11-6 0h6z"/>

                </svg>

                <span class="absolute -top-1 -right-1
                             w-4 h-4 rounded-full
                             bg-red-500 text-white
                             text-[10px]
                             flex items-center justify-center">

                    0

                </span>

            </button>

            {{-- User --}}
            <div class="flex items-center gap-3">

                <div class="text-right">

                    <div class="font-semibold text-gray-800">

                        {{ auth()->user()->name }}

                    </div>

                    <div class="text-xs text-gray-500">

                        {{ ucfirst(auth()->user()->role) }}

                    </div>

                </div>

                <div class="w-10 h-10 rounded-full
                            bg-blue-600
                            text-white
                            flex items-center justify-center
                            font-bold">

                    {{ strtoupper(substr(auth()->user()->name,0,1)) }}

                </div>

            </div>
           
                @csrf

            

            </form>

        </div>

    </div>

</nav>