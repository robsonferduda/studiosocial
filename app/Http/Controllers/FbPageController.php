<?php

namespace App\Http\Controllers;

use App\Classes\FBFeed;
use App\FbPageMonitor;
use App\FbPagePost;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class FbPageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $pages = FbPageMonitor::all();

        return view('pages/index', compact('pages'));
    }

    public function show($id) {
        $page = FbPageMonitor::where('id', $id)->first();

        return $page;
    }

    public function store(Request $request)
    {
        FbPageMonitor::create([
            'name' => $request->name, 
            'url' => $request->url
        ]);

        (new FBFeed())->pullMedias();

        return redirect('facebook-paginas');
    }

    public function update(Request $request)
    {   
        $page = FbPageMonitor::where('id', $request->id)->first();

        $page->update([
            'name' => $request->name,
            'url'  => $request->url
        ]);


       (new FBFeed())->pullMedias();

        return redirect('facebook-paginas');
    }

    public function destroy($id)
    {
        $page = FbPageMonitor::where('id', $id)->first();

        if($page->delete())
            Flash::success('<i class="fa fa-check"></i> Página <strong>'.$page->name.'</strong> excluída com sucesso');
        else
            Flash::error("Erro ao excluir o registro");

        return redirect('facebook-paginas');
    }

    public function medias()
    {

        $medias_temp = FbPagePost::orderBy('updated_time','DESC')->paginate(20);
        foreach ($medias_temp as $key => $media) {
            
            // $bag_comments = [];
            // if ($media->comments) {
            //     foreach($media->comments as $comment) {
            //         $bag_comments[] = ['text' => $comment->text, 'created_at' => $comment->timestamp];
            //     }
            // }

            $medias[] = array('id' => $media->id,
                'text' => $media->message,
                'username' => '',
                'created_at' => dateTimeUtcToLocal($media->updated_time),
                'sentiment' => '',
                'type_message' => 'facebook',
                'like_count' => '',
                'comments_count' => !empty($media->comment_count) ? $media->comment_count : 0,
                'social_media_id' => $media->social_media_id,
                'tipo' => 'facebook',
                'comments' => [],
                'link' => $media->permalink_url,
                'share_count' => '',
                'user_profile_image_url' => ''
             );


        }

        return view('pages/medias', compact('medias', 'medias_temp'));

    }
}