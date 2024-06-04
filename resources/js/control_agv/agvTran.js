let tableModal = $('#agvChanTable').DataTable({
	'language'    : lang,
	"lengthMenu"  :[50,100,150,"All"],
	scrollY       : "100%",
	scrollX       : true,
	scrollCollapse: true,
	order         : [[ 0, 'desc' ]]
});

function fillterTran(dataFil)
{
	return $.ajax({
		method: 'get',
		url: window.location.origin + '/agv-tran/filter',
		data: dataFil,
		dataType: 'json'
	});
}

setTimeout(function() {tableModal.columns.adjust().draw();}, 500);

$('#reservation').daterangepicker({
	timePicker       : true,
	timePicker24Hour : true,
	timePickerSeconds: true,
	startDate        : moment().startOf('day'),
	endDate          : moment(),
	locale           : {
    format: 'YYYY/MM/DD HH:mm:ss'
  }
});

$('#btnFilter').on('click', function()
{
	tableModal.clear().draw();

	dataFil = {
		'agv_id': $('#productIm').val(),
		'from'  : $('#reservation').val().split('-')[0],
		'to'    : $('#reservation').val().split('-')[1]
	}

	$('#modalLoad').modal();

	fillterTran(dataFil).done(function(data)
	{
		// console.log(data);
		for(let i of data.data)
		{
			tableModal.row.add([
				i.ID,
        i.NAME,
        i.MAPNAME,
        i.ANGLE,
        i.OFFSET,
        i.CODE,
        i.PROCESS,
        i.AGV_ID,
        i.Des,
        i.Pre_code,
        i.STATUS,
        i.GROUP_ID,
        i.Point_ID,
        i.REV,
        i.TARGET_ID,
        i.Sub_code,
        i.OPERATION,
        i.AGV_TYPE,
        i.CREATED_AT,
        i.UPDATED_AT,
			]).draw( false );
		}
		$('#modalLoad').modal('hide');

		setTimeout(function(){$('#modalLoad').modal('hide');}, 1000);
	}).fail(function(err)
	{
		$('#modalLoad').modal('hide');

		console.log(err);
	});
});