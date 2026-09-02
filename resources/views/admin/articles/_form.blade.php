@php $isEdit = isset($article); @endphp

<div class="row">

    {{-- LEFT COLUMN --}}
    <div class="col-lg-8">

        <div class="card card-outline card-primary">
            <div class="card-header"><h3 class="card-title">Content</h3></div>
            <div class="card-body">

                <div class="form-group">
                    <label>Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $isEdit ? $article->title : '') }}"
                           class="form-control @error('title') is-invalid @enderror" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label>Slug</label>
                    <input type="text" name="slug" value="{{ old('slug', $isEdit ? $article->slug : '') }}"
                           class="form-control @error('slug') is-invalid @enderror"
                           placeholder="Auto-generated from the title if left empty">
                    @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label>Excerpt <small class="text-muted">(shown on the articles list)</small></label>
                    <textarea name="excerpt" rows="2" maxlength="500"
                              class="form-control @error('excerpt') is-invalid @enderror">{{ old('excerpt', $isEdit ? $article->excerpt : '') }}</textarea>
                    @error('excerpt')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group mb-0">
                    <label>Body <span class="text-danger">*</span></label>
                    <textarea name="content" id="article-content"
                              class="form-control ck-editor @error('content') is-invalid @enderror">{{ old('content', $isEdit ? $article->content : '') }}</textarea>
                    @error('content')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

            </div>
        </div>

        {{-- Cover image --}}
        <div class="card card-outline card-primary">
            <div class="card-header"><h3 class="card-title">Cover Image</h3></div>
            <div class="card-body">
                <div class="row align-items-start">
                    @if($isEdit && $article->cover_url)
                    <div class="col-auto">
                        <img src="{{ $article->cover_url }}" style="width:160px;height:90px;object-fit:cover;border-radius:4px;">
                    </div>
                    @endif
                    <div class="col">
                        <div class="form-group">
                            <label>Upload Image</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('cover_file') is-invalid @enderror"
                                           name="cover_file" id="cover_file" accept="image/*">
                                    <label class="custom-file-label" for="cover_file">Choose image…</label>
                                </div>
                            </div>
                            <small class="text-muted">JPG/PNG/WebP, max 4 MB. Replaces the existing cover.</small>
                            @error('cover_file')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group mb-0">
                            <label>— or — Image URL</label>
                            <input type="text" name="cover_image" value="{{ old('cover_image', $isEdit ? $article->cover_image : '') }}"
                                   class="form-control" placeholder="https://…">
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- RIGHT COLUMN --}}
    <div class="col-lg-4">

        {{-- Publishing --}}
        <div class="card card-outline card-secondary">
            <div class="card-header"><h3 class="card-title">Publishing</h3></div>
            <div class="card-body">

                <div class="custom-control custom-switch mb-3">
                    <input type="hidden" name="is_published" value="0">
                    <input type="checkbox" class="custom-control-input" id="is_published"
                           name="is_published" value="1"
                           @checked(old('is_published', $isEdit ? $article->is_published : false))>
                    <label class="custom-control-label" for="is_published">Published</label>
                </div>

                <div class="form-group mb-0">
                    <label>Publish Date</label>
                    <input type="date" name="published_at"
                           value="{{ old('published_at', $isEdit ? $article->published_at?->format('Y-m-d') : '') }}"
                           class="form-control">
                    <small class="text-muted">Leave blank to use today when publishing.</small>
                </div>

            </div>
        </div>

        {{-- SEO --}}
        <div class="card card-outline card-secondary">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-search mr-1"></i> SEO</h3></div>
            <div class="card-body">
                <div class="form-group">
                    <label>SEO Title</label>
                    <input type="text" name="seo_title" maxlength="255"
                           value="{{ old('seo_title', $isEdit ? $article->seo_title : '') }}"
                           class="form-control" placeholder="Leave blank to use the article title">
                    <small class="text-muted">Max 255 chars.</small>
                </div>
                <div class="form-group">
                    <label>Meta Description</label>
                    <textarea name="seo_description" rows="3" maxlength="320"
                              class="form-control" placeholder="Leave blank to use the excerpt">{{ old('seo_description', $isEdit ? $article->seo_description : '') }}</textarea>
                    <small class="text-muted">Max 320 chars.</small>
                </div>
                <div class="custom-control custom-switch">
                    <input type="hidden" name="noindex" value="0">
                    <input type="checkbox" class="custom-control-input" id="noindex"
                           name="noindex" value="1"
                           @checked(old('noindex', $isEdit ? $article->noindex : false))>
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
                    <i class="fas fa-save mr-1"></i> {{ $isEdit ? 'Save Changes' : 'Create Article' }}
                </button>
                <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary btn-block">Cancel</a>
            </div>
        </div>

    </div>
</div>
