<x-app-layout>
    @push('css')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" integrity="sha512-EZSUkJWTjzDlspOoPSpUFR0o0Xy7jdzW//6qhUkoZ9c4StFkVsp9fbbd0O06p9ELS3H486m4wmrCELjza4JEog==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <style>
            .dropify-wrapper .dropify-message p {
                font-size: initial;
            }
        </style>
    @endpush

    @section('title', 'create user')

    <x-slot name="header">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-user icon-gradient bg-mean-fruit"></i>
            </div>
            <div>
                {{ isset($user) ? 'Edit' : 'Create' }} user.
                <div class="page-title-subheading">
                    You can {{ isset($user) ? 'update' : 'create' }} user.
                </div>
            </div>
        </div>
        <div class="page-title-actions">
            <div class="d-inline-block dropdown">
                <a href="{{ route('admin.users.index') }}" class="btn-shadow btn btn-dark">
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
            <form method="POST" action="{{ isset($user) ? route('admin.users.update', $user->id) : route('admin.users.store') }}" enctype="multipart/form-data">
                @csrf
                @isset($user)
                    @method('PUT')
                @endisset
                <div class="row">
                    <div class="col-md-8">
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <h5 class="card-title">User info</h5>

                                <div class="form-group">
                                    <label for="name">name</label>
                                    <input id="name" type="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $user->name ?? old('name') }}" placeholder="Enter user name" autofocus required>

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $user->email ?? old('email') }}" placeholder="Enter user email" required>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="confirm_password">Confirm Password</label>
                                    <input id="confirm_password" type="password" class="form-control @error('password') is-invalid @enderror" name="password_confirmation" placeholder="Confirm Password">

                                    @error('password')
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
                                <h5 class="card-title">Select role & status</h5>

                                <div class="form-group">
                                    <label for="role">Select role</label>

                                    <select id="role" class="form-control @error('role') is-invalid @enderror js-example-basic-single" name="role">

                                        @foreach ($roles as $key=>$role)
                                            <option value="{{ $role->id }}"

                                                @isset($user)
                                                    {{ $user->role->id == $role->id ? 'selected' : ''}}
                                                @endisset

                                            >{{ $role->name }}</option>
                                        @endforeach

                                    </select>

                                    @error('role')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="avatar">Avatar</label>

                                    <input type="file" id="avatar" class="dropify form-control @error('avatar') is-invalid @enderror" name="avatar" data-default-file="{{ isset($user) ? $user->getFirstMediaUrl('avatar') : ''}}" {{ !isset($user) ? 'required' : '' }}>

                                    @error('avatar')
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="status" name="status" @isset($user) {{ $user->status == true ? 'checked' : '' }} @endisset>
                                        <label class="custom-control-label" for="status">Status</label>
                                    </div>

                                    @error('status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    @isset($user)
                                        <i class="fas fa-arrow-circle-up"></i>
                                        Update
                                    @else
                                        <i class="fas fa-plus-circle"></i>
                                        Create
                                    @endisset
                                </button>

                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js" integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script>
            $(document).ready(function() {
                $('.dropify').dropify();
                $('.js-example-basic-single').select2();
            });
        </script>
    @endpush

</x-app-layout>
