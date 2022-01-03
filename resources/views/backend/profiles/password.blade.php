<x-app-layout>
    @section('title', 'update user informations')

    <x-slot name="header">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-door-lock icon-gradient bg-mean-fruit"></i>
            </div>
            <div>
                {{ __('Change password') }}
                <div class="page-title-subheading">
                    {{ __('You can change you password.') }}
                </div>
            </div>
        </div>
        <div class="page-title-actions">
            <div class="d-inline-block dropdown">
                <a href="{{ route('admin.dashboard') }}" class="btn-shadow btn btn-dark">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                        <i class="fa fa-arrow-left"></i>
                    </span>
                    Back to dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="row">
        <div class="col-6">
            <form method="POST" action="{{ route('admin.profile.password.update') }}">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-12">
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <h5 class="card-title">Update password</h5>

                                <div class="form-group">
                                    <label for="current_password">Current password</label>
                                    <input id="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" placeholder="Current password" autofocus>

                                    @error('current_password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="password">New password</label>
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="New Password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="password_confirmation">Confirm password</label>
                                    <input id="password_confirmation" type="password" class="form-control @error('password') is-invalid @enderror" name="password_confirmation" placeholder="Confirm password">
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-arrow-circle-up"></i>
                                    Update
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>
