$("#deleteButton").click(function(){
    var data = $(this).data('info').split(',');
    $("#deleteFileId").val(data[0]);
    $("#deleteFileName").text(data[1]);
    $('#deleteModal').modal('show');
  });
