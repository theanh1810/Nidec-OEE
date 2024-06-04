$('.select2').select2()
import { defaultsDeep } from "lodash";
import t from "../lang"
var route = `${window.location.origin}/api/control-agv/trans/list-command-api`;
// console.log(route);
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
    ordering: false,
    searching: false,
    dom: 'rt<"bottom"flp><"clear">',
    lengthMenu: [10, 15, 20, 25],
    ajax: {
        url: route,
        dataSrc: 'data',
        data: d => {
            d.page = (d.start / d.length) + 1
            d.agv = $('#agv').val()
            d.machine = $('.machine').val()
            d.status = $('.status').val()
        }
    },
    columns: [
        { data: 'ID', defaultContent: '', title: 'ID' },
        { data: 'agv.Name', defaultContent: '', title: t('AGV') },
        // { data: 'lines.Symbols'        , defaultContent: '' ,title: t('Line')},
        { data: 'StatusID'             , defaultContent: '' ,title: t('Status'),render: function(data){
            if(data == 5) return  t('Destroy');
            if(data == 2) return  t('Success');
            if(data == 1) return  t('Processing');
            if(data == 0) return  t('Find AGV');
            // console.log(1);
        }},
        { data: 'Return_Point', defaultContent: '' ,title: t('Location Take Materials'),render:function(data){
            return t('Warehouse')
        }},
        { data: 'lines.Symbols', defaultContent: '' ,title: t('Materials Return Location')},
        { data: 'user_created.username', defaultContent: '' ,title: t('User Created')},
        { data: 'Time_Created'         , defaultContent: '' ,title: t('Time Start')},
        { data: 'user_updated.username', defaultContent: '' ,title: t('User Updated')},
        { data: 'Time_Updated'         , defaultContent: '' ,title: t('Time End')}, 
        { data: {ID : 'ID',StatusID :'StatusID'},title: t('Action'),render: function (data) 
            {
                if(data.StatusID == 0 || data.StatusID == 1)
                {
                    if(role_delete_Trans)
                    {
                        return `
                        <button id="del-`+data.ID+`" class="btn btn-success btn-success" style="width: 80px">
                        `+ t('Success')+`
                        </button>
                        <button id="del-`+data.ID+`" class="btn btn-danger btn-delete" style="width: 80px">
                        `+ t('Destroy')+`
                        </button>
                        `;
                    }
                    else{
                        return ``
                    }

                } 
                else 
                {
                    if (data.StatusID == 2) {
                        return t('Success');
                    } else {
                        return t('Destroy');
                    }
                }
            }
        }
    ],
})
$('table').on('page.dt', function() {
    console.log(table.page.info())
})

$('.filter').on('click', () => {
    table.ajax.reload()
})

$(document).on('click', '.btn-delete', function() {
    let id = $(this).attr('id');
    // let name = $(this).parent().parent().children('td').first().text();
    var Row = $(this).closest("tr");
    var machine = Row.find("td:eq(4)").text();
    $('#modalRequestDes').modal();
    $('#nameDes').text( t('Command')+' '+t('To')+' '+machine);
    $('#idDes').val(id.split('-')[1]);
});

$(document).on('click', '.btn-success', function() {
    let id = $(this).attr('id');
    // let name = $(this).parent().parent().children('td').first().text();
    var Row = $(this).closest("tr");
    var machine = Row.find("td:eq(4)").text();
    $('#modalRequestSuc').modal();
    $('#nameSuc').text( t('Command')+' '+t('To')+' '+machine);
    $('#idSuc').val(id.split('-')[1]);
});