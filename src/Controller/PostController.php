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

    public function newComment($id, $slug): void
    {
        $this->commentHelpers->newComment($_POST, $_SERVER['REQUEST_URI']);
    }

    public function showEditComment($id, $slug, $idComment)
    {
        $comment = $this->comment->find($idComment);

        if ($comment->getUserId() === $_SESSION['id']) {
            $this->twig->display('post/edit_comment.html.twig', [
                'post' => $this->post->findPost($id),
                'comment' => $comment,
                'categories' => $this->category->all()
            ]);
        } else {
            header('Location: /login?forbidden=1');
        }
    }

    public function editComment($id, $slug, $idComment)
    {
        $this->commentHelpers->editComment($_POST, $_SERVER['REQUEST_URI']);
    }

    /**
     *  Method to report a comment
     *
     * @param int $id
     * @throws \Exception
     */
    public function reported(int $id, string $slug, int $idComment)
    {
        if ($_SESSION['id'] == null) {
            header('Location: /login?forbidden=1');
        } else {
            $comment = $this->comment->find($idComment);
            $comment->setStatusId(3);

            $this->comment->updateStatus($comment, $idComment);
            header('Location: /post/' . $id . '/' . $slug . '?reported=1');
        }
    }

    public function like()
    {
        if ($_SESSION['id'] == null) {
            header('Location: /login?forbidden=1');
        } else {
            $like = new Like();

            $like
                ->setPostId($_GET['post_id'])
                ->setUserId($_GET['user_id']);

            $this->like->createLike($like);
        }
    }

    public function unlike($id)
    {
        if ($_SESSION['id'] == null) {
            header('Location: /login?forbidden=1');
        } else {
            $this->like->deleteLike($id);
        }
    }
}
