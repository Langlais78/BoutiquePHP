$(document).ready(function() {
    $('#table-backoffice').DataTable({
        language: {
            // url: http://localhost/PHP/10-boutique + js/dataTables.french.json
            url: urlSite + 'js/dataTables.french.json'
        },
        "aoColumnDefs": [
            { 'bSortable': false, 'aTargets': [ 3,7,10,11 ] }
        ]
    });

    $('#table-infos-stock').DataTable({
        language: {
            // url: http://localhost/PHP/10-boutique + js/dataTables.french.json
            url: urlSite + 'js/dataTables.french.json'
        },
        "aoColumnDefs": [
            { 'bSortable': false, 'aTargets': [ 0,5 ] }
        ]
    });
});