<x-app-layout>

    @section('title', '- General')

    <x-slot name="header">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-settings icon-gradient bg-mean-fruit"></i>
            </div>
            <div>
                {{ __('General Settings') }}
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
            <div class="list-group">
                @include('backend.settings.sidebar')
            </div>
        </div>
        <div class="col-md-9">

            <div class="main-card mb-3 card">
                <div class="card-body">
                    <h5 class="card-title">How To Use:</h5>
                    <p>You can get the value of each setting anywhere on your site by calling <code>setting('key')</code></p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.settings.general.update') }}">
                @csrf
                @method('PUT')
                <div class="main-card mb-3 card">
                    <div class="card-body">

                        <div class="form-group">
                            <label for="site_title">Site title</label>
                            <input id="site_title" type="text" class="form-control @error('site_title') is-invalid @enderror" name="site_title" value="{{ setting('site_title') ?? old('site_title') }}">

                            @error('site_title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="site_description">Site description</label>
                            <textarea class="form-control @error('site_description') is-invalid @enderror" name="site_description">{{ setting('site_description') ?? old('site_description') }}</textarea>

                            @error('site_description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="site_address">Site address</label>
                            <textarea class="form-control @error('site_address') is-invalid @enderror" name="site_address">{{ setting('site_address') ?? old('site_address') }}</textarea>

                            @error('site_address')
                                <span class="invalid-feedback" role="alert">
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
</x-app-layout>
