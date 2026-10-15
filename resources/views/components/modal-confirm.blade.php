@props(['id' => 'statusConfirmModal', 'title' => 'Ubah Status?', 'confirmText' => 'Ya, Ubah'])

<div id="{{ $id }}"
     x-cloak
     x-show="open"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     x-on:duyufuna-status-confirm.window="open = true; url = $event.detail.url; status = $event.detail.status; statusLabel = $event.detail.label"
     class="fixed inset-0 z-50 flex items-center justify-center p-4"
     x-data="{ open: false, url: '', status: '', statusLabel: '', submit() { confirmStatusChange(this.url, this.status) } }">
    <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="open = false"></div>
    <div class="relative bg-white rounded-3xl shadow-2xl p-6 w-full max-w-sm text-center"
         x-trap.noscroll="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">
        <div class="w-16 h-16 rounded-full bg-blue-600/10 flex items-center justify-center mx-auto mb-4 ring-8 ring-blue-600/5">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $title }}</h3>
        <p class="text-sm text-gray-500 mb-2">Status lead akan diubah menjadi</p>
        <p class="text-sm font-bold text-blue-600 mb-5" x-text="statusLabel"></p>
        <div class="flex items-center gap-3 justify-center">
            <button @click="open = false" type="button" class="px-6 py-2.5 border border-gray-200 hover:bg-gray-50 text-gray-600 text-sm font-medium rounded-xl transition">Batal</button>
            <button @click="submit()" type="button" class="px-6 py-2.5 bg-blue-600 hover:bg-masjid text-white text-sm font-medium rounded-xl transition shadow-sm">{{ $confirmText }}</button>
        </div>
    </div>
</div>