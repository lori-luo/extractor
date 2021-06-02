<div>
    <!-- Navigation Links -->
    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
        <x-jet-nav-link href="{{ route('json_journal') }}" :active="request()->routeIs('json_journal')">
            Files/Upload
        </x-jet-nav-link>
        <x-jet-nav-link href="{{ route('json_journal.data') }}" :active="request()->routeIs('json_journal.data')">
            All Data
        </x-jet-nav-link>
        <x-jet-nav-link href="{{ route('json_journal.export') }}" :active="request()->routeIs('json_journal.export')">
            Export
        </x-jet-nav-link>
    </div>
</div>