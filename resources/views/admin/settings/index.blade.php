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

<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" id="settings-form">
    @csrf

    {{-- ── Tabs nav ────────────────────────────────────────────────── --}}
    <ul class="nav nav-tabs mb-0" id="settingsTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#tab-general">
                <i class="fas fa-globe mr-1"></i> General
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#tab-social">
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
    </ul>

    <div class="tab-content card card-outline card-primary" style="border-top:none;border-radius:0 0 4px 4px;">
        <div class="card-body">

            {{-- ── General ─────────────────────────────────────────── --}}
            <div class="tab-pane fade show active" id="tab-general">
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

            {{-- ── Social ───────────────────────────────────────────── --}}
            <div class="tab-pane fade" id="tab-social">
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">
                                <i class="fab fa-facebook text-primary mr-1"></i> Facebook URL
                            </label>
                            <input type="url" name="facebook_url"
                                   value="{{ old('facebook_url', setting('facebook_url')) }}"
                                   class="form-control" placeholder="https://www.facebook.com/yourpage">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">
                                <i class="fab fa-instagram text-danger mr-1"></i> Instagram URL
                            </label>
                            <input type="url" name="instagram_url"
                                   value="{{ old('instagram_url', setting('instagram_url')) }}"
                                   class="form-control" placeholder="https://www.instagram.com/yourpage">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">
                                <i class="fab fa-x-twitter mr-1"></i> X / Twitter URL
                            </label>
                            <input type="url" name="twitter_url"
                                   value="{{ old('twitter_url', setting('twitter_url')) }}"
                                   class="form-control" placeholder="https://x.com/yourhandle">
                        </div>
                    </div>

                </div>
            </div>

            {{-- ── App Links ─────────────────────────────────────────── --}}
            <div class="tab-pane fade" id="tab-applinks">
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
            <div class="tab-pane fade" id="tab-footer">
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
            <div class="tab-pane fade" id="tab-images">
                <div class="row">

                    @php
                        $imageFields = [
                            ['key' => 'logo',     'label' => 'Site Logo',     'hint' => 'PNG/SVG, shown in navbar & footer. Recommended: 200×60 px.'],
                            ['key' => 'favicon',  'label' => 'Favicon',       'hint' => 'ICO/PNG 32×32 px.'],
                            ['key' => 'og_image', 'label' => 'OG / Share Image', 'hint' => 'JPG/PNG 1200×630 px for social previews.'],
                        ];
                    @endphp

                    @foreach($imageFields as $field)
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold">{{ $field['label'] }}</label>

                            @php $current = setting($field['key']); @endphp
                            @if($current)
                            <div class="mb-2 p-2 border rounded" style="background:#f8f9fa;">
                                <img src="{{ asset('storage/' . $current) }}"
                                     alt="{{ $field['label'] }}"
                                     style="max-height:60px; max-width:100%; object-fit:contain;">
                                <p class="text-muted small mb-0 mt-1">Current image</p>
                            </div>
                            @endif

                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="{{ $field['key'] }}"
                                       id="img-{{ $field['key'] }}" accept="image/*"
                                       onchange="previewImage(this, 'preview-{{ $field['key'] }}')">
                                <label class="custom-file-label" for="img-{{ $field['key'] }}">
                                    Choose file…
                                </label>
                            </div>
                            <small class="text-muted d-block mt-1">{{ $field['hint'] }}</small>

                            <img id="preview-{{ $field['key'] }}" src="" alt=""
                                 style="display:none; max-height:60px; max-width:100%; margin-top:8px; border-radius:4px; object-fit:contain;">
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>

        </div>{{-- card-body --}}

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-1"></i> Save Settings
            </button>
        </div>
    </div>{{-- tab-content --}}

</form>

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

// Live image preview
function previewImage(input, previewId) {
    var preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@stop
