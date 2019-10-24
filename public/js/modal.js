$(document).on("click", ".delete-link", function () {
    let id = $(this).data('id');
    let idPost = $(this).data('idPost');
    $('.modal-delete').attr('data-id', +id);
    $('.modal-delete').attr('data-id-post', +idPost);
});

$(document).on("click", ".modal-delete", function () {
    let id = $(this).data('id');
    let idPost = $(this).data('id-post');
    let path = window.location.pathname;

    if(path.includes("posts")) {
        $('.modal-delete').attr('href','/admin/post/delete/'+id);
    } else if (path.includes("categories")) {
        $('.modal-delete').attr('href','/admin/category/delete/'+id);
    } else if (path.includes("roles")) {
        $('.modal-delete').attr('href','/admin/role/delete/'+id);
    } else if (path.includes("users")) {
        $('.modal-delete').attr('href','/admin/user/delete/'+id);
    } else if (path.includes("comments")) {
        $('.modal-delete').attr('href','/admin/post/'+idPost+'/comment/delete/'+id);
    }
});
