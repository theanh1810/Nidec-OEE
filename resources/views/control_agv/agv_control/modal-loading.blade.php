<div class="modal fade" id="modalLoading" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
	    <div class="modal-content">
	      	<div class="modal-header">
	      		{{__('Tran')}}
	      	</div>
	      	<div class="modal-body">
	      		<div id="loadingLoad" style="display: none;">
  	  				<x-my-alert type='success' text='{{__("Loading")}}...'/>
	        	</div>
	        	<div id="imgLoading" style="display: none;">
	        		<x-my-alert type='success' text='{{__("Loading")}}...'/>
	        	</div>
	        	<div id="loading">
	        		<div id="idAgvTran" style="display: none"></div>
	        		<div>
	        			<div>
	        				<h2><span>Lệnh đang thực hiện</span></h2>
	        			</div>
	        			<div>
	        				<table id="tranTable" class="table table-bordered table-striped" width="100%">
					            <thead>
					            <tr>
					              <th>AGV ID</th>
					              <th>{{__('From')}}</th>
					              <th>{{__('Process')}}</th>
					              <th>{{__('Tác vụ')}}</th>
					            </tr>
					            </thead>
					            <tbody>					            	
					            </tbody>
					          </table>
	        			</div>	        			
	        		</div>
	        		{{--<div>
	        			<br>
	        			<h2>Tạo lệnh mới</h2>
	        		</div>
					<div class="form-group col-sm-5" style="float: left;">
						<label for="mapName">{{__('Tên vị trí')}}
							@include('control_agv.agv_control.select-point', ['class' => 'load-tran'])						
					    </label>
					</div>
					<div class="form-group col-sm-5" style="float: left;">
					    <label for="angle">{{__('Nhiệm vụ')}}
							<i class="fas fa-times-circle"  id="errPrecode" style="display:none; color:red"></i>
							@include('control_agv.agv_control.select-operation', ['class' => 'operation'])
					    </label>
					</div>
					<div class="form-group col-sm-3" style="float: left;">
					    <label for="offset">{{__('Sufcode')}}
							<i class="fas fa-times-circle"  id="errSufcode" style="display:none; color:red"></i>	
							@include('control_agv.agv_control.select-operation', ['class' => 'sufcode'])
					    </label>
					</div>
					<div class="form-group col-sm-2" style="float: left; margin-top: 25px">
						<button class="btn btn-success" id="tran"><i class="fas fa-globe"></i> {{__('Tạo lệnh')}}</button>
					</div>--}}
				</div>
	      	</div>
	      	<div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times-circle"></i>
		        	{{__('Close')}}
		        </button>
		    </div>
	    </div>
  	</div>
</div>