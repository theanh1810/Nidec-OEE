$('.select2').select2()
import t from "../lang"
var route = `${window.location.origin}/api/productionplan/command`;
var route_show = `${window.location.origin}/production-plan/detail`;

import getConnection from '../oee/socket';
const socket = getConnection()

const table = $('table').DataTable({
    scrollX: true,
    // infoCallback: ( settings, start, end, max, total, pre ) => {
    //     console.log({settings, start, end, max, total, pre})
    // },
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
    dom: 'rt<"bottom"flp><"clear">',
    lengthMenu: [10, 15, 20, 25, 50],
    ajax: {
        url: route,
        dataSrc: 'data',
        data: d => {
            d.page = (d.start / d.length) + 1
            d.symbols = $('.symbols').val()
            d.name = $('.name').val()
            d.month = $('#Month').val()
            d.year = $('#Year').val()
        }
    },
    columns: [
        { data: 'ID', defaultContent: '', title: 'ID' },
        { data: 'Name', defaultContent: '', title: t('Name') },
        { data: 'Symbols', defaultContent: '', title: t('Symbols') },
        { data: 'Month', defaultContent: '', title: t('Month') },
        { data: 'Year', defaultContent: '', title: t('Year') },
        { data: 'Note', defaultContent: '', title: t('Note') },
        { data: 'user_created.username', defaultContent: '', title: t('User Created') },
        { data: 'Time_Created', defaultContent: '', title: t('Time Created') },
        { data: 'user_updated.username', defaultContent: '', title: t('User Updated') },
        { data: 'Time_Updated', defaultContent: '', title: t('Time Updated') },
        {
            data: 'ID',
            title: t('Action'),
            render: function(data) {
                return `
            <a href="` + route_show + `?ID=` + data + `" class="btn btn-success" style="width: 80px">
            ` + t('Detail') + `
            </a>
            <button id="del-` + data + `" class="btn btn-danger btn-delete" style="width: 80px">
            ` + t('Delete') + `
			</button>
            `;
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
    $('#modalRequestDel').modal();
    $('#nameDel').text(col1);
    $('#idDel').val(id.split('-')[1]);
    $(".modal-footer > .btn-danger").on('click',() => {
        socket.emit("refresh-plan", { plan_id: id.split("-")[1] });
    });
});
