$(document).ready(function () {

    var classGlobal = function () {
    };

    classGlobal.prototype.getList = function () {
        $.ajax({
            url: "/app/getitems",
            context: document.body
        }).done(function (response) {
            var res = $.parseJSON(response);
            console.log(res);
            //var arr = [];
            var items = '<table>';
            for (var i in res) {
                var pos = res[i];
                items += '<tr><td>' + res[i] + '</td><td>  <img src="/public/img/remove.png" alt="Remove Item" class="removeItem" removeId="' + i + '"/></td></tr>';
            }
            items += '</table>';
            $('#itemsList').html(items);
        });
    }

    classGlobal.prototype.removeItem = function (id) {        
        
        alert(id);
        $.ajax({
            url: "/app/getitems/remove/id/"+id, 
            context: document.body
        }).done(function (response) {
            var res = $.parseJSON(response);
            console.log(res);
            //var arr = [];
            var items = '<table>';
            for (var i in res) {
                var pos = res[i];
                items += '<tr><td>'+res[i]+'</td><td>  <img src="/public/img/remove.png" alt="Remove Item" class="removeItem" removeId="'+i+'"/></td></tr>';
            }
            items += '</table>';
            $('#itemsList').html(items);
        });
    }

    var objGlobal = new classGlobal();
    objGlobal.getList();   

    $('body').delegate('.removeItem', 'click', function () {    
        var id = $(this).attr('removeId');        
        objGlobal.removeItem(id);
    });

})