$(document).ready(function () {
    var row_id = $.vars.row_id,
            username = $.vars.username,
            table = $.vars.table;

    $.ajax({
        type: 'POST',
        url: "exec/comment_exec.php",
        data: {
            action: 'select',
            tbl: table,
            row_id: row_id
        }
    }).done(function (comments) {
        var data = $.parseJSON(comments);
        var i;
        for (i = 0; i < data.length; i++) {
            var d_id = data[i].comment_id;
            var d_username = data[i].username;
            var d_comment = data[i].comment;
            var d_date = data[i].date;

            var td = $("#tdComment").children("td");
            for (var x = 0; x < td.length; x++) {
                var current = $(td[x]);
                if (current.hasClass('comment')) {
                    current.html('<font color="#4d688f">' + d_username + '</font>: ' + d_comment + '<br>\n\
    <font size="2">' + d_date + '</font>');
                }
                if (current.hasClass('action')) {
                    if (username === d_username) {
                        current.html('<img class="delete" id="' + d_id + '" src="images/delete.png" onclick="btn_delete(this.id);"></a>');
                    } else {
                        current.html('');
                    }
                }
            }
            $("#tdComment").clone(true, false).insertBefore($("#tdComment")).removeClass('hide').removeAttr('id').attr("id", d_id);
        }
    });

    $("#btn_submit").click(function () {
        if ($("#comment").val() !== '') {
            var comment = $("#comment").val();

            $.ajax({
                type: 'POST',
                url: "exec/comment_exec.php",
                data: {
                    action: 'insert',
                    tbl: table,
                    row_id: row_id,
                    username: username,
                    comment: comment
                }
            }).done(function (id) {
                var td = $("#tdComment").children("td");
                for (var x = 0; x < td.length; x++) {
                    var current = $(td[x]);
                    if (current.hasClass('comment')) {
                        current.html('<font color="#4d688f">' + username + '</font>: ' + comment);
                    }
                    if (current.hasClass('action')) {
                        if (username === username) {
                            current.html('<img class="delete" id="' + id + '" src="images/delete.png" onclick="btn_delete(this.id);"></a>');
                        }
                        else {
                            current.html('');
                        }
                    }
                }
                $("#tdComment").clone(true, false).insertBefore($("#tdComment")).removeClass('hide').removeAttr('id').attr("id", id);

                $("#comment").val('');
            });
        }
    });

});

function btn_delete(id) {
    var r = confirm("Are you sure you want to delete this comment?");
    if (r === true) {
        $.ajax({
            url: "exec/comment_exec.php",
            type: 'POST',
            data: {
                action: 'delete',
                comment_id: id
            }
        }).done(function () {
            $("#" + id).hide();
        });
    }
}


