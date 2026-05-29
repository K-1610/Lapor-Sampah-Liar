(function(){
  const flash = document.querySelector('[data-flash]');
  if (flash) {
    const type = flash.getAttribute('data-flash-type') || 'success';
    const msg = flash.getAttribute('data-flash') || '';
    if (msg) {
      Swal.fire({
        icon: type,
        title: type === 'success' ? 'Berhasil' : 'Perhatian',
        text: msg,
        confirmButtonColor: '#198754'
      });
    }
  }

  const table = document.querySelector('.datatable');
  if (table && typeof $.fn.DataTable !== 'undefined') {
    $(table).DataTable({
      responsive: true,
      pageLength: 10,
      order: [],
      language: {
        search: "Cari:",
        lengthMenu: "Tampilkan _MENU_ data",
        zeroRecords: "Data tidak ditemukan",
        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
        infoEmpty: "Tidak ada data",
        paginate: { previous: "Sebelumnya", next: "Berikutnya" }
      }
    });
  }

  const inputImg = document.querySelector('[data-preview-input]');
  const preview = document.querySelector('[data-preview-target]');
  if (inputImg && preview) {
    inputImg.addEventListener('change', function(){
      const file = this.files && this.files[0];
      if (file) {
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('d-none');
      }
    });
  }

  document.querySelectorAll('[data-confirm]').forEach(btn => {
    btn.addEventListener('click', function(e){
      const msg = this.getAttribute('data-confirm') || 'Yakin?';
      if (!confirm(msg)) e.preventDefault();
    });
  });

  const ajaxStatus = document.querySelectorAll('[data-status-action]');
  ajaxStatus.forEach(el => {
    el.addEventListener('click', async function(){
      const id = this.getAttribute('data-id');
      const status = this.getAttribute('data-status');
      const token = document.querySelector('input[name="_csrf"]')?.value || '';
      try{
        const form = new FormData();
        form.append('id', id);
        form.append('status', status);
        form.append('_csrf', token);
        const res = await fetch(this.getAttribute('data-url'), { method:'POST', body: form });
        const json = await res.json();
        if (json.ok) Swal.fire('Berhasil', json.message, 'success').then(()=>location.reload());
        else Swal.fire('Gagal', json.message || 'Terjadi kesalahan', 'error');
      }catch(err){
        Swal.fire('Gagal', 'Tidak bisa memproses permintaan', 'error');
      }
    });
  });
})();
