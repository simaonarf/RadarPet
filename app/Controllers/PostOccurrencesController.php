<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\FlashMessage;
use App\Models\PostUserOccurence;

class PostOccurrencesController extends Controller
{
/*     public function index(Request $request): void
    {
        $paginator = $this->current_user->occurrences()->paginate(
            page: $request->getParam('page', 1)
        );

        $occurrences = $paginator->registers();
        $title = 'Minhas Ocorrências';

        $this->render('occurrences/index', compact('occurrences', 'paginator', 'title'));
    } */

    public function create(Request $request): void
    {
        $postId = $request->getParam('post_id');
        $location = $request->getParam('location');
        $description = $request->getParam('description');

        $occurrence = $this->current_user->occurrences()->new([
            'post_id' => $postId,
            'location' => $location,
            'description' => $description
        ]);

        if ($occurrence->save()) {
            FlashMessage::success('Ocorrência registrada com sucesso!');
        } else {
            FlashMessage::danger('Não foi possível registrar a ocorrência.');
        }

        $this->redirectBack();
    }

    public function destroy(Request $request): void
    {
        $id = $request->getParam('id');

        $occurrence = $this->current_user->occurrences()->findById($id);

        if ($occurrence) {
            $occurrence->destroy();
            FlashMessage::success('Ocorrência removida!');
        } else {
            FlashMessage::danger('Ocorrência não encontrada ou não pertence a você.');
        }

        $this->redirectBack();
    }
}
