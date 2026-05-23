@php $isEdit = isset($show); @endphp

<div class="row">

    {{-- LEFT COLUMN --}}
    <div class="col-lg-8">

        {{-- Basic Info --}}
        <div class="card card-outline card-primary">
            <div class="card-header"><h3 class="card-title">Basic Information</h3></div>
            <div class="card-body">

                <div class="form-group">
                    <label>Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $isEdit ? $show->getRawOriginal('title') : '') }}"
                           class="form-control @error('title') is-invalid @enderror" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Original Title</label>
                        <input type="text" name="original_title" value="{{ old('original_title', $isEdit ? $show->original_title : '') }}" class="form-control">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Turkish Title</label>
                        <input type="text" name="turkish_title" value="{{ old('turkish_title', $isEdit ? $show->turkish_title : '') }}" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>AI English Title</label>
                        <input type="text" name="ai_title" value="{{ old('ai_title', $isEdit ? $show->getRawOriginal('ai_title') : '') }}" class="form-control">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>AI Turkish Title</label>
                        <input type="text" name="ai_turkish_title" value="{{ old('ai_turkish_title', $isEdit ? $show->getRawOriginal('ai_turkish_title') : '') }}" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label>Slug</label>
                    <input type="text" name="slug" value="{{ old('slug', $isEdit ? $show->slug : '') }}"
                           class="form-control @error('slug') is-invalid @enderror"
                           placeholder="Auto-generated if left empty">
                    @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

            </div>
        </div>

        {{-- Synopsis --}}
        <div class="card card-outline card-primary">
            <div class="card-header"><h3 class="card-title">Synopsis</h3></div>
            <div class="card-body">
                <div class="form-group">
                    <label>Original Synopsis</label>
                    <textarea name="synopsis" id="synopsis" class="form-control ck-editor">{{ old('synopsis', $isEdit ? $show->getRawOriginal('synopsis') : '') }}</textarea>
                </div>
                <div class="form-group mb-0">
                    <label>AI Synopsis</label>
                    <textarea name="ai_synopsis" id="ai_synopsis" class="form-control ck-editor">{{ old('ai_synopsis', $isEdit ? $show->getRawOriginal('ai_synopsis') : '') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Poster --}}
        <div class="card card-outline card-primary">
            <div class="card-header"><h3 class="card-title">Poster</h3></div>
            <div class="card-body">
                <div class="row align-items-start">
                    @if($isEdit)
                    @php
                        $posterSrc = ($show->poster_local && file_exists(storage_path('app/public/'.$show->poster_local)))
                            ? asset('storage/'.$show->poster_local)
                            : ($show->poster ?: null);
                    @endphp
                    @if($posterSrc)
                    <div class="col-auto">
                        <img id="poster-preview" src="{{ $posterSrc }}" style="width:80px;height:110px;object-fit:cover;border-radius:4px;">
                    </div>
                    @endif
                    @endif
                    <div class="col">
                        <div class="form-group">
                            <label>Upload Poster</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="poster_file" id="poster_file"
                                           accept="image/*" onchange="previewPoster(this)">
                                    <label class="custom-file-label" for="poster_file">Choose image…</label>
                                </div>
                            </div>
                            <small class="text-muted">JPG/PNG/WebP, max 4 MB. Replaces existing poster.</small>
                        </div>
                        <div class="form-group mb-0">
                            <label>— or — Poster URL</label>
                            <input type="text" name="poster" value="{{ old('poster', $isEdit ? $show->poster : '') }}" class="form-control" placeholder="https://…">
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- RIGHT COLUMN --}}
    <div class="col-lg-4">

        {{-- Details --}}
        <div class="card card-outline card-secondary">
            <div class="card-header"><h3 class="card-title">Details</h3></div>
            <div class="card-body">

                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="">— Select —</option>
                        @foreach($statuses as $s)
                            <option value="{{ $s }}" @selected(old('status', $isEdit ? $show->status : '') === $s)>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Network</label>
                    <input type="text" name="network" list="network-list"
                           value="{{ old('network', $isEdit ? $show->network : '') }}" class="form-control">
                    <datalist id="network-list">
                        @foreach($networks as $n)
                            <option value="{{ $n }}">
                        @endforeach
                    </datalist>
                </div>

                <div class="row">
                    <div class="col-6 form-group">
                        <label>Year</label>
                        <input type="number" name="year" value="{{ old('year', $isEdit ? $show->year : '') }}"
                               class="form-control" min="1900" max="2100">
                    </div>
                    <div class="col-6 form-group">
                        <label>Runtime (min)</label>
                        <input type="number" name="runtime" value="{{ old('runtime', $isEdit ? $show->runtime : '') }}"
                               class="form-control" min="1">
                    </div>
                </div>

                <div class="form-group">
                    <label>Premiered</label>
                    <input type="date" name="premiered"
                           value="{{ old('premiered', $isEdit && $show->getRawOriginal('premiered') ? \Carbon\Carbon::parse($show->getRawOriginal('premiered'))->format('Y-m-d') : '') }}"
                           class="form-control">
                </div>

                <div class="row">
                    <div class="col-6 form-group">
                        <label>Rating</label>
                        <input type="number" name="rating" step="0.1" min="0" max="10"
                               value="{{ old('rating', $isEdit ? $show->rating : '') }}" class="form-control">
                    </div>
                    <div class="col-6 form-group">
                        <label>Subscribers</label>
                        <input type="number" name="subscribers" min="0"
                               value="{{ old('subscribers', $isEdit ? $show->subscribers : '') }}" class="form-control">
                    </div>
                </div>

            </div>
        </div>

        {{-- Genres --}}
        <div class="card card-outline card-secondary">
            <div class="card-header"><h3 class="card-title">Genres</h3></div>
            <div class="card-body" style="max-height:260px;overflow-y:auto;">
                @php $selectedGenres = $isEdit ? $show->genres->pluck('id')->toArray() : []; @endphp
                @foreach($genres as $genre)
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="genre_{{ $genre->id }}"
                           name="genre_ids[]" value="{{ $genre->id }}"
                           @checked(in_array($genre->id, old('genre_ids', $selectedGenres)))>
                    <label class="custom-control-label" for="genre_{{ $genre->id }}">{{ $genre->name }}</label>
                </div>
                @endforeach
            </div>
        </div>

        {{-- SEO --}}
        <div class="card card-outline card-secondary">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-search mr-1"></i> SEO</h3></div>
            <div class="card-body">
                <div class="form-group">
                    <label>SEO Title</label>
                    <input type="text" name="seo_title" maxlength="255"
                           value="{{ old('seo_title', $isEdit ? $show->seo_title : '') }}"
                           class="form-control" placeholder="Leave blank to use default format">
                    <small class="text-muted">Max 255 chars.</small>
                </div>
                <div class="form-group">
                    <label>Meta Description</label>
                    <textarea name="seo_description" rows="3" maxlength="320"
                              class="form-control" placeholder="Leave blank to use default">{{ old('seo_description', $isEdit ? $show->seo_description : '') }}</textarea>
                    <small class="text-muted">Max 320 chars.</small>
                </div>
                <div class="custom-control custom-switch">
                    <input type="hidden" name="noindex" value="0">
                    <input type="checkbox" class="custom-control-input" id="noindex"
                           name="noindex" value="1"
                           @checked(old('noindex', $isEdit ? $show->noindex : false))>
                    <label class="custom-control-label" for="noindex">
                        No-index this page <small class="text-muted">(hide from search engines)</small>
                    </label>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="card">
            <div class="card-body">
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-save mr-1"></i> {{ $isEdit ? 'Save Changes' : 'Create Show' }}
                </button>
                <a href="{{ route('admin.shows.index') }}" class="btn btn-secondary btn-block">Cancel</a>
            </div>
        </div>

    </div>
</div>
