<div class="form-group">
    <label class="font-weight-bold">Question <span class="text-danger">*</span></label>
    <input type="text" name="question" value="{{ old('question', $faq->question ?? '') }}"
           class="form-control @error('question') is-invalid @enderror"
           placeholder="Enter the question…" required>
    @error('question')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label class="font-weight-bold">Answer <span class="text-danger">*</span></label>
    {{-- Hidden input carries the value; CKEditor mounts onto the div below --}}
    <input type="hidden" name="answer" id="faq-answer"
           value="{{ old('answer', $faq->answer ?? '') }}">
    <div id="faq-answer-editor" class="@error('answer') border border-danger rounded @enderror"></div>
    @error('answer')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>
