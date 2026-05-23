@php $isEdit = isset($episode); @endphp

<div class="row">
    <div class="col-lg-8">

        <div class="card card-outline card-primary">
            <div class="card-header"><h3 class="card-title">Episode Details</h3></div>
            <div class="card-body">

                <div class="row">
                    <div class="col-md-4 form-group">
                        <label>Season <span class="text-danger">*</span></label>
                        <input type="number" name="season_number" min="1"
                               value="{{ old('season_number', $isEdit ? $episode->season_number : $nextSeason ?? '') }}"
                               class="form-control @error('season_number') is-invalid @enderror" required>
                        @error('season_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Episode <span class="text-danger">*</span></label>
                        <input type="number" name="episode_number" min="1"
                               value="{{ old('episode_number', $isEdit ? $episode->episode_number : $nextEpisode ?? '') }}"
                               class="form-control @error('episode_number') is-invalid @enderror" required>
                        @error('episode_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Air Date</label>
                        <input type="date" name="airs_on"
                               value="{{ old('airs_on', $isEdit && $episode->airs_on ? $episode->airs_on->format('Y-m-d') : '') }}"
                               class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label>Episode Title</label>
                    <input type="text" name="title"
                           value="{{ old('title', $isEdit ? $episode->title : '') }}"
                           class="form-control" placeholder="Optional">
                </div>

                <div class="form-group mb-0">
                    <label>Overview</label>
                    <textarea name="overview" id="overview" class="form-control ck-editor"
                              placeholder="Short description of this episode (optional)">{{ old('overview', $isEdit ? $episode->overview : '') }}</textarea>
                </div>

            </div>
        </div>

        <div class="card card-outline card-secondary">
            <div class="card-header"><h3 class="card-title">Thumbnail</h3></div>
            <div class="card-body">

                @if($isEdit && $episode->thumb_url)
                <div class="mb-3">
                    <img id="thumb-preview" src="{{ $episode->thumb_url }}"
                         style="width:160px;height:90px;object-fit:cover;border-radius:4px;">
                </div>
                @else
                <img id="thumb-preview" src="" style="display:none;width:160px;height:90px;object-fit:cover;border-radius:4px;margin-bottom:.75rem;">
                @endif

                <div class="form-group">
                    <label>Upload Thumbnail</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="thumb_file" id="thumb_file"
                                   accept="image/*" onchange="previewThumb(this)">
                            <label class="custom-file-label" for="thumb_file">Choose image…</label>
                        </div>
                    </div>
                    <small class="text-muted">JPG/PNG/WebP, max 4 MB. Replaces existing thumbnail.</small>
                </div>

                <div class="form-group mb-0">
                    <label>— or — Thumbnail URL</label>
                    <input type="text" name="thumb"
                           value="{{ old('thumb', $isEdit ? $episode->thumb : '') }}"
                           class="form-control" placeholder="https://…">
                </div>

            </div>
        </div>

    </div>

    <div class="col-lg-4">

        <div class="card card-outline card-secondary">
            <div class="card-header"><h3 class="card-title">Flags</h3></div>
            <div class="card-body">
                <div class="custom-control custom-switch mb-3">
                    <input type="hidden" name="has_aired" value="0">
                    <input type="checkbox" class="custom-control-input" id="has_aired" name="has_aired" value="1"
                           @checked(old('has_aired', $isEdit ? $episode->has_aired : false))>
                    <label class="custom-control-label" for="has_aired">Has Aired</label>
                </div>
                <div class="custom-control custom-switch">
                    <input type="hidden" name="season_finale" value="0">
                    <input type="checkbox" class="custom-control-input" id="season_finale" name="season_finale" value="1"
                           @checked(old('season_finale', $isEdit ? $episode->season_finale : false))>
                    <label class="custom-control-label" for="season_finale">Season Finale</label>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-save mr-1"></i> {{ $isEdit ? 'Save Changes' : 'Add Episode' }}
                </button>
                <a href="{{ route('admin.shows.episodes.index', $show) }}" class="btn btn-secondary btn-block">Cancel</a>
            </div>
        </div>

    </div>
</div>
