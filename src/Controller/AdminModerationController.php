<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use App\Services\Redirect;

class AdminModerationController extends Controller
{

    private $commentRepo;

    public function __construct()
    {
        $this->checkRole();
        $this->commentRepo = new CommentRepository();
        parent::__construct();
    }

    public function showPublication(int $id)
    {
        $this->twig->display('admin/moderation/publicated.html.twig', [
            'comments' => $this->commentRepo->findCommentsByStatusPaginated($id)[0],
            'pagination' => $this->commentRepo->findCommentsByStatusPaginated($id)[1]
        ]);
    }

    public function showModerated(int $id)
    {
        $this->twig->display('admin/moderation/moderated.html.twig', [
            'comments' => $this->commentRepo->findCommentsByStatusPaginated($id)[0],
            'pagination' => $this->commentRepo->findCommentsByStatusPaginated($id)[1]
        ]);
    }

    public function showReported(int $id)
    {
        $this->twig->display('admin/moderation/reported.html.twig', [
            'comments' => $this->commentRepo->findCommentsByStatusPaginated($id)[0],
            'pagination' => $this->commentRepo->findCommentsByStatusPaginated($id)[1]
        ]);
    }

    public function publicated(int $id)
    {
        $comment = $this->commentRepo->find($id);
        $comment->setStatusId(2);

        $this->commentRepo->updateStatus($comment, $id);
        header('Location: ' . $_SERVER['HTTP_REFERER'] . '?publicated=1');
    }

    public function moderated(int $id)
    {
        $comment = $this->commentRepo->find($id);
        $comment->setStatusId(4);

        $this->commentRepo->updateStatus($comment, $id);
        header('Location: ' . $_SERVER['HTTP_REFERER'] . '?moderated=1');
    }

    public function delete(int $id)
    {
        $this->commentRepo->delete($id);
        header('Location: ' . $_SERVER['HTTP_REFERER'] . '?delete=1');
    }
}
