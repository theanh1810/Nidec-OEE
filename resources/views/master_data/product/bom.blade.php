@extends('layouts.app')

@section('content')
<form action="{{route('masterData.product.add_product_and_materials_to_bom')}}" method="post">
	<div class="container-fluid">
	    <div class="row justify-content-center">
	        <div class="col-md-12">
	            <div class="card">
	                <div class="card-header">
	                	<span class="text-bold" style="font-size: 23px">
	                		{{ __('Product') }} : <span style="color:red;font-size: 23px">{{$pro ? $pro->Symbols : ''}}</span>
	                	</span>
						<div class="card-tools">
							<div>
								<p><span style="color:red"> * </span> <span>: {{__('Required information (maximum 20 characters)')}} </span> </p> 
							</div>
						</div>
	                </div>
	
						@csrf
	            <div class="card-body">
					<div class="row">
						<input type="number" value="{{$pro ? $pro->ID : ''}}"  class="form-control d-none" name="Product_BOM_ID"  >
						<div class="form-group col-md-4">
							<label for="symbolsProduct">{{__('Symbols')}} {{ __('Product') }} </label>
							<div class="input-group">
								<input type="text" value="{{$pro ? $pro->Symbols : ''}}" class="form-control" id="symbolsProduct" name="Symbols" placeholder="{{__('Enter')}} {{__('Symbols')}}" maxlength="20"  required>
								<div class="input-group-append" >
									<span class="input-group-text" style="color:Red">*</span>
								</div>
							</div>
						</div>
						<div class="form-group col-md-8">
							<label for="NoteProduct">{{__('Note')}}</label>
							<input type="text" value="{{$pro ? $pro->Note : ''}}" class="form-control" id="NoteProduct" name="Note" placeholder="{{__('Enter')}} {{__('Note')}}" >
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid">
	    <div class="row justify-content-center">
	        <div class="col-md-12">
	            <div class="card">
					<div class="mold  card-body">
						<div class="row">
							<div class="form-group col-md-2 ">
									<label for="Mold">{{__('Mold')}}</label>
									<div class="input-group">
									<input type="text" value=""  class="form-control mold" id="Mold"  maxlength="20">
									<div class="input-group-append">
											<span class="input-group-text" style="color:Red">*</span>
									</div>
								</div>
							</div>
							<div class="form-group col-md-2 ">
									<label for="Mold">{{__('Cavity')}}</label>
									<div class="input-group">
										<input type="number" value="" min="0" step="1" class="form-control mold" id="Cavity"  >
										<div class="input-group-append">
												<span class="input-group-text" style="color:Red">*</span>
										</div>
									</div>
							</div>
							<div class="form-group col-md-2 ">
									<label for="Mold">{{__('Cycle Time')}} ( s )</label>
									<div class="input-group">
										<input type="number" value="" min="0" step="0.0001" class="form-control mold" id="Cycle_Time"  >
										<div class="input-group-append">
												<span class="input-group-text" style="color:Red">*</span>
										</div>
									</div>
							</div>
							<div class="col-md-2" >
									<button type="button" class="btn btn-info add-mold">{{__('Create')}}</button>
							</div>
						</div>
						<table class="table table-striped table-hover table-mold">
							<thead>
								<th  class="d-none">ID {{ __('Mold') }} </th>
								<th>{{__('Symbols')}} {{ __('Mold') }}</th>
								<th>{{__('Cavity')}}</th>
								<th>{{__('Cycle Time')}}</th>
								<th>{{__('User Created')}}</th>
								<th>{{__('Time Created')}}</th>
								<th>{{__('Action')}}</th>
							</thead>
							<tbody>
								@foreach($data->where('Mold_ID','>',0) as $value)
									<tr class="tr-mater-{{$value->mold ? $value->mold->Symbols : '' }}">
										<td class="d-none">{{$value->mold ? $value->mold->Symbols : '' }}
										<input type="Text" value="{{$value->mold ? $value->mold->Symbols : '' }}" class="form-control d-none" id="mold" name="mold[]" >
										</td>
										<td>{{$value->mold ? $value->mold->Symbols : '' }}</td>
										<td><input type="number" value="{{floatval($value->Cavity)}}" step="0.0001" class="form-control" id="Cavity" name="Cavity[]" ></td>
										<td><input type="number" value="{{floatval($value->Cycle_Time)}}" step="0.0001" class="form-control" id="Cycle_Time" name="Cycle_Time[]" ></td>
										<td>{{$value->user_created ? $value->user_created->name : '--' }}</td>
										<td>{{$value->Time_Created}}</td>
										<td>
											<button id="{{$value->mold ? $value->mold->Symbols : '' }}" class="btn btn-danger btn-delete-mater" style="width: 80px">
												{{__('Delete')}}
											</button>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid">
	    <div class="row justify-content-center">
	        <div class="col-md-12">
	            <div class="card">
					<div class="materials card-body">
						<div class="row">
							<div class="form-group col-md-2 ">
								<label for="Materials">{{__('Materials')}}</label>
								<div class="input-group">
									<input type="text" value=""  class="form-control materials" id="Materials"  maxlength="20">
									<div class="input-group-append">
												<span class="input-group-text" style="color:Red">*</span>
									</div>
								</div>
							</div>
							<div class="form-group col-md-2 ">
								<label for="Materials">{{__('Usage/PCS')}} ( g )</label>
								<div class="input-group">
									<input type="number" min="0" step="0.0001" value=""  class="form-control materials" id="Quantity"  >
									<div class="input-group-append">
												<span class="input-group-text" style="color:Red">*</span>
									</div>
								</div>
							</div>
							<div class="form-group col-md-2 ">	
							</div>
							<div class="col-md-2" >
								<button type="button" class="btn btn-info add-materials">{{__('Create')}}</button>
							</div>
						</div>
						<table class="table table-striped table-hover table-materials">
							<thead>
								<th class="d-none">ID {{ __('Materials') }} </th>
								<th>{{__('Symbols')}} {{ __('Materials') }}</th>
								<th>{{__('Usage/Pcs')}} ( g )</th>
								<th>{{__('User Created')}}</th>
								<th>{{__('Time Created')}}</th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th>{{__('Action')}}</th>
							</thead>
							<tbody>
								@foreach($data->where('Materials_ID','>',0) as $value)
									<tr class="tr-mater-{{$value->materials ? $value->materials->Symbols : '' }}">
										<td class="d-none">{{$value->materials ? $value->materials->Symbols : '' }}
										<input type="text" value="{{$value->materials ? $value->materials->Symbols : '' }}" class="form-control d-none" id="materials" name="materials[]" >
										</td>
										<td>{{$value->materials ? $value->materials->Symbols : '' }}</td>
										<td><input type="number" value="{{floatval($value->Quantity_Material)}}" step="0.0001" class="form-control" id="Quantity_materials" name="Quantity_materials[]" ></td>
										<td>{{$value->user_created ? $value->user_created->name : '--' }}</td>
										<td>{{$value->Time_Created}}</td>
										<th></th>
										<th></th>
										<th></th>
										<th></th>
										<th></th>
										<td>
											<button id="{{$value->materials ? $value->materials->Symbols : '' }}" class="btn btn-danger btn-delete-mater" style="width: 80px">
												{{__('Delete')}}
											</button>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				<div class="card-footer">
					<a href="{{ route('masterData.product') }}" class="btn btn-info">{{__('Back')}}</a>
					<button type="submit" class="btn btn-success float-right">{{__('Save')}}</button>
				</div>
			</div>

		</div>
	</div>
	</div>
	</div>
</form>	
@endsection
@push('scripts')
	<script>
		$('.select2').select2()

		$('#tableProduct').DataTable({
			language: __languages.table,
			scrollX : '100%',
			scrollY : '100%'
		});
		let arr_pro = [];
		let arr_mater = [];
		let arr_mold = [];
		$('.add-product').on('click',function(){
			let product_id = $('.product').val();
			let product_symbols = $('.product :selected').text();
			console.log($.inArray(product_id, arr_pro ))
			if($.inArray(product_id, arr_pro ) == '-1' && product_id.length > 0)
			{
				arr_pro.push(product_id);
				$('.table-product tbody').append(`
					<tr class="tr-pro-`+product_id+`">
						<td>`+product_id+`
						<input type="number" value="`+product_id+`" class="form-control d-none" id="product" name="product[]" >
						</td>
						<td>`+product_symbols+`</td>
						<td><input type="number" value="1" step="0.0001" class="form-control" id="Quantity_product" name="Quantity_product[]" ></td>
						<td></td>
						<td></td>
						<td>
							<button id="`+product_id+`" class="btn btn-danger btn-delete-pro" style="width: 80px">
								{{__('Delete')}}
							</button>
						</td>
					</tr>
				
				`)
				$('.btn-delete-pro').on('click',function(){
					$('.tr-pro-'+$(this).attr('id')+'').remove()
					arr_pro.splice($.inArray($(this).attr('id'), arr_pro),1);
					console.log(arr_pro)
				})
			}
			console.log(product_id,product_symbols)
		})
		$('.add-materials').on('click',function(){
			
			let materials_id = $('#Materials').val();
			let materials_symbols =  $('#Materials').val();
			let quantity =  $('#Quantity').val();
			console.log($.inArray(materials_id, arr_mater) ,materials_id )
			if($.inArray(materials_id, arr_mater ) == '-1' && materials_id.length > 0 && quantity != '')
			{
				arr_mater.push(materials_id);
				$('.table-materials tbody').append(`
					<tr class="tr-mater-`+materials_id+`">
						<td class="d-none">`+materials_id+`
						<input type="text" value="`+materials_id+`" class="form-control d-none" id="materials" name="materials[]" >
						</td>
						<td>`+materials_symbols+`</td>
						<td><input type="number" value="`+quantity+`" step="0.0001" min ="0" class="form-control" id="Quantity_materials" name="Quantity_materials[]" ></td>
						<td></td>
						<td></td>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<td>
								<button id="`+materials_id+`" class="btn btn-danger btn-delete-mater" style="width: 80px">
									{{__('Delete')}}
								</button>
						</td>
					</tr>
				`)
				$('.btn-delete-mater').on('click',function(){
					$('.tr-mater-'+$(this).attr('id')+'').remove()
					arr_mater.splice($.inArray($(this).attr('id'), arr_mater),1);
					console.log(arr_mater)
				})
			}
			else
			{
				alert(`* : {{__('Required information (maximum 20 characters)')}}`)
			}
		})
		$('.add-mold').on('click',function(){
			
			let mold_id = $('#Mold').val();
			let mold_symbols =  $('#Mold').val();
			let cavity =  $('#Cavity').val();
			let cycle_time =  $('#Cycle_Time').val();
			if($.inArray(mold_id, arr_mold ) == '-1' && mold_id.length > 0 && cavity != '' && cycle_time != '' )
			{
				arr_mold.push(mold_id);
				$('.table-mold tbody').append(`
					<tr class="tr-mold-`+mold_id+`">
						<td class="d-none">`+mold_id+`
						<input type="text" value="`+mold_id+`" class="form-control d-none" id="mold" name="mold[]" >
						</td>
						<td>`+mold_symbols+`</td>
						<td><input type="number" value="`+cavity+`" min ="0" step="0.0001" class="form-control" id="Cavity" name="Cavity[]" ></td>
						<td><input type="number" value="`+cycle_time+`" min ="0" step="0.0001" class="form-control" id="Cycle_Time" name="Cycle_Time[]" ></td>
						<td></td>
						<td></td>
						<td>
								<button id="`+mold_id+`" class="btn btn-danger btn-delete-mold" style="width: 80px">
									{{__('Delete')}}
								</button>
						</td>
					</tr>
				`)
				$('.btn-delete-mold').on('click',function(){
					$('.tr-mold-'+$(this).attr('id')+'').remove()
					arr_mold.splice($.inArray($(this).attr('id'), arr_mold),1);
					console.log(arr_mold)
				})
			}
			else
			{
				alert(`* : {{__('Required information (maximum 20 characters)')}}`)
			}
		})
		$('.btn-delete-pro').on('click',function(){
					$('.tr-pro-'+$(this).attr('id')+'').remove()
					arr_pro.splice($.inArray($(this).attr('id'), arr_pro),1);
					console.log(arr_pro)
		})
		$('.btn-delete-mater').on('click',function(){
					$('.tr-mater-'+$(this).attr('id')+'').remove()
					arr_mater.splice($.inArray($(this).attr('id'), arr_mater),1);
					console.log(arr_mater)
		})
		$('.btn-delete-mold').on('click',function(){
					$('.tr-mold-'+$(this).attr('id')+'').remove()
					arr_mold.splice($.inArray($(this).attr('id'), arr_mold),1);
					console.log(arr_mold)
		})
	</script>
@endpush