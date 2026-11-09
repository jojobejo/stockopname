(function (window) {
    'use strict';
    window.StockopnameZahirImport = {
        init: function (options) {
            var $ = window.jQuery;
            var modal = $('#zahirCsvImportModal');
            if (!modal.length || modal.data('zahirImportBound')) { return; }
            modal.data('zahirImportBound', true);
            var body = modal.find('.zahir-import-modal-body');
            var progress = modal.find('.zahir-import-progress');
            var caption = modal.find('.zahir-import-progress-label');
            var busy = false;
            function updateFileState() {
                var input = body.find('input[type="file"]')[0];
                if (!input) { return; }
                var file = input.files && input.files[0];
                var valid = !!file && /\.csv$/i.test(file.name) && file.size > 0 && file.size <= 5242880;
                input.setCustomValidity(file && !valid ? 'Pilih file CSV berisi data, maksimal 5 MB.' : '');
                body.find('.zahir-file-name').text(file ? file.name : 'Pilih file CSV');
                body.find('.zahir-file-meta').text(file ? (valid ? Math.ceil(file.size / 1024) + ' KB - siap diimpor' : 'Pilih file CSV berisi data, maksimal 5 MB.') : 'Klik untuk memilih file - Maks. 5 MB');
                body.find('.zahir-import-submit').prop('disabled', busy || !valid);
                body.find('.zahir-import-current,.zahir-import-panel > .alert,.zahir-import-errors,.zahir-result').toggleClass('d-none', !!file);
            }
            function setBusy(value, text) {
                busy = value;
                modal.find('[data-dismiss="modal"],form button,form input').prop('disabled', value);
                progress.toggleClass('d-none', !value);
                progress.find('.progress-bar').css('width', '0%').attr('aria-valuenow', 0);
                caption.text(text || '');
                body.attr('aria-busy', value ? 'true' : 'false');
                updateFileState();
            }
            function failed() {
                body.empty().append($('<div>', {'class': 'alert alert-danger', role: 'alert'}).text('Koneksi terputus atau sesi berakhir. Periksa status impor sebelum mencoba lagi.'))
                    .append($('<button>', {type: 'button', 'class': 'btn btn-outline-primary', 'data-reload-zahir-import': '1'}).text('Muat ulang'));
            }
            function render(html) {
                var fragment = $('<div>').append($.parseHTML(html, document, false));
                var panel = fragment.find('.zahir-import-panel').first();
                if (!panel.length) { failed(); return; }
                body.empty().append(fragment.contents());
                if (options.onResponse) { options.onResponse(panel); }
                if (panel.attr('data-import-completed') === '1' && options.onCommitted) { options.onCommitted(); }
            }
            function load() {
                if (busy) { return; }
                body.html('<p class="text-muted" role="status">Memuat...</p>');
                setBusy(true, 'Memuat...');
                $.ajax({url: options.url, type: 'GET', dataType: 'html', timeout: 30000, cache: false})
                    .done(render).fail(failed).always(function () { setBusy(false); });
            }
            modal.on('show.bs.modal', load);
            modal.on('hide.bs.modal', function (event) { if (busy) { event.preventDefault(); } });
            body.on('click', '[data-reload-zahir-import]', load);
            body.on('change', 'input[type="file"]', updateFileState);
            body.on('submit', 'form', function (event) {
                event.preventDefault();
                if (busy || !this.reportValidity()) { return; }
                var data = new FormData(this);
                var operation = data.get('operation');
                setBusy(true, operation === 'import' ? 'Mengimpor CSV...' : 'Memproses...');
                $.ajax({url: this.action, type: 'POST', data: data, processData: false, contentType: false, dataType: 'html', timeout: 150000,
                    xhr: function () {
                        var xhr = $.ajaxSettings.xhr();
                        if (xhr.upload && operation === 'import') {
                            xhr.upload.addEventListener('progress', function (event) {
                                if (!event.lengthComputable) { return; }
                                var percent = Math.round(event.loaded * 100 / event.total);
                                progress.find('.progress-bar').css('width', percent + '%').attr('aria-valuenow', percent);
                                if (percent === 100) { caption.text('File diterima. Memproses data...'); }
                            });
                        }
                        return xhr;
                    }
                }).done(function (html) { render(html); body.scrollTop(0); })
                    .fail(failed).always(function () { setBusy(false); });
            });
        }
    };
}(window));
