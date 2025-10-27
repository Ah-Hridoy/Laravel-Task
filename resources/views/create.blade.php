<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Create Course</title>
  <link rel="stylesheet" href="/css/course-create.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
  <div class="container">
    <h1>Create Course</h1>

    <!-- @if(session('success'))
      <div class="alert success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
      <div class="alert error">
        <ul>
          @foreach($errors->all() as $er)
            <li>{{ $er }}</li>
          @endforeach
        </ul>
      </div>
    @endif -->

    <form id="course-form" method="POST" action="{{ route('courses.store') }}" enctype="multipart/form-data">
      @csrf

      <label>Title <span style="color:red">*</span></label>
      <input type="text" name="title" value="{{ old('title') }}"
      class="@error('title') is-invalid @enderror"
      >
       @error('title')
            <div style="color:red">{{ $message }}</div>
       @enderror

      <label>Category</label>
      <input type="text" name="category" value="{{ old('category') }}">

      <label>Description</label>
      <textarea name="description">{{ old('description') }}</textarea>

      <label>Feature Video (mp4,webm) — optional</label>
      <input type="file" name="feature_video" accept="video/*">

      <hr>

      <div id="modules-wrapper">
        <!-- modules will be inserted here -->
      </div>

      <button type="button" id="add-module">+ Add Module</button>

      <hr>
      <button type="submit">Save Course</button>
    </form>
  </div>

  <!-- Module Template (hidden) -->
  <div id="module-template" style="display:none;">
    <div class="module">
      <h3>Module <span class="module-index"></span></h3>
      <label>Module Title <span style="color:red">*</span></label>
      <input type="text" class="module-title" name="__MODULE_NAME__[title]" 
      class="@error('modules.*.title') is-invalid @enderror"
      >
       @error('modules.*.title')
            <div style="color:red">{{ $message }}</div>
       @enderror

      <label>Module Description</label>
      <textarea class="module-desc" name="__MODULE_NAME__[description]"></textarea>

      <div class="contents-wrapper"></div>
      <button type="button" class="add-content">+ Add Content</button>
      <button type="button" class="remove-module">Remove Module</button>
      <hr>
    </div>
  </div>

  <!-- Content Template (hidden) -->
  <div id="content-template" style="display:none;">
    <div class="content">
      <h4>Content <span class="content-index"></span></h4>
      <label>Title <span style="color:red">*</span></label>
      <input type="text" class="content-title" name="__CONTENT_NAME__[title]" required>

      <label>Type</label>
      <select class="content-type" name="__CONTENT_NAME__[type]">
        <option value="text">Text</option>
        <option value="image">Image</option>
        <option value="video">Video</option>
        <option value="link">Link</option>
        <option value="file">File</option>
      </select>

      <div class="field-body">
        <label>Body (for text or link)</label>
        <textarea name="__CONTENT_NAME__[body]"></textarea>
      </div>

      <div class="field-file" style="display:none;">
        <label>Upload File</label>
        <input type="file" name="__CONTENT_NAME__[file]">
      </div>

      <button type="button" class="remove-content">Remove Content</button>
      <hr>
    </div>
  </div>

  <script src="/js/course-create.js"></script>
</body>
</html>
