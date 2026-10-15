@props([
    'colspan' => 4,
    'message' => 'Belum ada data.',
])

<tr>
    <td colspan="{{ $colspan }}" class="p-8 text-center">
        <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        </div>
        <p class="text-gray-400 text-sm">{{ $message }}</p>
        {{ $slot }}
    </td>
</tr>
