<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use App\Services\Redirect;

class AdminModerationController extends Controller
{

    private $commentRepo;

    private $redirect;

    public function __construct()
    {
        parent::__construct();
        $this->checkRole();
        $this->commentRepo = new CommentRepository();
        $this->redirect = new Redirect();
    }

    public function showPublication(int $id)
    {
        $this->twig->display('admin/moderation/publication.html.twig', [
            'comments' => $this->commentRepo->findCommentsByStatus($id)
        ]);
    }

    public function showModerated(int $id)
    {
        $this->twig->display('admin/moderation/moderated.html.twig', [
            'comments' => $this->commentRepo->findCommentsByStatus($id)
        ]);
    }

    public function showReported(int $id)
    {
        $this->twig->display('admin/moderation/reported.html.twig', [
            'comments' => $this->commentRepo->findCommentsByStatus($id)
        ]);
    }

    public function publicated(int $id)
    {
        $comment = $this->commentRepo->find($id);
        $comment->setStatusId(2);

        $url = $this->redirect->redirectModeration($_SERVER['HTTP_REFERER']);

        $this->commentRepo->updateStatus($comment, $id);
        header('Location: ' . $url . '?publicated=1');
    }

    public function moderated(int $id)
    {
        $comment = $this->commentRepo->find($id);
        $comment->setStatusId(4);

        $url = $this->redirect->redirectModeration($_SERVER['HTTP_REFERER']);

        $this->commentRepo->updateStatus($comment, $id);
        header('Location: ' . $url . '?moderated=1');
    }

    public function delete(int $id)
    {
        $url = $this->redirect->redirectModeration($_SERVER['HTTP_REFERER']);

        $this->commentRepo->delete($id);
        header('Location: ' . $url . '?delete=1');
    }
}
