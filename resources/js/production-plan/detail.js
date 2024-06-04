$('.select2').select2()
import t from "../lang"
import getConnection from '../oee/socket'
var route = `${window.location.origin}/api/productionplan/detail`;
var route_export = `${window.location.origin}/production-plan/detail/export-materials`;
var route_his = `${window.location.origin}/api/productionplan/detail/history`;
const socket = getConnection()

var arr_status = []
jQuery("input[name='statusId']").each(function() {

    if (this.checked) {
        arr_status.push(this.value)
    }
});
const table = $('.table-plan-detail').DataTable({
    scrollX: true,
    // infoCallback: ( settings, start, end, max, total, pre ) => {
    //     console.log({settings, start, end, max, total, pre})
    // },
    language: __languages.table,
    aaSorting: [],
    language: {
        lengthMenu: t('Number of records _MENU_'),
        info: '',
        paginate: {
            previous: '‹',
            next: '›'
        },
    },
    processing: true,
    serverSide: true,
    searching: false,
    dom: 'rt<"bottom"flp><"clear">',
    // dom: '<"top"i>rt<"bottom"flp><"clear">',
    lengthMenu: [10, 20, 30, 40, 50],
    ajax: {
        url: route,
        dataSrc: 'data',
        data: d => {
            d.page = (d.start / d.length) + 1
            d.planId = $('#idPlan').val()
            d.product = $('.product').val()
            d.machine = $('.machine').val()
            d.status = arr_status
            d.from = $('#from').val()
            d.to = $('#to').val()

        }
    },
    columns: [{
            data: 'Symbols',
            defaultContent: '',
            title: t('Symbols Plan'),
            render: function(data) {
                if (data) {
                    let explode = data.split("--");
                    // console.log(explode);
                    return `<p>` + explode[0] + `</p><p>-` + explode[1] + `-` + explode[2] + `</p>`
                } else {
                    return ``;
                }

            }
        },
        { data: 'machine.Name', defaultContent: '', title: t('Machine') },
        { data: 'mold.Name', defaultContent: '', title: t('Mold') },
        { data: 'product.Name', defaultContent: '', title: t('Product') },
        { data: 'Date', defaultContent: '', title: t('Date') },

        {
            data: { id: 'ID', status: 'Status' },
            defaultContent: '',
            title: t('Output'),
            render: function(data) {
                return (data.Quantity_Production ?
                    parseFloat(data.Quantity_Production) : 0) + ` / ` + parseFloat(data.Quantity);
            }
        },
        {
            data: 'Quantity_Error',
            defaultContent: '',
            title: t('Quantity') + ' ' + t('Error'),
            render: function(data) {
                return parseFloat(data ? data : 0);
            }
        },
        {
            data: 'Time_Real_Start',
            defaultContent: '',
            title: t('Start'),
            render: function(data) {
                if (data) {
                    let explode = data.split(" ");
                    // console.log(explode);
                    return `<p>` + explode[1] + `</p><p>` + explode[0] + `</p>`
                } else {
                    return ``;
                }

            }
        },
        {
            data: 'Time_Real_End',
            defaultContent: '',
            title: t('End'),
            render: function(data) {
                if (data) {
                    let explode = data.split(" ");
                    // console.log(explode);
                    return `<p>` + explode[1] + `</p><p>` + explode[0] + `</p>`
                } else {
                    return ``;
                }

            }
        },
        { data: 'Version', defaultContent: '', title: t('Version') },
        { data: 'His', defaultContent: '', title: t('His') },
        {
            data: 'Status',
            title: t('Status'),
            render: function(data) {
                if (data == 0) {
                    return `<p style="color:red">` + t('Dont Production') + `</p>`;
                } else if (data == 1) {
                    return `<p style="color:Blue">` + t('Are Production') + `</p>`;
                } else if (data == 3) {
                    return `<p style="color:Blue">` + `Tạm dừng` + `</p>`;
                } else {
                    return `<p style="color:green">` + t('Success') + `</p>`;
                }

            }
        }, {
            data: { id: 'ID', status: 'Status' },
            title: t('Action'),
            render: function(data) {
                if (data.Status == 0) {
                    // return `
                    //     <button type="button" id="edit-` + data.ID + `"  class="btn btn-info btn-edit" >
                    //     ` + t('Edit') + `
                    //     </button>
                    //     <button id="del-` + data.ID + `"  class="btn btn-danger btn-delete" >
                    //     ` + t('Delete') + `
                    //     </button>
                    // `;
                    let bt = ``;
                    if (role_edit_plan) {
                        bt = bt + `
                        <button type="button" id="edit-` + data.ID + `"  class="btn btn-info btn-edit" >
                        ` + t('Edit') + `
                        </button>`
                    }
                    if (role_delete_plan) {
                        bt = bt + `  <button id="del-` + data.ID + `"  class="btn btn-danger btn-delete" >
                        ` + t('Delete') + `
                        </button>
                        `;
                    }
                    return bt;
                } else if (data.Status == 1) {
                    if (role_end_plan) {
                        return `
                        <button id="end-` + data.ID + `"  class="btn btn-warning btn-end" style="width: 110px">
                        ` + t('End') + `
                        </button>
                        `;
                    }

                } else {
                    return `

                    `;
                }

            }
        }
    ],
    rowsGroup: [0, 1, 2, 4, 11],
})
$('table').on('page.dt', function() {
    console.log(table.page.info())
})
$('.filter').on('click', () => {
    arr_status = []
    jQuery("input[name='statusId']").each(function() {

        if (this.checked) {
            arr_status.push(this.value)
        }
    });
    table.ajax.reload()
})
$('.list_status').on('change', () => {
    arr_status = []
    jQuery("input[name='statusId']").each(function() {

        if (this.checked) {
            arr_status.push(this.value)
        }
    });
    console.log(arr_status)
    table.ajax.reload()
})
$(document).on('click', '.btn-delete', function() {
    let id = $(this).attr('id');
    let name = $(this).parent().parent().children('td').first().text();

    $('#modalRequestDel').modal();
    $('#nameDel').text(name);
    $('#idDel').val(id.split('-')[1]);
    
    $(".modal-footer > .btn-danger").on("click", () => {
        socket.emit("refresh-plan", { plan_id: $("#idPlan").val() });
    });
});
$('.btn-import').on('click', function() {
    $('#modalImport').modal();
    $('#importFile').val('');
    $('.input-text').text(__input.file);
    $('.error-file').hide();
    $('.btn-save-file').prop('disabled', false);
    $('#product_id').val('');

});
let check_file = false;
$('#importFile').on('change', function() {

    let val = $(this).val();
    let name = val.split('\\').pop();
    let typeFile = name.split('.').pop().toLowerCase();
    $('.input-text').text(name);
    $('.error-file').hide();

    if (typeFile != 'xlsx' && typeFile != 'xls') {
        $('.error-file').show();
        $('.btn-save-file').prop('disabled', true);
    } else {
        $('.btn-save-file').prop('disabled', false);
        check_file = true;
    }
});
$('.btn-save-file').on('click', function() {
    $('.error-file').hide();

    if (check_file) {
        $('.btn-submit-file').click();
        socket.emit('refresh-plan', {plan_id:$('#idPlan').val()});
    } else {
        $('.error-file').show();
    }
});
$(document).on('click', '.btn-detail', function() {
    let id = $(this).attr('id');
    let name = $(this).parent().parent().children('td').first().text();

    $('#modalTableDetail').modal();
    $('#idUnit').val(id.split('-')[1]);
    tablehis.ajax.reload()
    $('#nameDel').text(name);

});


$(document).on('click', '.btn-end', function() {
    let id = $(this).attr('id');
    let name = $(this).parent().parent().children('td').first().text();

    $('#modalend').modal();
    $('#idEnd').val(id.split('-')[1]);
});
$(document).on('click', '.btn-edit', function() {
    $(`#Product option:selected`).attr('selected', false);
    $(`#Machine option:selected`).attr('selected', false);
    $(`#Mold option:selected`).attr('selected', false);
    $(`#Quantity`).val('');
    $(`#Date`).val('');
    $(`#Version`).val('');
    $(`#His`).val('');
    var currentRow = $(this).closest("tr");
    var col1 = currentRow.find("td:eq(1)").text();
    var col2 = currentRow.find("td:eq(2)").text();
    var col3 = currentRow.find("td:eq(3)").text();
    var col4 = currentRow.find("td:eq(4)").text();
    var col5 = currentRow.find("td:eq(5)").text();
    var col9 = currentRow.find("td:eq(9)").text();
    var col10 = currentRow.find("td:eq(10)").text();
    let id = $(this).attr('id');
    console.log(col1, col2, col3, col4, col5, col9, col10);
    let explode = col5.split("/");
    console.log(explode);
    $('#modalAddOrUpdate').modal();
    $(`#Product option:contains(` + col3 + `)`).attr('selected', 'selected');
    $(`#Machine option:contains(` + col1 + `)`).attr('selected', 'selected');
    $(`#Mold option:contains(` + col2 + `)`).attr('selected', 'selected');
    $(`#Quantity`).val(Number(explode[1]));
    $(`#Date`).val(col4);
    $(`#Version`).val(col9);
    $(`#His`).val(col10);
    $('#idDetail').val(id.split('-')[1]);
});
$(document).on('click', '.btn-create', function() {
    $('#modalAddOrUpdate').modal();
    $(`#Product`).val('');
    $(`#Machine`).val('');
    $(`#Mold`).val('');
    $(`#Quantity`).val('');
    $(`#Date`).val('');
    $(`#Version`).val('');
    $(`#His`).val('');
    $('#idDetail').val('');
});
var idplan = 0;
const tablehis = $('.table-his').DataTable({
    scrollX: true,
    searching: false,
    ordering: false,
    language: {
        lengthMenu: t('Number of records _MENU_'),
        info: t('Showing _START_ to _END_ of _TOTAL_ entries'),
        paginate: {
            previous: '‹',
            next: '›'
        },
    },
    processing: true,
    serverSide: true,
    searching: false,
    dom: 'rt<"bottom"flp><"clear">',
    lengthMenu: [5, 10, 20, 30, 40],
    ajax: {
        url: route_his,
        dataSrc: 'data',
        data: d => {
            delete d.columns
            delete d.order
            delete d.search
            d.page = (d.start / d.length) + 1
            d.idplan = idplan
        }
    },
    columns: [
        { data: 'ID', defaultContent: '', title: 'ID' },
        {
            data: 'Data_Table',
            defaultContent: '',
            title: t('Symbols'),
            render: function(data) {
                var datas = JSON.parse(data)
                let explode = datas.Symbols.split("--");
                return `<p>` + explode[0] + `</p><p>-` + explode[1] + `-` + explode[2] + `</p>`
            }
        },
        {
            data: 'Data_Table',
            defaultContent: '',
            title: t('Machine'),
            render: function(data) {
                var datas = JSON.parse(data)
                return datas.Part_Action
            }
        },
        {
            data: 'Data_Table',
            defaultContent: '',
            title: t('Mold'),
            render: function(data) {
                var datas = JSON.parse(data)
                return datas.Mold_ID
            }
        },
        {
            data: 'Data_Table',
            defaultContent: '',
            title: t('Product'),
            render: function(data) {
                var datas = JSON.parse(data)
                return datas.Product_ID
            }
        },
        {
            data: 'Data_Table',
            defaultContent: '',
            title: t('Date'),
            render: function(data) {
                var datas = JSON.parse(data)
                return datas.Date
            }
        },
        {
            data: 'Data_Table',
            defaultContent: '',
            title: t('Quantity'),
            render: function(data) {
                var datas = JSON.parse(data)
                return datas.Quantity
            }
        },
        {
            data: 'Data_Table',
            defaultContent: '',
            title: t('Note'),
            render: function(data) {
                var datas = JSON.parse(data)
                return datas.Note
            }
        },
        {
            data: 'Action_Name',
            defaultContent: '',
            title: t('Action Name'),
            render: function(data) {
                return t(data)
            }
        },
        {
            data: 'Data_Table',
            defaultContent: '',
            title: t('User Updated'),
            render: function(data) {
                var datas = JSON.parse(data)
                return datas.User_Updated
            }
        },
        {
            data: 'Data_Table',
            defaultContent: '',
            title: t('Time Updated'),
            render: function(data) {
                var datas = JSON.parse(data)
                var timecreated = moment(datas.Time_Updated, 'MMMM Do YYYY h:mm:ss a').format('YYYY-MM-DD HH:mm:ss')
                return timecreated
            }
        },
    ]
})
$(document).on('click', '.btn-history', function() {
    let id = $(this).attr('id');
    let name = $(this).parent().parent().children('td').first().text();

    $('#modalTableHistory').modal();
    $('#idUnit').val(id.split('-')[1]);
    idplan = id.split('-')[1];
    tablehis.ajax.reload()
    $('#nameDel').text(name);

});
socket.on('start-plan', (message) => {
    table.ajax.reload()
});
socket.on('end-plan', (message) => {
    table.ajax.reload()
});
