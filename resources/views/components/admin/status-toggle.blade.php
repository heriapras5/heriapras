@props([
    'resource' => null,
    'id' => null,
    'checked' => false,
    'title' => 'Ubah status (aktif/nonaktif)',
])

<label class="relative inline-flex items-center cursor-pointer shrink-0" title="{{ $title }}">
    <input type="checkbox" class="sr-only peer status-toggle" data-resource="{{ $resource }}" data-id="{{ $id }}" @checked((bool) $checked)>
    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-600/25 rounded-full peer peer-checked:bg-blue-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:border-gray-300 after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full peer-checked:after:border-white"></div>
</label>
