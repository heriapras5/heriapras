import $ from 'jquery';
import Swal from 'sweetalert2';
import Chart from 'chart.js/auto';

// Pastikan jQuery global aktif SEBELUM plugin Summernote dimuat,
// karena Summernote (UMD) menempel ke window.jQuery pada saat evaluasinya.
window.$ = window.jQuery = $;
window.Swal = window.Swal || Swal;
window.Chart = window.Chart || Chart;

// Kompatibilitas: Summernote 0.9.1 memanggil $.now() yang dihapus di jQuery 4
// (implementasi aslinya: jQuery.now = Date.now). Tanpa polyfill ini setiap
// init editor throw di Context._initialize setelah DOM editor disisipkan,
// memicu retry-loop menumpuk editor duplikat / fallback textarea polos.
if (typeof window.jQuery.now !== 'function') {
    window.jQuery.now = Date.now;
}

const summernoteReady = Promise.all([
    import('summernote/dist/summernote-lite.min.js'),
    import('summernote/dist/lang/summernote-id-ID.min.js'),
]).then(() => {
    // Plugin sudah menempel ke jQuery - tandai agar polling cepat selesai.
    window.__summernoteLoaded = true;
}).catch((e) => {
    console.error('summernote chunk load failed:', e);
});

function snHeight($this) {
    var h = $this.data('height');
    return h ? parseInt(h, 10) : 260;
}

function snCreateSummernote($this) {
    // Bersihkan sisa .note-editor dari percobaan init sebelumnya supaya
    // setiap init selalu mulai dari DOM bersih (anti editor duplikat).
    $this.siblings('.note-editor').remove();
    $this.removeData('summernote');
    $this.summernote({
        height: snHeight($this),
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture']],
            ['view', ['codeview']],
        ],
        lang: 'id-ID',
    });
    $this.data('summernote-init', true);
}

function snTryInit($this, wait) {
    if ($this.data('summernote-init')) {
        // Editor sudah aktif. Summernote 0.9 tidak memiliki API reposition
        // (pemanggilan lama hanya no-op), jadi tidak ada yang perlu diulang.
        return true;
    }
    if (typeof $this.summernote !== 'function') return false;
    if (!$this.is(':visible')) return false;
    try {
        snCreateSummernote($this);
        return true;
    } catch (e) {
        console.error('summernote init failed:', e);
        // Exception init bersifat deterministik. Urutan internal Context:
        // createLayout (DOM editor disisipkan) → _initialize (bisa throw)
        // → $note.hide() → data("summernote"). Jika throw, textarea masih
        // tampak dan guard internal kosong; mem-retry akan menyisipkan
        // editor BARU lagi = tumpukan editor duplikat. Bersihkan & tandai
        // gagal agar fallback textarea tampil tanpa tumpukan.
        $this.siblings('.note-editor').remove();
        $this.removeData('summernote');
        $this.addClass('summernote-failed');
        return false;
    }
}

var SN_MAX_HIDDEN_ATTEMPTS = 40;
var SN_MAX_PLUGIN_ATTEMPTS = 150;

function snInitWhenReady($this, wait, attempts, pluginAttempts) {
    attempts = attempts || 0;
    pluginAttempts = pluginAttempts || 0;
    if (!document.contains($this[0])) return;
    if ($this.data('summernote-init') || $this.hasClass('summernote-failed')) return;
    // Plugin belum dimuat (chunk async lambat): retry terus tanpa batas 40x.
    if (typeof $this.summernote !== 'function') {
        if (pluginAttempts >= SN_MAX_PLUGIN_ATTEMPTS) {
            $this.addClass('summernote-failed');
            console.error('summernote plugin tidak tersedia setelah menunggu.');
            return;
        }
        setTimeout(function () { snInitWhenReady($this, wait, attempts, pluginAttempts + 1); }, 80);
        return;
    }
    if (snTryInit($this, wait)) return;
    if (attempts >= SN_MAX_HIDDEN_ATTEMPTS) return;
    // Plugin sudah ada tapi elemen masih tersembunyi: batasi retry.
    setTimeout(function () { snInitWhenReady($this, wait, attempts + 1, pluginAttempts); }, 80);
}

window.initSummernote = function (scope, wait) {
    var $scope = scope ? $(scope) : $(document);
    $scope.find('.summernote').each(function () {
        snInitWhenReady($(this), !!wait, 0);
    });
};

// Pastikan global tersedia meski chunk plugin masih dimuat, lalu scan DOM.
// Dipanggil ulang tanpa syarat saat chunk selesai agar retry yang kehabisan
// batas tetap mendapat kesempatan init.
summernoteReady.then(() => {
    $(function () {
        window.initSummernote(document);
    });
    window.initSummernote(document);
});

// Tab/pane memakai x-show (display:none) - summernote tidak bisa diinisialisasi
// saat tersembunyi. Tangani saat elemen baru muncul di DOM. Pemindaian
// di-debounce dan perubahan dari dalam editor sendiri diabaikan agar
// pengetikan tidak membebani halaman form.
if (window.MutationObserver) {
    var snScanTimer = null;
    new MutationObserver(function (mutations) {
        var internal = mutations.length > 0 && mutations.every(function (m) {
            var t = m.target;
            return t && t.nodeType === 1 && t.closest && t.closest('.note-editor,.note-modal,.note-popover');
        });
        if (internal || snScanTimer) return;
        snScanTimer = setTimeout(function () {
            snScanTimer = null;
            window.initSummernote(document, true);
        }, 150);
    }).observe(document.body, {
        childList: true,
        subtree: true,
        attributes: true,
        attributeFilter: ['style'],
    });
} else {
    window.addEventListener('resize', function () {
        window.initSummernote(document, true);
    });
}

// meta content adalah URL biasa (bukan JSON) - baca langsung sebagai string.
function swalBase(customClass) {
    return {
        buttonsStyling: false,
        reverseButtons: true,
        customClass: Object.assign({
            popup: 'rounded-2xl shadow-lg border border-gray-100',
            confirmButton: 'btn btn-danger',
            cancelButton: 'btn btn-secondary',
        }, customClass || {}),
    };
}

window.confirmDeleteForm = function (formId, options) {
    options = options || {};
    if (!window.Swal) {
        if (window.confirm(options.text || 'Yakin ingin menghapus? Data yang dihapus tidak dapat dikembalikan!')) {
            document.getElementById(formId)?.submit();
        }
        return;
    }
    Swal.fire(Object.assign(swalBase(), {
        title: options.title || 'Konfirmasi Hapus',
        text: options.text || 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: options.confirmText || 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#ef4444',
    })).then((r) => {
        if (r.isConfirmed) document.getElementById(formId)?.submit();
    });
};

window.confirmDelete = function (url, options) {
    options = options || {};
    var submit = function () {
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = url;
        var csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = CSRF_TOKEN || document.querySelector('meta[name="csrf-token"]')?.content || '';
        var method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';
        form.appendChild(csrf);
        form.appendChild(method);
        document.body.appendChild(form);
        form.submit();
    };
    if (!window.Swal) {
        if (window.confirm(options.text || 'Yakin ingin menghapus? Data yang dihapus tidak dapat dikembalikan!')) submit();
        return;
    }
    Swal.fire(Object.assign(swalBase(), {
        title: options.title || 'Konfirmasi Hapus',
        text: options.text || 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: options.confirmText || 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#ef4444',
    })).then((r) => { if (r.isConfirmed) submit(); });
};

window.confirmLogout = function () {
    if (!window.Swal) {
        if (window.confirm('Apakah Anda yakin ingin logout?')) document.getElementById('logout-form')?.submit();
        return;
    }
    Swal.fire(Object.assign(swalBase({ confirmButton: 'btn btn-danger' }), {
        title: 'Konfirmasi Logout',
        text: 'Apakah Anda yakin ingin logout?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Logout',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#ef4444',
    })).then((r) => {
        if (r.isConfirmed) document.getElementById('logout-form')?.submit();
    });
};

window.hapusGambarGaleri = function (projectId, imageId, elementId) {
    var doDelete = function () {
        var token = CSRF_TOKEN || document.querySelector('meta[name="csrf-token"]')?.content || '';
        return fetch('/admin/projects/' + projectId + '/images/' + imageId, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
        }).then(function (res) { return res.json(); }).then(function (res) {
            if (!res.success) throw new Error(res.message || 'Gagal menghapus gambar.');
            document.getElementById(elementId)?.remove();
        }).catch(function () {
            if (window.Swal) {
                Swal.fire(Object.assign(swalBase({ confirmButton: 'btn btn-primary' }), {
                    title: 'Gagal', text: 'Gambar tidak dapat dihapus. Coba lagi.', icon: 'error', confirmButtonText: 'Tutup',
                }));
            }
        });
    };
    if (!window.Swal) {
        if (window.confirm('Hapus gambar ini?')) doDelete();
        return;
    }
    window.confirmRemoveImage().then(function (r) { if (r.isConfirmed) doDelete(); });
};

window.confirmRemoveImage = function (options) {
    options = options || {};
    if (!window.Swal) return Promise.resolve(window.confirm('Hapus gambar ini?'));
    return Swal.fire(Object.assign(swalBase({ confirmButton: 'btn btn-primary' }), {
        title: options.title || 'Hapus Gambar?',
        text: options.text || 'Gambar yang dipilih akan dihapus',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
    }));
};

var STATUS_TOGGLE_URL = document.querySelector('meta[name="status-toggle-url"]')?.content || '';
var CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.content || '';

$(document).on('change', '.status-toggle', function () {
    var $t = $(this);
    var revert = function () {
        $t.prop('checked', !$t.prop('checked'));
        Swal.fire({
            title: 'Gagal',
            text: 'Status tidak dapat diperbarui. Coba lagi.',
            icon: 'error',
            confirmButtonText: 'Tutup',
            buttonsStyling: false,
            customClass: { popup: 'rounded-2xl shadow-lg border border-gray-100', confirmButton: 'btn btn-primary' }
        });
    };
    $.ajax({
        url: STATUS_TOGGLE_URL,
        method: 'POST',
        data: {
            _token: CSRF_TOKEN,
            resource: $t.data('resource'),
            id: $t.data('id'),
            status: $t.prop('checked') ? 1 : 0
        },
        dataType: 'json',
        success: function (res) {
            if (!res.success) revert();
        },
        error: revert
    });
});
