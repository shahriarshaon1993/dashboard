<x-app-layout>

    @push('css')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" integrity="sha512-EZSUkJWTjzDlspOoPSpUFR0o0Xy7jdzW//6qhUkoZ9c4StFkVsp9fbbd0O06p9ELS3H486m4wmrCELjza4JEog==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <style>
            .dropify-wrapper .dropify-message p {
                font-size: initial;
            }
        </style>

        <style>
            .ck-editor__editable_inline {
                min-height: 352px !important;
            }
        </style>
    @endpush

    @section('title', 'create page')

    <x-slot name="header">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-news-paper icon-gradient bg-mean-fruit"></i>
            </div>
            <div>
                {{ isset($page) ? 'Edit' : 'Create' }} page.
                <div class="page-title-subheading">
                    You can {{ isset($page) ? 'update' : 'create' }} page.
                </div>
            </div>
        </div>
        <div class="page-title-actions">
            <div class="d-inline-block dropdown">
                <a href="{{ route('admin.pages.index') }}" class="btn-shadow btn btn-dark">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                        <i class="fa fa-arrow-left"></i>
                    </span>
                    Back to list
                </a>
            </div>
        </div>
    </x-slot>

    <div class="row">
        <div class="col-12">
            <form method="POST" action="{{ isset($page) ? route('admin.pages.update', $page->slug) : route('admin.pages.store') }}" enctype="multipart/form-data">
                @csrf
                @isset($page)
                    @method('PUT')
                @endisset
                <div class="row">
                    <div class="col-md-8">
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <h5 class="card-title">Page info</h5>

                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input id="title" type="title" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ $page->title ?? old('title') }}" placeholder="Enter page title" autofocus>

                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="excerpt">Excerpt</label>
                                    <textarea class="form-control @error('excerpt') is-invalid @enderror" name="excerpt" value="{{ $page->excerpt ?? old('excerpt') }}" >{{ $page->excerpt ?? '' }}</textarea>

                                    @error('excerpt')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="page-editor">Body</label>
                                    <textarea id="page-editor" class="form-control @error('body') is-invalid @enderror" name="body" value="{{ $page->body ?? old('body') }}">{{ $page->body ?? '' }}</textarea>

                                    @error('body')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <h5 class="card-title">Select image & status</h5>

                                <div class="form-group">
                                    <label for="featured_image">Featured image</label>

                                    <input type="file" id="featured_image" class="dropify form-control @error('image') is-invalid @enderror" name="featured_image" data-default-file="{{ isset($page) ? $page->getFirstMediaUrl('featured_image') : ''}}" {{ !isset($page) ? 'required' : '' }}>

                                    @error('featured_image')
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="status" name="status" @isset($page) {{ $page->status == true ? 'checked' : '' }} @endisset>
                                        <label class="custom-control-label" for="status">Status</label>
                                    </div>

                                    @error('status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    @isset($page)
                                        <i class="fas fa-arrow-circle-up"></i>
                                        Update
                                    @else
                                        <i class="fas fa-plus-circle"></i>
                                        Create
                                    @endisset
                                </button>

                            </div>
                        </div>

                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <h5 class="card-title">Meta info</h5>

                                <div class="form-group">
                                    <label for="meta_description">Meta description</label>
                                    <textarea class="form-control @error('meta_description') is-invalid @enderror" name="meta_description" value="{{ $page->meta_description ?? old('meta_description') }}">{{ $page->meta_description ?? '' }}</textarea>

                                    @error('meta_description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="meta_keywords">Meta keyword</label>
                                    <textarea class="form-control @error('meta_keywords') is-invalid @enderror" name="meta_keywords" value="{{ $page->meta_keywords ?? old('meta_keywords') }}" >{{ $page->meta_keywords ?? '' }}</textarea>

                                    @error('meta_keywords')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('js')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js" integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdn.ckeditor.com/ckeditor5/30.0.0/classic/ckeditor.js"></script>

        <script>
            $(document).ready(function() {
                $('.dropify').dropify();
            });
        </script>

        @include('backend.pages.ckeditor')
    @endpush

</x-app-layout>
