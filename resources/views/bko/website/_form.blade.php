<form action="{{ $options['url'] }}" method="post" enctype="multipart/form-data">
	{{ method_field($options['method']) }}
	{{ csrf_field() }}

	@php($organization_type_id = old('organization_type_id', $website->organization_type_id))

	<div class="form-group">
		<label for="project_holder_id">Structure</label>
		<div class="input-group">
			<select name="organization_type_id" id="organization_type_id" class="form-control">
				@if(!empty($organization_type_id))
					@php($organization_type = \App\OrganizationType::where('id', $organization_type_id)->first())
					@if(!empty($organization_type->id))
						<option value="{{ $organization_type->id }}">{{ $organization_type->name }}</option>
					@endif
				@endif
			</select>
			<span class="input-group-btn">
				<button class="btn btn-default" type="button" data-toggle="modal" data-target="#modalNewOrganizationType"><i class="fa fa-plus" aria-hidden="true"></i></button>
			</span>
		</div>
	</div>
	<div class="form-group">
		<label for="name">Nom de la structure*</label>
		<input type="text" class="form-control" name="name" id="name" value="{{ old('name', $website->name) }}">
	</div>
	<div class="form-group">
		<label for="themes">Thèmes*</label>
		<textarea class="form-control" rows="3" name="themes" id="themes">{{ old('themes', $website->themes) }}</textarea>
	</div>
	<div class="form-group">
		<label for="perimeter">Périmètre*</label>
		<textarea class="form-control" rows="3" name="perimeter" id="perimeter">{{ old('perimeter', $website->perimeter) }}</textarea>
	</div>
	<div class="form-group">
		<label for="perimeter_comments">Périmètre - Précisions</label>
		<textarea class="form-control" rows="3" name="perimeter_comments" id="perimeter_comments">{{ old('perimeter_comments', $website->perimeter_comments) }}</textarea>
	</div>
	<div class="form-group">
		<label for="delay">Délai</label>
		<textarea class="form-control" rows="3" name="delay" id="delay">{{ old('delay', $website->delay) }}</textarea>
	</div>
	<div class="form-group">
		<label for="allocated_budget">Budget alloué</label>
		<textarea class="form-control" rows="3" name="allocated_budget" id="allocated_budget">{{ old('allocated_budget', $website->allocated_budget) }}</textarea>
	</div>
	<div class="form-group">
		<label for="beneficiaries">Bénéficiaires</label>
		<textarea class="form-control" rows="3" name="beneficiaries" id="beneficiaries">{{ old('beneficiaries', $website->beneficiaries) }}</textarea>
	</div>
	<div class="form-group">
		<label for="website_url">Adresse internet*</label>
		<textarea class="form-control" rows="3" name="website_url" id="website_url">{{ old('website_url', $website->website_url) }}</textarea>
	</div>
	<div class="form-group">
		<label for="description">Observations</label>
		<textarea class="form-control" rows="3" name="description" id="description">{{ old('description', $website->description) }}</textarea>
	</div>
	<div class="form-group">
		<label for="logo">Logo</label>
		@if(!empty($website->getFirstMedia(\App\Website::MEDIA_COLLECTION)))
			<img src="{{ $website->getFirstMedia(\App\Website::MEDIA_COLLECTION)->getUrl() }}" alt="logo" class="img-responsive" style="width: 150px; margin-bottom: 15px;">
		@endif
		<input type="file" name="logo" id="logo" value="{{ old('logo') }}">
	</div>

	<button type="submit" class="btn btn-primary">Enregistrer</button>
</form>

@push('inline-script')
	<script>
		function saveNewItem(modalId, ajaxUrl, selector) {
			var modal = $('#'+modalId);

			modal.find('.alert').addClass('hidden');
			$.ajax({
				url: ajaxUrl,
				method: 'post',
				data: $('#form__'+modalId).serialize(),
				success: function(data){
					var option = new Option(data.name, data.id, true, true);
					$('#'+selector).append(option).trigger('change');
					modal.modal('hide');
				},
				error: function(data, json) {
					var alert_block = modal.find('.alert');
					alert_block.removeClass('hidden').empty();
					$.each(data.responseJSON.errors, function(k, item) {
						$.each(item, function(k2, item2) {
							alert_block.append($('<p>').html(item2));
						});
					});
				}
			});
		}

		function select2__ajaxOptions(url) {
			return {
				delay: 250,
				cache: true,
				url: url,
				dataType: 'json',
				method: 'post',
			};
		}

		(function($) {
			"use strict";

			$('#organization_type_id').select2({
				ajax: select2__ajaxOptions('{{ route('bko.structure.select2') }}')
			});

			$('#save__modalNewOrganizationType').on('click', function() {
				saveNewItem('modalNewOrganizationType', '{{ action('Bko\OrganizationTypeController@store') }}', 'organization_type_id');
			});

			$('#modalNewOrganizationType').on('hidden.bs.modal', function (e) {
				var _this = $(this);
				_this.find('input[type="text"], textarea').val('');
				_this.find('input[type="radio"], input[type="checkbox"]').prop('checked', false);
			});
		})(jQuery);
	</script>
@endpush

@section('after-content')
	@component('bko.components.modal')
		@slot('id', 'modalNewOrganizationType')
		@slot('title', "Ajout d'une structure")
		@slot('slot')
			@include('bko.forms._default', [
				'model' => new \App\OrganizationType(),
				'options' => [ 'method' => 'POST', 'url' => '#' ],
				'modal' => 'modalNewOrganizationType',
			])
		@endslot
		@slot('footer')
			<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
			<button type="button" class="btn btn-primary" id="save__modalNewOrganizationType">Ajouter</button>
		@endslot
	@endcomponent
@endsection