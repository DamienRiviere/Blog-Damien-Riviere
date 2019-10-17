$(document).on("click", ".delete-link", function () {
    let id = $(this).data('id');
    $('.modal-delete').attr('data-id', +id);
});

$(document).on("click", ".modal-delete", function () {
    let id = $(this).data('id');
    let path = window.location.pathname;

    if(path.includes("posts")) {
        $('.modal-delete').attr('href','/admin/post/delete/'+id);
    } else {
        $('.modal-delete').attr('href','/admin/category/delete/'+id);
    }
});
