@extends('layouts.main')

@push('myCss')
	<style>
		.select2 {
			font-size: 13px;
		}
		.select2-container--default .select2-results__option[aria-disabled=true] {
		    /*display: none;*/
		}
	</style>	
@endpush

@section('content')
	@include('control_agv.modal.modal_destroy_command')
	@include('control_agv.modal.modal_pid')

	<div class="container-fluid">
        <div class="row">
        	<!-- Command AGV -->
        	<div class="col-lg-12">
	            <div class="card">
		            <div class="card-header">
		            	<div class="row">
			              	<div class="col-md-4">
			              		{{ __('Status') }} {{ __('Command') }} {{ __('AGV') }}
			              	</div>
							<select name="" id="lineCommand1" class="form-control select2 col-md-3">
								<option value="">{{ __('Choose') }} {{ __('Position') }}</option>
								@foreach($warehouses as $ware)
									<option value="{{ $ware->Name }}">{{ $ware->Name }}</option>
								@endforeach
							</select>
			              	<input type="text" class="form-control col-md-3 hide" placeholder="{{ __('Scan') }} {{ __('Position') }}" id="lineCommand" value="{{ $line ? ($line->warehouse ? $line->warehouse->Name : '' ) : '' }}">
			              	<button class="btn btn-primary hide" style="margin-left: 0.5rem" id="btnSearch">
			              		<i class="fas fa-search"></i>
			              		{{ __('Search') }}
			              	</button>
		              	</div>
		            </div>
		            <div class="card-body">
		            	<div class="hide">
		            		<input type="text" id="pidSession"  value="{{ Session::has('command') ? Session::get('command')['pid'] : ($sesCom ? $sesCom->PID : '') }}">
		            		<input type="text" id="codeSession"  value="{{ Session::has('command') ? Session::get('command')['code'] : ($sesCom ? $sesCom->Code : '') }}">
		            	</div>
		              	<div class="alert alert-danger text-center col-md-12 hide" role="alert" id="alertSearch">
						</div>
						<div class="find-agv text-center col-md-12 hide" role="alert" id="findAgv">
							<button class="btn btn-warning" type="button">
							  	<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
							  	{{__('Filter AGV, Please Waiting')}}!
							</button>
						</div>
						<div class="destroy-command-agv text-center col-md-12 hide" role="alert" id="destroyCommandAgv">
							<button class="btn btn-danger" type="button">
							  	<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
							  	{{__('AGV Not Found')}}!
							</button>
						</div>
		            	<table class="table table-bordered" id="tableCommand">
		            		<thead>
		            			<th>{{ __('ID') }}</th>
		            			<th>{{ __('AGV') }}</th>
		            			<th>{{ __('Line') }}</th>
		            			<th>{{ __('Point') }} {{ __('Return') }}</th>
		            			<th>{{ __('Point') }} {{ __('From') }}</th>
		            			<th>{{ __('Status') }}</th>
		            			<th>{{ __('Process') }}</th>
		            			<th>{{ __('Task') }}</th>
		            			<th>{{ __('Type') }}</th>
		            			<th>{{ __('Confirm') }}</th>
		            			<th>{{ __('User Created') }}</th>
		            			<th>{{ __('Time Created') }}</th>
		            			<th>{{ __('Action') }}</th>
		            		</thead>
		            	</table>
		            </div>
		            <div class="card-footer justify-content-center">
		                <div class="row col-12 hide show-time">
		                	<div class="text-center col-9" style="align-items: center; display: flex; justify-content: center">
				                <button class="btn btn-sm btn-light btn-1" style="width: 230px; margin-right: 5px; margin-left: 5px">
				                	{{ __('LẤY HÀNG TỪ KHO') }} <span id="count1" class="my-count hide">(0s)</span>
				                </button>
				                <i class="fas fa-arrow-right"></i>
				                <button class="btn btn-sm btn-light btn-2" style="width: 230px; margin-right: 5px; margin-left: 5px">
				                	{{ __('MANG HÀNG TỚI MÁY') }} <span id="count2" class="my-count hide">(0s)</span>
				                </button>
				                <i class="fas fa-arrow-right"></i>
				                <button class="btn btn-sm btn-light btn-4" style="width: 230px; margin-right: 5px; margin-left: 5px">
				                	{{ __('XÁC NHẬN AGV NHẬP KHO') }} <span id="count4" class="my-count hide">(0s)</span>
				                </button>
				                <i class="fas fa-arrow-right"></i>
				                <button class="btn btn-sm btn-light btn-3" style="width: 230px; margin-right: 5px; margin-left: 5px">
				                	{{ __('MANG TRẢ VỀ KHO') }} <span id="count3" class="my-count hide">(0s)</span>
				                </button>
			                </div>
			                <div class="float-right col-3">
				                <button class="btn btn-info" style="width: 100%;">
				                	{{__('DỰ KIẾN')}} : <span id="time">60s</span>
				                </button>
			                </div>
		                </div>
		            </div>
	            </div>
          	</div>

          	<div class="col-lg-12">
          		<div class="card">
          			<div class="card-header">
          				{{ __('Chọn Kiểu Nhập') }}
          			</div>
          			<div class="card-body row">
          				<button class="btn btn-type-tran btn-light col-md-3" id="btn-2">
		          			{{__('Import')}}
		          		</button>
		          		<button class="btn btn-type-tran btn-light col-md-3" id="btn-3">
		          			{{__('Export')}}
		          		</button>
		          		<button class="btn btn-type-tran btn-light col-md-3" id="btn-1">
		          			{{__('Import')}} {{__('And')}} {{__('Export')}}
		          		</button>
		          		<button class="btn btn-type-tran btn-light col-md-3" id="btn-4">
		          			{{__('Change')}} {{__('Warehouse')}}
		          		</button>
          			</div>
          		</div>
          		
          	</div>

          	<!-- Create Command -->

          	<div class="col-lg-6">
	            <div class="card">
	              	<div class="card-header">
	              		{{ __('Create') }} {{ __('Command') }} {{ __('AGV') }}
	              	</div>
	              	<div class="card-body card-command">
	              		<div class="hide export-command">
		              		<div class="form-group">
				                <label for="PID">{{__('PID')}} {{__('Call')}} {{__('To')}}</label>
				                <input type="text" class="form-control" name="PID" id="pid" value="{{ Session::has('command') ? Session::get('command')['pid'] : '' }}">		                
				            </div>

				            <div class="alert alert-danger col-md-12 hide" role="alert" id="alertPid">
			                	{{ __('Không Có PID Hoặc Mã Thùng Thỏa Mãn') }}!
							</div>

							<div class="alert alert-danger col-md-12 hide" role="alert" id="alertPidW">
			                	{{ __('PID Gọi Đến Chưa Được Cài Đặt Kho Chứa') }}!
							</div>

				            <div class="form-group">
				                <!-- <label for="warehouseSelect1">{{__('Warehouse')}}</label> -->
		                    	<select id="warehouseSelect1" class="form-control select2">
		                    		
		                    	</select>
		                    </div>

				            <div class="col-sm-6">
				                <div class="form-group">
				                     <div class="custom-control custom-radio">
				                        <input class="custom-control-input" type="radio" id="customRadio1" name="Type" value="1" checked>
				                        <label for="customRadio1" class="custom-control-label">
				                        	{{__('Shelves')}} {{__('Null')}}
				                        </label>
				                    </div>
				                    <div class="custom-control custom-radio">
				                        <input class="custom-control-input" type="radio" name="Type" id="customRadio2" value="2">
				                        <label for="customRadio2" class="custom-control-label">
				                        	{{__('Shelves')}} {{__('Goods Available')}}
				                        </label>
				                    </div>
				                    <div class="custom-control custom-radio">
				                        <input class="custom-control-input" type="radio" id="customRadio3" name="Type" value="3">
				                        <label for="customRadio3" class="custom-control-label">
				                        	{{__('Shelves')}} {{__('Null')}} Frame
				                        </label>
				                    </div>
				                </div>
				           </div>
			           </div>

			           <div class="hide import-command">
				            <div class="form-group ">
				                <label for="Code">{{__('Code')}} {{__('Box')}} {{__('Import')}}</label>
				                <input type="text" class="form-control" id="code">
				            </div>

				            <div class="alert alert-danger col-md-12 hide" role="alert" id="alertCode">
			                	{{ __('Không Có PID Hoặc Mã Thùng Thỏa Mãn') }}!
							</div>

							<div class="alert alert-danger col-md-12 hide" role="alert" id="alertCodeW">
			                	{{__('Code')}} {{__('Box')}} {{__('Import')}} {{ __('Chưa Được Cài Đặt Kho Chứa') }}!
							</div>

				            <div class="form-group">
				                <!-- <label for="warehouseSelect2">{{__('Warehouse')}}</label> -->
		                    	<select id="warehouseSelect2" class="form-control select2">
		                    		
		                    	</select>
		                    </div>
	                    </div>
	            	</div>
	            	<div class="card-footer text-center">
	              		<button class="btn btn-info" style="width : 120px" id="btnCreate" disabled="true">
	              			<i class="fas fa-plus-square"></i>
	              			{{ __('Create Command') }}
	              		</button>
	              		<button class="btn btn-danger btn-delete-command" style="width : 120px">
	              			<i class="fas fa-trash"></i>
	              			{{ __('Delete') }}
	              		</button>
	              	</div>
          		</div>
          	</div>

          	<!-- Command Import Warehouse -->
	        <div class="col-lg-6 hide import-create">
	            <div class="card">
	              	<div class="card-header">
	              		{{ __('Command') }} {{ __('Import') }} {{ __('Product') }}
	              	</div>
	              	<div class="card-body card-import">
	                    <div class="form-group ">
	                        <label for="box">{{__('Code')}} {{__('Shelves')}}</label>
	                        <input type="text" class="form-control" name="PID" id="box">
	                    </div>

	                    <div class="alert alert-danger col-md-12 hide" role="alert" id="alertBox">
		                	{{ __('Không Có Mã Kệ Thỏa Mãn') }}!
						</div>

						<div class="form-group hide">
	                        <label for="typeImport">{{__('Type')}} {{__('Import')}}</label>
	                        <select name="" id="typeImport" class="form-control select2">
	                        	<option value="0">
	                        		{{ __('Import Shelves') }} {{ __('Goods Available') }}
	                        	</option>
	                        	<option value="1">
	                        		{{ __('Import Shelves') }} {{ __('Null') }}
	                        	</option>
	                        </select>
	                    </div>

	                    <div class="row shelves">
	                        <div class ="form-group col-12 ">
		                        <div class="row" >
		                        	<label for="PositionAGV" class="col-10">
		                        		{{__('Import')}} {{__('Shelves')}}
		                        	</label>
		                        	<button type="button" class="btn btn-primary btn-add-shelves col-md-2">
		                        		<i class="fas fa-plus-square"></i>
		                        		{{__('Add')}}
		                        	</button>
		                        </div>
	                        </div>

	                        <div class="col-8"> 
	                        	<label>
	                        		{{ __('Box') }}
	                        	</label>
	                    	</div>
	                        <div class="col-3">
	                        	<label>
	                        		{{ __('Quantity') }}
	                        	</label>
	                        </div>
	                        @for($i = 1 ; $i <= 3 ; $i++)
	                        	<div class="col-12 row">
			                        <div class="form-group col-8 inp-{{ $i }} my-shelves">
			                        	<input type="text" class="form-control">
			                        </div>
			                        <div class ="form-group col-3 inp-{{ $i }}" >
			                        	<input type="number" min="0" class="form-control">
			                        </div>
			                        <div class ="form-group col-1 inp-{{ $i }}" >
			                        	<button type="button" id ="delete-{{ $i }}" class="btn btn-danger btn-delete">
			                        		<i class="fas fa-trash"></i>
			                        	</button>
			                        </div>
			                        <div class="alert alert-danger col-md-12 hide alert-shelves" role="alert">
					                	{{ __('Không Có Mã Thùng Thỏa Mãn') }}!
									</div>
								</div>
	                        @endfor
	                    </div>
	              	</div>
	              	<div class="card-footer text-center">
	              		<button class="btn btn-info" style="width : 120px;" id="btnImport" disabled="true">
	              			<i class="fas fa-plus-square"></i>
	              			{{ __('Import') }}
	              		</button>
	              		<button class="btn btn-danger btn-delete-import" style="width : 120px">
	              			<i class="fas fa-trash"></i>
	              			{{ __('Delete') }}
	              		</button>
	              	</div>
	            </div>
	        </div>
    	</div>
	</div>

@endsection

@push('scripts')
	<script src="socket.io/socket.io.js"></script>
	<script>
	    var socket = io("{{ config('app.server_socket').config('app.port_socket') }}");
	</script>
	{{-- <script src="{{ asset('js/command_agv.js') }}"></script> --}}
	<script>
		$('.select2').select2();

		$('#lineCommand').val($('#lineCommand1').val());

		// socket.on('filter-line', (msg) => 
		// {
		// 	console.log(msg);
		// 	$('#lineCommand').val(msg);
		// });
		$('#staticBackdrop').modal({backdrop: 'static', keyboard: false});

		let auth    = "{{$editCom || Auth::user()->level == 9999}}";

		let editCom = !auth ? 'hide' : '' ;

		let table = $('#tableCommand').DataTable({
			language : __languages.table,
			search : false,
			paginate : false
		});

		$('#lineCommand1').on('change', function() 
		{
			let val = $(this).val();

			$('.find-agv').addClass('hide');
			$('#lineCommand').val(val);
			table.clear().draw();
			$('#lineCommand').click();

			if(val == '')
			{
				$('#alertSearch').removeClass('hide');
				$('#alertSearch').text("{{ __('Line') }}" + " {{ __('Does Not Exist') }}");
				$('#pid').prop("disabled", true);
			} else
			{
				$('#alertSearch').addClass('hide');
				$('#alertSearch').text('');
				$('#pid').prop("disabled", false);
			}
		});
		// $('.pagination').pagination({
		// 	items      : 3,
		// 	itemsOnPage: 1,
		// 	cssStyle   : 'light-theme'
		// });

		// $('.prev').text('Ok 1');
		// $('.next').text('Ok 1');

		$('#warehouseSelect1').select2().next().hide();
		$('#warehouseSelect2').select2().next().hide();

		let i           = 4;
		let command     = 0;
		let counter     = 8;
		let limit       = 2;
		let done        = false;
		let warehouse   = [];
		let warehouseID = [];
		let materials   = [];
		let find        = [];
		let find1       = [];
		let lineId      = 0;
		let idTrans     = 0;
		let pid         = [];
		let boxs        = [];
		let checkBox    = true;
		let boxId       = 0;
		let timeDelay   = 300;
		let load        = false;
		let active 		= true;
		let findAgv;
		let setOut;

		function removeStyle()
		{
			$('.card-import').removeAttr('style');
			$('.card-command').removeAttr('style');
		}

		function calculateCard()
		{
			let cardImport  = $('.card-import').height();
			let cardCommand = $('.card-command').height();

			if (cardImport > cardCommand) 
			{
				$('.card-command').height(cardImport);
			} else
			{
				$('.card-import').height(cardCommand);
			}
		}

		function getTrans(dataSend)
		{
			return $.ajax({
				method  : 'get',
				url     : window.location.origin + '/control-agv/trans/filter-line',
				data    : dataSend,
				dataType: 'json'
			});
		}

		function destroyTrans(dataSend)
		{
			return $.ajax({
				method  : 'get',
				url     : window.location.origin + '/control-agv/trans/destroy-command',
				data    : dataSend,
				dataType: 'json'
			});
		}

		function listPID(data)
		{
			return $.ajax({
				method  : 'get',
				url     : window.location.origin + '/list-pid',
				data    : {},
				dataType: 'json'
			});
		}

		function listWarehouse(send)
		{
			return $.ajax({
				method  : 'get',
				url     : window.location.origin + '/list-warehouse',
				data    : send,
				dataType: 'json'
			});
		}

		function listBox()
		{
			return $.ajax({
				method  : 'get',
				url     : window.location.origin + '/list-box',
				data    : {},
				dataType: 'json'
			});
		}

		function createTrans(data)
		{
			return $.ajax({
				method  : 'get',
				// url     : "{{ route('controlAGV.trans.createTrans') }}",
				url     : window.location.origin + '/control-agv/trans/create-trans',
				data    : data,
				dataType: 'json'
			});
		}

		function importWarehouse(dataImport)
		{
			return $.ajax({
				method  : 'get',
				// url     : "{{ route('controlAGV.trans.importWarehouse') }}",
				url     : window.location.origin + '/control-agv/trans/import-warehouse',
				data    : dataImport,
				dataType: 'json'
			});
		}

		function putSession(dataPut)
		{
			return $.ajax({
				method  : 'get',
				// url     : "{{ route('putData') }}",
				url     : window.location.origin + '/put-data',
				data    : dataPut,
				dataType: 'json'
			});
		}

		function lineCom()
		{
			// console.log(idTrans);

			let pi = $('#pidSession').val();
			let co = $('#codeSession').val();

			if ($('#lineCommand').val() == '') 
			{
				$('#pid').attr('disabled', true);
				$('#code').attr('disabled', true);
				$('#box').attr('disabled', true);
				$('.my-shelves input').attr('disabled', true);
				$('input[type="number"]').attr('disabled', true);
			} else 
			{
				
				let mybt = $('.btn-type-tran.btn-success').attr('id');
				
				if (typeof(mybt) != "undefined") 
				{
					// console.log('đấ');
					let typ = mybt.split('-').pop();
					// console.log(typ);

					switch (typ)
					{
						case '2' :
							$('#code').attr('disabled', false);
							$('#pid').attr('disabled', true);
							break;
							
						default :
							$('#pid').attr('disabled', false);
							$('#code').attr('disabled', true);
							break;
					}
				}

				if (co != '') 
				{
					$('#box').attr('disabled', false);
				} else 
				{
					$('#box').attr('disabled', true);
				}

				$('.my-shelves input').attr('disabled', true);
				$('input[type="number"]').attr('disabled', true);

				// update lich su
				if(idTrans == '0')
				{
					$('#pid').val('');
					$('#code').val('');
					
				} else
				{
					$('#pid').val(pi);
					$('#code').val(co);
				}

				let ou;

				cleOu();

				function cleOu()
				{
					clearTimeout(ou);

					ou = setTimeout(function() {
						if (pi != '') 
						{
							// console.log('tri');
							if (typeof(mybt) != "undefined")
							{
								if(mybt.split('-').pop() != '2')
								{
									$("#pid").trigger("keyup");
								}
							}
						}

						if (co != '') 
						{
							setTimeout(function() {
								// console.log('tri1');

								$("#code").trigger("keyup");
							}, 300)
						}

						// $('#customRadio'.);

					}, 500);
				}
				
			}
			
		}

		function btnClass()
		{
			$('.btn-1').addClass('btn-light');
			$('.btn-2').addClass('btn-light');
			$('.btn-3').addClass('btn-light');
			$('.btn-4').addClass('btn-light');
			$('.btn-1').removeClass('btn-success');
			$('.btn-2').removeClass('btn-success');
			$('.btn-3').removeClass('btn-success');
			$('.btn-4').removeClass('btn-success');
			$('.my-count').addClass('hide');
		}

		function removeClass()
		{
			$('.btn-1').removeClass('btn-light');
			$('.btn-2').removeClass('btn-light');
			$('.btn-3').removeClass('btn-light');
			$('.btn-4').removeClass('btn-light');
			$('.btn-1').removeClass('btn-success');
			$('.btn-2').removeClass('btn-success');
			$('.btn-3').removeClass('btn-success');
			$('.btn-4').removeClass('btn-success');
			$('.my-count').addClass('hide');
		}

		let set      = true;
		let setCount;
		let runCount = 0;

		function setTime(i)
		{
			clearTimeout(setCount);

			setCount = setTimeout(function(){
				runCount++;
				$('#count'+i).text(runCount+'(s)');
				setTime(i);
			}, 1000);
		}

		function addClassBtn(value)
		{
			removeClass();

			switch (value) {
				case '1' :
					$('.btn-1').addClass('btn-success');
					$('#count1').removeClass('hide');
					set ? setTime(1) : '';
					set = false;
					break;
				case '2' :
					$('.btn-2').addClass('btn-success');
					$('#count2').removeClass('hide');
					set ? setTime(2) : '';
					set = false;
					break;
				case '3' :
					$('.btn-4').addClass('btn-success');
					$('#count4').removeClass('hide');
					set ? setTime(4) : '';
					set = false;
					break;
				case '4' :
					$('.btn-4').addClass('btn-success');
					break;
				case '5' :
					$('.btn-4').addClass('btn-success');
					break;
				case '6' :
					$('.btn-4').addClass('btn-success');
					break;
				case '7' :
					$('.btn-4').addClass('btn-success');
					break;
				case '8' :
					$('.btn-3').addClass('btn-success');
					$('#count3').removeClass('hide');
					set ? setTime(3) : '';
					set = false;
					break;
				case '9' :
					$('.btn-3').addClass('btn-success');
					break;
				default : 
					btnClass();
					break;
			}
		}

		function count()
		{
			// console.log('run');

			active = false;

			clearInterval(findAgv);

			findAgv = setInterval(function(){
				counter++;
			}, 60000);
		}

		let old = 0;

		function findTrans()
		{
			clearTimeout(setOut);

			let dataSend = {
				'_token' : $('meta[name="csrf-token"]').attr('content'),
				'line'	 : $('#lineCommand').val(),
			}

			if ($('#lineCommand').val() == '') 
			{
				table.clear().draw();
				btnClass();
				$('.show-time').addClass('hide');

				return false;
			}

			getTrans(dataSend).done(function(data) {
				btnClass();
				$('#findAgv').addClass('hide');
				$('#destroyCommandAgv').addClass('hide');

				// console.log(data);
				if (!data.status) 
				{
					$('#alertSearch').removeClass('hide');
					$('#alertSearch').text(data.data);
					$('#btnImport').prop('disabled', true);
					$('#btnCreate').prop('disabled', true);
					table.clear().draw();
					btnClass();
					$('.show-time').addClass('hide');
					$('#pid').val('');
					$('#code').val('');
					$('.my-shelves input').val('');
					$('input[type="number"]').val('');
					$('#warehouseSelect1').val(0);
					$('#warehouseSelect2').val(0);
					active  = true;
					counter = 1;
					idTrans = 0;

				} else 
				{
					lineId  = data.lineId;

					if (data.data.length == 0) 
					{
						$('#btnImport').prop('disabled', false);
						$('#btnCreate').prop('disabled', false);
						table.clear().draw();
						$('.show-time').addClass('hide');
						btnClass();
						active  = true;
						counter = 1;
						idTrans = 0;
					} else
					{
						if (old != data.data[0].ProcessID) 
						{
							old      = data.data[0].ProcessID;
							set      = true;
							runCount = 0;
						}

						$('#btnImport').prop('disabled', true);
						$('#btnCreate').prop('disabled', true);
						$('.show-time').removeClass('hide');
						
						let sumTable = table.data().count();
						
						if (sumTable == 0) {

							table.clear().draw();

							for(dat of data.data)
							{
								table.row.add([
									dat.ID,
									dat.agv ? dat.agv.Name : "Chưa Có AGV Nhận Lệnh",
									dat.lines ? dat.lines.Name : dat.Line_ID,
									dat.return_point ? (
										dat.return_point.Display ? dat.return_point.Display : dat.return_point.Name
										) 
									: dat.Return_Point,
									dat.from_point ? (
										dat.from_point.Display ? dat.from_point.Display : dat.from_point.Name
										) 
									: dat.From_Point,
									dat.StatusID,
									dat.ProcessID,
									dat.Task,
									dat.Type,
									dat.Confirm,
									dat.user_created ? dat.user_created.name : '',
									dat.Time_Created,
									`<button class="btn btn-danger btn-destroy-command `+ editCom+`" id="del-`+dat.ID+`">
										<i class="fas fa-trash"></i>
									</button>`
								]).draw();

								if (checkBox && (dat.Type == '1' || dat.Type == '2')) 
								{
									$('#btnImport').prop('disabled', false);
									idTrans = dat.ID;
								}
								// Them chuc nang hien thi thong bao tim kiem agv
								if (!dat.agv) 
								{
									$('#findAgv').removeClass('hide');

									if (counter == limit) 
									{
										$('#findAgv').addClass('hide');
										$('#destroyCommandAgv').removeClass('hide');
									}

									if (active) 
									{
										count();
										counter = 1;
									}
								} else 
								{
									active = false;
									counter = 1;

									$('#findAgv').addClass('hide');
								}

								addClassBtn(dat.ProcessID);

								let time   = parseInt(dat.Estimate);
								let house  = 0;
								let minit  = 0;
								let second = 0;

								if(time >= 3600)
								{
									house      = parseInt(time/3600);
									let timeAg = parseInt(time%3600);
									minit 	   = parseInt(timeAg/60);
									second 	   = parseInt(timeAg%60);
								} else if (time >= 60)
								{
									minit 	   = parseInt(time/60);
									second 	   = parseInt(time%60);
								} else 
								{
									second 	   = parseInt(time);
								}

								let time1;

								if (house != 0) 
								{
									time1 = house+'h:'+minit+'m:'+second+'s';
								} else if (minit != 0) 
								{
									time1 = minit+'m:'+second+'s';
								} else 
								{
									time1 = second+'s';
								}
								// console.log(time1);

								$('#time').text(time1);
							}
						} else 
						{
							let j = 0;
							let countTable = table.row().data().length;

							if (data.data.length >= sumTable/countTable) 
							{
								for(dat of data.data)
								{
									// Them chuc nang hien thi thong bao tim kiem agv
									if (!dat.agv) 
									{
										$('#findAgv').removeClass('hide');

										if (counter == limit) 
										{
											$('#findAgv').addClass('hide');
											$('#destroyCommandAgv').removeClass('hide');
										}

										if (active) 
										{
											count();
											counter = 1;
										}
									} else 
									{
										active = false;
										counter = 1;

										$('#findAgv').addClass('hide');
									}

									if (table.row(j).data() != undefined ) 
									{
										table.row(j).data([
											dat.ID,
											dat.agv ? dat.agv.Name : "Chưa Có AGV Nhận Lệnh",
											dat.lines ? dat.lines.Name : dat.Line_ID,
											dat.return_point ? (
												dat.return_point.Display ? dat.return_point.Display : dat.return_point.Name
												) 
											: dat.Return_Point,
											dat.from_point ? (
												dat.from_point.Display ? dat.from_point.Display : dat.from_point.Name
												) 
											: dat.From_Point,
											dat.StatusID,
											dat.ProcessID,
											dat.Task,
											dat.Type,
											dat.Confirm,
											dat.user_created ? dat.user_created.name : '',
											dat.Time_Created,
											`<button class="btn btn-danger btn-destroy-command `+ editCom + `" id="del-`+dat.ID+`">
												<i class="fas fa-trash"></i>
											</button>`
										]);
									} else 
									{
										table.row.add([
											dat.ID,
											dat.agv ? dat.agv.Name : "Chưa Có AGV Nhận Lệnh",
											dat.lines ? dat.lines.Name : dat.Line_ID,
											dat.return_point ? (
												dat.return_point.Display ? dat.return_point.Display : dat.return_point.Name
												) 
											: dat.Return_Point,
											dat.from_point ? (
												dat.from_point.Display ? dat.from_point.Display : dat.from_point.Name
												) 
											: dat.From_Point,
											dat.StatusID,
											dat.ProcessID,
											dat.Task,
											dat.Type,
											dat.Confirm,
											dat.user_created ? dat.user_created.name : '',
											dat.Time_Created,
											`<button class="btn btn-danger btn-destroy-command `+editCom+`" id="del-`+dat.ID+`">
												<i class="fas fa-trash"></i>
											</button>`
										]).draw();
									}

									j++;

									if (checkBox && (dat.Type == '1' || dat.Type == '2')) 
									{
										$('#btnImport').prop('disabled', false);
										idTrans = dat.ID;
									}

									addClassBtn(dat.ProcessID);

									let time   = parseInt(dat.Estimate);
									let house  = 0;
									let minit  = 0;
									let second = 0;

									if(time >= 3600)
									{
										house      = parseInt(time/3600);
										let timeAg = parseInt(time%3600);
										minit 	   = parseInt(timeAg/60);
										second 	   = parseInt(timeAg%60);
									} else if (time >= 60)
									{
										minit 	   = parseInt(time/60);
										second 	   = parseInt(time%60);
									} else 
									{
										second 	   = parseInt(time);
									}

									let time1;

									if (house != 0) 
									{
										time1 = house+'h:'+minit+'m:'+second+'s';
									} else if (minit != 0) 
									{
										time1 = minit+'m:'+second+'s';
									} else 
									{
										time1 = second+'s';
									}
									// console.log(time1);

									$('#time').text(time1);
								}
							} else 
							{
								table.clear().draw();

								for(dat of data.data)
								{
									// Them chuc nang hien thi thong bao tim kiem agv
									if (!dat.agv) 
									{
										$('#findAgv').removeClass('hide');

										if (counter == limit) 
										{
											$('#findAgv').addClass('hide');
											$('#destroyCommandAgv').removeClass('hide');
										}

										if (active) 
										{
											count();
										}
									} else 
									{
										active = false;
										counter = 1;

										$('#findAgv').addClass('hide');
									}

									table.row.add([
										dat.ID,
										dat.agv ? dat.agv.Name : "Chưa Có AGV Nhận Lệnh",
										dat.lines ? dat.lines.Name : dat.Line_ID,
										dat.return_point ? (
											dat.return_point.Display ? dat.return_point.Display : dat.return_point.Name
											) 
										: dat.Return_Point,
										dat.from_point ? (
											dat.from_point.Display ? dat.from_point.Display : dat.from_point.Name
											) 
										: dat.From_Point,
										dat.StatusID,
										dat.ProcessID,
										dat.Task,
										dat.Type,
										dat.Confirm,
										dat.user_created ? dat.user_created.name : '',
										dat.Time_Created,
										`<button class="btn btn-danger btn-destroy-command `+editCom+`" id="del-`+dat.ID+`">
											<i class="fas fa-trash"></i>
										</button>`
									]).draw();

									if (checkBox && (dat.Type == '1' || dat.Type == '2')) 
									{
										$('#btnImport').prop('disabled', false);
										idTrans = dat.ID;
									}

									addClassBtn(dat.ProcessID);

									let time   = parseInt(dat.Estimate);
									let house  = 0;
									let minit  = 0;
									let second = 0;

									if(time >= 3600)
									{
										house      = parseInt(time/3600);
										let timeAg = parseInt(time%3600);
										minit 	   = parseInt(timeAg/60);
										second 	   = parseInt(timeAg%60);
									} else if (time >= 60)
									{
										minit 	   = parseInt(time/60);
										second 	   = parseInt(time%60);
									} else 
									{
										second 	   = parseInt(time);
									}

									let time1;

									if (house != 0) 
									{
										time1 = house+'h:'+minit+'m:'+second+'s';
									} else if (minit != 0) 
									{
										time1 = minit+'m:'+second+'s';
									} else 
									{
										time1 = second+'s';
									}
									// console.log(time1);

									$('#time').text(time1);
								}
							}
							
						}
						
					}

					setOut = setTimeout(function(){
						findTrans();
					}, 7000);
				}
			}).fail(function(err) {
				console.log(err);
			});
		}

		listPID().done(function(data) {
			data.status ? pid = data.data : '';

			$('#staticBackdrop').modal('hide');

			load = true;
			// console.log(pid);
			for(dat of data.data)
			{
				if (dat.materials) 
				{
					materials.push(dat.materials);

					if (dat.materials.warehouse) 
					{
						for(ware of dat.materials.warehouse)
						{
							let check = warehouse.filter(function(e) {
								return e.ID == ware.ID;
							});

							if (check.length == '0') 
							{
								warehouse.push(ware);
							}

							// warehouseID.push(ware.ID);
						}

						// warehouseID = [...new Set(warehouseID)];
					}
				}
			}

			done = true;

		}).fail(function(err) {
			console.log(err);
		});

		function addOptionSelect1(dataAdd1)
		{
			$('#alertPidW').addClass('hide');

			if (dataAdd1.length == 0) 
			{
				$('#alertPidW').removeClass('hide');

				return false;
			}

			let index = 0;

			for(dat of dataAdd1)
			{
				$('#warehouseSelect1').append(`
					<option value=`+dat.ID+`>`+dat.Name+`</option>
				`);

				index++;
			}
		}

		function addOptionSelect2(dataAdd2)
		{
			$('#alertCodeW').addClass('hide');

			if (dataAdd2.length == 0) 
			{
				$('#alertCodeW').removeClass('hide');

				return false;
			}

			let index = 0;

			for(dat of dataAdd2)
			{
				$('#warehouseSelect2').append(`
					<option value=`+dat.ID+`>`+dat.Name+`</option>
				`);

				index++;
			}
		}

		function runTime()
		{
			let timeout;

			timeout = setTimeout(function() {
				clearTimeout(timeout);
				if (done) 
				{
					done = false;

					let send = {
						'_token': $('meta[name="csrf-token"]').attr('content'),
						'id'    : warehouseID
					};

					listWarehouse(send).done(function(data) {
						if (data.status) {
							for(dat of data.data)
							{
								warehouse.push(dat);
							}
						}

						$('#warehouseSelect1 option').remove();
						$('#warehouseSelect2 option').remove();

						let index = 0;

						for(dat of warehouse)
						{
							if (index == 0) 
							{
								$('#warehouseSelect1').append(`
									<option value="0">{{__('Choose')}} {{__('Position')}}</option>
								`);

								$('#warehouseSelect2').append(`
									<option value="0">{{__('Choose')}} {{__('Position')}}</option>
								`);
							}

							$('#warehouseSelect1').append(`
								<option value=`+dat.ID+`>`+dat.Name+`</option>
							`);

							$('#warehouseSelect2').append(`
								<option value=`+dat.ID+`>`+dat.Name+`</option>
							`);

							index++;
						}
					}).fail(function(err) {
						console.log(err)
					});
				} else 
				{
					runTime();
				}

			}, 100);
		}

		listBox().done(function(data) {
			boxs = data.data;
		}).fail(function(err) {
			console.log(err);
		});

		runTime();
		removeStyle();
		calculateCard();

		$('.btn-add-shelves').on('click', function() {
			$('.shelves').append(`
				<div class="col-12 row">
					<div class ="form-group col-8 inp-`+i+` my-shelves" >
		            	<input type="text" class="form-control" name="ShelvesID">
		            </div>
		            <div class ="form-group col-3 inp-`+i+`" >
		            	<input type="number" min="0" class="form-control" name="Quantity">
		            </div>
		            <div class ="form-group col-1 inp-`+i+`" >
		            	<button type="button" id ="delete-`+i+`" class="btn btn-danger btn-delete" >
		            		<i class="fas fa-trash"></i>
		            	</button>
		            </div>
		            <div class="alert alert-danger col-md-12 hide alert-shelves" role="alert">
		            	Không Có Mã Thùng Thỏa Mãn!
					</div>
				</div>
			`);

			i++;
			removeStyle();
			calculateCard();
		});

		function resetInput()
		{
			$('#pid').val('');
			$('#code').val('');
			$('#warehouseSelect1').val(0);
			$('#warehouseSelect2').val(0);
			$('#box').val('');
			$('.my-shelves').val('');
			$('input[type="number"]').val('');
			$('#alertPid').addClass('hide');
			$('#alertCode').addClass('hide');
			$('#warehouseSelect1 option').remove();
			$('#warehouseSelect2 option').remove();

			$('.import-create').addClass('hide');
			$('.import-command').addClass('hide');
			$('.export-command').addClass('hide');
		}

		$('#btn-1').on('click', function() { // Nhap va Xuat
			resetInput();
			$('.import-create').removeClass('hide');
			$('.import-command').removeClass('hide');
			$('.export-command').removeClass('hide');
			removeStyle();
			calculateCard();
			setTimeout(function() { lineCom(); }, 300);
		});

		$('#btn-2').on('click', function() { // Nhap
			resetInput();
			$('.import-create').removeClass('hide');
			$('.import-command').removeClass('hide');
			removeStyle();
			calculateCard();
			setTimeout(function() { lineCom(); }, 300);
		});

		$('#btn-3').on('click', function() { // Xuat
			resetInput();
			$('.import-create').addClass('hide');
			$('.export-command').removeClass('hide');
			removeStyle();
			calculateCard();
			setTimeout(function() { lineCom(); }, 300);
		});

		$('#btn-4').on('click', function() { // Chuyen Kho
			resetInput();
			// $('.import-create').removeClass('hide');
			$('.import-command').removeClass('hide');
			$('.export-command').removeClass('hide');
			removeStyle();
			calculateCard();
			setTimeout(function() { lineCom(); }, 300);
		});

		$('#lineCommand').on('keyup', function(e) {
			if (e.which == 13) 
			{
				$('#btnSearch').click();
			}
		});

		$('#btnSearch').on('click', function() {
			$('#alertSearch').addClass('hide');
			
			findTrans();
		});

		if ($('#lineCommand').val() != '')
		{
			$('#btnSearch').click();
		} else
		{
			let set;

			set = setInterval(function()
			{
				$('#btnSearch').click();

				if ($('#lineCommand').val() != '') 
				{
					clearInterval(set);
				}

			}, 3000);
		}

		$('.btn-confirm').on('click', function() {
			$('.btn-confirm').prop('disabled', true);

			let dataSend = {
				'_token' : $('meta[name="csrf-token"]').attr('content'),
				'id'	 : command,
			}

			destroyTrans(dataSend).done(function(data) {

				$('#modalDestroyCommand').modal('hide');
				idTrans = 0;
				
				Toast.fire({
					icon : 'warning',
					text  : data.data
				});

				table
		        .row( $('#del-'+command).parent().parent() )
		        .remove()
		        .draw();

		        command = 0;

		        if (table.data().count() == 0) 
		        {
		        	$('#btnImport').prop('disabled', false);
					$('#btnCreate').prop('disabled', false);
					$('#findAgv').addClass('hide');
		        }

			}).fail(function(err) {
				console.log(err);
			});
		});

		let out;

		// Create New Command
		$('#pid').on('keyup', function(e) 
		{
			let check = $('#pid').val();
			find      = [];
			let down  = true;

			// $('#warehouseSelect1').val(0).select2();
			// $('#warehouseSelect1 option[value="0"]').prop('disabled', false).select2();

			$('#alertPid').addClass('hide');
			// $('#warehouseSelect1').select2().next().hide();

			clearTimeout(out);

			$('#warehouseSelect1').children().remove().select2();

			out = setTimeout(function(){
				removeStyle();
				calculateCard();
			}, timeDelay);

			if (e.which == 13 || down) 
			{
				clearTimeout(out);
				out = setTimeout(function()
				{
					let newCheck = check.split('-');
					let show     = true;
					
					if(check == '') return false;

					removeStyle();

					$('#warehouseSelect1').select2().next().show();

					setTimeout(function(){
						removeStyle();
						calculateCard();
					}, timeDelay);
					
					for (let i = newCheck.length; i >= newCheck.length; i--) {
						// if (i != newCheck.length) 
						// {
						// 	newCheck.splice(-1,1);
						// }

						str  = newCheck.join('-').toLowerCase();
						find = [];

						if (load) 
						{
							find = pid.filter(function(e) 
							{
								if(!e.materials)
								{
									return false;
								}

								return 	e.materials.Symbols.trim().toLowerCase() == str ||
										e.materials.Symbols.trim().toLowerCase()+'-'+e.Lot_Number.toLowerCase() == str
							});
						}
						
						// console.log(find);
						if (find.length != 0)
						{
							let dataAdd1 = [];
							let dataId   = [];
							// $('#warehouseSelect1 option').prop('disabled', true).select2();

							// if (find[0].materials.warehouse.length != 0) 
							// {
							// 	$('#warehouseSelect1').val(find[0].materials.warehouse[0].ID).select2();
							// } else 
							// {
							// 	$('#warehouseSelect1').val(0).select2();
							// 	$('#warehouseSelect1 option[value="0"]').prop('disabled', false).select2();
							// }

							for(fin of find)
							{
								for(ware of fin.materials.warehouse)
								{
									if (dataId.indexOf(ware.ID) == '-1') 
									{
										dataId.push(ware.ID);
										dataAdd1.push(ware);
									}
									// $('#warehouseSelect1 option[value="'+ware.ID+'"]').prop('disabled', false).select2(); 
								}
							}

							addOptionSelect1(dataAdd1);

							show = false;

							break;
						}
					}

					if (show) 
					{
						$('#warehouseSelect1').val(0).select2();
						$('#warehouseSelect1 option[value="0"]').prop('disabled', false).select2();
						$('#alertPid').removeClass('hide');
					}
				}, timeDelay);
			}
		});

		// Create Import
		$('#code').on('keyup', function(e) {
			let check = $('#code').val();
			find1     = [];
			let down  = true;

			$('#warehouseSelect2').children().remove().select2();

			// $('#warehouseSelect2').val(0).select2();
			// $('#warehouseSelect2 option[value="0"]').prop('disabled', false).select2();


			$('#alertCode').addClass('hide');
			// $('#warehouseSelect2').select2().next().hide();

			clearTimeout(out);

			out = setTimeout(function() {
				removeStyle();
				calculateCard();
			}, timeDelay);

			if (e.which == 13 || down) 
			{
				clearTimeout(out);

				out = setTimeout(function() 
				{
					let newCheck = check.split('-');
					let show     = true;
					let str;
					
					if(check == '') return false;

					removeStyle();

					$('#warehouseSelect2').select2().next().show();
					// console.log('sa');
					setTimeout(function(){
						removeStyle();
						calculateCard();
					}, 300)
					
					for (let i = newCheck.length; i > 0 ; i--) {
						if (i != newCheck.length) 
						{
							newCheck.splice(-1,1);
						}

						str   = newCheck.join('-').toLowerCase();
						find1 = [];

						if(load)
						{
							find1 = pid.filter(function(e) 
							{
								if(!e.materials)
								{
									return false;
								}
								
								return 	e.materials.Symbols.trim().toLowerCase() == str ||
										e.materials.Symbols.trim().toLowerCase()+'-'+e.Lot_Number.toLowerCase() == str
							});
						}
						

						if (find1.length != 0)
						{
							let dataAdd2 = [];
							let dataId2  = [];
							// $('#warehouseSelect2 option').prop('disabled', true).select2();

							// if (find1[0].materials.warehouse.length != 0) 
							// {
							// 	$('#warehouseSelect2').val(find1[0].materials.warehouse[0].ID).select2();
							// } else 
							// {
							// 	$('#warehouseSelect2').val(0).select2();
							// 	$('#warehouseSelect2 option[value="0"]').prop('disabled', false).select2();
							// }

							for(fin of find1)
							{
								for(ware of fin.materials.warehouse)
								{
									if (dataId2.indexOf(ware.ID) == '-1') 
									{
										dataId2.push(ware.ID);
										dataAdd2.push(ware);
									}
									// $('#warehouseSelect2 option[value="'+ware.ID+'"]').prop('disabled', false).select2();
								}
							}

							addOptionSelect2(dataAdd2);

							show = false;

							break;
						}
					}

					if (show) 
					{
						$('#warehouseSelect2').val(0).select2();
						$('#warehouseSelect2 option[value="0"]').prop('disabled', false).select2();
						$('#alertCode').removeClass('hide');
					}
				}, timeDelay);
			}
		});

		$('.btn-type-tran').on('click', function() {
			$('.btn-type-tran').addClass('btn-light');
			$('.btn-type-tran').removeClass('btn-success');

			$(this).removeClass('btn-light');
			$(this).addClass('btn-success');

			find  = [];
			find1 = [];
		});

		$('#btnCreate').on('click', function() {

			$('#btnCreate').prop('disabled', true);

			let type  = $('input[type="radio"]:checked').val();
			let idBtn = 'id-0';

			$('.btn-type-tran').each(function(e){
				if($(this).hasClass('btn-success'))
				{
					idBtn = $(this).attr('id');
					
					return true;
				}
			});

			let typeTrans = idBtn.split('-').pop();

			let dataSend  = {
				'_token'         : $('meta[name="csrf-token"]').attr('content'),
				'line'           : lineId,
				'typeProduct'    : type,
				'fromPoint'      : find.length != 0 ? find[0].ID : '',
				'warehouseFrom'  : $('#warehouseSelect1').val(),
				'returnPoint'    : find1.length != 0 ? find1[0].ID : '',
				'warehouseReturn': $('#warehouseSelect2').val(),
				'typeTrans'		 : typeTrans
			}

			// console.log(dataSend);

			if (dataSend.line == '0') 
			{
				Toast.fire({
					icon : 'warning',
					title : 'Nhập Line Hoặc Nhấn Tìm Kiếm Để Tìm Line'
				});

				$('#btnCreate').prop('disabled', false);

				return false;
			}

			if (typeTrans == '0') 
			{
				Toast.fire({
					icon : 'warning',
					title : 'Chọn Kiểu Tạo Lệnh!'
				});

				$('#btnCreate').prop('disabled', false);

				return false;
			} else if (typeTrans == '1' || typeTrans == '4') // Nhap va xuat
			{
				if (
						dataSend.returnPoint     == ''   || 
						dataSend.warehouseReturn == null || 
						dataSend.warehouseReturn == '0'  ||
						dataSend.fromPoint       == ''   || 
						dataSend.warehouseFrom   == null ||
						dataSend.warehouseFrom   == '0'
					) {
					Toast.fire({
						icon  : 'warning',
						title : 'Nhập Sản Phẩm Và Chọn Vị Trí Cần Nhập Và Xuất Kho!'
					});

					$('#btnCreate').prop('disabled', false);

					return false;
				}
			} else if (typeTrans == '2') // Nhap kho
			{
				fromPoint     = '' ;
				warehouseFrom = null

				if (
					dataSend.returnPoint     == ''   || 
					dataSend.warehouseReturn == null || 
					dataSend.warehouseReturn == '0'
				) {
					Toast.fire({
						icon  : 'warning',
						title : 'Nhập Sản Phẩm Và Chọn Vị Trí Cần Nhập Kho!'
					});

					$('#btnCreate').prop('disabled', false);

					return false;
				}
			} else if (typeTrans== '3') // Xuat kho
			{
				returnPoint     = '';
				warehouseReturn = null;

				if (
					dataSend.fromPoint     == ''   || 
					dataSend.warehouseFrom == null ||
					dataSend.warehouseFrom == '0'
				) {
					Toast.fire({
						icon : 'warning',
						title : 'Nhập Sản Phẩm Và Chọn Vị Trí Cần Xuất Kho!'
					});

					$('#btnCreate').prop('disabled', false);

					return false;
				}
			}

			console.log(dataSend);

			clearTimeout(setOut);

			let myBtn = setInterval(function() {
				$('#btnCreate').prop('disabled', true);
			}, 100);

			createTrans(dataSend).done(function(data) 
			{
				clearInterval(myBtn);

				if (data.status) 
				{
					let dataPut = {
						'_token' : $('meta[name="csrf-token"]').attr('content'),
						'pid'	 : $('#pid').val().trim().toUpperCase(),
						'from'	 : $('#warehouseSelect1').val(),
						'code'	 : $('#code').val().trim().toUpperCase(),
						'to'	 : $('#warehouseSelect2').val(),
						'radio'	 : $('input[type="radio"]:checked').val(),
						'command': data.data.ID,
					}

					putSession(dataPut).done(function(da){console.log(da)}).fail(function(er){console.log(er)});

					dat     = data.data;
					active  = false;
					counter = 1;
					idTrans = data.data.ID;

					// Them chuc nang hien thi thong bao tim kiem agv
					if (!dat.agv) 
					{
						$('#findAgv').removeClass('hide');

						if (counter == limit) 
						{
							$('#findAgv').addClass('hide');
							$('#destroyCommandAgv').removeClass('hide');
						}

						count();
					} else 
					{
						$('#findAgv').addClass('hide');
					}

					table.row.add([
						dat.ID,
						dat.agv ? dat.agv.Name : "Chưa Có AGV Nhận Lệnh",
						dat.lines ? dat.lines.Name : dat.Line_ID,
						dat.return_point ? (
							dat.return_point.Display ? dat.return_point.Display : dat.return_point.Name
							) 
						: dat.Return_Point,
						dat.from_point ? (
							dat.from_point.Display ? dat.from_point.Display : dat.from_point.Name
							) 
						: dat.From_Point,
						dat.StatusID,
						dat.ProcessID,
						dat.Task,
						dat.Type,
						dat.Confirm,
						dat.user_created ? dat.user_created.name : '',
						dat.Time_Created,
						`<button class="btn btn-danger btn-destroy-command `+editCom+`" id="del-`+dat.ID+`">
							<i class="fas fa-trash"></i>
						</button>`
					]).draw();

					Toast.fire({
						icon : 'success',
						title : data.message
					});

					findTrans();
				} else 
				{					
					$('#btnCreate').prop('disabled', false);

					Toast.fire({
						icon : 'warning',
						title : data.message
					});
				}
				
			}).fail(function(err) {
				clearInterval(myBtn);

				console.log(err);
			});
			console.log(dataSend);
		});
		// Command Import Warehouse
		$('#box').on('keyup', function(e) {
			$('#alertBox').addClass('hide');
			let down = true;

			if (e.which == 13 || down) // an Enter
			{
				clearTimeout(out);

				out = setTimeout(function() 
				{
					let value = $('#box').val().trim().toLowerCase();
					checkBox  = true;

					$('#alertBox').removeClass('hide');
					$('#btnImport').prop('disabled', true);

					for(let i = 0; i < boxs.length; i++)
					{
						let box     = boxs[i];
						let symbols = box.Symbols.trim().toLowerCase();
						checkBox    = false;

						if (symbols == value) 
						{
							$('#alertBox').addClass('hide');
							$('#btnImport').prop('disabled', false);

							boxId    = box.ID;
							checkBox = true;
							break;
						}
					}
				}, timeDelay);
			}
		});

		let swatShow = true;

		$('#btnImport').on('click', function() 
		{
			$(this).prop('disabled', true);

			let typeIm = $('#typeImport').val();
			swatShow   = true;

			let dataImport  = {
				'_token'             : $('meta[name="csrf-token"]').attr('content'),
				'Warehouse_Detail_ID': lineId,
				'Shleves'			 : boxId,
				'Label_ID'           : [],
				'Materials_ID'       : [],
				'Quantity'			 : [],
				'Command'			 : idTrans,
			}

			if(dataImport.Command == '0')
			{
				Toast.fire({
					icon : 'warning',
					title: "Không Có Lệnh Để Nhập Kho!"
				});
			} else 
			{
				let arrCheckLabel = [];

				$('.my-shelves').parent().each(function(e) {
					let label  = $(this).children().children('input[type="text"]').val().trim().toLowerCase();
					let qty    = $(this).children().children('input[type="number"]').val().trim();

					if (label != '' && qty != '') 
					{
						arrCheckLabel.push(label);

						for(let i = 0; i < pid.length; i++)
						{
							let pi      = pid[i];

							let sym     = pi.materials ? pi.materials.Symbols : 'Null';

							let symbols = sym.trim().toLowerCase()+'-'+pi.Lot_Number.trim().toLowerCase();

							if (symbols == label) 
							{
								dataImport.Label_ID.push(pi.ID);
								dataImport.Materials_ID.push(pi.materials.ID);
								dataImport.Quantity.push(qty);

								break;
							}
						}
					}
				});

				// console.log(dataImport);

				if (dataImport.Label_ID.length == dataImport.Quantity.length && arrCheckLabel.length == dataImport.Label_ID.length) 
				{
					importWarehouse(dataImport).done(function(data) {
						if(data.status)
						{
							Toast.fire({
								icon : 'success',
								title : data.message
							});
						} else 
						{
							Toast.fire({
								icon : 'warning',
								title : data.message
							});
						}
						
						$('#btnImport').prop('disabled', false);

					}).fail(function(err) {
						console.log(err);

						Toast.fire({
							icon : 'warning',
							title : data.message
						});
					});
				} else 
				{
					Toast.fire({
						icon : 'warning',
						title : 'Không Có Thùng Thỏa Mãn'
					});
				}
			}

		});

		// Khoa cac vi tri khong co du lieu
		$('#lineCommand').on('keyup', function() {
			lineCom();
			$('#pid').val('');
			$('#code').val('');
			$('#box').val('');
			$('.my-shelves input').val('');
			$('input[type="number"]').val('');
		});

		lineCom();

		$('#pid').on('keyup', function() 
		{
			if ($('#pid').val() == '')
			{
				$('#code').attr('disabled', true);
				$('#box').attr('disabled', true);
				$('.my-shelves input').attr('disabled', true);
				$('input[type="number"]').attr('disabled', true);
			} else 
			{
				$('#code').attr('disabled', false);
				$('#box').attr('disabled', true);
				$('.my-shelves input').attr('disabled', true);
				$('input[type="number"]').attr('disabled', true);
			}
		});

		$('#code').on('keyup', function() 
		{
			$('#box').val('');
			$('.my-shelves input').val('');
			$('input[type="number"]').val('');

			if ($('#code').val() == '')
			{
				$('#box').attr('disabled', true);
				$('.my-shelves input').attr('disabled', true);
				$('input[type="number"]').attr('disabled', true);
			} else 
			{
				$('#box').attr('disabled', false);
				$('.my-shelves input').attr('disabled', true);
				$('input[type="number"]').attr('disabled', true);
			}
		});

		$('#box').on('keyup', function() 
		{
			if (swatShow) 
			{
				$('.my-shelves input').each(function() 
				{
					if ($(this).val() != '') 
					{
						Swal.fire({
							icon: 'warning',
							title: "Bạn Có Muốn Xóa Thùng Đang Có",
							showCloseButton: true,
					  		showCancelButton: true,
					  		confirmButtonColor: '#d33',
					  		confirmButtonText: __button.delete,
					  		cancelButtonText: __calculate.cancel,
						}).then((result) => {
							// console.log(result);
						 	if (result.value) {
							    $('.my-shelves input').val('');
								$('input[type="number"]').val('');
							}
						});

						swatShow = false;

						return false;
					}
				})
			}
			
			if ($('#box').val() == '')
			{
				$('.my-shelves input').attr('disabled', true);
				$('input[type="number"]').attr('disabled', true);
			} else 
			{
				$('.my-shelves input').attr('disabled', false);
				$('input[type="number"]').attr('disabled', false);
			}
		});

		$('.btn-delete-command').on('click', function() {
			$(this).parent()
			.parent()
			.children('.card-body')
			.children('div')
			.children('input')
			.val('');
		});

		$('.btn-delete-import').on('click', function() {
			$(this).parent()
			.parent()
			.children('.card-body')
			.children('div')
			.children('input')
			.val('');
		});

		$(document).on('keyup', '.my-shelves', function(e) {
			$('.alert-shelves').removeClass('hide');
			$('.alert-shelves').addClass('hide');
			let down = true;
			let my 	 = $(this);
			swatShow = true;

			if (e.which == 13 || down) 
			{
				clearTimeout(out);

				out = setTimeout(function() 
				{
					let value      = my.children('input').val().trim().toLowerCase();

					for(let i = 0; i < pid.length; i++)
					{
						let pi      = pid[i];
						let sym     = pi.materials ? pi.materials.Symbols : 'Null';
						let symbols = sym.trim().toLowerCase()+'-'+pi.Lot_Number.trim().toLowerCase();

						if (symbols == value) 
						{
							$('#btnImport').prop('disabled', false);
							$(this).parent().children('.alert').addClass('hide');

							break;
						} else 
						{
							$('#btnImport').prop('disabled', true);
							$(this).parent().children('.alert').removeClass('hide');
						}
					}
				}, timeDelay);
			}		
		});

		$(document).on('click', '.btn-delete', function() {
			let parent      = $(this).parent().attr('class');
			let classRemove = parent.split(' ').pop();

			$('.'+classRemove).remove();

			removeStyle();
			calculateCard();

			if (classRemove.split('-').pop() == i) 
			{
				i--;
			}
		});

		$(document).on('click', '.btn-destroy-command', function() {
			let id  = $(this).attr('id');
			command = 0;
			command = id.split('-').pop();

			$('#modalDestroyCommand').modal();
			$('.btn-confirm').prop('disabled', false);
		});
		// Auto Xuong Dong Sau Khi Enter
		function autoEnter(typ)
		{
			if($('#pid').val().length == '0' && (typ == '1' || typ == '3' || typ == '4')) 
			{
				$('#pid').select();
			} else if ($('#code').val().length == '0' && (typ == '1' || typ == '2' || typ == '4'))
			{
				$('#code').select();
			} else if ($('#box').val().length == '0' && (typ == '1' || typ == '2'))
			{
				$('#box').select();
			} else if (typ == '1' || typ == '2')
			{
				$('.col-12.row').not('.hide').each(function() {
					let tex = $(this).children('div').children('input[type="text"]').val();
					let num = $(this).children('div').children('input[type="number"]').val();
					if(tex.length == '0')
					{
						$(this).children('div').children('input[type="text"]').select();
						return false;
					} else if(num.length == '0')
					{
						$(this).children('div').children('input[type="number"]').select();
						return false;
					}
				});
			}
		}

		$(document).on('keyup', 'input.form-control', function(e) {
			if (e.which == 13) 
			{
				if(!$(this).hasClass('form-control-sm'))
				{
					// console.log($(this));
					let idBtn = 'id-0';

					$('.btn-type-tran').each(function(e)
					{
						if($(this).hasClass('btn-success'))
						{
							idBtn = $(this).attr('id');
							
							return true;
						}
					});
						
					let bt = idBtn.split('-').pop();

					// console.log(bt);

					if(bt != 0)
					{
						switch (bt)
						{
							case '1' :
								autoEnter(1);
								break;
							case '2' :
								autoEnter(2);
								break;
							case '3' :
								autoEnter(3);
								break;
							case '4' :
								autoEnter(4);
								break;
						}
					}
				}
			}
		});
	</script>
@endpush