<?php

namespace App\Controller;

use App\Helpers\CommentHelpers;
use App\Model\Like;
use App\Repository\LikeRepository;
use App\Repository\PostRepository;
use App\Repository\CommentRepository;
use App\Repository\CategoryRepository;

class PostController extends Controller
{
    private $post;

    private $category;

    private $like;

    private $commentHelpers;

    public function __construct()
    {
        $this->post = new PostRepository();
        $this->category = new CategoryRepository();
        $this->comment = new CommentRepository();
        $this->like = new LikeRepository();
        $this->commentHelpers = new CommentHelpers();
        parent::__construct();
    }

    public function posts(): void
    {
        $this->twig->display(
            'post/index.html.twig',
            [
                'posts' => $this->post->findPostsPaginated()[0],
                'pagination' => $this->post->findPostsPaginated()[1],
                'categories' => $this->category->all()
            ]
        );
    }

    public function show($id): void
    {
        $this->twig->display(
            'post/show.html.twig',
            [
                'post' => $this->post->findPost($id),
                'comments' => $this->comment->findCommentsPaginated($id)[0],
                'pagination' => $this->comment->findCommentsPaginated($id)[1],
                'categories' => $this->category->all(),
                'like' => $this->like->findLike($id),
                'countPosts' => $this->post->findCountPostsByUser($this->post->findPost($id)->getUserId()),
                'countComments' => $this->comment->findCountCommentsByUser($this->post->findPost($id)->getUserId())
            ]
        );
    }

    public function category($id): void
    {
        $this->twig->display(
            'post/category.html.twig',
            [
                'category' => $this->category->find($id),
                'posts' => $this->post->findPostsPaginatedByCategory($id)[0],
                'pagination' => $this->post->findPostsPaginatedByCategory($id)[1],
                'categories' => $this->category->all()
            ]
        );
    }

    public function newComment(): void
    {
        $this->commentHelpers->newComment($this->data->getData(), $this->server->getServer('REQUEST_URI'));
    }

    public function showEditComment(int $id, string $slug, int $idComment)
    {
        $comment = $this->comment->find($idComment);

        if ($comment->getUserId() != $this->session->getItem('id')) {
            return header('Location: /login?forbidden=1');
        }

        $this->twig->display('post/edit_comment.html.twig', [
            'post' => $this->post->findPost($id),
            'comment' => $comment,
            'categories' => $this->category->all()
        ]);
    }

    public function editComment()
    {
        $this->commentHelpers->editComment($this->data->getData(), $this->server->getServer('REQUEST_URI'));
    }

    /**
     *  Method to report a comment
     *
     * @param int $id
     * @param string $slug
     * @param int $idComment
     * @throws \Exception
     */
    public function reported(int $id, string $slug, int $idComment)
    {
        if ($this->session->getItem("id") === null) {
            return header('Location: /login?forbidden=1');
        }

        $comment = $this->comment->find($idComment);
        $comment->setStatusId(3);
        $this->comment->updateStatus($comment, $idComment);
        return header('Location: /post/' . $id . '/' . $slug . '?reported=1');
    }

    public function like()
    {
        if ($this->session->getItem("id") === null) {
            return header('Location: /login?forbidden=1');
        }

        $like = new Like();
        $like
            ->setPostId($this->dataUrl->getDataUrl("post_id"))
            ->setUserId($this->dataUrl->getDataUrl('user_id'));

        $this->like->createLike($like);
    }

    public function unlike($id)
    {
        if ($this->session->getItem("id") === null) {
            return header('Location: /login?forbidden=1');
        }

        $this->like->deleteLike($id);
    }
}
