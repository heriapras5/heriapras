@props(['id' => 'drawer', 'title' => '', 'width' => 'max-w-lg'])

<div x-data="{ open: false }"
     x-show="open"
     x-cloak
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50">
    <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="open = false"></div>
    <div class="fixed inset-y-0 right-0 w-full {{ $width }} bg-white shadow-2xl flex flex-col"
         x-trap.noscroll="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-x-full"
         x-transition:enter-end="opacity-100 translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-x-0"
         x-transition:leave-end="opacity-0 translate-x-full">
        <div class="flex items-center justify-between px-6 h-16 border-b border-gray-100 shrink-0">
            <h3 class="text-base font-bold text-gray-900">{{ $title }}</h3>
            <button @click="open = false" class="p-2 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-6" id="{{ $id }}-body">
            {{ $slot }}
        </div>
    </div>
</div>
