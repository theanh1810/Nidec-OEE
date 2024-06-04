@extends('layouts.app')
@section('content')
    <div class="card">
        <div class="card-header">
            <span class="text-bold" style="font-size: 23px">
                {{ __('Visualization') }} {{ __('Plan') }}
            </span>
            <button type="button" class="btn btn-tool btn-sm" data-card-widget="maximize"data-animation-speed="100" id="maximize" style="float: right; margin: auto;"><i class="fas fa-expand"></i></button>
                <div class="card-tools">
                    	
                </div>
        </div>
        <div class="card-body ">
            <form action="{{route('kitting.plan.visualization',['ID'=>$ID])}}" method="get">
                <div class ="row">
                    <input type="text" class="form-control d-none"  id="plan_id" name="ID" value="{{$ID}}">
                        <div class="form-group col-md-2" >
                            <label>{{__('Machine')}}</label>
                            <select id="machine" class="form-control select2"  name="machine" >
                                <option value="">
                                    {{__('Choose')}} {{__('Machine')}}
                                </option>
                                @foreach($machines as $machine)
                                    <option value="{{$machine->ID}}" {{old('machine')
                                            ? (old('machine') == $machine->ID ? 'selected' : '') : ($mac ? ($mac == $machine->ID ? 'selected' : '') : '') }}>
                                        {{ $machine->Symbols }}
                                    </option>
                                @endforeach 		
                                </select>
                            </div>
                        <div class="form-group col-md-2" >
                            <label>{{__('Product')}}</label>
                            <select id="product" class="form-control select2" name="product" >
                            <option value="">
                                    {{__('Choose')}} {{__('Product')}}
                                </option>  		
                                @foreach($products as $product)
                                    <option value="{{$product->ID}}" {{old('product')
                                                ? (old('product') == $product->ID ? 'selected' : '') : ($pro ? ($pro == $product->ID ? 'selected' : '') : '') }}>
                                        {{ $product->Symbols }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label>{{__('From')}}</label>
                            <input type="date" class="form-control" id="from" name="from" value="{{$from}}">
                            <span role="alert" class="d-none from-to">
                                <strong style="color: red">
                                      {{__('Choose')}} {{__('Time')}} {{__('From')}} {{__('And')}} {{__('To')}}
                                </strong>
                            </span>
                        </div>
                        <div class="form-group col-md-2">
                            <label>{{__('To')}}</label>
                            <input type="date" class="form-control" id="to" name="to" value="{{$to}}">
                            <span role="alert" class="d-none from-to">
                                <strong style="color: red">
                                      {{__('Choose')}} {{__('Time')}} {{__('From')}} {{__('And')}} {{__('To')}}
                                </strong>
                            </span>
                        </div>
                        <div class="col-2" >
                            <button type="submit" class="btn btn-info">{{__('Filter')}}</button>
                            <!-- <button type="button" class="btn btn-cancel btn-danger">
	                			{{__('Cancel')}} {{__('Command')}}
	                		</button> -->
                        </div>
                </div>
                <br>
                <div class="col-md-6 row">
                        <div class="col-md-12">
                            <button class="btn btn-secondary" style="width: 50px; height: 25px; background:#96C2e3 ""></button>
                                {{__('Quantity')}} {{__('Production')}}
                            <button class="btn btn-success" style="margin-left: 10px; width: 50px; height: 25px; background:  #eff6fb"></button>
                                {{__('Quantity')}} {{__('Plan')}} 
                        </div>
                    </div>
                    <br>
                    <div id='gantt_here' style='width:100%; height:600px; position: relative;'></div>
                </div>
        </form>
        </div>
        <div class="card-footer">
            <a href="{{ route('productionplan.detail', ['ID' => $ID]) }}"" class=" btn btn-info"style="width: 80px">{{ __('Back') }}</a>
        </div>
    </div>
@endsection
@push('scripts')  
    <!-- <script>
        $(document).on('click', '.btn-cancel', function()
        {
            $.ajax({
                method: 'get',
                url: "#",
                dataType: 'json'
            }).done(function(data) {
                let a = ''
                
                $.each( data.data, function( key, value ) {
                    a += `<tr>
                                <td>`+value.ID+`</td>
                                <td>`+value.machine.Name+`</td>
                                <td>`+value.materials.Symbols+`</td>
                                <td>`+value.Quantity_Plan+`</td>
                                <td>`+value.Production_Shift+`</td>
                                <td>
                                    <a href="`+window.location.origin +'/kitting-list/plan/cancel_command_runtime?id='+value.ID+`" class="btn btn-warning" style="width: 80px">
										{{__('Cancel')}}
									</a>
                                </td>
                         </tr>`
                });
                
                $('tbody').append(a)
               
            }).fail(function(err) {
                console.log(err);
            })
            $('#modalRequestCan').modal();
        });
    </script> -->
    <script>
        $('.select2').select2();
        var pro
        var pro1
        var pro2
        var pro3
        var pro4
        // var secondGridColumns = {
		//     columns: 
        //     [
        //         {
        //             name: "status", label: "Status", width: 1, align: "center", template: function (task) {
        //                 var progress = task.progress || 0;
        //                 return Math.floor(progress * 100) + "";
        //             }
        //         },
        //         {
        //             name: "impact", width: 1, label: "Impact", template: function (task) {
        //                 return (task.duration * 1000).toLocaleString("en-US", {style: 'currency', currency: 'USD'});
        //             }
        //         }
        //     ]
        // };
        gantt.plugins({
		    marker: true
        });

        var dateToStr = gantt.date.date_to_str(gantt.config.task_date);
        var today = new Date();
        console.log(today)
        gantt.addMarker({
            start_date: today,
            css: "today",
            text: "Today",
            title: "Today: " + dateToStr(today)
        });
        // gantt.config.work_time = true;
        // gantt.config.duration_unit = "hour";
        gantt.config.scale_height = 30;
        gantt.config.columns=[
        {name:"quan",label:"{{__('Machine')}}",tree:true,width: 300,},
        {name:"text",label:"{{__('Output')}}",tree:false,width:100,},
        
	    ];
        // gantt.config.scales = [
        // {unit: "hour", step: 1, format: "%g %a"},
		// {unit: "hour", step: 1, format: "%g %a"},
		// {unit: "day", step: 1, format: "%j %F, %l"},
		// {unit: "minute", step: 1, format: "%i"}
	    // ];
        // gantt.config.layout = {
        //     css: "gantt_container",
        //     rows: [
        //         {
        //             cols: [
        //                 {view: "grid", width: 300, scrollY: "scrollVer"},
                        
        //                 {view: "timeline", scrollX: "scrollHor", scrollY: "scrollVer"},
                        
        //                 {view: "grid", width: 1, bind: "task", scrollY: "scrollVer", config: secondGridColumns},
        //                 {view: "scrollbar", id: "scrollVer"}
        //             ]
        //         },
        //         {view: "scrollbar", id: " ", height: 50}
        //     ]
        // };
        var hourFormatter = gantt.ext.formatters.durationFormatter({
            enter: "hour", 
            store: "minute", 
            format: "hour",
            short: true	
        });
        gantt.config.lightbox.sections = [
            {name: "description", height: 70, map_to: "text", type: "textarea", focus: true},
            {name: "time", type: "duration", map_to: "auto", formatter: hourFormatter}
        ];
        function toggle_grid() {
            gantt.config.show_grid = !gantt.config.show_grid;
            gantt.render()
        }
        function toggle_chart() {
            gantt.config.show_chart = !gantt.config.show_chart;
            gantt.render()
        }
       
        gantt.init('gantt_here');
        // gantt.parse({
		// 	data: [
		// 		{ id: 13, text: "1000/1000",quan:"M11-05-J75EII(M1-13)",start_date: "08-06-2022", type: "project",  progress: 0.1, open: true },
		// 		{ id: 17, quan: "FE8-3262",text:"1000/1000", start_date: "08-06-2022", duration: "2", parent: "13",type: "project", render:"split", progress: 1, open: true },
        //         { id: 18, quan: "FE8-3262",text:"1000/1000", start_date: "08-06-2022", duration: "1", parent: "17", progress: 1, open: true },
		// 		{ id: 19, quan: "FE8-3023",text:"800/1000", start_date: "10-06-2022", duration: "1", parent: "17", progress: 0, open: true },
        //         { id: 20, quan: "FE8-3023",text:"0/1000", start_date: "11-06-2022", duration: "1", parent: "17", progress: 0, open: true },
                

		// 		// { id: 26, quan: "M14-02-TOSHIBA 75T-220610-12",text:"1000/20000", start_date: "08-06-2022", type: "project",  progress: 0.1, open: true },
		// 		// { id: 27, quan: "20401-001700A000",text:"1000/1000", start_date: "10-06-2022", duration: "1", parent: "26", progress: 1, open: true },
        //         // { id: 29, quan: "M14-02-TOSHIBA 75T-220610-10",text:"0/1000", type: "project",  progress: 0, open: true  },
        //         // { id: 30, quan: "20401-001700A000",text:"800/1000", start_date: "13-06-2022", duration: "1", parent: "29", progress: 0, open: true },
        //         // { id: 31, quan: "M14-02-TOSHIBA 75T-220610-11",text:"0/1000",  type: "project",  progress: 0, open: true },
        //         // { id: 32, quan: "20401-001700A000",text:"800/1000", start_date: "14-06-2022", duration: "1", parent: "31", progress: 0, open: true },
        //         // { id: 33, quan: "M14-02-TOSHIBA 75T-220610-9",text:"0/1000",  type: "project", progress: 0, open: true },
        //         // { id: 34, quan: "20401-001700A000",text:"0/1000", start_date: "15-06-2022", duration: "1", parent: "33", progress: 0, open: true },
		// 	]
		// });
        function get_data(dataGet) {
            return $.ajax({
                method: 'get',
                url: "{{route('kitting.plan.visualization.data')}}",
                data: dataGet,
                dataType: 'json'
            });
        }
        function checkData() {
            dataGet = {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'plan_id': {{ $ID }},
                'machine': '{{$mac}}',  
                'product': '{{$pro}}',
                'from': '{{$from}}',
                'to': '{{$to}}' ,
            }
           
            get_data(dataGet).done(function(data) {
                task1 = {
                    'data': data.data
                };
                gantt.parse(task1);
            }).fail(function(err) {
                console.log(err);
            })
        }
        setInterval(checkData, 2000);
    </script>
@endpush