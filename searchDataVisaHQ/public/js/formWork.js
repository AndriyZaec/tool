/**
 * Created by andrij on 30.07.15.
 */

function createForm(){
    var count = $("button[id^='Remove']").length;
    count++;
    $("#newForms").append('<button type="button" class="btn btn-danger" onclick="deleteForm('+count+')" id="Remove'+count+'"><span class="glyphicon glyphicon-remove-circle"></span></button>'
        +'<div class="form-inline">'
        + '<textarea class="form-control" id="Domains'+count+'" name="Domains'+count+'" placeholder="Domains..." style="width: 20%;height: 20%;resize: none" ></textarea> '
        + '<textarea class="form-control" id="Robots'+count+'" name="Robots'+count+'" placeholder="Robots..." style="width: 20%;height: 20%;resize: none"></textarea>'
        + '</div>'
        + '<br/><br/> '
    );
}function deleteForm(num){
    $('#Domains'+num).remove();
    $('#Robots'+num).remove();
    $('#Remove'+num).remove();
}
