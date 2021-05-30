<div>
    <!-- Navigation Links -->
    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
        <x-jet-nav-link href="{{ route('xml_pub_med') }}" :active="request()->routeIs('xml_pub_med')">
            Files/Upload
        </x-jet-nav-link>
        <x-jet-nav-link href="{{ route('xml_pub_med.data') }}" :active="request()->routeIs('xml_pub_med.data')">
            All Data
        </x-jet-nav-link>
        <x-jet-nav-link href="{{ route('xml_pub_med.export') }}" :active="request()->routeIs('xml_pub_med.export')">
            Export
        </x-jet-nav-link>
    </div>
</div>