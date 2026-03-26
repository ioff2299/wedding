<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Административная панель — Свадьба</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Cormorant+Garamond:ital,wght@0,300;0,400&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <script>window.csrfToken = '{{ csrf_token() }}';</script>
</head>
<body class="admin-body">

<!-- Navbar -->
<nav class="admin-navbar">
    <div class="admin-navbar-brand">Панель управления</div>
    <div class="admin-navbar-actions">
        <a href="/" target="_blank" class="admin-logout-btn jost" style="text-decoration:none;">
            ↗ Открыть сайт
        </a>
        <form method="POST" action="{{ route('admin.logout') }}" style="margin:0;">
            @csrf
            <button type="submit" class="admin-logout-btn jost">Выйти</button>
        </form>
    </div>
</nav>

<!-- Stats -->
<div class="admin-stats">
    <div class="stat-card">
        <div class="stat-number jost">{{ $stats['total'] }}</div>
        <div class="stat-label jost">Всего ответов</div>
    </div>
    <div class="stat-card">
        <div class="stat-number jost" style="color:#2D7A2D;">{{ $stats['attending'] }}</div>
        <div class="stat-label jost">Придут</div>
    </div>
    <div class="stat-card">
        <div class="stat-number jost" style="color:#C83232;">{{ $stats['declined'] }}</div>
        <div class="stat-label jost">Не придут</div>
    </div>
</div>

<!-- Tabs -->
<div class="admin-tabs">
    <div class="tab-nav">
        <button class="tab-btn active jost" data-tab="blocks">Настройка блоков</button>
        <button class="tab-btn jost" data-tab="guests">Гости</button>
    </div>

    <!-- ════════ TAB: BLOCKS ════════ -->
    <div class="tab-content active" id="tab-blocks">
        @foreach($blocks as $block)
        <div class="block-card" id="block-{{ $block->key }}">
            <div class="block-card-header" onclick="toggleBlock('{{ $block->key }}')">
                <span class="block-card-title jost">{{ $block->title }}</span>
                <span class="block-card-toggle jost">▼</span>
            </div>
            <div class="block-card-body">
                <form onsubmit="saveBlock(event, '{{ $block->key }}')" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    @php $content = $block->content; $key = $block->key; @endphp

                    <div class="admin-field">
                        <label class="admin-label jost">Видимость на сайте</label>
                        <select name="is_visible" class="admin-input jost" style="max-width:280px;">
                            <option value="1" @selected($block->is_visible)>Показывать</option>
                            <option value="0" @selected(!$block->is_visible)>Скрыть</option>
                        </select>
                    </div>

                    {{-- ── HERO ── --}}
                    @if($key === 'hero')
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                        <div class="admin-field">
                            <label class="admin-label jost">Имя жениха</label>
                            <input type="text" name="groom_name" class="admin-input jost" value="{{ $content['groom_name'] ?? '' }}">
                        </div>
                        <div class="admin-field">
                            <label class="admin-label jost">Имя невесты</label>
                            <input type="text" name="bride_name" class="admin-input jost" value="{{ $content['bride_name'] ?? '' }}">
                        </div>
                    </div>
                    <div class="admin-field">
                        <label class="admin-label jost">Слоган (в script-шрифте)</label>
                        <input type="text" name="tagline" class="admin-input jost" value="{{ $content['tagline'] ?? '' }}">
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                        <div class="admin-field">
                            <label class="admin-label jost">Текст Save the Date</label>
                            <input type="text" name="save_date_text" class="admin-input jost" value="{{ $content['save_date_text'] ?? '' }}">
                        </div>
                        <div class="admin-field">
                            <label class="admin-label jost">Дата свадьбы</label>
                            <input type="text" name="date" class="admin-input jost" value="{{ $content['date'] ?? '' }}" placeholder="25/06/2026">
                        </div>
                    </div>
                    <div class="admin-field">
                        <label class="admin-label jost">Вступительный текст</label>
                        <textarea name="intro_text" class="admin-textarea jost">{{ $content['intro_text'] ?? '' }}</textarea>
                    </div>
                    <div class="admin-field">
                        <label class="admin-label jost">Фото пары</label>
                        <div class="image-upload-wrap" id="img-wrap-{{ $block->key }}">
                            @if(!empty($block->image_path))
                            <img src="/{{ $block->image_path }}" class="current-image" alt="Фото" id="img-preview-{{ $block->key }}">
                            @else
                            <img src="" class="current-image" alt="Фото" id="img-preview-{{ $block->key }}" style="display:none;">
                            @endif
                            <div>
                                <label class="btn-upload jost">
                                    Выбрать фото
                                    <input type="file" name="image_file_only" accept="image/*" style="display:none;" onchange="previewAndUpload(event, '{{ $block->key }}')">
                                </label>
                                <div id="upload-status-{{ $block->key }}" style="font-size:0.72rem; color:var(--text-light); margin-top:4px; display:none;"></div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- ── LOCATION ── --}}
                    @if($key === 'location')
                    <div id="venues-list-{{ $block->key }}" class="events-list">
                        @foreach(($content['venues'] ?? []) as $i => $venue)
                        <div class="event-item">
                            <input type="text" name="venues[{{ $i }}][time]" class="admin-input jost" value="{{ $venue['time'] ?? '' }}" placeholder="Время">
                            <input type="text" name="venues[{{ $i }}][name]" class="admin-input jost" value="{{ $venue['name'] ?? '' }}" placeholder="Название места">
                            <input type="text" name="venues[{{ $i }}][address]" class="admin-input jost" value="{{ $venue['address'] ?? '' }}" placeholder="Адрес">
                            <button type="button" class="btn-remove-event" onclick="removeItem(this.closest('.event-item'))">×</button>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn-add-event jost" onclick="addVenue('{{ $block->key }}')">+ Добавить место</button>
                    <div class="admin-field" style="margin-top:18px;">
                        <label class="admin-label jost">Адрес для карты (Яндекс)</label>
                        <input type="text" name="map_address" class="admin-input jost" value="{{ $content['map_address'] ?? '' }}" placeholder="Город, улица, дом">
                        <p class="jost" style="font-size:0.68rem; color:var(--text-light); margin-top:6px;">Координаты подставятся при сохранении (геокодер Яндекса, если в .env задан YANDEX_MAPS_API_KEY), если широта/долгота не заполнены вручную.</p>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                        <div class="admin-field">
                            <label class="admin-label jost">Широта (опционально)</label>
                            <input type="text" name="map_lat" class="admin-input jost" value="{{ $content['map_lat'] ?? '' }}" placeholder="55.7558">
                        </div>
                        <div class="admin-field">
                            <label class="admin-label jost">Долгота (опционально)</label>
                            <input type="text" name="map_lng" class="admin-input jost" value="{{ $content['map_lng'] ?? '' }}" placeholder="37.6173">
                        </div>
                    </div>
                    <div class="admin-field">
                        <label class="admin-label jost">Фотографии локации (слайдер на сайте)</label>
                        <p class="jost" style="font-size:0.68rem; color:var(--text-light); margin:0 0 10px;">Можно выбрать сразу несколько файлов (Ctrl/Cmd + клик). Сохраните блок — фото появятся в карусели над картой.</p>
                        <div id="loc-gallery-{{ $block->key }}">
                            @foreach(($content['location_images'] ?? []) as $img)
                            <div class="location-img-row" style="display:flex; align-items:center; gap:10px; margin-bottom:8px;">
                                <input type="hidden" name="location_images_existing[]" value="{{ $img }}">
                                <img src="/{{ $img }}" alt="" style="max-height:56px; border-radius:4px; object-fit:cover;">
                                <button type="button" class="btn-remove-event" onclick="this.closest('.location-img-row').remove()">×</button>
                            </div>
                            @endforeach
                        </div>
                        <input type="file" name="location_images_new[]" class="admin-input jost" accept="image/*" multiple>
                    </div>
                    @endif

                    {{-- ── BETWEEN TEXT ── --}}
                    @if($key === 'between_text')
                    <div class="admin-field">
                        <label class="admin-label jost">Текст блока</label>
                        <textarea name="text" class="admin-textarea jost" rows="6">{{ $content['text'] ?? '' }}</textarea>
                    </div>
                    @endif

                    {{-- ── TIMING ── --}}
                    @if($key === 'timing')
                    <p class="admin-label jost" style="margin-bottom:10px;">События в тайминге</p>
                    <div id="events-list-{{ $block->key }}" class="events-list">
                        @foreach(($content['events'] ?? []) as $i => $event)
                        <div class="event-item">
                            <input type="text" name="events[{{ $i }}][time]" class="admin-input jost" value="{{ $event['time'] ?? '' }}" placeholder="Время">
                            <input type="text" name="events[{{ $i }}][title]" class="admin-input jost" value="{{ $event['title'] ?? '' }}" placeholder="Заголовок">
                            <input type="text" name="events[{{ $i }}][description]" class="admin-input jost" value="{{ $event['description'] ?? '' }}" placeholder="Описание">
                            <button type="button" class="btn-remove-event" onclick="removeItem(this.closest('.event-item'))">×</button>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn-add-event jost" onclick="addEvent('{{ $block->key }}')">+ Добавить событие</button>
                    @endif

                    {{-- ── DRESS CODE ── --}}
                    @if($key === 'dress_code')
                    <div class="admin-field">
                        <label class="admin-label jost">Вступление</label>
                        <input type="text" name="intro" class="admin-input jost" value="{{ $content['intro'] ?? '' }}">
                    </div>
                    <div class="admin-field">
                        <label class="admin-label jost">Описание</label>
                        <textarea name="description" class="admin-textarea jost">{{ $content['description'] ?? '' }}</textarea>
                    </div>
                    <div class="admin-field">
                        <label class="admin-label jost">Заключение</label>
                        <input type="text" name="outro" class="admin-input jost" value="{{ $content['outro'] ?? '' }}">
                    </div>
                    <div class="admin-field">
                        <label class="admin-label jost">Цветовая палитра</label>
                        <div class="colors-editor" id="colors-{{ $block->key }}">
                            @foreach(($content['colors'] ?? []) as $ci => $color)
                            <div class="color-item">
                                <input type="color" name="colors[{{ $ci }}]" value="{{ $color }}">
                                <button type="button" class="btn-remove-color" onclick="removeItem(this.closest('.color-item'))">удалить</button>
                            </div>
                            @endforeach
                            <button type="button" class="btn-add-color" onclick="addColor('{{ $block->key }}')">+</button>
                        </div>
                    </div>
                    <div class="admin-field">
                        <label class="admin-label jost">Фото (dress code)</label>
                        <div class="image-upload-wrap" id="img-wrap-{{ $block->key }}">
                            @if(!empty($block->image_path))
                            <img src="/{{ $block->image_path }}" class="current-image" alt="Dress code" id="img-preview-{{ $block->key }}">
                            @else
                            <img src="" class="current-image" alt="" id="img-preview-{{ $block->key }}" style="display:none;">
                            @endif
                            <div>
                                <label class="btn-upload jost">
                                    Выбрать фото
                                    <input type="file" name="image_file_only" accept="image/*" style="display:none;" onchange="previewAndUpload(event, '{{ $block->key }}')">
                                </label>
                                <div id="upload-status-{{ $block->key }}" style="font-size:0.72rem; color:var(--text-light); margin-top:4px; display:none;"></div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- ── DETAILS ── --}}
                    @if($key === 'details')
                    <div class="admin-field">
                        <label class="admin-label jost">Текст про подарки / пожелания</label>
                        <textarea name="wishes_text" class="admin-textarea jost" rows="5">{{ $content['wishes_text'] ?? '' }}</textarea>
                    </div>
                    <div class="admin-field">
                        <label class="admin-label jost">Фраза про конверт</label>
                        <input type="text" name="gift_text" class="admin-input jost" value="{{ $content['gift_text'] ?? '' }}">
                    </div>
                    @endif

                    {{-- ── FORM ── --}}
                    @if($key === 'form')
                    @php $fo = $content['form_options'] ?? []; @endphp
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                        <div class="admin-field">
                            <label class="admin-label jost">Дедлайн ответа</label>
                            <input type="text" name="submit_deadline" class="admin-input jost" value="{{ $content['submit_deadline'] ?? '' }}" placeholder="10/06/2026">
                        </div>
                    </div>
                    <div class="admin-field">
                        <label class="admin-label jost">Заключительная фраза</label>
                        <input type="text" name="closing_text" class="admin-input jost" value="{{ $content['closing_text'] ?? '' }}">
                    </div>
                    <div class="admin-field">
                        <label class="admin-label jost">Подпись</label>
                        <input type="text" name="closing_subtext" class="admin-input jost" value="{{ $content['closing_subtext'] ?? '' }}">
                    </div>
                    <p class="admin-label jost" style="margin-top:16px;">Тексты вопросов на сайте</p>
                    @foreach(['attending' => 'Вопрос про присутствие', 'food' => 'Вопрос про еду', 'alcohol' => 'Вопрос про алкоголь', 'allergy' => 'Аллергия'] as $qk => $qt)
                    <div class="admin-field">
                        <label class="admin-label jost">{{ $qt }}</label>
                        <input type="text" name="question_labels[{{ $qk }}]" class="admin-input jost" value="{{ $content['question_labels'][$qk] ?? '' }}">
                    </div>
                    @endforeach
                    <p class="admin-label jost" style="margin-top:12px;">Варианты «Присутствие» (value = то, что сохранится в ответе)</p>
                    <div id="form-opt-attending" class="events-list">
                        @foreach(($fo['attending'] ?? []) as $i => $row)
                        <div class="event-item form-opt-row">
                            <input type="text" name="form_options[attending][{{ $i }}][value]" class="admin-input jost" value="{{ $row['value'] ?? '' }}" placeholder="value">
                            <input type="text" name="form_options[attending][{{ $i }}][label]" class="admin-input jost" value="{{ $row['label'] ?? '' }}" placeholder="Подпись">
                            <button type="button" class="btn-remove-event" onclick="removeFormOptRow(this)">×</button>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn-add-event jost" onclick="addFormOptionRow('form-opt-attending','attending')">+ Вариант</button>
                    <p class="admin-label jost" style="margin-top:12px;">Варианты «Еда»</p>
                    <div id="form-opt-food" class="events-list">
                        @foreach(($fo['food'] ?? []) as $i => $row)
                        <div class="event-item form-opt-row">
                            <input type="text" name="form_options[food][{{ $i }}][value]" class="admin-input jost" value="{{ $row['value'] ?? '' }}" placeholder="value">
                            <input type="text" name="form_options[food][{{ $i }}][label]" class="admin-input jost" value="{{ $row['label'] ?? '' }}" placeholder="Подпись">
                            <button type="button" class="btn-remove-event" onclick="removeFormOptRow(this)">×</button>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn-add-event jost" onclick="addFormOptionRow('form-opt-food','food')">+ Вариант</button>
                    <p class="admin-label jost" style="margin-top:12px;">Варианты «Алкоголь» (можно выбрать несколько)</p>
                    <div id="form-opt-alcohol" class="events-list">
                        @foreach(($fo['alcohol'] ?? []) as $i => $row)
                        <div class="event-item form-opt-row">
                            <input type="text" name="form_options[alcohol][{{ $i }}][value]" class="admin-input jost" value="{{ $row['value'] ?? '' }}" placeholder="value">
                            <input type="text" name="form_options[alcohol][{{ $i }}][label]" class="admin-input jost" value="{{ $row['label'] ?? '' }}" placeholder="Подпись">
                            <button type="button" class="btn-remove-event" onclick="removeFormOptRow(this)">×</button>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn-add-event jost" onclick="addFormOptionRow('form-opt-alcohol','alcohol')">+ Вариант</button>
                    @endif

                    <div style="display:flex; align-items:center;">
                        <button type="submit" class="btn-save-block jost">Сохранить</button>
                        <span class="save-status jost" id="status-{{ $block->key }}">✓ Сохранено</span>
                    </div>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    <!-- ════════ TAB: GUESTS ════════ -->
    <div class="tab-content" id="tab-guests">
        <div class="guests-toolbar">
            <span class="guests-count jost">Всего заполнили форму: <strong>{{ $stats['total'] }}</strong></span>
            <a href="{{ route('admin.guests.export') }}" class="btn-export jost">
                ↓ Скачать CSV
            </a>
        </div>

        <div class="guests-table-wrap">
            @if($guests->isEmpty())
            <div class="guests-empty cormorant">
                Пока нет ответов от гостей
            </div>
            @else
            <table class="guests-table">
                <thead>
                    <tr>
                        <th class="jost">#</th>
                        <th class="jost">Имя</th>
                        <th class="jost">Придёт</th>
                        <th class="jost">Еда</th>
                        <th class="jost">Алкоголь</th>
                        <th class="jost">Аллергия</th>
                        <th class="jost">Дата ответа</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($guests as $i => $guest)
                    <tr>
                        <td class="jost" style="color:var(--text-light); font-size:0.75rem;">{{ $i + 1 }}</td>
                        <td class="jost" style="font-weight:500;">{{ $guest->name }}</td>
                        <td>
                            @if($guest->attending)
                                <span class="badge badge-yes jost">Да</span>
                            @else
                                <span class="badge badge-no jost">Нет</span>
                            @endif
                        </td>
                        <td class="jost" style="font-size:0.8rem; color:var(--text-light);">
                            @php
                            $foodMap = [
                                'none' => 'Нет',
                                'no_meat' => 'Без мяса',
                                'no_fish' => 'Без рыбы',
                                'vegetarian' => 'Вегетарианец',
                            ];
                            @endphp
                            {{ $foodMap[$guest->food_preference] ?? ($guest->food_preference ?: '—') }}
                        </td>
                        <td class="jost" style="font-size:0.78rem;">
                            @php
                            $alcoholMap = [
                                'red_dry'         => 'Красное сухое',
                                'red_sweet'       => 'Красное п/сладкое',
                                'white_dry'       => 'Белое сухое',
                                'white_sweet'     => 'Белое п/сладкое',
                                'champagne_dry'   => 'Шампанское сухое',
                                'champagne_sweet' => 'Шампанское п/сладкое',
                                'whiskey'         => 'Виски/коньяк',
                                'none'            => 'Не пьёт',
                            ];
                            $prefs = $guest->alcohol_preferences ?? [];
                            $labels = array_map(fn($v) => $alcoholMap[$v] ?? $v, $prefs);
                            echo implode(', ', $labels) ?: '—';
                            @endphp
                        </td>
                        <td class="jost" style="font-size:0.8rem; color:var(--text-light);">
                            {{ $guest->food_allergy ?: '—' }}
                        </td>
                        <td class="jost" style="font-size:0.75rem; color:var(--text-light); white-space:nowrap;">
                            {{ $guest->created_at->format('d.m.Y H:i') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
</div><!-- .admin-tabs -->

<div style="height:60px;"></div>

<script>
/* ─── Tabs ─── */
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById('tab-' + btn.dataset.tab).classList.add('active');
    });
});

/* ─── Block accordion ─── */
function toggleBlock(key) {
    const card = document.getElementById('block-' + key);
    card.classList.toggle('is-open');
}

/* ─── Remove list item and re-index parent ─── */
function removeItem(el) {
    const list = el.closest('.events-list');
    el.remove();
    if (list) reindexList(list, list.id.startsWith('venues') ? 'venues' : 'events');
}

/* ─── Save block (text fields only, no image) ─── */
async function saveBlock(e, key) {
    e.preventDefault();
    const form   = e.target;
    const status = document.getElementById('status-' + key);
    const btn    = form.querySelector('.btn-save-block');

    btn.textContent = 'Сохраняем…';
    btn.disabled    = true;

    const formData = new FormData(form);
    formData.append('_method', 'PUT');
    // Remove any file inputs to avoid re-uploading accidentally
    formData.delete('image');
    formData.delete('image_file_only');

    if (key === 'location') {
        const gal = document.getElementById('loc-gallery-' + key);
        if (gal && gal.querySelectorAll('.location-img-row').length === 0) {
            formData.append('location_images_existing[]', '');
        }
    }

    try {
        const res  = await fetch(`/admin/blocks/${key}`, {
            method:  'POST',
            headers: { 'X-CSRF-TOKEN': window.csrfToken, 'Accept': 'application/json' },
            body:    formData,
        });
        const json = await res.json();

        if (json.success) {
            status.classList.add('visible');
            setTimeout(() => status.classList.remove('visible'), 2500);
        }
    } catch(err) {
        alert('Ошибка сохранения: ' + err.message);
    } finally {
        btn.textContent = 'Сохранить';
        btn.disabled    = false;
    }
}

/* ─── Preview then upload image ─── */
async function previewAndUpload(e, key) {
    const file = e.target.files[0];
    if (!file) return;

    const preview  = document.getElementById('img-preview-' + key);
    const statusEl = document.getElementById('upload-status-' + key);

    // Immediate local preview via FileReader
    if (preview) {
        const reader = new FileReader();
        reader.onload = (ev) => {
            preview.src = ev.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }

    if (statusEl) {
        statusEl.textContent = 'Загружаем…';
        statusEl.style.display = 'block';
        statusEl.style.color = 'var(--text-light)';
    }

    const formData = new FormData();
    formData.append('image', file);

    try {
        const res  = await fetch(`/admin/blocks/${key}/image`, {
            method:  'POST',
            headers: { 'X-CSRF-TOKEN': window.csrfToken, 'Accept': 'application/json' },
            body:    formData,
        });
        const json = await res.json();

        if (json.success && json.block?.image_path) {
            // Update preview with server URL
            if (preview) {
                preview.src = '/' + json.block.image_path;
                preview.style.display = 'block';
            }
            if (statusEl) {
                statusEl.textContent = '✓ Загружено';
                statusEl.style.color = '#2D7A2D';
                setTimeout(() => { statusEl.style.display = 'none'; }, 2500);
            }
        } else {
            if (statusEl) {
                statusEl.textContent = 'Ошибка загрузки';
                statusEl.style.color = '#C83232';
            }
        }
    } catch(err) {
        if (statusEl) {
            statusEl.textContent = 'Ошибка: ' + err.message;
            statusEl.style.color = '#C83232';
        }
    }
}

/* ─── Add timing event ─── */
function addEvent(key) {
    const list = document.getElementById('events-list-' + key);
    if (!list) return;
    const idx = list.children.length;
    const div = document.createElement('div');
    div.className = 'event-item';
    div.innerHTML = `
        <input type="text" name="events[${idx}][time]" class="admin-input jost" placeholder="Время">
        <input type="text" name="events[${idx}][title]" class="admin-input jost" placeholder="Заголовок">
        <input type="text" name="events[${idx}][description]" class="admin-input jost" placeholder="Описание">
        <button type="button" class="btn-remove-event" onclick="removeItem(this.closest('.event-item'))">×</button>
    `;
    list.appendChild(div);
}

/* ─── Add venue ─── */
function addVenue(key) {
    const list = document.getElementById('venues-list-' + key);
    if (!list) return;
    const idx = list.children.length;
    const div = document.createElement('div');
    div.className = 'event-item';
    div.innerHTML = `
        <input type="text" name="venues[${idx}][time]" class="admin-input jost" placeholder="Время">
        <input type="text" name="venues[${idx}][name]" class="admin-input jost" placeholder="Название места">
        <input type="text" name="venues[${idx}][address]" class="admin-input jost" placeholder="Адрес">
        <button type="button" class="btn-remove-event" onclick="removeItem(this.closest('.event-item'))">×</button>
    `;
    list.appendChild(div);
}

/* ─── Add color swatch ─── */
function addColor(key) {
    const editor = document.getElementById('colors-' + key);
    if (!editor) return;
    const addBtn = editor.querySelector('.btn-add-color');
    const idx    = editor.querySelectorAll('.color-item').length;
    const div    = document.createElement('div');
    div.className = 'color-item';
    div.innerHTML = `
        <input type="color" name="colors[${idx}]" value="#C9A96E">
        <button type="button" class="btn-remove-color" onclick="removeItem(this.closest('.color-item'))">удалить</button>
    `;
    editor.insertBefore(div, addBtn);
}

/* Re-index input names after removal */
function reindexList(list, prefix) {
    Array.from(list.children).forEach((item, i) => {
        item.querySelectorAll('input, textarea').forEach(input => {
            if (input.name) {
                input.name = input.name.replace(/\[\d+\]/, `[${i}]`);
            }
        });
    });
}

function removeFormOptRow(btn) {
    const row = btn.closest('.form-opt-row');
    const list = row?.closest('.events-list');
    row?.remove();
    if (list && list.id && list.id.startsWith('form-opt-')) {
        reindexFormOpts(list);
    }
}

function reindexFormOpts(list) {
    const group = list.id.replace('form-opt-', '');
    Array.from(list.querySelectorAll('.form-opt-row')).forEach((row, i) => {
        const inputs = row.querySelectorAll('input.admin-input');
        if (inputs[0]) inputs[0].name = `form_options[${group}][${i}][value]`;
        if (inputs[1]) inputs[1].name = `form_options[${group}][${i}][label]`;
    });
}

function addFormOptionRow(listId, group) {
    const list = document.getElementById(listId);
    if (!list) return;
    const idx = list.querySelectorAll('.form-opt-row').length;
    const div = document.createElement('div');
    div.className = 'event-item form-opt-row';
    div.innerHTML = `
        <input type="text" name="form_options[${group}][${idx}][value]" class="admin-input jost" placeholder="value">
        <input type="text" name="form_options[${group}][${idx}][label]" class="admin-input jost" placeholder="Подпись">
        <button type="button" class="btn-remove-event" onclick="removeFormOptRow(this)">×</button>
    `;
    list.appendChild(div);
}
</script>

</body>
</html>
