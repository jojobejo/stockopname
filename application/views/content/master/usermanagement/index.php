<body class="hold-transition sidebar-mini sidebar-collapse">
<div class="wrapper">
    <?php $this->load->view('partial/main/navbar') ?>
    <?php $this->load->view('partial/main/sidebar') ?>
    <div class="content-wrapper">
        <section class="content p-3 p-md-4">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
            <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
            <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
            <style>
                .erp-page{color:#172033}.erp-hero{background:linear-gradient(135deg,#0f766e,#2563eb);border-radius:18px;color:#fff;padding:22px 24px;box-shadow:0 18px 40px rgba(37,99,235,.22)}
                .erp-panel{background:#fff;border:1px solid #e5e7eb;border-radius:14px;box-shadow:0 12px 30px rgba(15,23,42,.08)}.erp-panel-header{padding:16px 18px;border-bottom:1px solid #edf2f7}
                .btn-erp{background:linear-gradient(135deg,#2563eb,#0f766e);border:0;color:#fff}.btn-erp:hover{color:#fff;filter:brightness(.95)}
                .form-control,.form-select,.select2-container--default .select2-selection--single{border-radius:10px;border-color:#d7dee8}.select2-container .select2-selection--single{height:38px}.select2-selection__rendered{line-height:36px!important}.select2-selection__arrow{height:36px!important}
                table.dataTable thead th{position:sticky;top:0;background:#f8fafc!important;z-index:2}.badge-soft-success{background:#dcfce7;color:#166534}.badge-soft-danger{background:#fee2e2;color:#991b1b}
                .avatar{width:38px;height:38px;border-radius:50%;object-fit:cover;background:#e2e8f0}.action-group{display:flex;gap:6px;flex-wrap:wrap}.skeleton{min-height:72px;border-radius:12px;background:linear-gradient(90deg,#f1f5f9,#e2e8f0,#f1f5f9);background-size:200% 100%;animation:shimmer 1.1s infinite}@keyframes shimmer{to{background-position:-200% 0}}
                @media(max-width:768px){.erp-hero{padding:18px}.filter-grid{grid-template-columns:1fr!important}.action-group .btn{flex:1}}
            </style>

            <div class="erp-page">
                <div class="erp-hero mb-3">
                    <div class="d-flex flex-column flex-md-row justify-content-between gap-3 align-items-md-center">
                        <div><h3 class="mb-1 fw-bold">User Management</h3><div class="opacity-75">Hybrid legacy + normalized role permission untuk KARISMA ERP.</div></div>
                        <button class="btn btn-light fw-semibold" id="btnAdd"><i class="fas fa-user-plus me-1"></i> Tambah User</button>
                    </div>
                </div>

                <div class="erp-panel mb-3">
                    <div class="erp-panel-header">
                        <div class="d-grid filter-grid gap-2" style="grid-template-columns:repeat(5,minmax(0,1fr));">
                            <select id="filter_departemen" class="form-select"><option value="">Departemen</option><?php foreach($departemen as $d): ?><option value="<?= html_escape($d['id']) ?>"><?= html_escape($d['text']) ?></option><?php endforeach ?></select>
                            <select id="filter_jobdesk" class="form-select"><option value="">Jobdesk</option><?php foreach($jobdesk as $j): ?><option value="<?= html_escape($j['nama_jobdesk']) ?>"><?= html_escape($j['nama_jobdesk']) ?></option><?php endforeach ?></select>
                            <select id="filter_akses" class="form-select"><option value="">Akses</option><?php foreach($akses_level as $a): ?><option value="<?= html_escape($a['nama_akses_level']) ?>"><?= html_escape($a['nama_akses_level']) ?></option><?php endforeach ?></select>
                            <select id="filter_status" class="form-select"><option value="">Status</option><option value="1">Aktif</option><option value="0">Nonaktif</option></select>
                            <button class="btn btn-outline-secondary" id="btnResetFilter"><i class="fas fa-undo me-1"></i> Reset</button>
                        </div>
                    </div>
                    <div class="p-3">
                        <div id="tableSkeleton" class="skeleton mb-2"></div>
                        <table class="table table-hover align-middle w-100 compact" id="tableUsers">
                            <thead><tr><th>User</th><th>NIK</th><th>Departemen</th><th>Jobdesk</th><th>Akses</th><th>Status</th><th>Aksi</th></tr></thead>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<div class="modal fade" id="modalUser" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <form class="modal-content" id="formUser" enctype="multipart/form-data">
            <div class="modal-header">
                <h5 class="modal-title">Form User</h5>
                <button type="button" class="btn-close js-close-user-modal" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id" name="id">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">NIK</label><input class="form-control" name="nik" id="nik" required></div>
                    <div class="col-md-6"><label class="form-label">Nama</label><input class="form-control" name="nm_karyawan" id="nm_karyawan" required></div>
                    <div class="col-md-6"><label class="form-label">Username</label><input class="form-control" name="username" id="username" required></div>
                    <div class="col-md-6"><label class="form-label">Password</label><input type="password" class="form-control" name="password" id="password" placeholder="Isi saat tambah / reset manual"></div>
                    <div class="col-md-4"><label class="form-label">Departemen</label><input class="form-control" name="departemen" id="departemen" list="listDepartemen"></div>
                    <div class="col-md-4"><label class="form-label">Jobdesk</label><select class="form-select select2" name="jobdesk_id" id="jobdesk_id"><option value="">Pilih</option><?php foreach($jobdesk as $j): ?><option value="<?= html_escape($j['id_jobdesk'] ?? '') ?>" data-text="<?= html_escape($j['nama_jobdesk']) ?>"><?= html_escape($j['nama_jobdesk']) ?></option><?php endforeach ?></select><input type="hidden" name="jobdesk" id="jobdesk"></div>
                    <div class="col-md-4"><label class="form-label">Akses Level</label><select class="form-select select2" name="akses_lv_id" id="akses_lv_id"><option value="">Pilih</option><?php foreach($akses_level as $a): ?><option value="<?= html_escape($a['id_akses_level'] ?? '') ?>" data-text="<?= html_escape($a['nama_akses_level']) ?>"><?= html_escape($a['nama_akses_level']) ?></option><?php endforeach ?></select><input type="hidden" name="akses_lv" id="akses_lv"></div>
                    <div class="col-md-4"><label class="form-label">Tim</label><input type="number" class="form-control" name="tim" id="tim" value="0"></div>
                    <div class="col-md-4"><label class="form-label">Wilayah</label><input type="number" class="form-control" name="wilayah" id="wilayah" value="0"></div>
                    <div class="col-md-4"><label class="form-label">Status</label><select class="form-select" name="status" id="status"><option value="1">Aktif</option><option value="0">Nonaktif</option></select></div>
                    <div class="col-md-12"><label class="form-label">Foto Profile</label><input type="file" class="form-control" name="foto" accept="image/*"></div>
                </div>
                <datalist id="listDepartemen"><?php foreach($departemen as $d): ?><option value="<?= html_escape($d['id']) ?>"></option><?php endforeach ?></datalist>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-outline-secondary js-close-user-modal" data-bs-dismiss="modal" data-dismiss="modal">Batal</button><button class="btn btn-erp" type="submit"><i class="fas fa-save me-1"></i> Simpan</button></div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
$(function(){
    const modal = new bootstrap.Modal(document.getElementById('modalUser'));
    $('.select2').select2({width:'100%', dropdownParent: $('#modalUser')});
    $('.js-close-user-modal').on('click', function(e){
        e.preventDefault();
        modal.hide();
    });
    function selectedText(id){ return $('#' + id + ' option:selected').data('text') || $('#' + id + ' option:selected').text(); }
    const table = $('#tableUsers').DataTable({
        processing:true, serverSide:true, responsive:true, searchDelay:350,
        ajax:{url:'<?= base_url('master/user-management/list') ?>', type:'POST', data:function(d){d.departemen=$('#filter_departemen').val();d.jobdesk=$('#filter_jobdesk').val();d.akses_lv=$('#filter_akses').val();d.status=$('#filter_status').val();}},
        columns:[
            {data:null, render:r=>`<div class="d-flex align-items-center gap-2"><img class="avatar" src="${r.foto ? '<?= base_url() ?>'+r.foto : '<?= base_url('assets/images/Karisma.png') ?>'}"><div><div class="fw-semibold">${r.nm_karyawan||'-'}</div><small class="text-muted">${r.username||'-'}</small></div></div>`},
            {data:'nik'}, {data:'departemen'}, {data:'jobdesk'}, {data:'akses_lv'},
            {data:null, render:r=> r.status === undefined ? '<span class="badge bg-secondary">Legacy</span>' : (parseInt(r.status)===1 ? '<span class="badge badge-soft-success">Aktif</span>' : '<span class="badge badge-soft-danger">Nonaktif</span>')},
            {data:null, orderable:false, searchable:false, render:r=>`<div class="action-group"><button class="btn btn-sm btn-outline-primary btn-edit" data-id="${r.id}"><i class="fas fa-edit"></i></button><button class="btn btn-sm btn-outline-warning btn-reset" data-id="${r.id}"><i class="fas fa-key"></i></button><button class="btn btn-sm btn-outline-secondary btn-toggle" data-id="${r.id}"><i class="fas fa-power-off"></i></button><button class="btn btn-sm btn-outline-danger btn-delete" data-id="${r.id}"><i class="fas fa-trash"></i></button></div>`}
        ],
        initComplete:function(){ $('#tableSkeleton').hide(); }
    });
    $('#filter_departemen,#filter_jobdesk,#filter_akses,#filter_status').on('change',()=>table.ajax.reload());
    $('#btnResetFilter').on('click',function(){ $('.filter-grid select').val(''); table.ajax.reload(); });
    $('#btnAdd').on('click',function(){ $('#formUser')[0].reset(); $('#id').val(''); $('.select2').val('').trigger('change'); modal.show(); });
    $('#jobdesk_id').on('change',()=>$('#jobdesk').val(selectedText('jobdesk_id')));
    $('#akses_lv_id').on('change',()=>$('#akses_lv').val(selectedText('akses_lv_id')));
    $('#tableUsers').on('click','.btn-edit',function(){ $.getJSON('<?= base_url('master/user-management/detail/') ?>'+$(this).data('id'),function(res){ if(!res.status){toastr.error(res.message);return;} $.each(res.data,function(k,v){$('#'+k).val(v);}); $('#jobdesk_id').val(res.data.jobdesk_id || '').trigger('change'); $('#akses_lv_id').val(res.data.akses_lv_id || '').trigger('change'); $('#password').val(''); modal.show(); }); });
    $('#formUser').on('submit',function(e){ e.preventDefault(); $('#jobdesk').val(selectedText('jobdesk_id')); $('#akses_lv').val(selectedText('akses_lv_id')); const id=$('#id').val(); const url=id?'<?= base_url('master/user-management/update/') ?>'+id:'<?= base_url('master/user-management/save') ?>'; $.ajax({url:url,type:'POST',data:new FormData(this),processData:false,contentType:false,dataType:'json',success:function(res){res.status?toastr.success(res.message):toastr.error(res.message); if(res.status){modal.hide(); table.ajax.reload(null,false);}},error:function(){toastr.error('Server tidak merespons.');}}); });
    $('#tableUsers').on('click','.btn-delete',function(){ const id=$(this).data('id'); Swal.fire({title:'Hapus user?',icon:'warning',showCancelButton:true,confirmButtonText:'Hapus'}).then(r=>{ if(r.isConfirmed) $.post('<?= base_url('master/user-management/delete/') ?>'+id,function(res){res.status?toastr.success(res.message):toastr.error(res.message); table.ajax.reload(null,false);},'json'); }); });
    $('#tableUsers').on('click','.btn-toggle',function(){ $.post('<?= base_url('master/user-management/toggle-status/') ?>'+$(this).data('id'),function(res){res.status?toastr.success(res.message):toastr.warning(res.message); table.ajax.reload(null,false);},'json'); });
    $('#tableUsers').on('click','.btn-reset',function(){ const id=$(this).data('id'); Swal.fire({title:'Reset Password',input:'password',inputPlaceholder:'Password baru minimal 6 karakter',showCancelButton:true,confirmButtonText:'Reset'}).then(r=>{ if(r.isConfirmed) $.post('<?= base_url('master/user-management/reset-password/') ?>'+id,{password:r.value},function(res){res.status?toastr.success(res.message):toastr.error(res.message);},'json'); }); });
});
</script>
</body>
