(function () {
    let req;
    let link;
    let like;
    let icon = document.getElementById("iconLike");

    if (document.getElementById("likeButton") != null) {
        link = document.getElementById("likeButton");
        like = "like";
    } else {
        link = document.getElementById("unlikeButton");
        like = "unlike";
    }

    function alertContents()
    {
        if (req.readyState === XMLHttpRequest.DONE) {
            if (req.status === 200) {
                console.log(req.responseText);
            } else {
                console.log("Il y a eu un problème avec la requête.");
            }
        }
    }

    function makeRequest()
    {
        let slug = document.getElementById(link.getAttribute("id")).getAttribute("data-slug");
        let postId = document.getElementById(link.getAttribute("id")).getAttribute("data-post-id");
        let userId = document.getElementById(link.getAttribute("id")).getAttribute("data-user-id");

        req = new XMLHttpRequest();

        if (!req) {
            alert("Abandon :( Impossible de créer une instance de XMLHTTP");
            return false;
        }
        req.onreadystatechange = alertContents;
        req.open("GET", "/post/" + postId + "/" + slug + "/" + like + "?post_id=" + postId + "&user_id=" + userId, true);
        req.send();

        if (like === "like") {
            icon.setAttribute("class", "fas fa-thumbs-up");
        } else if (like === "unlike") {
            icon.setAttribute("class", "far fa-thumbs-up");
        }
    }

    if (link != null) {
        link.addEventListener("click", makeRequest);
    }
})();
