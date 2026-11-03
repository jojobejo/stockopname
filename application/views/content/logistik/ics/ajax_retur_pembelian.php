<script>
    $(function() {
        const $faktur = $('#select_faktur');
        const $barang = $('#select_barang');
        const $exp = $('#select_exp');
        const $kode = $('#kode_barang');
        const $lot = $('#nolot_isi');

        function setSelectDisabled($el, disabled) {
            $el.prop('disabled', disabled);
            if (disabled) {
                $el.val(null).trigger('change');
            }
        }

        setSelectDisabled($barang, true);
        setSelectDisabled($exp, true);
        setSelectDisabled($lot, true);

        $faktur.select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih nomor faktur',
            allowClear: true,
            ajax: {
                url: "<?= base_url('ics/retur/pembelian/faktur_select2') ?>",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        term: params.term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

        $barang.select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih barang',
            allowClear: true,
            ajax: {
                url: "<?= base_url('ics/retur/pembelian/barang_select2') ?>",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        term: params.term,
                        kd_faktur: $faktur.val()
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

        $exp.select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih expired date',
            allowClear: true,
            ajax: {
                url: "<?= base_url('ics/retur/pembelian/exp_select2') ?>",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        term: params.term,
                        kd_faktur: $faktur.val(),
                        kd_barang: $barang.val()
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

        function renderDetailTable(rows) {
            const $tbody = $('#input_retur_penjualan tbody');
            $tbody.empty();

            if (!rows || rows.length === 0) {
                return;
            }

            rows.forEach(function(r) {
                const tr = `
                    <tr>
                        <td>${r.kd_faktur || ''}</td>
                        <td>${r.kd_barang || ''}</td>
                        <td>${r.nama_barang || ''}</td>
                        <td>${r.tgl_expired || ''}</td>
                        <td>${r.no_lot || '-'}</td>
                        <td>${r.qty || ''}</td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm btn-delete-detail" data-id="${r.id}">Hapus</button>
                        </td>
                    </tr>
                `;
                $tbody.append(tr);
            });
        }

        function loadDetailTable() {
            $.ajax({
                url: "<?= base_url('ics/retur/pembelian/list_detail') ?>",
                type: "GET",
                dataType: "json",
                success: function(res) {
                    renderDetailTable(res);
                },
                error: function() {
                    $('#input_retur_penjualan tbody').empty();
                }
            });
        }

        $faktur.on('change', function() {
            const hasFaktur = !!$faktur.val();
            $kode.val('');
            setSelectDisabled($barang, !hasFaktur);
            setSelectDisabled($exp, true);
        });

        $barang.on('select2:select', function(e) {
            const data = e.params.data || {};
            $kode.val(data.id || '');
        });

        $barang.on('change', function() {
            const hasBarang = !!$barang.val();
            if (!hasBarang) {
                $kode.val('');
            }
            setSelectDisabled($exp, !hasBarang);
        });

        $('#btninputdata').on('click', function(e) {
            e.preventDefault();

            const payload = {
                kd_faktur: $faktur.val(),
                kd_barang: $kode.val(),
                tgl_expired: $exp.val(),
                qty: $('#qtyinput').val(),
                no_lot: $lot.val()
            };

            if (!payload.kd_faktur || !payload.kd_barang || !payload.tgl_expired || !payload.qty) {
                alert('Data belum lengkap.');
                return;
            }

            $.ajax({
                url: "<?= base_url('ics/retur/pembelian/add_detail') ?>",
                type: "POST",
                dataType: "json",
                data: payload,
                success: function(res) {
                    if (res && res.status) {
                        $('#qtyinput').val('');
                        alert(res.message || 'Data tersimpan');
                        loadDetailTable();
                    } else {
                        alert((res && res.message) ? res.message : 'Gagal menyimpan data');
                    }
                },
                error: function() {
                    alert('Server error. Coba lagi.');
                }
            });
        });

        $('#input_retur_penjualan tbody').on('click', '.btn-delete-detail', function() {
            const id = $(this).data('id');
            if (!id) {
                alert('ID tidak valid.');
                return;
            }
            if (!confirm('Hapus data retur ini?')) {
                return;
            }

            $.ajax({
                url: "<?= base_url('ics/retur/pembelian/delete_detail') ?>",
                type: 'POST',
                dataType: 'json',
                data: {
                    id: id
                },
                success: function(res) {
                    if (res && res.status) {
                        alert(res.message || 'Data terhapus');
                        loadDetailTable();
                    } else {
                        alert((res && res.message) ? res.message : 'Gagal menghapus data');
                    }
                },
                error: function() {
                    alert('Server error. Coba lagi.');
                }
            });
        });

        $('#rekamreturpembelian').on('click', function(e) {
            e.preventDefault();

            const kdRetur = $('#nofresnsi').val();
            const keterangan = $('#keterangan_retur').val();
            const tglTransaksi = $('#tgl_transaksi').val();

            if (!kdRetur) {
                alert('No referensi belum diisi.');
                return;
            }

            if (!confirm('Rekam retur pembelian sekarang?')) {
                return;
            }

            $.ajax({
                url: "<?= base_url('ics/retur/rekam_pembelian') ?>",
                type: 'POST',
                dataType: 'json',
                data: {
                    kd_retur: kdRetur,
                    keterangan: keterangan,
                    tgl_transaksi: tglTransaksi
                },
                success: function(res) {
                    if (res && res.status) {
                        alert(res.message || 'Retur pembelian tersimpan');
                        location.reload();
                    } else {
                        alert((res && res.message) ? res.message : 'Gagal rekam retur');
                    }
                },
                error: function() {
                    alert('Server error. Coba lagi.');
                }
            });
        });

        loadDetailTable();
    });
</script>
