
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu — Cozy Cafe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { margin: 0; background: #f5f7fb; font-family: 'Segoe UI', system-ui, sans-serif; }
        .admin-layout { display: flex; }
        .admin-content { flex: 1; padding: 0; margin-left: 260px; }

        .topbar {
            display: flex; align-items: center; gap: 10px; padding: 14px 30px;
            border-bottom: 1px solid #e5e7eb; background: #fff; font-size: 13px; color: #6b7280;
        }
        .topbar strong { color: #111827; }

        .form-card {
            background: #fff;
            border-radius: 24px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
            margin: 28px 30px;
            max-width: 760px;
        }

        .form-card-header {
            padding: 20px 28px;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .form-card-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0b1533;
            font-size: 16px;
        }

        .form-card-title { font-size: 15px; font-weight: 700; color: #0b1533; }
        .form-card-sub   { font-size: 12px; color: #9ca3af; margin-top: 1px; }

        .form-body { padding: 28px; }

        .field-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: #6b7280;
            letter-spacing: 0.6px;
            margin-bottom: 7px;
            display: block;
        }

        .input-custom {
            width: 100%;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            padding: 10px 14px;
            font-size: 13px;
            background: #f9fafb;
            color: #111827;
            transition: all 0.18s;
            outline: none;
            appearance: none;
        }
        .input-custom:focus {
            background: #fff;
            border-color: #0b1533;
            box-shadow: 0 0 0 3px rgba(11,21,51,0.07);
        }
        select.input-custom { cursor: pointer; }
        textarea.input-custom { resize: vertical; min-height: 90px; }

        .price-group {
            display: flex;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
            background: #f9fafb;
            transition: all 0.18s;
        }
        .price-group:focus-within {
            background: #fff;
            border-color: #0b1533;
            box-shadow: 0 0 0 3px rgba(11,21,51,0.07);
        }
        .price-prefix {
            padding: 10px 14px;
            font-size: 13px;
            font-weight: 700;
            color: #9ca3af;
            background: transparent;
            border-right: 1px solid #e5e7eb;
            white-space: nowrap;
        }
        .price-input {
            flex: 1;
            border: none;
            background: transparent;
            padding: 10px 14px;
            font-size: 13px;
            color: #111827;
            outline: none;
        }

        /* ── Current image preview ── */
        .img-current-wrap {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 16px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            margin-bottom: 12px;
        }
        .img-current-wrap img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
        }
        .img-current-info { font-size: 12px; color: #6b7280; }
        .img-current-info strong { display: block; font-size: 13px; color: #0b1533; margin-bottom: 2px; }

        .file-upload-area {
            border: 1.5px dashed #d1d5db;
            border-radius: 12px;
            padding: 18px;
            text-align: center;
            background: #f9fafb;
            cursor: pointer;
            transition: all 0.18s;
            position: relative;
        }
        .file-upload-area:hover { border-color: #0b1533; background: #f1f5f9; }
        .file-upload-area input[type="file"] {
            position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
        }
        .file-upload-icon { font-size: 20px; color: #9ca3af; margin-bottom: 6px; }
        .file-upload-text { font-size: 13px; color: #6b7280; }
        .file-upload-text span { font-weight: 600; color: #0b1533; }
        .file-upload-hint { font-size: 11px; color: #9ca3af; margin-top: 3px; }

        .form-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding: 20px 28px;
            border-top: 1px solid #f3f4f6;
            background: #fafafa;
        }
        .btn-cancel-custom {
            padding: 10px 22px;
            border-radius: 30px;
            border: 1px solid #e5e7eb;
            background: #fff;
            color: #4b5563;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: all 0.15s;
        }
        .btn-cancel-custom:hover { background: #f3f4f6; color: #111827; }
        .btn-save-custom {
            padding: 10px 22px;
            border-radius: 30px;
            border: none;
            background: #0b1533;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            cursor: pointer;
            transition: background 0.15s;
        }
        .btn-save-custom:hover { background: #1e2f5e; }

        .alert-custom {
            margin: 0 30px 20px;
            border-radius: 14px;
            border: 1px solid #fca5a5;
            background: #fff1f2;
            padding: 12px 16px;
            font-size: 13px;
            color: #be123c;
        }
    </style>
</head>
<body>

<div class="admin-layout">
    <?php echo $__env->make('admin.layout.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="admin-content">
        <div class="topbar">
            <i class="fa-solid fa-utensils text-secondary"></i>
            <span>Manager</span>
            <span style="color:#d1d5db;">/</span>
            <a href="<?php echo e(route('admin.menu.index')); ?>" class="text-decoration-none" style="color:#6b7280;">Manajemen Menu</a>
            <span style="color:#d1d5db;">/</span>
            <strong>Edit Menu</strong>
        </div>

        <div style="padding: 0 30px 10px; margin-top: 28px;">
            <div style="font-size:32px;font-weight:700;color:#0b1533;">Edit Menu</div>
            <div style="color:#6b7280;margin-bottom:20px;">Ubah detail informasi atau perbarui foto dari menu item pilihan.</div>
        </div>

        <?php if($errors->any()): ?>
        <div class="alert-custom">
            <div class="fw-bold mb-1"><i class="fa-solid fa-circle-exclamation me-1"></i> Terdapat kesalahan:</div>
            <ul class="mb-0 ps-3">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>

        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon"><i class="fa-solid fa-pencil"></i></div>
                <div>
                    <div class="form-card-title"><?php echo e($menu->name); ?></div>
                    <div class="form-card-sub">Perbarui informasi menu di bawah ini</div>
                </div>
            </div>

            <form id="edit-menu-form" action="<?php echo e(route('admin.menu.update', $menu->menu_id)); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="form-body">

                    
                    <div class="mb-4">
                        <label class="field-label">Nama Menu</label>
                        <input type="text" name="name" value="<?php echo e(old('name', $menu->name)); ?>"
                               class="input-custom" placeholder="Contoh: Cappuccino Latte" required>
                    </div>

                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="field-label">Kategori Utama</label>
                            <select id="category_id" onchange="loadSubCategories(this.value)" class="input-custom" required>
                                <option value="">— Pilih Kategori —</option>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($cat->category_id); ?>"
                                        <?php echo e($menu->category_id == $cat->category_id ? 'selected' : ''); ?>>
                                        <?php echo e($cat->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="field-label">Sub Kategori</label>
                            <select name="sub_id" id="sub_id" class="input-custom" required>
                                <option value="">— Pilih Kategori Dulu —</option>
                            </select>
                        </div>
                    </div>

                    
                    <div class="mb-4">
                        <label class="field-label">Harga (Rupiah)</label>
                        <div class="price-group">
                            <span class="price-prefix">Rp</span>
                            <input type="number" name="price"
                                   value="<?php echo e(old('price', (int) $menu->price)); ?>"
                                   class="price-input" placeholder="0" min="0" step="1" required>
                        </div>
                    </div>

                    
                    <div class="mb-4">
                        <label class="field-label">Deskripsi</label>
                        <textarea name="description" class="input-custom"
                                  placeholder="Tulis rincian komposisi produk..."><?php echo e(old('description', $menu->description)); ?></textarea>
                    </div>

                    
                    <div class="mb-2">
                        <label class="field-label">Foto Produk</label>

                        
                        <?php if($menu->image_url): ?>
                        <div class="img-current-wrap">
                            <img src="<?php echo e(asset('storage/' . $menu->image_url)); ?>"
                                 onerror="this.src='<?php echo e(asset($menu->image_url)); ?>'; this.onerror=null;"
                                 id="current-img" alt="Foto saat ini">
                            <div class="img-current-info">
                                <strong>Foto Saat Ini</strong>
                                Biarkan kosong jika tidak ingin mengubah foto.
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="file-upload-area" id="upload-area">
                            <input type="file" name="image" accept="image/*" onchange="previewFile(event)">
                            <div id="upload-placeholder">
                                <div class="file-upload-icon"><i class="fa-regular fa-image"></i></div>
                                <div class="file-upload-text"><span>Klik untuk ganti foto</span> atau drag & drop</div>
                                <div class="file-upload-hint">PNG, JPG, WEBP — Maks. 2MB</div>
                            </div>
                            <img id="img-preview" src="" alt="" style="display:none; max-height:110px; border-radius:10px; margin:0 auto;">
                        </div>
                    </div>

                   

                    
                    <div class="mb-2" style="border-top: 1px solid #f3f4f6; padding-top: 24px; margin-top: 8px;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <label class="field-label" style="margin-bottom:2px;">Opsi / Catatan Menu</label>
                                <div style="font-size:12px; color:#9ca3af;">
                                    Contoh: Level Pedas, Pakai Gula, Catatan Tambahan. Opsi ini akan muncul sebagai modal saat customer menambah menu ke keranjang.
                                </div>
                            </div>
                            <button type="button" class="btn-cancel-custom" onclick="addOptionGroup()">
                                <i class="fa-solid fa-plus"></i> Tambah Grup
                            </button>
                        </div>

                        <div id="option-groups-container"></div>
                    </div>

                    
                    <template id="option-group-template">
                        <div class="option-group-card" style="border:1px solid #e5e7eb; border-radius:14px; padding:16px; margin-bottom:14px; background:#f9fafb;">
                            <div class="row g-2 mb-2">
                                <div class="col-md-6">
                                    <label class="field-label" style="font-size:10px;">Nama Grup</label>
                                    <input type="text" class="input-custom group-name-input"
                                           placeholder="Contoh: Level Pedas" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="field-label" style="font-size:10px;">Jenis Input</label>
                                    <select class="input-custom group-type-input" onchange="handleTypeChange(this)">
                                        <option value="radio">Radio (pilih satu)</option>
                                        <option value="checkbox">Checkbox (boleh lebih dari satu)</option>
                                        <option value="text">Teks bebas</option>
                                    </select>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn-cancel-custom w-100 justify-content-center remove-group-btn" onclick="removeGroup(this)" style="color:#dc2626; border-color:#fca5a5;">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input group-required-input" type="checkbox" style="cursor:pointer;">
                                <label class="form-check-label" style="font-size:12px; color:#6b7280;">Wajib dipilih customer</label>
                            </div>

                            
                            <div class="group-placeholder-wrap" style="display:none; margin-bottom:10px;">
                                <label class="field-label" style="font-size:10px;">Placeholder (opsional)</label>
                                <input type="text" class="input-custom group-placeholder-input" placeholder="Contoh: Tulis catatan tambahan...">
                            </div>

                            
                            <div class="row g-2 group-minmax-wrap" style="display:none; margin-bottom:10px;">
                                <div class="col-md-6">
                                    <label class="field-label" style="font-size:10px;">Minimal Pilihan</label>
                                    <input type="number" min="0" class="input-custom group-min-input" placeholder="0">
                                </div>
                                <div class="col-md-6">
                                    <label class="field-label" style="font-size:10px;">Maksimal Pilihan</label>
                                    <input type="number" min="1" class="input-custom group-max-input" placeholder="Tanpa batas jika kosong">
                                </div>
                            </div>

                            
                            <div class="group-options-wrap">
                                <label class="field-label" style="font-size:10px;">Daftar Pilihan</label>
                                <div class="options-list"></div>
                                <button type="button" class="btn-cancel-custom mt-1" style="font-size:12px; padding:6px 14px;" onclick="addOption(this)">
                                    <i class="fa-solid fa-plus"></i> Tambah Pilihan
                                </button>
                            </div>
                        </div>
                    </template>

                    
                    <template id="option-item-template">
                        <div class="d-flex gap-2 align-items-center mb-2 option-item-row">
                            <input type="text" class="input-custom option-name-input" placeholder="Contoh: Level 1" required style="flex:1;">
                            <button type="button" class="btn-cancel-custom" onclick="removeOption(this)" style="color:#dc2626; border-color:#fca5a5; padding:8px 12px;">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    </template>

                    
                    <input type="hidden" name="option_groups_json" id="option_groups_json">



                </div>

                <div class="form-footer">
                    <a href="<?php echo e(route('admin.menu.index')); ?>" class="btn-cancel-custom">
                        <i class="fa-solid fa-xmark"></i> Batal
                    </a>
                    <button type="submit" class="btn-save-custom">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>




<script>
const currentSubId = "<?php echo e(old('sub_id', $menu->sub_id)); ?>";

function loadSubCategories(categoryId) {
    const subSelect = document.getElementById('sub_id');
    subSelect.innerHTML = '<option value="">Sedang memuat...</option>';
    if (!categoryId) { subSelect.innerHTML = '<option value="">— Pilih Kategori Dulu —</option>'; return; }

    fetch(`/admin/get-subcategories/${categoryId}`)
        .then(r => r.json())
        .then(data => {
            subSelect.innerHTML = '<option value="">— Pilih Sub Kategori —</option>';
            data.forEach(sub => {
                const selected = sub.sub_id == currentSubId ? 'selected' : '';
                subSelect.innerHTML += `<option value="${sub.sub_id}" ${selected}>${sub.name}</option>`;
            });
        }).catch(() => { subSelect.innerHTML = '<option value="">Gagal memuat data</option>'; });
}

function previewFile(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('img-preview');
    const placeholder = document.getElementById('upload-placeholder');
    const currentImg = document.getElementById('current-img');
    if (file) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.style.display = 'block';
            placeholder.style.display = 'none';
            if (currentImg) currentImg.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

const optionGroupsContainer = document.getElementById('option-groups-container');
const groupTemplate = document.getElementById('option-group-template');
const optionItemTemplate = document.getElementById('option-item-template');

const existingOptionGroups = <?php echo json_encode($optionGroupsForJs, 15, 512) ?>;


function addOptionGroup(data = null) {
    const clone = groupTemplate.content.cloneNode(true);
    const card = clone.querySelector('.option-group-card');

    if (data) {
        card.querySelector('.group-name-input').value = data.name;
        card.querySelector('.group-type-input').value = data.input_type;
        card.querySelector('.group-required-input').checked = data.is_required;
        card.querySelector('.group-placeholder-input').value = data.placeholder || '';
        card.querySelector('.group-min-input').value = data.min_select ?? '';
        card.querySelector('.group-max-input').value = data.max_select ?? '';
    }

    optionGroupsContainer.appendChild(clone);

    const addedCard = optionGroupsContainer.lastElementChild;
    handleTypeChange(addedCard.querySelector('.group-type-input'));

    if (data && data.options && data.options.length > 0) {
        data.options.forEach(name => addOptionToCard(addedCard, name));
    }
}

function addOptionToCard(card, name = '') {
    const clone = optionItemTemplate.content.cloneNode(true);
    if (name) clone.querySelector('.option-name-input').value = name;
    card.querySelector('.options-list').appendChild(clone);
}

function addOption(buttonEl) {
    const card = buttonEl.closest('.option-group-card');
    addOptionToCard(card);
}

function removeOption(buttonEl) {
    buttonEl.closest('.option-item-row').remove();
}

function removeGroup(buttonEl) {
    buttonEl.closest('.option-group-card').remove();
}

function handleTypeChange(selectEl) {
    const card = selectEl.closest('.option-group-card');
    const type = selectEl.value;

    const optionsWrap  = card.querySelector('.group-options-wrap');
    const minmaxWrap    = card.querySelector('.group-minmax-wrap');
    const placeholderWrap = card.querySelector('.group-placeholder-wrap');

    if (type === 'text') {
        optionsWrap.style.display = 'none';
        minmaxWrap.style.display = 'none';
        placeholderWrap.style.display = 'block';
    } else if (type === 'checkbox') {
        optionsWrap.style.display = 'block';
        minmaxWrap.style.display = 'flex';
        placeholderWrap.style.display = 'none';
    } else {
        optionsWrap.style.display = 'block';
        minmaxWrap.style.display = 'none';
        placeholderWrap.style.display = 'none';
    }
}

existingOptionGroups.forEach(g => addOptionGroup(g));

document.getElementById('edit-menu-form').addEventListener('submit', function (e) {
    const groups = [];

    document.querySelectorAll('.option-group-card').forEach(card => {
        const name = card.querySelector('.group-name-input').value.trim();
        const type = card.querySelector('.group-type-input').value;

        if (!name) return;

        const group = {
            name: name,
            input_type: type,
            is_required: card.querySelector('.group-required-input').checked,
            min_select: card.querySelector('.group-min-input').value || null,
            max_select: card.querySelector('.group-max-input').value || null,
            placeholder: card.querySelector('.group-placeholder-input').value || null,
            options: [],
        };

        if (type !== 'text') {
            card.querySelectorAll('.option-name-input').forEach(input => {
                const val = input.value.trim();
                if (val) group.options.push(val);
            });

            if (group.options.length === 0) {
                e.preventDefault();
                alert(`Grup "${name}" belum punya pilihan. Tambahkan minimal 1 pilihan atau hapus grup ini.`);
                throw new Error('Validation stopped');
            }
        }

        groups.push(group);
    });

    document.getElementById('option_groups_json').value = JSON.stringify(groups);
});


document.addEventListener('DOMContentLoaded', function () {
    const initialCategoryId = document.getElementById('category_id').value;
    if (initialCategoryId) loadSubCategories(initialCategoryId);
});
</script>
</body>
</html><?php /**PATH C:\laragon\www\newCafeApp\resources\views/admin/manager/management/menu/edit.blade.php ENDPATH**/ ?>