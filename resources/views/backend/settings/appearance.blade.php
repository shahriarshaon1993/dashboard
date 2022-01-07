<x-app-layout>
    @push('css')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" integrity="sha512-EZSUkJWTjzDlspOoPSpUFR0o0Xy7jdzW//6qhUkoZ9c4StFkVsp9fbbd0O06p9ELS3H486m4wmrCELjza4JEog==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <style>
            .dropify-wrapper .dropify-message p {
                font-size: initial;
            }
        </style>
    @endpush

    @section('title', '- Appearance')

    <x-slot name="header">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-settings icon-gradient bg-mean-fruit"></i>
            </div>
            <div>
                {{ __('Appearance settings') }}
            </div>
        </div>
        <div class="page-title-actions">
            <div class="d-inline-block dropdown">
                <a href="{{ route('admin.dashboard') }}" class="btn-shadow btn btn-dark">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                        <i class="fa fa-arrow-left"></i>
                    </span>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="row">
        <div class="col-md-3">
            @include('backend.settings.sidebar')
        </div>
        <div class="col-md-9">

            <form method="POST" action="{{ route('admin.settings.appearance.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="main-card mb-3 card">
                    <div class="card-body">

                        <div class="form-group">
                            <label for="site_logo">Logo (Only image are allowed)</label>

                            <input type="file" id="site_logo" class="dropify form-control @error('site_logo') is-invalid @enderror" name="site_logo" data-default-file="{{ setting('site_logo') != null ? Storage::url(setting('site_logo')) : '' }}">

                            @error('site_logo')
                                <span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="site_favicon">Favicon (Only image are allowed, size: 33 x 33)</label>

                            <input type="file" id="site_favicon" class="dropify form-control @error('site_favicon') is-invalid @enderror" name="site_favicon" data-default-file="{{ setting('site_favicon') != null ? Storage::url(setting('site_favicon')) : '' }}">

                            @error('site_favicon')
                                <span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Update
                        </button>

                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('js')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js" integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script>
            $(document).ready(function() {
                $('.dropify').dropify();
            });
        </script>
    @endpush
</x-app-layout>
