<x-app-layout>
    <x-slot name="header">
        <x-json-article-sub-links />
    </x-slot>

    <div class="py-12">
        <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <livewire:page-article-show-data />
            </div>
        </div>
    </div>
</x-app-layout>