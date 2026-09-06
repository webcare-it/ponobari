@extends('admin.master')

@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="row">
                <div class="col">
                    <div class="card radius-10 mb-0">
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if(Session::has('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Success!</strong> {{ Session::get('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <form action="{{ route('admin.theme.colors.update') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="card border-top border-0 border-4 border-primary">
                                            <div class="card-body p-5">
                                                <div class="card-title d-flex align-items-center">
                                                    <div><i class="bx bxs-paint me-1 font-22 text-primary"></i></div>
                                                    <h5 class="mb-0 text-primary">Website Theme Colors</h5>
                                                </div>
                                                <hr>

                                                <!-- Primary Color -->
                                                <div class="form-group mb-4">
                                                    <label for="primary_color" class="form-label fw-bold">Primary Color</label>
                                                    <div class="input-group">
                                                        <input type="color" name="primary_color" id="primary_color" 
                                                               value="{{ $general_setting->primary_color ?? '#053C6B' }}" 
                                                               class="form-control form-control-color" style="height: 50px;">
                                                        <input type="text" class="form-control" placeholder="#053C6B" 
                                                               value="{{ $general_setting->primary_color ?? '#053C6B' }}" 
                                                               id="primary_color_text" readonly>
                                                    </div>
                                                    <small class="text-muted">Used for primary buttons, headers, and brand elements</small>
                                                </div>

                                                <!-- Secondary Color -->
                                                <div class="form-group mb-4">
                                                    <label for="secondary_color" class="form-label fw-bold">Secondary Color</label>
                                                    <div class="input-group">
                                                        <input type="color" name="secondary_color" id="secondary_color" 
                                                               value="{{ $general_setting->secondary_color ?? '#333333' }}" 
                                                               class="form-control form-control-color" style="height: 50px;">
                                                        <input type="text" class="form-control" placeholder="#333333" 
                                                               value="{{ $general_setting->secondary_color ?? '#333333' }}" 
                                                               id="secondary_color_text" readonly>
                                                    </div>
                                                    <small class="text-muted">Used for secondary buttons and text</small>
                                                </div>

                                                <!-- Accent Color -->
                                                <div class="form-group mb-4">
                                                    <label for="accent_color" class="form-label fw-bold">Accent Color</label>
                                                    <div class="input-group">
                                                        <input type="color" name="accent_color" id="accent_color" 
                                                               value="{{ $general_setting->accent_color ?? '#f41127' }}" 
                                                               class="form-control form-control-color" style="height: 50px;">
                                                        <input type="text" class="form-control" placeholder="#f41127" 
                                                               value="{{ $general_setting->accent_color ?? '#f41127' }}" 
                                                               id="accent_color_text" readonly>
                                                    </div>
                                                    <small class="text-muted">Used for highlights, links, and special elements</small>
                                                </div>

                                                <!-- Category Background Color -->
                                                <div class="form-group mb-4">
                                                    <label for="category_bg_color" class="form-label fw-bold">Category Background Color</label>
                                                    <div class="input-group">
                                                        <input type="color" name="category_bg_color" id="category_bg_color" 
                                                               value="{{ $general_setting->category_bg_color ?? '#053C6B' }}" 
                                                               class="form-control form-control-color" style="height: 50px;">
                                                        <input type="text" class="form-control" placeholder="#053C6B" 
                                                               value="{{ $general_setting->category_bg_color ?? '#053C6B' }}" 
                                                               id="category_bg_color_text" readonly>
                                                    </div>
                                                    <small class="text-muted">Background color for category sections</small>
                                                </div>

                                                <!-- Header Background Color -->
                                                <div class="form-group mb-4">
                                                    <label for="header_bg_color" class="form-label fw-bold">Header Background Color</label>
                                                    <div class="input-group">
                                                        <input type="color" name="header_bg_color" id="header_bg_color" 
                                                               value="{{ $general_setting->header_bg_color ?? '#ffffff' }}" 
                                                               class="form-control form-control-color" style="height: 50px;">
                                                        <input type="text" class="form-control" placeholder="#ffffff" 
                                                               value="{{ $general_setting->header_bg_color ?? '#ffffff' }}" 
                                                               id="header_bg_color_text" readonly>
                                                    </div>
                                                    <small class="text-muted">Background color for the website header</small>
                                                </div>

                                                <!-- Footer Background Color -->
                                                <div class="form-group mb-4">
                                                    <label for="footer_bg_color" class="form-label fw-bold">Footer Background Color</label>
                                                    <div class="input-group">
                                                        <input type="color" name="footer_bg_color" id="footer_bg_color" 
                                                               value="{{ $general_setting->footer_bg_color ?? '#333333' }}" 
                                                               class="form-control form-control-color" style="height: 50px;">
                                                        <input type="text" class="form-control" placeholder="#333333" 
                                                               value="{{ $general_setting->footer_bg_color ?? '#333333' }}" 
                                                               id="footer_bg_color_text" readonly>
                                                    </div>
                                                    <small class="text-muted">Background color for the website footer</small>
                                                </div>

                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-primary btn-lg">
                                                        <i class="bx bx-save me-2"></i> Save Theme Colors
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Color Preview Panel -->
                                    <div class="col-md-4">
                                        <div class="card border-top border-0 border-4 border-info">
                                            <div class="card-body p-4">
                                                <h6 class="card-title mb-3">
                                                    <i class="bx bx-palette me-2 text-info"></i> Color Preview
                                                </h6>
                                                <hr>

                                                <div class="mb-3">
                                                    <p class="small mb-2">Primary Color</p>
                                                    <div class="color-preview-box" id="preview_primary" 
                                                         style="background-color: {{ $general_setting->primary_color ?? '#053C6B' }}; 
                                                                 height: 60px; border-radius: 5px; border: 2px solid #ddd;">
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <p class="small mb-2">Secondary Color</p>
                                                    <div class="color-preview-box" id="preview_secondary" 
                                                         style="background-color: {{ $general_setting->secondary_color ?? '#333333' }}; 
                                                                 height: 60px; border-radius: 5px; border: 2px solid #ddd;">
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <p class="small mb-2">Accent Color</p>
                                                    <div class="color-preview-box" id="preview_accent" 
                                                         style="background-color: {{ $general_setting->accent_color ?? '#f41127' }}; 
                                                                 height: 60px; border-radius: 5px; border: 2px solid #ddd;">
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <p class="small mb-2">Category Background</p>
                                                    <div class="color-preview-box" id="preview_category_bg" 
                                                         style="background-color: {{ $general_setting->category_bg_color ?? '#053C6B' }}; 
                                                                 height: 60px; border-radius: 5px; border: 2px solid #ddd;">
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <p class="small mb-2">Header Background</p>
                                                    <div class="color-preview-box" id="preview_header_bg" 
                                                         style="background-color: {{ $general_setting->header_bg_color ?? '#ffffff' }}; 
                                                                 height: 60px; border-radius: 5px; border: 2px solid #ddd;">
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <p class="small mb-2">Footer Background</p>
                                                    <div class="color-preview-box" id="preview_footer_bg" 
                                                         style="background-color: {{ $general_setting->footer_bg_color ?? '#333333' }}; 
                                                                 height: 60px; border-radius: 5px; border: 2px solid #ddd;">
                                                    </div>
                                                </div>

                                                <hr>
                                                <div class="alert alert-info small mb-0">
                                                    <i class="bx bx-info-circle me-2"></i>
                                                    Changes will be applied to your website immediately after saving.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Update text input and preview when color picker changes
        document.getElementById('primary_color').addEventListener('input', function(e) {
            document.getElementById('primary_color_text').value = e.target.value;
            document.getElementById('preview_primary').style.backgroundColor = e.target.value;
        });

        document.getElementById('secondary_color').addEventListener('input', function(e) {
            document.getElementById('secondary_color_text').value = e.target.value;
            document.getElementById('preview_secondary').style.backgroundColor = e.target.value;
        });

        document.getElementById('accent_color').addEventListener('input', function(e) {
            document.getElementById('accent_color_text').value = e.target.value;
            document.getElementById('preview_accent').style.backgroundColor = e.target.value;
        });

        document.getElementById('category_bg_color').addEventListener('input', function(e) {
            document.getElementById('category_bg_color_text').value = e.target.value;
            document.getElementById('preview_category_bg').style.backgroundColor = e.target.value;
        });

        document.getElementById('header_bg_color').addEventListener('input', function(e) {
            document.getElementById('header_bg_color_text').value = e.target.value;
            document.getElementById('preview_header_bg').style.backgroundColor = e.target.value;
        });

        document.getElementById('footer_bg_color').addEventListener('input', function(e) {
            document.getElementById('footer_bg_color_text').value = e.target.value;
            document.getElementById('preview_footer_bg').style.backgroundColor = e.target.value;
        });
    </script>
@endsection
