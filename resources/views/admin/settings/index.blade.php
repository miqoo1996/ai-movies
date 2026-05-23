@extends('adminlte::page')

@section('title', 'General Settings')

@section('content_header')
    <h1 class="m-0">General Settings</h1>
@stop

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

    {{-- ── Tabs nav ────────────────────────────────────────────────── --}}
    <ul class="nav nav-tabs mb-0" id="settingsTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#tab-general">
                <i class="fas fa-globe mr-1"></i> General
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" id="social-tab-link" onclick="toggleSocialPanel(event)">
                <i class="fas fa-share-alt mr-1"></i> Social
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#tab-applinks">
                <i class="fas fa-mobile-alt mr-1"></i> App Links
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#tab-footer">
                <i class="fas fa-align-left mr-1"></i> Footer
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#tab-images">
                <i class="fas fa-image mr-1"></i> Images
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#tab-seo">
                <i class="fas fa-search mr-1"></i> SEO
            </a>
        </li>
    </ul>

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" id="settings-form">
    @csrf
    <div class="tab-content card card-outline card-primary" style="border-top:none;border-radius:0 0 4px 4px;">

            {{-- ── General ─────────────────────────────────────────── --}}
            <div class="tab-pane fade show active card-body" id="tab-general">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Site Name</label>
                            <input type="text" name="site_name"
                                   value="{{ old('site_name', setting('site_name')) }}"
                                   class="form-control" placeholder="DiziBul">
                            <small class="text-muted">Shown in navbar and browser title.</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Site Tagline</label>
                            <input type="text" name="site_tagline"
                                   value="{{ old('site_tagline', setting('site_tagline')) }}"
                                   class="form-control" placeholder="Turkish Dramas? We've got the çay!">
                            <small class="text-muted">Short description shown in footer.</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Sign In URL</label>
                            <input type="url" name="signin_url"
                                   value="{{ old('signin_url', setting('signin_url')) }}"
                                   class="form-control" placeholder="https://your-platform.com/">
                            <small class="text-muted">Where the "Sign In" navbar button points.</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── App Links ─────────────────────────────────────────── --}}
            <div class="tab-pane fade card-body" id="tab-applinks">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">
                                <i class="fab fa-apple mr-1"></i> App Store URL
                            </label>
                            <input type="url" name="appstore_url"
                                   value="{{ old('appstore_url', setting('appstore_url')) }}"
                                   class="form-control" placeholder="https://apps.apple.com/…">
                            <small class="text-muted">Leave blank to hide the button.</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">
                                <i class="fab fa-google-play mr-1"></i> Google Play URL
                            </label>
                            <input type="url" name="playstore_url"
                                   value="{{ old('playstore_url', setting('playstore_url')) }}"
                                   class="form-control" placeholder="https://play.google.com/…">
                            <small class="text-muted">Leave blank to hide the button.</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Footer ───────────────────────────────────────────── --}}
            <div class="tab-pane fade card-body" id="tab-footer">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Copyright Text</label>
                            <input type="text" name="footer_copyright"
                                   value="{{ old('footer_copyright', setting('footer_copyright')) }}"
                                   class="form-control" placeholder="All Rights Reserved.">
                            <small class="text-muted">Shown after "© Year SiteName".</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Footer Tagline</label>
                            <input type="text" name="footer_tagline"
                                   value="{{ old('footer_tagline', setting('footer_tagline')) }}"
                                   class="form-control" placeholder="Made with ♥ for Turkish drama fans">
                            <small class="text-muted">Right-hand text in the bottom bar.</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Images ───────────────────────────────────────────── --}}
            <div class="tab-pane fade card-body" id="tab-images">
                <div class="row">

                    @php
                        $imageFields = [
                            ['key' => 'logo',     'label' => 'Site Logo',        'hint' => 'PNG/SVG, shown in navbar & footer. Recommended: 200×60 px.'],
                            ['key' => 'favicon',  'label' => 'Favicon',          'hint' => 'ICO/PNG 32×32 px.'],
                            ['key' => 'og_image', 'label' => 'OG / Share Image', 'hint' => 'JPG/PNG 1200×630 px for social previews.'],
                        ];
                    @endphp

                    @foreach($imageFields as $field)
                    @php $current = setting($field['key']); @endphp
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold">{{ $field['label'] }}</label>

                            {{-- Current / default image preview --}}
                            <div class="mb-2 p-2 border rounded d-flex align-items-center"
                                 style="background:#f8f9fa; min-height:80px;" id="current-{{ $field['key'] }}">
                                @if($current)
                                    <div>
                                        <img src="{{ asset('storage/' . $current) }}"
                                             alt="{{ $field['label'] }}"
                                             style="max-height:64px; max-width:100%; object-fit:contain; display:block;">
                                        <p class="text-success small mb-0 mt-1">
                                            <i class="fas fa-check-circle mr-1"></i>Uploaded image active
                                        </p>
                                    </div>
                                @elseif($field['key'] === 'favicon')
                                    <div>
                                        <img src="{{ asset('favicon.ico') }}" alt="Default favicon"
                                             style="width:32px;height:32px;object-fit:contain;display:block;">
                                        <p class="text-muted small mb-0 mt-1">
                                            <i class="fas fa-info-circle mr-1"></i>Default favicon.ico
                                        </p>
                                    </div>
                                @elseif($field['key'] === 'logo')
                                    <div>
                                        <p class="font-weight-bold mb-0" style="font-size:20px;letter-spacing:-0.5px;">
                                            {{ setting('site_name', 'DiziBul') }}
                                        </p>
                                        <p class="text-muted small mb-0 mt-1">
                                            <i class="fas fa-info-circle mr-1"></i>Text logo (no image uploaded)
                                        </p>
                                    </div>
                                @else
                                    <div>
                                        <i class="fas fa-image fa-2x text-muted"></i>
                                        <p class="text-muted small mb-0 mt-1">
                                            <i class="fas fa-info-circle mr-1"></i>Not set
                                        </p>
                                    </div>
                                @endif
                            </div>

                            {{-- New upload --}}
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="{{ $field['key'] }}"
                                       id="img-{{ $field['key'] }}" accept="image/*"
                                       onchange="previewImage(this, 'preview-{{ $field['key'] }}', 'current-{{ $field['key'] }}')">
                                <label class="custom-file-label" for="img-{{ $field['key'] }}">
                                    Choose file…
                                </label>
                            </div>
                            <small class="text-muted d-block mt-1">{{ $field['hint'] }}</small>

                            {{-- Live preview of newly selected file --}}
                            <div id="preview-{{ $field['key'] }}" style="display:none;"
                                 class="mt-2 p-2 border border-primary rounded" style="background:#f8f9fa;">
                                <img src="" alt="" class="preview-img"
                                     style="max-height:64px; max-width:100%; object-fit:contain; display:block;">
                                <p class="text-primary small mb-0 mt-1">
                                    <i class="fas fa-arrow-up mr-1"></i>New image (not saved yet)
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>

            {{-- ── SEO ──────────────────────────────────────────────── --}}
            <div class="tab-pane fade card-body" id="tab-seo">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">SEO Title Format</label>
                            <input type="text" name="seo_title_format"
                                   value="{{ old('seo_title_format', setting('seo_title_format')) }}"
                                   class="form-control" placeholder="{title} — {{ setting('site_name', 'DiziBul') }}">
                            <small class="text-muted">Use <code>{title}</code> as a placeholder for the page/show title.</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Default Meta Description</label>
                            <textarea name="seo_default_description" rows="2"
                                      class="form-control" maxlength="320"
                                      placeholder="Discover the best Turkish TV series and dramas.">{{ old('seo_default_description', setting('seo_default_description')) }}</textarea>
                            <small class="text-muted">Used on pages without a custom description. Max 320 chars.</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold">Google Analytics 4 ID</label>
                            <input type="text" name="google_analytics_id"
                                   value="{{ old('google_analytics_id', setting('google_analytics_id')) }}"
                                   class="form-control" placeholder="G-XXXXXXXXXX">
                            <small class="text-muted">Measurement ID. Leave blank to disable.</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold">Google Tag Manager ID</label>
                            <input type="text" name="google_tag_manager_id"
                                   value="{{ old('google_tag_manager_id', setting('google_tag_manager_id')) }}"
                                   class="form-control" placeholder="GTM-XXXXXXX">
                            <small class="text-muted">Container ID. Leave blank to disable.</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold">Search Console Verification</label>
                            <input type="text" name="search_console_verify"
                                   value="{{ old('search_console_verify', setting('search_console_verify')) }}"
                                   class="form-control" placeholder="Paste meta content value…">
                            <small class="text-muted">Value from Google's <code>&lt;meta name="google-site-verification"&gt;</code>.</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold d-block">Global Robots Indexing</label>
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-outline-success {{ setting('robots_noindex', 'index') === 'index' ? 'active' : '' }}">
                                    <input type="radio" name="robots_noindex" value="index"
                                           {{ setting('robots_noindex', 'index') === 'index' ? 'checked' : '' }}>
                                    <i class="fas fa-check-circle mr-1"></i> Index (default)
                                </label>
                                <label class="btn btn-outline-danger {{ setting('robots_noindex') === 'noindex' ? 'active' : '' }}">
                                    <input type="radio" name="robots_noindex" value="noindex"
                                           {{ setting('robots_noindex') === 'noindex' ? 'checked' : '' }}>
                                    <i class="fas fa-ban mr-1"></i> No-index all
                                </label>
                            </div>
                            <small class="d-block text-muted mt-1">No-index blocks search engines from indexing <em>all</em> pages.</small>
                        </div>
                    </div>
                </div>
            </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-1"></i> Save Settings
            </button>
        </div>
        </form>{{-- end settings form --}}
    </div>{{-- tab-content --}}

    {{-- ── Social Links panel (outside the settings form to allow own forms) --}}
    <div id="social-panel" class="card card-outline card-primary mt-0" style="display:none;border-top:none;border-radius:0 0 4px 4px;">
        <div class="card-body">

            <table class="table table-sm table-bordered mb-3">
                <thead class="thead-light">
                    <tr>
                        <th style="width:36px"></th>
                        <th>Platform</th>
                        <th>URL</th>
                        <th style="width:90px">Status</th>
                        <th style="width:110px"></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($socialLinks as $link)
                    <tr class="{{ $link->is_active ? '' : 'table-secondary text-muted' }}">
                        <td class="align-middle text-center">
                            <i class="{{ $link->icon }}" style="font-size:18px;"></i>
                        </td>
                        <td class="align-middle font-weight-bold">{{ $link->label }}</td>
                        <td class="align-middle">
                            <form method="POST" action="{{ route('admin.social-links.update', $link) }}"
                                  class="d-flex" style="gap:.4rem;">
                                @csrf @method('PUT')
                                <input type="url" name="url" value="{{ $link->url }}"
                                       class="form-control form-control-sm" required>
                                <button class="btn btn-sm btn-outline-primary" title="Save URL">
                                    <i class="fas fa-save"></i>
                                </button>
                            </form>
                        </td>
                        <td class="align-middle text-center">
                            <form method="POST" action="{{ route('admin.social-links.toggle', $link) }}">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm {{ $link->is_active ? 'btn-success' : 'btn-secondary' }}"
                                        title="{{ $link->is_active ? 'Active — click to deactivate' : 'Inactive — click to activate' }}">
                                    <i class="fas {{ $link->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                    {{ $link->is_active ? 'Active' : 'Off' }}
                                </button>
                            </form>
                        </td>
                        <td class="align-middle text-right">
                            <form method="POST" action="{{ route('admin.social-links.destroy', $link) }}"
                                  onsubmit="return confirm('Remove {{ $link->label }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Remove">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-3">No social links yet.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            {{-- Add new link --}}
            <div class="card card-outline card-secondary mb-0">
                <div class="card-header py-2">
                    <h3 class="card-title"><i class="fas fa-plus mr-1"></i> Add Social Link</h3>
                </div>
                <div class="card-body py-3">
                    <form method="POST" action="{{ route('admin.social-links.store') }}"
                          class="d-flex align-items-end" style="gap:.75rem;flex-wrap:wrap;">
                        @csrf
                        <div class="form-group mb-0">
                            <label class="text-xs font-weight-bold text-muted text-uppercase mb-1">Platform</label>
                            <select name="platform" class="form-control form-control-sm" required>
                                <option value="">Select…</option>
                                @foreach($socialPresets as $key => $preset)
                                    <option value="{{ $key }}">{{ $preset['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-0 flex-grow-1">
                            <label class="text-xs font-weight-bold text-muted text-uppercase mb-1">URL</label>
                            <input type="url" name="url" class="form-control form-control-sm" placeholder="https://…" required>
                        </div>
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus mr-1"></i> Add
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>{{-- end social panel --}}

@stop

@section('js')
<script>
// Custom file input label
document.querySelectorAll('.custom-file-input').forEach(function(input) {
    input.addEventListener('change', function() {
        var label = this.nextElementSibling;
        label.textContent = this.files.length ? this.files[0].name : 'Choose file…';
    });
});

// Social tab toggle — panel lives outside the main form to avoid nesting
function hideSocialPanel() {
    document.getElementById('social-panel').style.display = 'none';
    document.querySelector('.tab-content').style.display  = '';
    document.getElementById('social-tab-link').classList.remove('active');
}

function toggleSocialPanel(e) {
    e.preventDefault();
    var isSocial = document.getElementById('social-panel').style.display !== 'none';

    if (isSocial) {
        hideSocialPanel();
        document.querySelector('[href="#tab-general"]').click();
    } else {
        document.getElementById('social-panel').style.display = 'block';
        document.querySelector('.tab-content').style.display  = 'none';
        document.querySelectorAll('#settingsTabs .nav-link').forEach(function(l) { l.classList.remove('active'); });
        document.getElementById('social-tab-link').classList.add('active');
    }
}

// When any regular tab is clicked while the social panel is open, restore tab-content first
document.querySelectorAll('#settingsTabs [data-toggle="tab"]').forEach(function(tab) {
    tab.addEventListener('click', function() {
        if (document.getElementById('social-panel').style.display !== 'none') {
            hideSocialPanel();
        }
    });
});

// Live image preview — shows new file, dims the current one
function previewImage(input, previewId, currentId) {
    var previewBox = document.getElementById(previewId);
    var currentBox = document.getElementById(currentId);
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            previewBox.querySelector('.preview-img').src = e.target.result;
            previewBox.style.display = 'block';
            if (currentBox) currentBox.style.opacity = '0.4';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@stop
