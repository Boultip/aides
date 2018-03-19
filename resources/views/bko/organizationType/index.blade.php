@extends('layouts.bko')

@section('heading')
    <div class="heading-with-actions">
        <div class="title">Liste des organisations</div>
        <div class="actions">
            <a href="{{ route('bko.export.table', ['table' => 'organization_types']) }}" data-tooltip="tooltip" title="Exporter en CSV">
                <i class="fa fa-file-excel-o" aria-hidden="true"></i>
            </a>
        </div>
    </div>
@endsection

@section('content')
    <table class="table table-striped table-hover" id="table__orgaTypes">
        <thead>
        <tr>
            <th>Nom</th>
            <th>Description</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($organizationTypes as $organizationType)
            <tr>
                <td>{{ $organizationType->name }}</td>
                <td>{!! $organizationType->description_html !!}</td>
                <td class="text-right col-actions">
                    <a href="{{ route('bko.structure.edit', $organizationType) }}" data-tooltip="tooltip"
                       title="Modifier"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                    <a href="#" class="deleteItemBtn" title="Supprimer" data-toggle="modal"
                       data-target="#modalDeleteItem" data-tooltip="tooltip" data-id="{{ $organizationType->id }}">
                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

@push('inline-script')
    <script>
        var table;

        (function ($) {
            "use strict";

            table = $('#table__orgaTypes').DataTable({
                "columns": [
                    {type: 'natural'},
                    {type: 'natural'},
                    {"orderable": false}
                ],
            });
        })(jQuery);
    </script>
@endpush

@section('after-content')
    @include('bko.components.modals.delete', [
        'title' => "Suppression d'une organisation",
        'question' => "Êtes-vous sûr de vouloir supprimer cette organisation ?<br/>Cette suppression entrainera la suppression des sites de recensement associés.",
        'action' => 'Bko\OrganizationTypeController@destroy',
    ])
@endsection