$('.select2').select2()
import t from "../lang"
var route = `${window.location.origin}/api/warehouse-system/export`;
var route_show = `${window.location.origin}/setting/setting-unit/show`;
console.log(route);
const table = $('table').DataTable({
    scrollX: true,
    aaSorting: [],
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
    lengthMenu: [10, 15, 20, 25, 50],
    dom: 'rt<"bottom"flp><"clear">',
    ajax: {
        url: route,
        dataSrc: 'data',
        data: d => {
            d.page = (d.start / d.length) + 1
            d.materials = $('.materials').val()
            d.machine = $('.machine').val()
            d.status = $('.status').val()
            d.type = $('.Type').val()
        }
    },
    columns: [
        { data: 'ID', defaultContent: '', title: 'ID', },
        {
            data: 'plan.Symbols',
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
        {
            data: 'product.Symbols',
            defaultContent: '',
            title: t('Product'),
            render: function(data) {
                // console.log(data)
                return `<p>` + data + `</p>`;
            }
        },
        { data: 'machine.Name', defaultContent: '', title: t('Machine') },
        {
            data: 'Quantity_Export',
            defaultContent: '',
            title: t('Quantity') + ' ' + t('Export') + '(Kg)',
            render: function(data) {
                // console.log(data)
                if (data) {
                    return `<p>` + parseFloat(data) + `</p>`;
                } else {
                    return 0;
                }

            }
        },
        {
            data: 'Type',
            title: t('Type'),
            render: function(data, status) {
                if (data == 1) {
                    return t('AGV');
                } else
                if (data == 2) {
                    return t('Normal');
                } else {
                    return ``;
                }
            }
        },
        {
            data: 'Note',
            title: t('Note'),
            render: function(data) {
                return t(data);
            }
        },
        { data: 'user_updated.username', defaultContent: '', title: t('User Updated') },
        { data: 'Time_Updated', defaultContent: '', title: t('Time Updated') },
        {
            data: 'Status',
            title: t('Status'),
            render: function(data, status) {
                if (data == 0) {
                    return `<p style="color:red">` + t('Dont Export') + `</p>`;
                } else
                if (data == 1) {
                    return `<p style="color:blue">` + t('Are Export') + '(' + t('Waiting for AGV') + ')' + `</p>`;
                } else if (data == 2) {
                    return `<p style="color:blue">` + t('Are Export') + '(' + t('AGV Shipping') + ')' + `</p>`;
                } else {
                    return `<p style="color:green">` + t('Success Export') + `</p>`;
                }
            }
        },
        {
            data: { id: 'ID', status: 'Status' },
            title: t('Action'),
            render: function(data) {
                if (data.Status == 0) {
                    return `
                    <button id="ex-` + data.ID + `" class="btn btn-success btn-export" >
                    ` + t('Export') + `
                    </button>
                    <button id="del-` + data.ID + `" class="btn btn-danger btn-delete" >
                    ` + t('Cancel') + `
                    </button>
                `;
                } else if (data.Status == 1 && data.Type == 2) {
                    return `<button id="suc-` + data.ID + `" class="btn btn-success btn-success" >
                    ` + t('Success') + `
                    </button>`
                } else {
                    return ``;
                }
            }
        }
    ]
})
$('table').on('page.dt', function() {
    console.log(table.page.info())
})
$('.filter').on('click', () => {
    table.ajax.reload()
})
$(document).on('click', '.btn-delete', function() {
    let id = $(this).attr('id');
    let name = $(this).parent().parent().children('td').first().text();
    var currentRow = $(this).closest("tr");
    var col1 = currentRow.find("td:eq(1)").text();
    $('#modalRequestCan').modal();
    $('#nameDel').text(col1);
    $('#idCan').val(id.split('-')[1]);
});
$(document).on('click', '.btn-export', function() {
    let id = $(this).attr('id');
    var currentRow = $(this).closest("tr");
    var col1 = currentRow.find("td:eq(0)").html();
    var col2 = currentRow.find("td:eq(2)").text();
    var col3 = currentRow.find("td:eq(3)").text();
    let name = $(this).parent().parent().children('td').first().text();
    $('#modalAddOrUpdate').modal();
    $('#Machine').val(col3);
    $('#Materials').val(col2);
    $('#idex').val(id.split('-')[1]);
});
let mater = 0
socket.on('export-materials', (message) => {
    mater++;
    $('#modal_alert_export').modal();
    $('#alert').text('Có ' + mater + ' NVL cần Xuất');
    table.ajax.reload()
});

socket.on('update-status-agv', (message) => {
    console.log(message);
    table.ajax.reload()
});