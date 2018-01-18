@extends('layouts.bko')

@section('heading', $title)

@section('content')
	<div class="row">
		<div class="col-lg-12">
			<div class="row filters-table">
				<div class="form-group">
					<label for="filter__thematic">Thématique</label>
					<select id="filter__thematic" class="form-control select2-filter">
						<option></option>
						@foreach($primary_thematics as $thematic)
							<option value="{{ $thematic->name }}">{{ $thematic->name }}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="filter__subthematic">Sous-thématique</label>
					<select id="filter__subthematic" class="form-control select2-filter">
						<option></option>
						@foreach($primary_thematics as $primary)
							@if(empty($subthematics[$primary->id]))
								@continue
							@endif
							<optgroup label="{{ $primary->name }}">
								@foreach($subthematics[$primary->id] as $thematic)
									<option value="{{ $thematic->name }}">{{ $thematic->name }}</option>
								@endforeach
							</optgroup>
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="filter__projectHolder">Porteur du dispositif</label>
					<select id="filter__projectHolder" class="form-control select2-filter">
						<option></option>
						@foreach($project_holders as $project_holder)
							<option value="{{ $project_holder->name }}">{{ $project_holder->name }}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="filter__perimeter">Périmètre</label>
					<select id="filter__perimeter" class="form-control select2-filter">
						<option></option>
						@foreach($perimeters as $perimeter)
							<option value="{{ $perimeter->name }}">{{ $perimeter->name }}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="filter__beneficiary">Bénéficiaire</label>
					<select id="filter__beneficiary" class="form-control select2-filter">
						<option></option>
						@foreach($beneficiaries as $beneficiary)
							<option value="{{ $beneficiary->name }}">{{ $beneficiary->name }}</option>
						@endforeach
					</select>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<table class="table table-striped table-hover table-condensed" id="table__callsForProjects">
				<thead>
					<tr>
						<th>Thématique</th>
						<th>Sous-thématique</th>
						<th>Intitulé</th>
						<th>Date de clotûre</th>
						<th>Porteur du dispositif</th>
						<th>Périmètre</th>
						<th>Objectifs</th>
						<th>Bénéficiaires</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					@foreach($callsForProjects as $callForProject)
						<tr>
							<td>{{ $callForProject->thematic->name }}</td>
							<td>{{ empty($callForProject->subthematic_id) ? '' : $subthematics[$callForProject->thematic->id]->firstWhere('id', $callForProject->subthematic_id)->name }}</td>
							<td>{{ $callForProject->name }}</td>
							<td>{{ empty($callForProject->closing_date) ? '' : $callForProject->closing_date->format('d/m/Y') }}</td>
							<td>{{ empty($callForProject->project_holder_id) ? '' : $project_holders->firstWhere('id', $callForProject->project_holder_id)->name }}</td>
							<td>{{ empty($callForProject->perimeter_id) ? '' : $perimeters->firstWhere('id', $callForProject->perimeter_id)->name }}</td>
							<td>{{ \Illuminate\Support\Str::words($callForProject->objectives, 50) }}</td>
							<td>{{ empty($callForProject->beneficiary_id) ? '' : $beneficiaries->firstWhere('id', $callForProject->beneficiary_id)->name }}</td>
							<td class="text-right col-actions">
								<a href="{{ route('bko.call.edit', $callForProject) }}" title="Modifier"><i class="fa fa-pencil" aria-hidden="true"></i></a>
								<a href="#" class="deleteItemBtn" title="Supprimer" data-toggle="modal" data-target="#modalDeleteItem" data-id="{{ $callForProject->id }}">
									<i class="fa fa-trash-o" aria-hidden="true"></i>
								</a>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
@endsection

@push('inline-script')
	<script>
		var table;

		function filterResults() {
			var filter__thematic = $.fn.DataTable.ext.type.search.string($.fn.dataTable.util.escapeRegex($('#filter__thematic').val()));
			var filter__subthematic = $.fn.DataTable.ext.type.search.string($.fn.dataTable.util.escapeRegex($('#filter__subthematic').val()));
			var filter__projectHolder = $.fn.DataTable.ext.type.search.string($.fn.dataTable.util.escapeRegex($('#filter__projectHolder').val()));
			var filter__perimeter = $.fn.DataTable.ext.type.search.string($.fn.dataTable.util.escapeRegex($('#filter__perimeter').val()));
			var filter__beneficiary = $.fn.DataTable.ext.type.search.string($.fn.dataTable.util.escapeRegex($('#filter__beneficiary').val()));

			table.columns(0).search(filter__thematic ? '^'+filter__thematic+'$' : '', true, false);
			table.columns(1).search(filter__subthematic ? '^'+filter__subthematic+'$' : '', true, false);
			table.columns(4).search(filter__projectHolder ? '^'+filter__projectHolder+'$' : '', true, false);
			table.columns(5).search(filter__perimeter ? '^'+filter__perimeter+'$' : '', true, false);
			table.columns(7).search(filter__beneficiary ? '^'+filter__beneficiary+'$' : '', true, false);
			table.draw();
		}

		(function($) {
			"use strict";

			table = $('#table__callsForProjects').DataTable({
				"columns": [
					null,
					null,
					null,
					null,
					null,
					null,
					null,
					null,
					{ "orderable": false }
				],
			});

			$('.select2-filter').select2({
				allowClear: true,
			}).on('select2:unselecting', function() {
				$(this).data('unselecting', true);
			}).on('select2:opening', function(e) {
				if ($(this).data('unselecting')) {
					$(this).removeData('unselecting');
					e.preventDefault();
				}
			}).on('change', function() {
				filterResults();
			});

			$('#filter__closingDate').on('change', function() {
				filterResults();
			});
		})(jQuery);
	</script>
@endpush

@section('after-content')
	@include('bko.components.modals.delete', [
		'title' => "Suppression d'un appel à projets",
		'question' => "Êtes-vous sûr de vouloir supprimer cet appel à projets ?",
		'action' => 'Bko\CallForProjectsController@destroy',
	])
@endsection