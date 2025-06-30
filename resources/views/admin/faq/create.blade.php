@extends('admin.layout.app')
@section('title', 'FAQs')
@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <form action="{{ url('admin/faq-store') }}" method="POST">
                    @csrf
                    <a href="{{ url('/admin/faq-index') }}" class="btn mb-3" style="background: #ff5608;">Back</a>
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>FAQ</h4>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Question</label>
                                        <input name="question" class="form-control @error('question') is-invalid @enderror">
                                        @error('question')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea name="description" class="form-control @error('description') is-invalid @enderror">
                                         @error('description')
<div class="invalid-feedback">{{ $message }}</div>
@enderror               
                                        </textarea>
                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <button type="submit" class="btn btn-primary mr-1" type="submit">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>

@endsection
@section('js')
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('description');
    </script>

    <script>
        $(document).ready(function() {
            $('input, select, textarea').on('focus', function() {
                const $feedback = $(this).parent().find('.invalid-feedback');
                if ($feedback.length) {
                    $feedback.hide();
                    $(this).removeClass('is-invalid');
                }
            });
        });
    </script>
@endsection
