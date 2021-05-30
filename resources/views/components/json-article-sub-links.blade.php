<div>
    <!-- Navigation Links -->
    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
        <x-jet-nav-link href="{{ route('json_article') }}" :active="request()->routeIs('json_article')">
            Files/Upload
        </x-jet-nav-link>
        <x-jet-nav-link href="{{ route('json_article.data') }}" :active="request()->routeIs('json_article.data')">
            All Data
        </x-jet-nav-link>
        <x-jet-nav-link href="{{ route('json_article.export') }}" :active="request()->routeIs('json_article.export')">
            Export
        </x-jet-nav-link>
    </div>
</div>