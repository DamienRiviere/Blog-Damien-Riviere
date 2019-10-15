$(document).on("click", ".delete-article", function () {
    let id = $(this).data('id');
    $('.modal-delete-article').attr('data-id', +id);
});

$(document).on("click", ".modal-delete-article", function () {
    let id = $(this).data('id');
    $('.modal-delete-article').attr('href','/admin/post/delete/'+id);
});
