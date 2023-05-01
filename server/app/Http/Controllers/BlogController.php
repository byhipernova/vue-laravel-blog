<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\BlogComment;

class BlogController extends Controller
{
    function index(){
        $blogs = Blog::all();
        return response()->json($blogs);
    }

    function show($id){
        $blog = Blog::find($id);
        return response()->json($blog);
    }


    function store(Request $request){
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'image' => 'required|image|max:2048'
        ]);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $blog = new Blog();
            $blog->title = $request->title;
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $blog->image = $imageName;
            $blog->user_id = $request->user()->id;
            $blog->content = $request->content;
            $blog->save();

            return response()->json(['message'=> 'Blog başarıyla oluşturuldu']);
        }else{
            return response()->json(['message'=> 'Blog oluşturulamadı']);
        }
    }

    function blogCommentStore(Request $request){
        $blogComment = new BlogComment();
        $blogComment->blog_id = $request->blog_id;
        $blogComment->comment = $request->comment;
        $blogComment->user_id = $request->user()->id;
        $blogComment->save();
        $blogComment->name = $request->user()->name;

        return response()->json($blogComment);
    }

    function showBlogComments($id){
        $blogComments = DB::table('blog_comments')
        ->join('users', 'blog_comments.user_id', '=', 'users.id')
        ->where('blog_comments.blog_id', $id)
        ->select('blog_comments.*', 'users.name')
        ->get();;


        return response()->json($blogComments);
    }

}
