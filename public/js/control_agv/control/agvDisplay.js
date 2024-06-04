function runtime()
{
	$('#reservation').daterangepicker({
		timePicker: true,
		timePicker24Hour: true,
		timePickerSeconds: true,
	    startDate: moment().subtract(1, 'days'),
	    endDate: moment(),
	    locale: {
	      format: 'YYYY/MM/DD HH:mm:ss'
	    }
	});
}
runtime();

function pin(layout) 
{	
	// return $.ajax({
	// 	method: 'get',
	// 	url: window.location.origin + '/agv-control/agv-info',
	// 	// cache: true,
	// 	data: {'layout': layout},
	// 	dataType: 'json'
	// });
}

function ratioAgv(dataRatio) 
{
	let ratioAgv = $.ajax({
		method: 'get',
		url: window.location.origin + '/agv-control/ratio',
		cache: false,
		data: dataRatio,
		dataType: 'json'
	});

	return ratioAgv;
}

function agvLog(dataLog) 
{
	let agvLog = $.ajax({
		method: 'get',
		url: window.location.origin + '/agv-control/agv-log',
		cache: false,
		data: dataLog,
		dataType: 'json'
	});

	return agvLog;
}

function detail(id, from, to)
{
	tableModal.clear().draw();
	for (let i = 1; i <= $('#numAgv').text(); i++) {
		if (i != id) 
		{
			$('#agv'+i).hide();
		} else {
			$('#agv'+i).show();
		}
	}
	$('#showDetailAgv').hide();
	$('#chartRatio').hide();
	$('#logAgv').hide();
	$('#modalDetail').modal();
	$('#loadingDetail').show();
	dataRatio = {
		"_token": $('meta[name="csrf-token"]').attr('content'),
		'agv_id': id,
		'from'  : from,
		'to'  : to,
	}
	$('.my-modal-body').css('height', $(window).height() - 217);
	agvLog(dataRatio).done(function(dataL) 
	{
		for (let i = 0; i < dataL.data.length; i++) 
		{
	    	tableModal.row.add([
	    		i+1,
	    		// dataL.data[i].Agv_name,
	    		dataL.data[i].Device_error,
	    		dataL.data[i].Move_error,
	    		dataL.data[i].Perform_error,
	    		dataL.data[i].Tran,
	    		dataL.data[i].Status_agv,
	    		dataL.data[i].TIME
	    	]).draw( false );
	    }
	}).fail(function(er) 
	{
		console.log(er);
		$('#loadingDetail').hide();
	});
	ratioAgv(dataRatio).done(function(data) 
	{
			$('#showDetailAgv').show();
			$('#chartRatio').show();
			$('#logAgv').show();
			$('#loadingDetail').hide();
			$('#runTime').text('RUN TIME: ' +data.data[0].RUNTIME);
			$('#ideTime').text('IDE TIME: ' +data.data[0].IDETIME);
			$('#errorTime').text('ERROR TIME: ' +data.data[0].ERRORTIME);
			$('#runTime').css('color', '#00a65a');
			$('#ideTime').css('color', '#f39c12');
			$('#errorTime').css('color', '#f56954');
			let pieData   = {
				 	labels: [
				      'RUNTIME', 
				      'IDETIME',
				      'ERRORTIME', 
				  ],
				  datasets: [
				    {
				      data: [data.data[0].RUNTIME,data.data[0].IDETIME,data.data[0].ERRORTIME],
				      backgroundColor : ['#00a65a', '#f39c12', '#f56954'],
				    }
				  ]
			}
		    //Create pie or douhnut chart
		    // You can switch between pie and douhnut using the method below.
		    let pieChart = new Chart(pieChartCanvas, {
		      type: 'pie',
		      data: pieData,
		      options: pieOptions      
		    })
			tableModal.columns.adjust().draw();
	}).fail(function(err)
	{
		console.log(err);
		$('#loadingDetail').hide();
	});
}

function loading(id)
{
	return $.ajax({
		method: 'get',
		url: window.location.origin + '/agv-tran/show',
		data: {'id': id},
		dataType: 'json'
	});
}

function tran(dataInsertTran)
{
	return $.ajax({
		mehtod: 'get',
		url: window.location.origin + '/agv-tran/insert',
		data: dataInsertTran,
		dataType: 'json'
	});
}

function destroyTran(dataDestroy)
{
	return $.ajax({
		method: 'get',
		url: window.location.origin + '/agv-tran/destroy',
		data: dataDestroy,
		dataType: 'json'
	});
}

function activeAgv(dataUp) 
{
	let upOrIn = $.ajax({
		method: 'get',
		url: window.location.origin + '/setting-agv/active',
		data: dataUp,
		dataType: 'json'
	});

	return upOrIn;
}

let lengthDataL = 0;
let pieChartCanvas = $('#pieChart').get(0).getContext('2d')
let pieOptions     = {
  maintainAspectRatio : false,
  responsive : true,
}

let tableModal = $('#tableModalDetail').DataTable({
	'language': lang,
	scrollY: "60vh",
    scrollX: true,
    scrollCollapse: true,
	searching: false,
	lengthChange: false,
	info: false,
	bPaginate: false
});

let tableTran = $('#tranTable').DataTable({
	'language': lang,
	scrollY: "25vh",
    scrollX: true,
    scrollCollapse: true,
	searching: false,
	lengthChange: false,
	info: false,
	bPaginate: false
});

$('.detail').on('click', function() 
{
	let id = Number($(this).attr('id').split('detail')[1]);
	runtime();
	detail(id, $('#reservation').val().split('-')[0], $('#reservation').val().split('-')[1]);
});

$('#filter').on('click', function()
{
	for (let i = 1; i <= $('#numAgv').text(); i++) {
		if ($('#agv'+i).css('display') != 'none' && $('#agv'+i).length) 
		{
			detail(i, $('#reservation').val().split('-')[0], $('#reservation').val().split('-')[1]);
		}
	}
});

// Loading
$('.loading').on('click', function()
{
	let id = $(this).attr('id').split('loading')[1];
	$('#idAgvTran').text(id);
	$('#modalLoading').modal();
	$('#imgLoading').show();
	$('#loading').hide();
	$('.my-type').hide();
	$('#loadingLoad').hide();
	$('.operation').val(0);
	// $('.precode').val(0);
	$('.load-tran').val(0);
	$('#tranPoint').text('');
	$('#tranAgv').text('');
	$('#pointErr').hide();
	$('#precodeErr').hide();
	
	tableTran.clear();
	// $('#tranPrecode').text('');
	// $('#tranSuf').text('');
	console.log(id);
	loading(id).done(function(data)
	{
		console.log(data);
		$('#imgLoading').hide();
		$('#loading').show();
		$('.type-0').show();
		$('.type-'+$('#text-type'+id).text()).show();
		$('.point-type-'+$('#text-type'+id).text()+'.layout'+$('#layoutAgv').val()).show();
		console.log('.point-type-'+$('#text-type'+id).text()+'.layout'+$('#layoutAgv').val());
		if (data.data != null) 
		{			
			for (let i = 0; i < data.data.length; i++) 
			{
				tableTran.row.add([
					data.data[i].AGV_ID,
					data.data[i].NAME,
					data.data[i].Des,
					'<button class="btn btn-danger destroy" id="desTran'+data.data[i].ID+'"><i class="fas fa-times-circle"></i> Hủy Lệnh</button> '
				]);
			}
			
			$('#tranPoint').text(data.data.NAME);
			$('#tranAgv').text(data.data.Des);
			// $('#tranPrecode').text(data.data.Pre_code);
			// $('#tranSuf').text(data.data.Sub_code);
			setTimeout(function() {tableTran.columns.adjust().draw();}, 300);
		}		
	}).fail(function(err)
	{
		console.log(err);
	});
});

$('#tran').on('click', function()
{
	// console.log($('.precode').val(), $('.sufcode').val());
	dataInsertTran = {
		"_token": $('meta[name="csrf-token"]').attr('content'),
		'target': $('.load-tran').val(),
		'agv_id': $('#idAgvTran').text(),
		'operation': $('.operation').val(),
	}
	if ($('.load-tran').val() == '0' && $('.operation').val() == '0') 
	{
		$('#pointErr').show();
		$('#precodeErr').show();
	} else if ($('.load-tran').val() == '0') 
	{
		$('#pointErr').show();
		if ($('.operation').val() != '0') 
		{
			$('#precodeErr').hide();			
		}
	} else if ($('.operation').val() == '0') 
	{
		$('#precodeErr').show();
		if ($('.load-tran').val() != '0') 
		{
			$('#pointErr').hide();			
		}
	} else
	{
		$('#loadingLoad').show();
		// console.log(dataInsertTran);
		tran(dataInsertTran).done(function(data)
		{

			$('#loadingLoad').hide();
			$('#modalLoading').modal('hide');
			// console.log(data);
		}).fail(function(err)
		{
			console.log(err);
		});
	}
});

$('#tranTable tbody').on('click', '.destroy', function()
{
	// console.log($(this).attr('id'));
	$('#modalDelTran').modal();
	let id = $(this).attr('id').split('desTran')[1];

	let position = $(this).parents('tr');

	$('#destroyTranSave').on('click', function()
	{
		dataDestroy = {
			'id' : id
		}
		destroyTran(dataDestroy).done(function(data)
		{
			tableTran.row(position).remove().draw();

			$('#modalDelTran').modal('hide');
			$('#desTran'+id).hide();
			$('#sucTran'+id).show();
			// console.log(data);
		}).fail(function(err)
		{
			console.log(err);
		});
	});
});

$(document).on('click', '.my-btn-info', function()
{
	let id = $(this).attr('id').split('active-agv')[1];
	// console.log(id);
	dataUpdate = {
		'id': id,
		'actived' : 1
	}

	activeAgv(dataUpdate).done(function(data)
	{
		$('#card-display'+data.data.ID).hide();	
		// console.log(data);
	}).fail(function(err)
	{
		// console.log(err);
	});

});


