<x-app-layout>
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">
    @endpush


    @section('title', 'page management')

    <x-slot name="header">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-news-paper icon-gradient bg-mean-fruit"></i>
            </div>
            <div>
                {{ __('Pages') }}
                <div class="page-title-subheading">
                    {{ __('This is a page management section, you can create, edit and delete pages') }}
                </div>
            </div>
        </div>
        @permission('admin.pages.create')
        <div class="page-title-actions">
            <div class="d-inline-block dropdown">
                <a href="{{ route('admin.pages.create') }}" class="btn-shadow btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                        <i class="fas fa-plus"></i>
                    </span>
                    Create
                </a>
            </div>
        </div>
        @endpermission
    </x-slot>

    <div class="row">
        <div class="col-md-12">
            <div class="main-card mb-3 card">
                <div class="table-responsive p-3">
                    <table id="datatable" class="align-middle mb-0 table table-borderless table-striped table-hover">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Title</th>
                            <th class="text-center">Url</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Last modified</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($pages as $key=> $page)
                                <tr>
                                    <td class="text-center text-muted">{{ $key + 1 }}</td>
                                    <td>
                                        {{ $page->title }}
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('page', $page->slug) }}">{{ $page->slug }}</a>
                                    </td>
                                    <td class="text-center">
                                        @if ($page->status == true)
                                            <span class="badge badge-info">
                                                Active
                                            </span>
                                        @else
                                            <span class="badge badge-danger">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $page->updated_at->diffForHumans() }}</td>
                                    <td class="text-center">

                                        <a href="{{ route('admin.pages.edit', $page->slug) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <button type="button" class="btn btn-danger btn-sm" onclick="deleteData({{ $page->id }})">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>

                                        <form id="delete-form-{{ $page->id }}" method="POST" action="{{ route('admin.pages.destroy', $page->slug) }}" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>

        <script>
            $(document).ready(function() {
                $('#datatable').DataTable();
            } );
        </script>
    @endpush

</x-app-layout>
