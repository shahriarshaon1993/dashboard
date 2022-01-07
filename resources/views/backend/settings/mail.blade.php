<x-app-layout>

    @section('title', '- Mail')
    <x-slot name="header">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-settings icon-gradient bg-mean-fruit"></i>
            </div>
            <div>
                {{ __('Mail Settings') }}
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

            <form method="POST" action="{{ route('admin.settings.mail.update') }}">
                @csrf
                @method('PUT')
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="mail_mailer">Mailer</label>
                                    <input id="mail_mailer" type="text" class="form-control @error('mail_mailer') is-invalid @enderror" name="mail_mailer" value="{{ setting('mail_mailer') ?? old('mail_mailer') }}" placeholder="Mail mailer">

                                    @error('mail_mailer')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="mail_encryption">Mail encryption</label>
                                    <input id="mail_encryption" type="text" class="form-control @error('mail_encryption') is-invalid @enderror" name="mail_encryption" value="{{ setting('mail_encryption') ?? old('mail_encryption') }}" placeholder="Mail encryption">

                                    @error('mail_encryption')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="mail_host">Mail host</label>
                                    <input id="mail_host" type="text" class="form-control @error('mail_host') is-invalid @enderror" name="mail_host" value="{{ setting('mail_host') ?? old('mail_host') }}" placeholder="Mail host">

                                    @error('mail_host')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="mail_port">Mail port</label>
                                    <input id="mail_port" type="text" class="form-control @error('mail_port') is-invalid @enderror" name="mail_port" value="{{ setting('mail_port') ?? old('mail_port') }}" placeholder="Mail port">

                                    @error('mail_port')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="mail_username">Mail username</label>
                            <input id="mail_username" type="text" class="form-control @error('mail_username') is-invalid @enderror" name="mail_username" value="{{ setting('mail_username') ?? old('mail_username') }}" placeholder="Mail username">

                            @error('mail_username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="mail_password">Mail password</label>
                            <input id="mail_password" type="password" class="form-control @error('mail_password') is-invalid @enderror" name="mail_password" value="{{ setting('mail_password') ?? old('mail_password') }}" placeholder="Mail password">

                            @error('mail_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="mail_from_address">Mail from address</label>
                            <input id="mail_from_address" type="email" class="form-control @error('mail_from_address') is-invalid @enderror" name="mail_from_address" value="{{ setting('mail_from_address') ?? old('mail_from_address') }}" placeholder="Mail from address">

                            @error('mail_from_address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="mail_from_name">Mail from name</label>
                            <input id="mail_from_name" type="text" class="form-control @error('mail_from_name') is-invalid @enderror" name="mail_from_name" value="{{ setting('mail_from_name') ?? old('mail_from_name') }}" placeholder="Mail from name">

                            @error('mail_from_name')
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
