    <!-- Modal -->
<div class="modal fade" id="modal-hosts-add" tabindex="-1" aria-labelledby="exampleModalLongTitle" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Add Hosts</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <b><u>Hosts To Add</u></b>
            <div class="text-center mb-2 mt-2">
                <i class="fas fa-info-circle text-info"></i>Autodiscovery is attempted on hosts in a cluster!
            </div>
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2">
                <button class="btn btn-sm pull-right btn-primary" id="addBox">
                    <i class="fa fa-plus"></i>
                </button>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="showPasswordCheck">
                  <label class="form-check-label" for="showPasswordCheck">
                    Show Passwords
                  </label>
                </div>
            </div>
            <div class="mt-2" id="inputBoxes"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="addHosts">Add</button>
      </div>
    </div>
  </div>
</div>
<script>

let inputTemplate = `<div class="input-group mb-3 serverGroup">
    <input placeholder="ip / hostname"  name="connectDetails" class="form-control" autocomplete="off"/>
    <input placeholder="trust password" name="trustPassword"  type="password" class="form-control trustPasswordInput" autocomplete="off"/>
    <input placeholder="alias" name="alias"  class="form-control" autocomplete="off"/>
    <button class="btn btn-danger removeRow" type="button"><i class="fa fa-trash"></i></button>
</div>`;

$("#modal-hosts-add").on("shown.bs.modal", function(){
    $("#inputBoxes").empty().append(inputTemplate);
});

$("#modal-hosts-add").on("change", "#showPasswordCheck", function(){
    if($(this).is(":checked")){
        $("#modal-hosts-add").find(".trustPasswordInput").attr("type", "text");
    }else{
        $("#modal-hosts-add").find(".trustPasswordInput").attr("type", "password");
    }
});

$("#modal-hosts-add").on("click", "#addBox", function(){
    $("#inputBoxes").append(inputTemplate);
})

$("#modal-hosts-add").on("click", ".removeRow", function(){
    $(this).parents(".input-group").remove();
});

$("#modal-hosts-add").on("click", "#addHosts", function(){
    let serverGroups = $(".serverGroup");
    let details = {
        hostsDetails: []
    };
    serverGroups.map(function(){

        let connectDetailsInput = $(this).find("input[name=connectDetails]");
        let trustPasswordInput = $(this).find("input[name=trustPassword]");

        let connectDetailsInputVal = connectDetailsInput.val();
        let trustPasswordInputVal = trustPasswordInput.val();

        let alias = $(this).find("input[name=alias]").val();

        if(connectDetailsInputVal == ""){
            connectDetailsInput.focus();
            toastr["error"]("Please provide connection details");
            return false;
        } else if(trustPasswordInputVal == ""){
            trustPasswordInput.focus();
            toastr["error"]("Please provide trust provide");
            return false;
        }

        details.hostsDetails.push({
            name: connectDetailsInputVal,
            trustPassword: trustPasswordInputVal,
            alias: alias
        });
    });

    ajaxRequest("/api/Hosts/AddHostsController/add", details, function(data){
        let x = makeToastr(data);
        if(x.state == "error"){
            return false;
        }
        location.reload();
    });
});
</script>
