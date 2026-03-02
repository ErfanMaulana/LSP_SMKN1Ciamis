# AJAX Search Implementation Guide

Sistem AJAX Search dengan Debounce untuk semua halaman admin LSP SMKN 1 Ciamis.

## 🎯 Fitur
- Real-time search tanpa perlu enter/klik tombol
- Debounce 500ms untuk menghindari terlalu banyak request
- Filter otomatis
- Loading indicator
- Error handling

## ✅ Yang Sudah Diimplementasikan

### 1. Halaman Asesor (COMPLETE)
- ✅ Controller updated
- ✅ Partial view dibuat
- ✅ JavaScript AJAX added
- ✅ Search by: nama, ID, no_reg
- ✅ Filter by: keahlian, status

### 2. Halaman Asesi (Controller Ready)
- ✅ Controller updated dengan AJAX support
- ⏳ Perlu: Partial view + JavaScript

### 3. Halaman Skema (Controller Ready)
- ✅ Controller updated dengan AJAX support
- ⏳ Perlu: Partial view + JavaScript

## 📋 Cara Implementasi di Halaman Baru

### Step 1: Update Controller

```php
public function index(Request $request)
{
    $query = Model::with('relations');
    
    // Search filter
    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('column1', 'LIKE', "%{$search}%")
              ->orWhere('column2', 'LIKE', "%{$search}%");
        });
    }
    
    // Additional filters
    if ($request->has('filter1') && $request->filter1 != '') {
        $query->where('filter_column', $request->filter1);
    }
    
    $data = $query->paginate(10);
    
    // If AJAX request, return only table rows
    if ($request->ajax()) {
        return view('admin.module.partials.table-rows', compact('data'))->render();
    }
    
    return view('admin.module.index', compact('data'));
}
```

### Step 2: Buat Partial View

Buat file: `resources/views/admin/[module]/partials/table-rows.blade.php`

```blade
@forelse($data as $item)
<tr>
    <td>{{ $item->column1 }}</td>
    <td>{{ $item->column2 }}</td>
    <!-- ... kolom lainnya ...  -->
</tr>
@empty
<tr>
    <td colspan="4" class="text-center">
        <div style="padding: 40px 20px;">
            <i class="bi bi-search" style="font-size: 48px; color: #cbd5e1;"></i>
            <p style="color: #64748b;">Tidak ada data ditemukan</p>
        </div>
    </td>
</tr>
@endforelse
```

### Step 3: Update View Index

#### A. Tambahkan ID ke search input dan filters

```blade
<input type="text" id="searchInput" placeholder="Cari..." autocomplete="off">

<select class="filter-select" id="filter1">
    <option value="">Semua</option>
    <!-- options -->
</select>
```

#### B. Ganti tbody dengan include partial

```blade
<tbody id="dataTableBody">
    @include('admin.module.partials.table-rows', ['data' => $items])
</tbody>
```

#### C. OPTION 1: Tambahkan JavaScript inline

```blade
<script>
    let searchTimeout;
    const searchInput = document.getElementById('searchInput');
    const filter1 = document.getElementById('filter1');
    const tableBody = document.getElementById('dataTableBody');

    function performSearch() {
        const params = new URLSearchParams();
        if (searchInput.value) params.append('search', searchInput.value);
        if (filter1.value) params.append('filter1', filter1.value);

        tableBody.innerHTML = `<tr><td colspan="4" class="text-center">
            <div style="padding: 40px;">
                <div class="spinner-border text-primary"></div>
                <p style="color: #64748b;">Mencari data...</p>
            </div>
        </td></tr>`;

        fetch(`{{ route('admin.module.index') }}?${params}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(html => tableBody.innerHTML = html)
        .catch(() => tableBody.innerHTML = `<tr><td colspan="4" class="text-center">
            <i class="bi bi-exclamation-triangle text-danger"></i>
            <p>Terjadi kesalahan</p>
        </td></tr>`);
    }

    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(performSearch, 500); // Debounce 500ms
    });

    filter1.addEventListener('change', performSearch);
</script>
```

#### OPTION 2: Gunakan Reusable Script (ajax-search.js)

```blade
@section('scripts')
<script src="{{ asset('js/ajax-search.js') }}"></script>
<script>
    AjaxSearch.init({
        searchInputId: 'searchInput',
        tableBodyId: 'dataTableBody',
        routeUrl: '{{ route("admin.module.index") }}',
        filters: ['#filter1', '#filter2'],
        debounceDelay: 500,
        colspan: 4 // sesuaikan jumlah kolom
    });
</script>
@endsection
```

## 🔧 Customization

### Ubah Debounce Delay
```javascript
debounceDelay: 300  // 300ms (lebih cepat)
debounceDelay: 1000 // 1 detik (lebih lambat)
```

### Custom Loading HTML
```javascript
loadingHtml: `<tr><td colspan="4">Your custom loading...</td></tr>`
```

### Custom Error HTML  
```javascript
errorHtml: `<tr><td colspan="4">Your custom error...</td></tr>`
```

## 📝 Checklist Implementasi

### Untuk Setiap Halaman:

- [ ] Update Controller:
  - [ ] Add Request parameter
  - [ ] Add search query logic
  - [ ] Add filter logic
  - [ ] Add AJAX check: `if ($request->ajax())`
  
- [ ] Buat Partial View:
  - [ ] Create `partials/table-rows.blade.php`
  - [ ] Move table row content dari index
  - [ ] Add empty state styling
  
- [ ] Update Index View:
  - [ ] Add ID to search input: `id="searchInput"`
  - [ ] Add ID to filters: `id="xxxFilter"`
  - [ ] Add ID to tbody: `id="xxxTableBody"`
  - [ ] Replace tbody content with `@include`
  - [ ] Add JavaScript (inline or external)

## 🎨 Halaman yang Perlu Diimplementasikan

1. ✅ **Asesor** - DONE
2. ⏳ **Asesi** - Controller ready, perlu view update
3. ⏳ **Skema** - Controller ready, perlu view update
4. ⏳ **Mitra**
5. ⏳ **Jurusan**
6. ⏳ **Verifikasi Asesi**
7. ⏳ **Akun Asesi (NIK)**
8. ⏳ **Profile Content**
9. ⏳ **Banner Carousel**
10. ⏳ **Social Media**

## 💡 Tips

1. **Debounce**: Delay 500ms optimal untuk user experience
2. **Loading State**: Selalu tampilkan loading indicator
3. **Error Handling**: Tangani error dengan pesan yang jelas
4. **Empty State**: Beri feedback ketika hasil kosong
5. **Pagination**: AJAX search akan reset ke page 1

## 🚀 Quick Start

Untuk halaman baru, copy structure dari halaman Asesor yang sudah complete:
- Controller: `app/Http/Controllers/AsesorController.php`
- Partial View: `resources/views/admin/asesor/partials/table-rows.blade.php`
- Index View: `resources/views/admin/asesor/index.blade.php` (lihat bagian script)

---

**Dibuat oleh:** GitHub Copilot Assistant  
**Tanggal:** 2 Maret 2026
